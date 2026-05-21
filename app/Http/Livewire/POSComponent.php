<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Sales;
use App\Models\Patient;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Setting;
use App\Models\AuditTrail;
use App\Models\DiscountApprovalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\SmsService;
use App\Services\EmailService;
use App\Models\SmsTemplate;
use App\Mail\PaymentReceiptMail;

class POSComponent extends Component
{
    use WithPagination;

    protected $listeners = [
        'confirmCheckout' => 'checkout',
        'sellWithoutPendingDiscount' => 'sellWithoutPendingDiscount',
    ];

    protected $paginationTheme = 'bootstrap';

    public $cart = [];
    public $totalAmount = 0;

    public $patientId;
    public $patientSearchTerm = '';
    public $searchResults = [];
    public $showPatientDropdown = false;

    public $productSearchTerm = '';
    public $selectedCategoryId = '';

    // Frame editing on prescription carts
    public $hasPrescriptionCart        = false;
    public $prescriptionConsultationId = null;
    public $frameSearchTerm            = '';
    public $frameSearchResults         = [];

    // Split payment entries: [['method' => 'cash', 'amount' => 200.00], ...]
    public $payments         = [];
    public $newPaymentMethod = 'cash';
    public $newPaymentAmount = '';

    public $amountPaid = 0; // computed: sum of $payments
    public $change     = 0;

    // Discount
    public $discountType   = 'percentage'; // 'percentage' | 'fixed'
    public $discountValue  = 0;
    public $discountAmount = 0;
    public $finalAmount    = 0;

    // Discount approval
    public $discountApproved     = false;
    public $discountApprovedBy   = null;   // approver name (display only)
    public $discountApprovedById = null;   // approver user_id (saved to sale)
    public $showApprovalModal    = false;
    public $approvalEmail        = '';
    public $approvalPassword     = '';
    public $approvalError        = '';
    public $pendingDiscountApprovalId = null;
    public $pendingDiscountApprovalStatus = null;

    public $lastSaleId;

    // Receipt data for inline printing
    public $receiptData = null;
    public $showReceipt = false;

    // Guard flag — prevents double-firing from Livewire listener + Alpine
    public $checkoutProcessing = false;

    // Part payment
    public $isPartPayment     = false;
    public $hasFramesOrLenses = false;

    protected $casts = [
        'amountPaid'     => 'float',
        'totalAmount'    => 'float',
        'change'         => 'float',
        'discountValue'  => 'float',
        'discountAmount' => 'float',
        'finalAmount'    => 'float',
    ];

    // Within-request cache — reset on each Livewire hydration cycle (private, not persisted)
    private $cachedCartProducts = null;

    public function mount()
    {
        $this->calculateTotal();
    }

    /* ===================== PATIENT ===================== */

    public function updatedPatientSearchTerm()
    {
        if (strlen($this->patientSearchTerm) >= 2) {
            $this->searchResults = Patient::where('name', 'like', '%' . $this->patientSearchTerm . '%')
                ->orWhere('contact', 'like', '%' . $this->patientSearchTerm . '%')
                ->orWhere('pxnumber', 'like', '%' . $this->patientSearchTerm . '%')
                ->limit(10)
                ->get()
                ->map(fn ($p) => [
                    'id'       => $p->id,
                    'name'     => $p->name,
                    'contact'  => $p->contact  ?? '',
                    'pxnumber' => $p->pxnumber ?? '',
                ])
                ->toArray();
            $this->showPatientDropdown = true;
        } else {
            $this->showPatientDropdown = false;
        }
    }

    public function selectPatient($id)
    {
        $patient = Patient::find($id);
        if (!$patient) return;

        $this->patientId           = $patient->id;
        $this->patientSearchTerm   = $patient->name;
        $this->showPatientDropdown = false;

        // This loads items if a Doctor already prescribed them
        $this->loadPatientCart();

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'info',
            'message' => 'Patient selected. You can now add items to the cart.'
        ]);
    }

    public function clearPatient()
    {
        $this->patientId                  = null;
        $this->patientSearchTerm          = '';
        $this->cart                       = [];
        $this->payments                   = [];
        $this->newPaymentMethod           = 'cash';
        $this->newPaymentAmount           = '';
        $this->amountPaid                 = 0;
        $this->discountValue              = 0;
        $this->hasPrescriptionCart        = false;
        $this->prescriptionConsultationId = null;
        $this->frameSearchTerm            = '';
        $this->frameSearchResults         = [];
        $this->resetDiscountApproval();
        $this->calculateTotal();

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'info',
            'message' => 'Patient cleared. Cart reset.'
        ]);
    }

    /* ===================== PENDING CARTS ===================== */

    public function loadAllPendingCarts()
    {
        try {
            Log::info('=== loadAllPendingCarts START ===');

            // Fetch doctor prescription carts that are waiting for cashier checkout.
            $pendingCarts = Cart::with(['product', 'patient', 'dispensedBy'])
                ->where('purchased', false)
                ->where('status', 'pending')
                ->whereNotNull('consultation_id')
                ->where('consultation_id', '!=', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($pendingCarts->isEmpty()) {
                $this->dispatchBrowserEvent('notify', [
                    'type'    => 'info',
                    'message' => 'No doctor prescription carts are waiting.'
                ]);
                return;
            }

            // Group the items by patient so one patient shows as one "Order"
            $groupedCarts = $pendingCarts->groupBy('patient_id');
            $cartsData    = [];

            foreach ($groupedCarts as $patientId => $items) {
                $firstItem = $items->first();
                $patient   = $firstItem->patient;
                $dispenser = $firstItem->dispensedBy; // The Doctor/Pharmacist who added items

                $cartsData[] = [
                    'patient_id'       => $patientId,
                    'patient_name'     => $patient ? $patient->name : 'Walk-in Patient',
                    'patient_contact'  => $patient ? $patient->contact : 'N/A',
                    'patient_number'   => $patient ? $patient->pxnumber : 'N/A',
                    // Show the name of the person who prepared the order
                    'cashier_name'     => $dispenser ? $dispenser->name : 'System',
                    'cashier_id'       => $firstItem->dispensed_by,
                    'item_count'       => $items->count(),
                    'total_quantity'   => $items->sum('quantity'),
                    'total_amount'     => (float) $items->sum('total'),
                    'created_at'       => $firstItem->created_at->format('d M Y, h:i A'),
                    'created_at_human' => $firstItem->created_at->diffForHumans(),
                    'consultation_id'   => $firstItem->consultation_id,
                    // 'is_mine' tells the Cashier if THEY were the one who added the items
                    'is_mine'          => $firstItem->dispensed_by == Auth::id(),
                    'items'            => $items->map(function ($item) {
                        return [
                            'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                            'quantity'     => $item->quantity,
                            'frequency'    => $item->frequency,
                            'eye'          => $item->eye,
                            'price'        => (float) $item->price,
                            'total'        => (float) $item->total,
                        ];
                    })->toArray()
                ];
            }

            // Sort so that carts created by the current user appear at the top
            usort($cartsData, function ($a, $b) {
                return $b['is_mine'] <=> $a['is_mine'];
            });

            // Send the data to the Alpine.js modal
            $this->dispatchBrowserEvent('show-pending-carts', [
                'carts'         => $cartsData,
                'totalCarts'    => count($cartsData),
                'currentUserId' => Auth::id()
            ]);

        } catch (\Exception $e) {
            Log::error('loadAllPendingCarts ERROR: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Failed to load queue: ' . $e->getMessage()
            ]);
        }
    }

    public function loadApprovedDiscounts()
    {
        $requests = DiscountApprovalRequest::with(['patient', 'cashier', 'approver'])
            ->where('status', DiscountApprovalRequest::STATUS_APPROVED)
            ->where('cashier_id', Auth::id())
            ->latest('approved_at')
            ->latest()
            ->get();

        $approvedDiscounts = $requests
            ->filter(function ($request) {
                if ($this->discountRequestCartIsStillOpen($request)) {
                    return true;
                }

                $request->delete();
                return false;
            })
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'patient_id' => $request->patient_id,
                    'patient_name' => $request->patient->name ?? 'Walk-in Patient',
                    'patient_number' => $request->patient->pxnumber ?? 'N/A',
                    'cashier_name' => $request->cashier->name ?? 'Cashier',
                    'approver_name' => $request->approver->name ?? 'Manager',
                    'discount_type' => $request->discount_type,
                    'discount_value' => (float) $request->discount_value,
                    'discount_amount' => (float) $request->discount_amount,
                    'gross_amount' => (float) $request->gross_amount,
                    'final_amount' => (float) $request->final_amount,
                    'approved_at' => optional($request->approved_at)->format('d M Y, h:i A') ?? $request->updated_at->format('d M Y, h:i A'),
                    'approved_at_human' => optional($request->approved_at ?? $request->updated_at)->diffForHumans(),
                    'items' => collect($request->cart_snapshot ?? [])->map(function ($item) {
                        return [
                            'name' => $item['name'] ?? 'Item',
                            'quantity' => (int) ($item['quantity'] ?? 1),
                            'total' => (float) ($item['total'] ?? $item['subtotal'] ?? 0),
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        if (empty($approvedDiscounts)) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'info',
                'message' => 'No approved discounts are waiting for you.',
            ]);
        }

        $this->dispatchBrowserEvent('show-approved-discounts', [
            'discounts' => $approvedDiscounts,
            'totalDiscounts' => count($approvedDiscounts),
        ]);
    }

    public function applyApprovedDiscount($requestId)
    {
        $request = DiscountApprovalRequest::with(['patient', 'approver'])
            ->where('cashier_id', Auth::id())
            ->where('status', DiscountApprovalRequest::STATUS_APPROVED)
            ->find($requestId);

        if (!$request) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Approved discount was not found or has already been used.',
            ]);
            return;
        }

        if (!$this->discountRequestCartIsStillOpen($request)) {
            $request->delete();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => 'This approved discount was removed because the cart was already sold or deleted.',
            ]);
            return;
        }

        if ($request->patient_id) {
            $this->patientId = $request->patient_id;
            $this->patientSearchTerm = $request->patient->name ?? '';
            $this->showPatientDropdown = false;
            $this->loadPatientCart(null, $this->discountRequestCartIds($request)->toArray());
        }

        if (empty($this->cart)) {
            $this->loadCartFromDiscountSnapshot($request->cart_snapshot ?? []);
        }

        if (!$this->currentCartMatchesDiscountRequest($request)) {
            $this->resetDiscountApproval();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'The current cart no longer matches this approved discount.',
            ]);
            return;
        }

        $this->discountType = $request->discount_type;
        $this->discountValue = (float) $request->discount_value;
        $this->calculateTotal();

        $discountEligibleSubtotal = $this->getDiscountEligibleSubtotal();
        if ($discountEligibleSubtotal <= 0) {
            $this->resetDiscountApproval();
            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => 'Approved discounts can only be applied to Frames and Lenses.',
            ]);
            return;
        }

        $this->discountAmount = min((float) $request->discount_amount, (float) $discountEligibleSubtotal);
        $this->finalAmount = max(0, (float) $this->totalAmount - (float) $this->discountAmount);
        $this->discountApproved = true;
        $this->discountApprovedBy = $request->approver->name ?? 'Manager';
        $this->discountApprovedById = $request->approved_by;
        $this->pendingDiscountApprovalId = $request->id;
        $this->pendingDiscountApprovalStatus = $request->status;
        $this->updateChange();
        $this->newPaymentAmount = $this->finalAmount > 0 ? $this->finalAmount : '';

        $this->dispatchBrowserEvent('close-approved-discounts-modal');
        $this->dispatchBrowserEvent('pos-cart-loaded');
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Approved discount and cart loaded. Proceed with payment.',
        ]);
    }

    public function loadCartFromList($patientId, $consultationId = null)
    {
        $patient = Patient::find($patientId);
        if (!$patient) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Patient not found.'
            ]);
            return;
        }

        $this->patientId = $patient->id;
        $this->patientSearchTerm = $patient->name;
        $this->showPatientDropdown = false;
        $this->loadPatientCart($consultationId);
        $this->newPaymentAmount = $this->finalAmount > 0 ? $this->finalAmount : '';

        $this->dispatchBrowserEvent('close-pending-carts-modal');
        $this->dispatchBrowserEvent('pos-cart-loaded');
        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => count($this->cart) . ' item(s) loaded for ' . $patient->name . '. Proceed with payment.'
        ]);
    }

    public function deletePendingCart($patientId, $cashierId)
    {
        try {
            if ($cashierId !== Auth::id()) {
                $this->dispatchBrowserEvent('notify', [
                    'type'    => 'error',
                    'message' => 'You can only delete your own carts.'
                ]);
                return;
            }

            $deleted = Cart::where('patient_id', $patientId)
                ->where('dispensed_by', $cashierId)
                ->where('purchased', false)
                ->where('status', 'pending')
                ->delete();

            if ($deleted > 0) {
                $this->dispatchBrowserEvent('notify', [
                    'type'    => 'success',
                    'message' => 'Cart deleted successfully.'
                ]);
                $this->loadAllPendingCarts();
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'type'    => 'warning',
                    'message' => 'No items found to delete.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Delete pending cart error: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Failed to delete cart.'
            ]);
        }
    }

    /* ===================== CART ===================== */

    public function addToCart($productId)
    {
        $product = Product::with('category')->find($productId);
        if (!$product || $product->quantity <= 0) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Product not available or out of stock.'
            ]);
            return;
        }

        $currentQty = isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] : 0;
        if ($currentQty >= $product->quantity) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'warning',
                'message' => 'Cannot add more. Maximum stock: ' . $product->quantity
            ]);
            return;
        }

        $this->cart[$productId] = [
            'product_id' => $productId,
            'quantity'  => $currentQty + 1,
            'frequency' => null,
            'eye'       => null,
            'cart_id'   => $this->cart[$productId]['cart_id'] ?? null,
        ];

        $this->resetDiscountApprovalForCartChange();
        $this->calculateTotal();
        $this->persistCart($productId);

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => $product->name . ' added to cart.'
        ]);
    }

    public function updateQuantity($cartKey, $qty)
    {
        $productId = $this->getCartItemProductId($cartKey);
        $product = Product::find($productId);
        if (!$product) return;

        $qty = max(1, min((int) $qty, $product->quantity));

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] = $qty;
        } else {
            $this->cart[$cartKey] = [
                'product_id' => $productId,
                'quantity'  => $qty,
                'frequency' => null,
                'eye'       => null,
                'cart_id'   => null,
            ];
        }

        $this->resetDiscountApprovalForCartChange();
        $this->calculateTotal();
        $this->persistCart($cartKey);
    }

    public function removeFromCart($cartKey)
    {
        $cartItem = $this->cart[$cartKey] ?? null;

        // Block removal of non-Frame prescription items
        if (
            is_array($cartItem) &&
            ($cartItem['from_prescription'] ?? false) &&
            !($cartItem['is_frame'] ?? false)
        ) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Prescription items can only be removed if they belong to the Frame category.',
            ]);
            return;
        }

        $productId = $this->getCartItemProductId($cartKey, $cartItem);
        unset($this->cart[$cartKey]);
        $this->resetDiscountApprovalForCartChange();
        $this->calculateTotal();

        if ($this->patientId) {
            if (is_array($cartItem) && !empty($cartItem['cart_id'])) {
                Cart::where('id', $cartItem['cart_id'])
                    ->where('patient_id', $this->patientId)
                    ->where('purchased', false)
                    ->delete();
            } else {
                Cart::where('patient_id', $this->patientId)
                    ->where('dispensed_by', Auth::id())
                    ->where('product_id', $productId)
                    ->where('purchased', false)
                    ->delete();
            }
        }

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'info',
            'message' => 'Item removed from cart.'
        ]);
    }

    public function clearCart()
    {
        if (empty($this->cart)) return;

        if ($this->patientId) {
            Cart::where('patient_id', $this->patientId)
                ->where('purchased', false)
                ->delete();
        }

        $this->cart                = [];
        $this->payments            = [];
        $this->newPaymentMethod    = 'cash';
        $this->newPaymentAmount    = '';
        $this->amountPaid          = 0;
        $this->discountValue       = 0;
        $this->hasPrescriptionCart        = false;
        $this->prescriptionConsultationId = null;
        $this->frameSearchTerm            = '';
        $this->frameSearchResults         = [];
        $this->resetDiscountApproval();
        $this->calculateTotal();

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'info',
            'message' => 'Cart cleared successfully.'
        ]);
    }

    /* ===================== FRAME EDITING ===================== */

    private function isFrameProduct(Product $product): bool
    {
        return str_contains(strtolower($product->category->name ?? ''), 'frame');
    }

    public function updatedFrameSearchTerm()
    {
        if (strlen($this->frameSearchTerm) < 2) {
            $this->frameSearchResults = [];
            return;
        }

        $frameCategoryIds = Category::whereRaw('LOWER(name) LIKE ?', ['%frame%'])->pluck('id');

        $this->frameSearchResults = Product::with('category')
            ->whereIn('category_id', $frameCategoryIds)
            ->where(fn ($q) =>
                $q->where('name', 'like', '%' . $this->frameSearchTerm . '%')
                  ->orWhere('batch_number', 'like', '%' . $this->frameSearchTerm . '%')
            )
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(fn ($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'batch' => $p->batch_number ?? '',
                'price' => (float) $p->selling_price,
                'stock' => $p->quantity,
            ])
            ->toArray();
    }

    public function addFrameProduct($productId)
    {
        $product = Product::with('category')->find($productId);

        if (!$product) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Product not found.']);
            return;
        }

        if (!$this->isFrameProduct($product)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Only Frame category products can be added here.']);
            return;
        }

        if ($product->quantity <= 0) {
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'This frame is out of stock.']);
            return;
        }

        // Check if already in cart; if so, increment quantity
        foreach ($this->cart as $cartKey => $cartItem) {
            $existingProductId = $this->getCartItemProductId($cartKey, $cartItem);
            if ($existingProductId === (int) $productId) {
                $currentQty = is_array($cartItem) ? $cartItem['quantity'] : $cartItem;
                if ($currentQty >= $product->quantity) {
                    $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'Maximum stock reached for this frame.']);
                    return;
                }
                if (is_array($this->cart[$cartKey])) {
                    $this->cart[$cartKey]['quantity'] = $currentQty + 1;
                } else {
                    $this->cart[$cartKey] = $currentQty + 1;
                }
                $this->resetDiscountApprovalForCartChange();
                $this->calculateTotal();
                $this->persistCart($cartKey);
                $this->frameSearchTerm    = '';
                $this->frameSearchResults = [];
                $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $product->name . ' quantity updated.']);
                return;
            }
        }

        // New item — add as a regular (non-prescription) cart entry
        $this->cart[$productId] = [
            'product_id'       => (int) $productId,
            'quantity'         => 1,
            'frequency'        => null,
            'eye'              => null,
            'cart_id'          => null,
            'from_prescription' => false,
            'is_frame'         => true,
        ];

        $this->resetDiscountApprovalForCartChange();
        $this->calculateTotal();
        $this->persistCart($productId);

        $this->frameSearchTerm    = '';
        $this->frameSearchResults = [];

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $product->name . ' added to cart.']);
    }

    /* ===================================================== */

    public function updateCartItemMetadata($cartKey, $frequency, $eye)
    {
        if (!isset($this->cart[$cartKey])) return;

        $productId = $this->getCartItemProductId($cartKey);
        $product = Product::with('category')->find($productId);

        if (!$product) {
            return;
        }

        if (!$product->isDrugCategory()) {
            $frequency = null;
            $eye = null;
        }

        if (is_array($this->cart[$cartKey])) {
            $this->cart[$cartKey]['product_id'] = $productId;
            $this->cart[$cartKey]['frequency'] = $frequency ?: null;
            $this->cart[$cartKey]['eye']       = $eye ?: null;
        } else {
            $qty = $this->cart[$cartKey];
            $this->cart[$cartKey] = [
                'product_id' => $productId,
                'quantity'  => $qty,
                'frequency' => $frequency ?: null,
                'eye'       => $eye ?: null,
                'cart_id'   => null,
            ];
        }

        $this->resetDiscountApprovalForCartChange();
        $this->persistCart($cartKey);

        if ($product->isDrugCategory()) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'success',
                'message' => 'Frequency and eye information updated.'
            ]);
        }
    }

    /* ===================== TOTALS ===================== */

    private function getCartItemProductId($cartKey, $cartItem = null): ?int
    {
        $cartItem = $cartItem ?? ($this->cart[$cartKey] ?? null);

        if (is_array($cartItem) && !empty($cartItem['product_id'])) {
            return (int) $cartItem['product_id'];
        }

        return is_numeric($cartKey) ? (int) $cartKey : null;
    }

    private function getCartProductIds(): array
    {
        return collect($this->cart)
            ->map(fn ($cartItem, $cartKey) => $this->getCartItemProductId($cartKey, $cartItem))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    // Fetches cart products once per request; subsequent calls return the cached collection.
    private function fetchCartProducts(): \Illuminate\Support\Collection
    {
        if ($this->cachedCartProducts === null) {
            $productIds = $this->getCartProductIds();
            $this->cachedCartProducts = $productIds
                ? Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id')
                : collect();
        }
        return $this->cachedCartProducts;
    }

    private function isDiscountEligibleProduct($product): bool
    {
        $categoryName = strtolower($product->category->name ?? '');

        return str_contains($categoryName, 'frame') || str_contains($categoryName, 'lens');
    }

    private function getDiscountEligibleSubtotal(): float
    {
        $products = $this->fetchCartProducts();

        return round(collect($this->cart)->map(function ($cartItem, $cartKey) use ($products) {
            $productId = $this->getCartItemProductId($cartKey, $cartItem);
            $product = $products[$productId] ?? null;

            if (!$product || !$this->isDiscountEligibleProduct($product)) {
                return 0;
            }

            $qty = is_array($cartItem) ? ($cartItem['quantity'] ?? 1) : $cartItem;

            return (float) $qty * (float) $product->selling_price;
        })->sum(), 2);
    }

    private function calculateTotal()
    {
        $this->totalAmount = 0;

        if (empty($this->cart)) {
            $this->hasFramesOrLenses = false;
            $this->isPartPayment     = false;
            $this->discountAmount    = 0;
            $this->finalAmount       = 0;
            $this->updateChange();
            return;
        }

        $this->cachedCartProducts = null; // invalidate so we get fresh data after cart changes
        $products = $this->fetchCartProducts();
        foreach ($this->cart as $cartKey => $cartItem) {
            $productId = $this->getCartItemProductId($cartKey, $cartItem);

            if (isset($products[$productId])) {
                $qty               = is_array($cartItem) ? $cartItem['quantity'] : $cartItem;
                $this->totalAmount += $qty * $products[$productId]->selling_price;
            }
        }

        $this->totalAmount = (float) $this->totalAmount;

        $this->hasFramesOrLenses = $products->contains(fn ($product) => $this->isDiscountEligibleProduct($product));

        // Apply discount only to Frames and Lenses.
        $discountEligibleSubtotal = $this->getDiscountEligibleSubtotal();
        $discountValue = (float) ($this->discountValue ?? 0);
        if ($discountValue > 0 && $discountEligibleSubtotal > 0) {
            if ($this->discountType === 'percentage') {
                $pct = min(100, $discountValue);
                $this->discountAmount = round($discountEligibleSubtotal * ($pct / 100), 2);
            } else {
                $this->discountAmount = round(min($discountEligibleSubtotal, $discountValue), 2);
            }
        } else {
            $this->discountAmount = 0;
        }
        $this->finalAmount = max(0, $this->totalAmount - $this->discountAmount);

        // Auto-disable part payment if cart no longer has frames/lenses
        if (!$this->hasFramesOrLenses) {
            $this->isPartPayment = false;
        }

        $this->amountPaid = $this->getTotalPaid();
        $this->updateChange();
    }

    /* ===================== SPLIT PAYMENT ===================== */

    public function addPayment()
    {
        $amount = round((float) ($this->newPaymentAmount ?? 0), 2);

        if ($amount <= 0) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Enter a valid payment amount.',
            ]);
            return;
        }

        $this->payments[] = [
            'method' => $this->newPaymentMethod,
            'amount' => $amount,
        ];

        // Pre-fill next entry with remaining balance
        $remaining = max(0, round($this->finalAmount - $this->getTotalPaid(), 2));
        $this->newPaymentAmount = $remaining > 0 ? $remaining : '';

        $this->amountPaid = $this->getTotalPaid();
        $this->updateChange();
    }

    public function removePayment($index)
    {
        array_splice($this->payments, $index, 1);
        $this->amountPaid = $this->getTotalPaid();

        // Pre-fill remaining after removal
        $remaining = max(0, round($this->finalAmount - $this->getTotalPaid(), 2));
        $this->newPaymentAmount = $remaining > 0 ? $remaining : '';

        $this->updateChange();
    }

    public function selectNewPaymentMethod($method)
    {
        $this->newPaymentMethod = $method;
        // Pre-fill with remaining when switching method
        $remaining = max(0, round($this->finalAmount - $this->getTotalPaid(), 2));
        if ($remaining > 0 && (float) ($this->newPaymentAmount ?? 0) == 0) {
            $this->newPaymentAmount = $remaining;
        }
    }

    private function getTotalPaid(): float
    {
        return round(collect($this->payments)->sum('amount'), 2);
    }

    public function updatedDiscountType()
    {
        $this->resetDiscountApproval();
        $this->calculateTotal();
    }

    public function updatedDiscountValue()
    {
        $this->resetDiscountApproval();
        $this->calculateTotal();
    }

    private function resetDiscountApproval()
    {
        $this->discountApproved     = false;
        $this->discountApprovedBy   = null;
        $this->discountApprovedById = null;
        $this->pendingDiscountApprovalId = null;
        $this->pendingDiscountApprovalStatus = null;
    }

    private function resetDiscountApprovalForCartChange(): void
    {
        if ($this->discountApproved || $this->pendingDiscountApprovalId || (float) $this->discountAmount > 0) {
            $this->resetDiscountApproval();
        }
    }

    public function removeDiscount()
    {
        $this->discountValue = 0;
        $this->discountAmount = 0;
        $this->finalAmount = (float) $this->totalAmount;
        $this->resetDiscountApproval();
        $this->newPaymentAmount = max(0, round($this->finalAmount - $this->getTotalPaid(), 2));
        $this->updateChange();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'info',
            'message' => 'Discount removed. You can sell at full price.',
        ]);
    }

    public function sellWithoutPendingDiscount()
    {
        $this->deletePendingDiscountRequestsForCurrentCart();
        $this->removeDiscount();
        $this->initiateCheckout();
    }

    public function confirmSellWithoutPendingDiscount($request = null)
    {
        if (!$request) {
            $request = $this->findPendingDiscountRequestForCurrentCart();
        }

        $this->dispatchBrowserEvent('confirm-sell-without-pending-discount', [
            'discountAmount' => number_format((float) ($request->discount_amount ?? $this->discountAmount), 2),
            'fullAmount' => number_format((float) $this->totalAmount, 2),
            'discountedAmount' => number_format((float) ($request->final_amount ?? $this->finalAmount), 2),
        ]);
    }

    /* ===================== DISCOUNT APPROVAL ===================== */

    public function requestDiscountApproval()
    {
        if (!$this->hasFramesOrLenses || $this->getDiscountEligibleSubtotal() <= 0) {
            $this->removeDiscount();
            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => 'Discounts can only be applied to Frames and Lenses.',
            ]);
            return;
        }

        if ($this->discountAmount <= 0) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'warning',
                'message' => 'Enter a discount amount first.',
            ]);
            return;
        }

        if (Auth::user()?->hasRole(['Manager', 'Super Admin'])) {
            $this->discountApproved = true;
            $this->discountApprovedBy = Auth::user()->name;
            $this->discountApprovedById = Auth::id();
            $this->pendingDiscountApprovalId = null;
            $this->pendingDiscountApprovalStatus = null;

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Discount approved.',
            ]);
            return;
        }

        if ($this->pendingDiscountApprovalId) {
            $request = DiscountApprovalRequest::find($this->pendingDiscountApprovalId);
            if ($request && $request->status === DiscountApprovalRequest::STATUS_PENDING) {
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'info',
                    'message' => 'Discount approval request is already pending.',
                ]);
                return;
            }
        }

        $snapshot = $this->discountApprovalCartSnapshot();
        $duplicateRequest = $this->findActiveDiscountRequestForSnapshot($snapshot);

        if ($duplicateRequest) {
            $this->pendingDiscountApprovalId = $duplicateRequest->id;
            $this->pendingDiscountApprovalStatus = $duplicateRequest->status;

            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => 'A discount request already exists for one or more products in this cart.',
            ]);
            return;
        }

        $request = DiscountApprovalRequest::create([
            'cashier_id' => Auth::id(),
            'patient_id' => $this->patientId,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'discount_amount' => $this->discountAmount,
            'gross_amount' => $this->totalAmount,
            'final_amount' => $this->finalAmount,
            'cart_snapshot' => $snapshot,
            'status' => DiscountApprovalRequest::STATUS_PENDING,
        ]);

        // Notify all Managers and Super Admins that a discount needs approval
        \App\Services\NotificationService::sendToRoles(
            ['Manager', 'Super Admin'],
            'discount_approval_request',
            'Discount Approval Requested',
            Auth::user()->name . ' is requesting a '
                . $this->discountValue
                . ($this->discountType === 'percentage' ? '%' : ' GH₵')
                . ' discount.',
            'fas fa-percent',
            'text-warning',
            route('admin.discount-approvals')
        );

        $this->pendingDiscountApprovalId = $request->id;
        $this->pendingDiscountApprovalStatus = $request->status;
        $this->showApprovalModal = false;
        $this->approvalEmail = '';
        $this->approvalPassword = '';
        $this->approvalError = '';

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Discount approval request sent to Manager/Super Admin.',
        ]);
        return;
    }

    public function checkDiscountApprovalStatus()
    {
        if (!$this->pendingDiscountApprovalId || $this->discountApproved) {
            return;
        }

        $request = DiscountApprovalRequest::with('approver')->find($this->pendingDiscountApprovalId);

        if (!$request) {
            $this->pendingDiscountApprovalId = null;
            $this->pendingDiscountApprovalStatus = null;
            return;
        }

        $this->pendingDiscountApprovalStatus = $request->status;

        if ($request->status === DiscountApprovalRequest::STATUS_APPROVED) {
            $this->discountApproved = true;
            $this->discountApprovedBy = $request->approver->name ?? 'Manager';
            $this->discountApprovedById = $request->approved_by;

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Discount approved by ' . $this->discountApprovedBy . '. You can complete the sale now.',
            ]);
            return;
        }

        if ($request->status === DiscountApprovalRequest::STATUS_REJECTED) {
            $this->pendingDiscountApprovalId = null;
            $this->pendingDiscountApprovalStatus = null;
            $this->discountValue = 0;
            $this->calculateTotal();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Discount request was rejected.',
            ]);
        }
    }

    public function approveDiscount()
    {
        $this->approvalError = '';

        $throttleKey = 'discount-approve:' . (request()->ip() ?? auth()->id());

        if (RateLimiter::tooManyAttempts($throttleKey, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->approvalError    = "Too many failed attempts. Please wait {$seconds} seconds before trying again.";
            $this->approvalPassword = '';
            return;
        }

        $approver = \App\Models\User::where('email', trim($this->approvalEmail))
            ->where('is_active', true)
            ->first();

        if (!$approver || !\Illuminate\Support\Facades\Hash::check($this->approvalPassword, $approver->password)) {
            RateLimiter::hit($throttleKey, decay: 600); // 10-minute window
            $this->approvalError    = 'Invalid credentials. Please try again.';
            $this->approvalPassword = '';
            return;
        }

        if (!$approver->hasRole(['Manager', 'Super Admin'])) {
            RateLimiter::hit($throttleKey, decay: 600);
            $this->approvalError    = 'This account does not have permission to approve discounts. A Manager or Super Admin is required.';
            $this->approvalPassword = '';
            return;
        }

        RateLimiter::clear($throttleKey);

        $this->discountApproved     = true;
        $this->discountApprovedBy   = $approver->name;
        $this->discountApprovedById = $approver->id;
        $this->showApprovalModal    = false;
        $this->approvalEmail        = '';
        $this->approvalPassword     = '';

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => 'Discount of GH₵ ' . number_format($this->discountAmount, 2) . ' approved by ' . $approver->name . '.',
        ]);
    }

    public function cancelApproval()
    {
        $this->showApprovalModal = false;
        $this->approvalEmail     = '';
        $this->approvalPassword  = '';
        $this->approvalError     = '';
    }

    private function updateChange()
    {
        $totalPaid    = $this->getTotalPaid();
        $finalAmount  = (float) ($this->finalAmount ?? 0);
        $this->change = max(0, $totalPaid - $finalAmount);
    }

    private function discountApprovalCartSnapshot(): array
    {
        $products = $this->fetchCartProducts();

        return collect($this->cart)->map(function ($cartItem, $cartKey) use ($products) {
            $productId = $this->getCartItemProductId($cartKey, $cartItem);
            $product = $products[$productId] ?? null;
            $quantity = is_array($cartItem) ? ($cartItem['quantity'] ?? 1) : $cartItem;

            return [
                'product_id' => $productId,
                'name' => $product->name ?? 'Unknown product',
                'quantity' => (int) $quantity,
                'price' => (float) ($product->selling_price ?? 0),
                'subtotal' => (float) ($quantity * ($product->selling_price ?? 0)),
                'frequency' => is_array($cartItem) ? ($cartItem['frequency'] ?? null) : null,
                'eye' => is_array($cartItem) ? ($cartItem['eye'] ?? null) : null,
                'cart_id' => is_array($cartItem) ? ($cartItem['cart_id'] ?? null) : null,
            ];
        })->values()->toArray();
    }

    private function findActiveDiscountRequestForSnapshot(array $snapshot): ?DiscountApprovalRequest
    {
        $productIds = collect($snapshot)
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($productIds->isEmpty()) {
            return null;
        }

        return DiscountApprovalRequest::whereIn('status', [
                DiscountApprovalRequest::STATUS_PENDING,
                DiscountApprovalRequest::STATUS_APPROVED,
            ])
            ->when($this->patientId, fn ($query) => $query->where('patient_id', $this->patientId))
            ->when(!$this->patientId, fn ($query) => $query->where('cashier_id', Auth::id())->whereNull('patient_id'))
            ->latest()
            ->get()
            ->first(function ($request) use ($productIds) {
                if (!$this->discountRequestCartIsStillOpen($request)) {
                    $request->delete();
                    return false;
                }

                $requestProductIds = collect($request->cart_snapshot ?? [])
                    ->pluck('product_id')
                    ->filter()
                    ->map(fn ($id) => (int) $id);

                return $requestProductIds->intersect($productIds)->isNotEmpty();
            });
    }

    private function discountRequestCartIds(DiscountApprovalRequest $request)
    {
        return collect($request->cart_snapshot ?? [])
            ->pluck('cart_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function discountRequestProductIds(DiscountApprovalRequest $request)
    {
        return collect($request->cart_snapshot ?? [])
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function discountRequestCartIsStillOpen(DiscountApprovalRequest $request): bool
    {
        $cartIds = $this->discountRequestCartIds($request);

        if ($cartIds->isNotEmpty()) {
            $query = Cart::whereIn('id', $cartIds)
                ->where('purchased', false)
                ->where('status', 'pending');

            if ($request->patient_id) {
                $query->where('patient_id', $request->patient_id);
            }

            return (int) $query->count() === $cartIds->count();
        }

        $productIds = $this->discountRequestProductIds($request);

        if ($productIds->isEmpty()) {
            return false;
        }

        if (!$request->patient_id) {
            return true;
        }

        $openProductIds = Cart::where('patient_id', $request->patient_id)
            ->where('purchased', false)
            ->where('status', 'pending')
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique();

        return $productIds->diff($openProductIds)->isEmpty();
    }

    private function snapshotQuantities(array $snapshot): array
    {
        return collect($snapshot)
            ->filter(fn ($item) => !empty($item['product_id']))
            ->groupBy(fn ($item) => (int) $item['product_id'])
            ->map(fn ($items) => (int) $items->sum(fn ($item) => (int) ($item['quantity'] ?? 1)))
            ->sortKeys()
            ->toArray();
    }

    private function currentCartQuantities(): array
    {
        return collect($this->cart)
            ->map(function ($cartItem, $cartKey) {
                return [
                    'product_id' => $this->getCartItemProductId($cartKey, $cartItem),
                    'quantity' => is_array($cartItem) ? (int) ($cartItem['quantity'] ?? 1) : (int) $cartItem,
                ];
            })
            ->filter(fn ($item) => !empty($item['product_id']))
            ->groupBy('product_id')
            ->map(fn ($items) => (int) $items->sum('quantity'))
            ->sortKeys()
            ->toArray();
    }

    private function currentCartMatchesDiscountRequest(DiscountApprovalRequest $request): bool
    {
        $requestCartIds = $this->discountRequestCartIds($request);

        if ($requestCartIds->isNotEmpty()) {
            $currentCartIds = collect($this->cart)
                ->pluck('cart_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->sort()
                ->values();

            if ($currentCartIds->toArray() !== $requestCartIds->sort()->values()->toArray()) {
                return false;
            }
        }

        return $this->currentCartQuantities() === $this->snapshotQuantities($request->cart_snapshot ?? []);
    }

    private function currentCartIds()
    {
        return collect($this->cart)
            ->pluck('cart_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values();
    }

    private function findPendingDiscountRequestForCurrentCart(): ?DiscountApprovalRequest
    {
        if (empty($this->cart)) {
            return null;
        }

        $currentCartIds = $this->currentCartIds();
        $currentProductIds = collect($this->getCartProductIds())
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($currentProductIds->isEmpty()) {
            return null;
        }

        return DiscountApprovalRequest::where('status', DiscountApprovalRequest::STATUS_PENDING)
            ->when($this->patientId, fn ($query) => $query->where('patient_id', $this->patientId))
            ->when(!$this->patientId, fn ($query) => $query->where('cashier_id', Auth::id())->whereNull('patient_id'))
            ->latest()
            ->get()
            ->first(function ($request) use ($currentCartIds, $currentProductIds) {
                if (!$this->discountRequestCartIsStillOpen($request)) {
                    $request->delete();
                    return false;
                }

                $requestCartIds = $this->discountRequestCartIds($request);

                if ($requestCartIds->isNotEmpty() && $currentCartIds->isNotEmpty()) {
                    return $requestCartIds->intersect($currentCartIds)->isNotEmpty();
                }

                $requestProductIds = $this->discountRequestProductIds($request);

                return $requestProductIds->intersect($currentProductIds)->isNotEmpty()
                    && $this->currentCartQuantities() === $this->snapshotQuantities($request->cart_snapshot ?? []);
            });
    }

    private function deletePendingDiscountRequestsForCurrentCart(): void
    {
        while ($request = $this->findPendingDiscountRequestForCurrentCart()) {
            $request->delete();
        }
    }

    /* ===================== CART PERSISTENCE ===================== */

    private function persistCart($cartKey)
    {
        if (!$this->patientId) return;

        $cartData = $this->cart[$cartKey] ?? null;
        $productId = $this->getCartItemProductId($cartKey, $cartData);
        $product = Product::with('category')->find($productId);
        if (!$product) return;

        $quantity  = is_array($cartData) ? $cartData['quantity'] : $cartData;
        $frequency = is_array($cartData) ? ($cartData['frequency'] ?? null) : null;
        $eye       = is_array($cartData) ? ($cartData['eye'] ?? null) : null;
        $cartId    = is_array($cartData) ? ($cartData['cart_id'] ?? null) : null;

        if (!$product->isDrugCategory()) {
            $frequency = null;
            $eye = null;
        }

        if ($cartId) {
            Cart::where('id', $cartId)
                ->where('patient_id', $this->patientId)
                ->where('purchased', false)
                ->update([
                    'quantity'     => $quantity,
                    'price'        => $product->selling_price,
                    'total'        => $product->selling_price * $quantity,
                    'frequency'    => $frequency,
                    'eye'          => $eye,
                    'status'       => 'pending',
                    'is_dispensed' => false,
                ]);

            return;
        }

        Cart::updateOrCreate(
            [
                'patient_id'   => $this->patientId,
                'dispensed_by' => Auth::id(),
                'product_id'   => $productId,
                'purchased'    => false,
            ],
            [
                'consultation_id' => $this->prescriptionConsultationId,
                'quantity'        => $quantity,
                'price'           => $product->selling_price,
                'total'           => $product->selling_price * $quantity,
                'frequency'       => $frequency,
                'eye'             => $eye,
                'status'          => 'pending',
                'is_dispensed'    => false,
            ]
        );
    }

    private function loadPatientCart($consultationId = null, array $cartIds = [])
    {
        if (!$this->patientId) return;

        $this->payments         = [];
        $this->newPaymentMethod = 'cash';
        $this->newPaymentAmount = '';
        $this->discountValue    = 0;
        $this->resetDiscountApproval();

        // Fetch ANY pending items for this patient, regardless of who added them
        $items = Cart::with('product.category')
            ->where('patient_id', $this->patientId)
            ->where('purchased', false)
            ->when(!empty($cartIds), fn ($query) => $query->whereIn('id', $cartIds))
            ->when($consultationId, fn ($query) => $query->where('consultation_id', $consultationId))
            ->get();

        $this->cart = [];
        if ($items->isNotEmpty()) {
            foreach ($items as $item) {
                if ($item->product && $item->product->quantity >= $item->quantity) {
                    $fromPrescription = !empty($item->consultation_id) && $item->consultation_id != 0;
                    $categoryName     = strtolower($item->product->category->name ?? '');
                    $this->cart['cart_' . $item->id] = [
                        'product_id'       => $item->product_id,
                        'quantity'         => $item->quantity,
                        'frequency'        => $item->product->isDrugCategory() ? $item->frequency : null,
                        'eye'              => $item->product->isDrugCategory() ? $item->eye : null,
                        'cart_id'          => $item->id,
                        'from_prescription' => $fromPrescription,
                        'is_frame'         => str_contains($categoryName, 'frame'),
                    ];
                }
            }
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'success',
                'message' => count($this->cart) . ' prescribed items loaded.'
            ]);
        }

        $this->hasPrescriptionCart = collect($this->cart)
            ->contains(fn ($item) => is_array($item) && ($item['from_prescription'] ?? false));

        // Store the consultation_id from any prescription item so cashier-added frames use it
        $prescriptionItem = $items->first(fn ($i) => !empty($i->consultation_id) && $i->consultation_id != 0);
        $this->prescriptionConsultationId = $prescriptionItem ? $prescriptionItem->consultation_id : null;

        $this->calculateTotal();
    }

    private function loadCartFromDiscountSnapshot(array $snapshot): void
    {
        $this->cart = [];

        foreach ($snapshot as $item) {
            $productId = $item['product_id'] ?? null;

            if (!$productId) {
                continue;
            }

            $product = Product::with('category')->find($productId);

            if (!$product || $product->quantity <= 0) {
                continue;
            }

            $quantity = max(1, min((int) ($item['quantity'] ?? 1), (int) $product->quantity));

            $this->cart[$productId] = [
                'product_id' => (int) $productId,
                'quantity' => $quantity,
                'frequency' => $product->isDrugCategory() ? ($item['frequency'] ?? null) : null,
                'eye' => $product->isDrugCategory() ? ($item['eye'] ?? null) : null,
                'cart_id' => null,
            ];

            $this->persistCart($productId);
        }

        $this->calculateTotal();
    }

    /* ===================== CHECKOUT ===================== */

    public function initiateCheckout()
    {
        if (empty($this->cart)) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Please add items to cart before checkout.',
            ]);
            return;
        }

        if ($this->discountAmount > 0 && !$this->discountApproved) {
            $this->confirmSellWithoutPendingDiscount();
            return;
        }

        if (!$this->discountApproved && $pendingRequest = $this->findPendingDiscountRequestForCurrentCart()) {
            $this->confirmSellWithoutPendingDiscount($pendingRequest);
            return;
        }

        $amountPaid  = $this->getTotalPaid();
        $finalAmount = (float) ($this->finalAmount ?? 0);

        if ($amountPaid <= 0 && $finalAmount > 0) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Please add at least one payment before checkout.',
            ]);
            return;
        }

        if (!$this->isPartPayment && $amountPaid < $finalAmount) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Total paid (GH₵ ' . number_format($amountPaid, 2) . ') is less than total due (GH₵ ' . number_format($finalAmount, 2) . ').',
            ]);
            return;
        }

        if ($this->isPartPayment && $amountPaid >= $finalAmount) {
            // Payments cover full amount — treat as full payment
            $this->isPartPayment = false;
        }

        $balance = max(0, $finalAmount - $amountPaid);

        $this->dispatchBrowserEvent('show-checkout-confirmation', [
            'totalAmount'    => number_format($finalAmount, 2),
            'amountPaid'     => number_format($amountPaid, 2),
            'change'         => number_format($this->change, 2),
            'balance'        => number_format($balance, 2),
            'isPartPayment'  => $this->isPartPayment,
            'itemCount'      => count($this->cart),
            'discountAmount' => $this->discountAmount > 0 ? number_format($this->discountAmount, 2) : null,
        ]);
    }

    public function dismissReceipt()
    {
        $this->receiptData = null;
        $this->showReceipt = false;
    }

    public function checkout()
    {
        // Guard: prevent double-firing from Livewire listener + Alpine
        if ($this->checkoutProcessing) {
            Log::info('=== CHECKOUT already processing — duplicate call ignored ===');
            return;
        }
        $this->checkoutProcessing = true;

        Log::info('=== CHECKOUT METHOD CALLED ===');

        if (empty($this->cart)) {
            $this->checkoutProcessing = false;
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Cart is empty']);
            return;
        }

        if ($this->discountAmount > 0 && !$this->discountApproved) {
            $this->checkoutProcessing = false;
            $this->confirmSellWithoutPendingDiscount();
            return;
        }

        if (!$this->discountApproved && $pendingRequest = $this->findPendingDiscountRequestForCurrentCart()) {
            $this->checkoutProcessing = false;
            $this->confirmSellWithoutPendingDiscount($pendingRequest);
            return;
        }

        if ($this->discountAmount > 0 && $this->pendingDiscountApprovalId) {
            $approvedRequest = DiscountApprovalRequest::where('id', $this->pendingDiscountApprovalId)
                ->where('status', DiscountApprovalRequest::STATUS_APPROVED)
                ->first();

            if (
                !$approvedRequest ||
                !$this->discountRequestCartIsStillOpen($approvedRequest) ||
                !$this->currentCartMatchesDiscountRequest($approvedRequest)
            ) {
                $this->checkoutProcessing = false;
                $this->resetDiscountApproval();

                $this->dispatchBrowserEvent('notify', [
                    'type' => 'error',
                    'message' => 'Discount approval is no longer valid for this cart. Request approval again or remove the discount.',
                ]);
                return;
            }
        }

        $amountPaid    = (float) ($this->amountPaid ?? 0);
        $isPartPayment = $this->isPartPayment;
        $finalAmount   = (float) ($this->finalAmount ?? 0);

        if (!$isPartPayment && $amountPaid < $finalAmount) {
            $this->checkoutProcessing = false;
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Insufficient payment']);
            return;
        }

        DB::beginTransaction();

        try {
            $products = Product::with('category')->whereIn('id', $this->getCartProductIds())->get()->keyBy('id');

            $requiredQuantities = [];

            foreach ($this->cart as $cartKey => $cartItem) {
                $productId = $this->getCartItemProductId($cartKey, $cartItem);
                $qty = is_array($cartItem) ? $cartItem['quantity'] : $cartItem;

                if (!$productId) {
                    throw new \Exception('Invalid cart item detected');
                }

                $requiredQuantities[$productId] = ($requiredQuantities[$productId] ?? 0) + $qty;
            }

            // Stock validation
            foreach ($requiredQuantities as $productId => $requiredQuantity) {
                if (!isset($products[$productId]) || $products[$productId]->quantity < $requiredQuantity) {
                    $productName = isset($products[$productId]) ? $products[$productId]->name : 'Unknown';
                    throw new \Exception('Insufficient stock for ' . $productName);
                }
            }

            // Generate transaction ID using UUID for guaranteed uniqueness
            $transactionId = now()->format('dmY') . '-' . strtoupper(Str::random(8));

            // Calculate profit
            $total_profit = 0;
            foreach ($this->cart as $cartKey => $cartItem) {
                $productId = $this->getCartItemProductId($cartKey, $cartItem);

                if (isset($products[$productId])) {
                    $qty           = is_array($cartItem) ? $cartItem['quantity'] : $cartItem;
                    $total_profit += ($products[$productId]->selling_price - $products[$productId]->cost_price) * $qty;
                }
            }

            $paymentStatus = $isPartPayment ? 'partial' : 'paid';

            // Create sale record
            $sale = Sales::create([
                'user_id'         => Auth::id(),
                'patient_id'      => $this->patientId,
                'consultation_id' => $this->prescriptionConsultationId,
                'total_amount'    => $finalAmount,
                'amount_paid'     => $amountPaid,
                'payment_status'  => $paymentStatus,
                'profit'          => $isPartPayment ? 0 : max(0, $total_profit - $this->discountAmount),
                'transaction_id'  => $transactionId,
                'discount_type'        => $this->discountAmount > 0 ? $this->discountType      : null,
                'discount_value'       => $this->discountAmount > 0 ? $this->discountValue     : null,
                'discount_amount'      => $this->discountAmount,
                'discount_approved_by' => $this->discountAmount > 0 ? $this->discountApprovedById : null,
            ]);

            // Send SMS receipt for full payments only
            if ($paymentStatus === 'paid' && $this->patientId) {
                $patient = \App\Models\Patient::find($this->patientId);
                if ($patient?->contact) {
                    $clinic = \App\Models\Setting::getSettings()->clinic_name ?? 'the clinic';
                    $msg = SmsTemplate::render('payment_receipt', [
                        '[NAME]'   => $patient->name,
                        '[AMOUNT]' => number_format($finalAmount, 2),
                        '[TXN_ID]' => $transactionId,
                        '[CLINIC]' => $clinic,
                    ]);
                    if ($msg) (new SmsService)->send($patient->contact, $msg, $patient->id, 'payment_receipt');
                }
                if ($patient?->email) {
                    $clinic = \App\Models\Setting::getSettings()->clinic_name ?? 'the clinic';
                    (new EmailService)->send($patient->email, new PaymentReceiptMail(
                        $patient->name,
                        $clinic,
                        number_format($finalAmount, 2),
                        $transactionId,
                        now()->format('M d, Y h:i A'),
                    ));
                }
            }

            // Log one PaymentTransaction per split-payment entry
            foreach ($this->payments as $payment) {
                \App\Models\PaymentTransaction::create([
                    'sale_id'        => $sale->id,
                    'amount'         => $payment['amount'],
                    'payment_method' => $payment['method'],
                    'notes'          => $isPartPayment ? 'Initial deposit' : 'Full payment',
                    'collected_by'   => Auth::id(),
                ]);
            }

            // Create sale items & decrement stock (stock reserved even for partial)
            foreach ($this->cart as $cartKey => $cartItem) {
                $productId = $this->getCartItemProductId($cartKey, $cartItem);
                $product   = $products[$productId];
                $qty       = is_array($cartItem) ? $cartItem['quantity'] : $cartItem;
                $frequency = is_array($cartItem) ? ($cartItem['frequency'] ?? null) : null;
                $eye       = is_array($cartItem) ? ($cartItem['eye'] ?? null)       : null;
                if (!$product->isDrugCategory()) {
                    $frequency = null;
                    $eye = null;
                }
                $cartId    = is_array($cartItem) ? ($cartItem['cart_id'] ?? null)  : null;

                SaleItem::create([
                    'sale_id'             => $sale->id,
                    'cart_id'             => $cartId,
                    'product_id'          => $productId,
                    'prescribed_quantity' => $isPartPayment ? $qty : 0,
                    'dispensed_quantity'  => $isPartPayment ? 0    : $qty,
                    'selling_price'       => $product->selling_price,
                    'subtotal'            => $qty * $product->selling_price,
                    'frequency'           => $frequency,
                    'eye'                 => $eye,
                    'notes'               => $isPartPayment ? 'On Hold - Part Payment' : ($frequency ? 'Prescription Sale' : 'Direct POS Sale'),
                ]);

                $product->decrement('quantity', $qty);
            }

            // Mark the exact cart rows included in this checkout.
            // Prescription cart rows are created by doctors, so filtering by cashier user id
            // can leave them pending and allow stock to be deducted again on a later sale.
            if ($this->patientId) {
                $cartIds = collect($this->cart)
                    ->filter(fn ($cartItem) => is_array($cartItem) && !empty($cartItem['cart_id']))
                    ->pluck('cart_id')
                    ->unique()
                    ->values();

                if ($cartIds->isNotEmpty()) {
                    Cart::whereIn('id', $cartIds)
                        ->where('patient_id', $this->patientId)
                        ->where('purchased', false)
                        ->update([
                            'purchased'    => true,
                            'status'       => 'completed',
                            'is_dispensed' => !$isPartPayment,
                            'dispensed_at' => !$isPartPayment ? now() : null,
                            'dispensed_by' => Auth::id(),
                        ]);
                } else {
                    Cart::where('patient_id', $this->patientId)
                        ->where('dispensed_by', Auth::id())
                        ->where('purchased', false)
                        ->update([
                            'purchased'    => true,
                            'status'       => 'completed',
                            'is_dispensed' => !$isPartPayment,
                            'dispensed_at' => !$isPartPayment ? now() : null,
                            'dispensed_by' => Auth::id(),
                        ]);
                }
            }

            if ($this->discountAmount > 0 && $this->discountApprovedById) {
                if ($this->pendingDiscountApprovalId) {
                    DiscountApprovalRequest::where('id', $this->pendingDiscountApprovalId)
                        ->where('status', DiscountApprovalRequest::STATUS_APPROVED)
                        ->update(['status' => DiscountApprovalRequest::STATUS_USED]);
                }

                AuditTrail::record(
                    'discount.approved',
                    'Discount of GH₵ ' . number_format($this->discountAmount, 2) . ' approved by ' . $this->discountApprovedBy . ' for sale ' . $sale->transaction_id,
                    $sale,
                    [],
                    [
                        'discount_type'   => $this->discountType,
                        'discount_value'  => $this->discountValue,
                        'discount_amount' => $this->discountAmount,
                        'approved_by_id'  => $this->discountApprovedById,
                        'approved_by'     => $this->discountApprovedBy,
                    ],
                    $this->patientId
                );
            }

            AuditTrail::record(
                'payment.received',
                'Recorded ' . ($isPartPayment ? 'part payment' : 'full payment') . ' for sale ' . $sale->transaction_id,
                $sale,
                [],
                [
                    'total_amount' => $sale->total_amount,
                    'amount_paid' => $amountPaid,
                    'payment_status' => $paymentStatus,
                    'items' => count($this->cart),
                ],
                $this->patientId
            );

            DB::commit();

            // Low-stock alerts — fire after commit so stock values are final in DB
            $lowStockThreshold = 5;
            foreach ($requiredQuantities as $productId => $deducted) {
                if (!isset($products[$productId])) continue;
                $remaining = $products[$productId]->quantity - $deducted;
                if ($remaining >= 0 && $remaining <= $lowStockThreshold) {
                    $stockLabel = $remaining === 0 ? 'OUT OF STOCK' : $remaining . ' unit' . ($remaining === 1 ? '' : 's') . ' left';
                    \App\Services\NotificationService::sendToRoles(
                        ['Super Admin', 'Manager'],
                        'low_stock',
                        'Low Stock: ' . $products[$productId]->name,
                        $stockLabel . ' — restock soon.',
                        'fas fa-exclamation-triangle',
                        $remaining === 0 ? 'text-danger' : 'text-warning',
                        route('admin.inventory-alerts')
                    );
                }
            }

            Log::info('=== Sale completed. Sale ID: ' . $sale->id . ' ===');

            $saleData     = Sales::with(['items.product', 'patient', 'user'])->find($sale->id);
            $changeAmount = $this->change;

            // Fresh settings from DB
            $settings = Setting::getSettings();

            // Build receipt data array
            $this->lastSaleId = $saleData->id;
            $this->receiptData = [
                'sale_id'         => $saleData->id,
                'transaction_id'  => $saleData->transaction_id,
                'created_at'      => $saleData->created_at->format('M d, Y h:i A'),
                'gross_amount'    => $this->totalAmount,
                'discount_type'   => $this->discountType,
                'discount_value'  => $this->discountValue,
                'discount_amount' => $this->discountAmount,
                'total_amount'    => $saleData->total_amount,
                'amount_paid'    => $amountPaid,
                'balance'        => max(0, (float) $saleData->total_amount - $amountPaid),
                'payments'       => $this->payments,
                'payment_status' => $paymentStatus,
                'change'         => $isPartPayment ? 0 : $changeAmount,
                'served_by'      => $saleData->user->name ?? 'Staff',
                'clinic_name'    => ($settings && $settings->clinic_name)    ? $settings->clinic_name    : 'PHARMACY POS',
                'clinic_address' => ($settings && $settings->clinic_address) ? $settings->clinic_address : 'Medical Dispensary',
                'clinic_contact' => ($settings && $settings->clinic_contact) ? $settings->clinic_contact : '',
                'clinic_email'   => ($settings && $settings->clinic_email)   ? $settings->clinic_email   : '',
                'clinic_logo'    => $settings ? $settings->logoDataUri() : null,
                'patient'        => $saleData->patient ? [
                    'name'     => $saleData->patient->name,
                    'contact'  => $saleData->patient->contact  ?? 'N/A',
                    'pxnumber' => $saleData->patient->pxnumber ?? 'N/A',
                ] : null,
                'items' => $saleData->items->map(function ($item) {
                    return [
                        'name'          => $item->product->name ?? 'N/A',
                        'quantity'      => $item->dispensed_quantity,
                        'selling_price' => $item->selling_price,
                        'subtotal'      => $item->subtotal,
                    ];
                })->toArray(),
            ];

            $this->dispatchBrowserEvent('receipt-data-ready', array_merge(
                $this->receiptData,
                ['printed_at' => now()->format('M d, Y h:i A')]
            ));

            $this->showReceipt = true;

            $this->dispatchBrowserEvent('close-processing-modal');

            $this->dispatchBrowserEvent('notify', [
                'type'    => 'success',
                'message' => 'Transaction completed successfully!',
            ]);

            $this->cart             = [];
            $this->payments         = [];
            $this->newPaymentMethod = 'cash';
            $this->newPaymentAmount = '';
            $this->amountPaid       = 0;
            $this->change           = 0;
            $this->totalAmount      = 0;
            $this->discountValue    = 0;
            $this->discountAmount   = 0;
            $this->finalAmount      = 0;
            $this->discountApproved     = false;
            $this->discountApprovedBy   = null;
            $this->discountApprovedById = null;
            $this->pendingDiscountApprovalId = null;
            $this->pendingDiscountApprovalStatus = null;
            $this->isPartPayment        = false;
            $this->hasFramesOrLenses    = false;
            $this->checkoutProcessing   = false;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage());

            $this->checkoutProcessing = false;

            $this->dispatchBrowserEvent('close-processing-modal');
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Transaction failed: ' . $e->getMessage(),
            ]);
        }
    }

    /* ===================== RENDER ===================== */

    public function render()
    {
        $products = Product::with('category')
            ->where(function ($q) {
                $term = $this->productSearchTerm;
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('batch_number', 'like', '%' . $term . '%');
            })
            ->where(fn ($q) => $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', now()))
            ->when($this->selectedCategoryId, fn($q) => $q->where('category_id', $this->selectedCategoryId))
            ->paginate(12);

        $categories     = Cache::remember('pos.categories', 300, fn() => Category::orderBy('name')->get());
        $clinicSettings = Cache::remember('pos.settings', 300, fn() => Setting::getSettings());

        $cartProducts = $this->fetchCartProducts();

        $totalPaid       = $this->getTotalPaid();
        $discountBlocked = $this->discountAmount > 0 && !$this->discountApproved;
        $canCheckout     = $discountBlocked || ($totalPaid > 0 && (
            ($this->isPartPayment && $totalPaid < $this->finalAmount) ||
            $totalPaid >= $this->finalAmount
        ));
        $pendingPrescriptionCartCount = Cart::where('purchased', false)
            ->where('status', 'pending')
            ->whereNotNull('consultation_id')
            ->where('consultation_id', '!=', 0)
            ->distinct()
            ->count('patient_id');
        $approvedDiscountCount = DiscountApprovalRequest::where('status', DiscountApprovalRequest::STATUS_APPROVED)
            ->where('cashier_id', Auth::id())
            ->count();

        return view('livewire.pos-component', compact(
            'products', 'categories', 'clinicSettings',
            'cartProducts', 'discountBlocked', 'canCheckout', 'totalPaid',
            'pendingPrescriptionCartCount', 'approvedDiscountCount'
        ))->layout('layouts.secretary.secretary-layout');
    }
}
