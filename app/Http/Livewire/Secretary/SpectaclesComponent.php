<?php

namespace App\Http\Livewire\Secretary;

use App\Models\LensOrder;
use App\Models\Refractions;
use App\Models\Product;
use App\Models\Setting;
use App\Models\AuditTrail;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SmsService;
use App\Services\EmailService;
use App\Models\SmsTemplate;
use App\Mail\SpectaclesReadyMail;

class SpectaclesComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $searchTerm   = '';
    public $fromDate;
    public $toDate;
    public $statusFilter = '';
    public $quickFilter  = '';
    public $labFilter    = '';
    public $doctorFilter = '';
    public $sortField      = 'created_at';
    public $sortDirection  = 'desc';
    public $activeRefractionId = null;

    // Bulk selection
    public $selectedOrders = [];
    public $selectAllOrders = false;
    public $bulkStatus = '';

    // Inline editing
    public $editingOrderId;
    public $editField = [];

    // Order creation modal
    public $showOrderModal      = false;
    public $selectedRefractionId;
    public $selectedFrameId;
    public $selectedLensId;
    public $frameSearchTerm  = '';
    public $lensSearchTerm   = '';
    public $framePrice       = 0;
    public $lensPrice        = 0;
    public $pickUpDate;
    public $orderNotes;
    public $labName          = '';
    public $labReference     = '';
    public $showFrameResults = false;
    public $showLensResults  = false;

    // Renewal date editing
    public $renewalEditOrderId = null;
    public $renewalEditDate    = '';

    // Cancel confirmation
    public $cancelConfirmId = null;
    public $cancelReason    = '';

    // Print preview
    public $showPrintPreview    = false;
    public $printPreviewOrderId = null;
    public $autoPrint           = false;

    protected $queryString = [
        'searchTerm',
        'statusFilter',
        'quickFilter',
        'doctorFilter',
        'labFilter',
    ];

    protected $listeners = ['updateOrderStatus'];

    /* =================== LIFECYCLE =================== */

    public function mount()
    {
        $this->fromDate = now()->subMonths(3)->format('Y-m-d');
        $this->toDate   = now()->format('Y-m-d');
    }

    /* =================== FILTERS =================== */

    public function updatedSearchTerm()  { $this->resetPage(); }
    public function updatedStatusFilter(){ $this->resetPage(); }
    public function updatedDoctorFilter(){ $this->resetPage(); }
    public function updatedLabFilter()   { $this->resetPage(); }

    public function setQuickFilter($filter)
    {
        $this->quickFilter = $this->quickFilter === $filter ? '' : $filter;
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->quickFilter  = '';
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->searchTerm   = '';
        $this->fromDate     = now()->subMonths(3)->format('Y-m-d');
        $this->toDate       = now()->format('Y-m-d');
        $this->statusFilter = '';
        $this->quickFilter  = '';
        $this->labFilter    = '';
        $this->doctorFilter = '';
        $this->resetPage();
    }

    /* =================== SORTING =================== */

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortField     = $field;
        $this->resetPage();
    }

    public function selectRefraction($refractionId): void
    {
        $this->activeRefractionId = (int) $refractionId;
    }

    /* =================== BULK SELECTION =================== */

    public function toggleOrderSelection($orderId)
    {
        $orderId              = (int) $orderId;
        $this->selectedOrders = in_array($orderId, $this->selectedOrders)
            ? array_values(array_diff($this->selectedOrders, [$orderId]))
            : array_values(array_unique([...$this->selectedOrders, $orderId]));
        $this->selectAllOrders = false;
    }

    public function updatedSelectAllOrders()
    {
        if ($this->selectAllOrders) {
            $this->selectedOrders = $this->getFilteredQuery()
                ->whereHas('lensOrder')
                ->with('lensOrder:id,refraction_id')
                ->get()
                ->map(fn($r) => $r->lensOrder?->id)
                ->filter()
                ->values()
                ->toArray();
        } else {
            $this->selectedOrders = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedOrders  = [];
        $this->selectAllOrders = false;
    }

    public function bulkUpdateStatus()
    {
        if (!$this->bulkStatus || empty($this->selectedOrders)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Select orders and a status first.']);
            return;
        }

        $updated = 0;
        foreach (LensOrder::whereIn('id', $this->selectedOrders)->get() as $order) {
            $old = $order->status;
            $order->update(['status' => $this->bulkStatus]);
            $this->appendOrderNote($order, "Bulk status changed to {$this->bulkStatus} by " . (Auth::user()->name ?? 'staff'));
            $this->recordOrderAudit('spectacles.bulk_status_changed', $order, ['status' => $old], ['status' => $this->bulkStatus]);
            $updated++;
        }

        $this->bulkStatus = '';
        $this->clearSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "{$updated} order(s) updated."]);
    }

    /* =================== INLINE EDITING =================== */

    public function startEdit($orderId, $field)
    {
        $this->editingOrderId    = $orderId;
        $order                   = LensOrder::findOrFail($orderId);
        $this->editField[$field] = $order->{$field};
    }

    public function saveEdit($orderId, $field)
    {
        $order = LensOrder::findOrFail($orderId);
        $old   = $order->{$field};
        $order->update([$field => $this->editField[$field] ?? null]);
        $this->recordOrderAudit('spectacles.field_edited', $order, [$field => $old], [$field => $order->{$field}]);
        $this->editingOrderId = null;
        $this->editField      = [];
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => ucfirst($field) . ' updated.']);
    }

    public function cancelEdit()
    {
        $this->editingOrderId = null;
        $this->editField      = [];
    }

    /* =================== STATUS MANAGEMENT =================== */

    public function updateOrderStatus($orderId, $newStatus)
    {
        $this->updateStatus($orderId, $newStatus);
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order     = LensOrder::findOrFail($orderId);
        $oldStatus = $order->status;

        $noteMap = [
            'Collected' => 'Collected by ' . (Auth::user()->name ?? 'staff') . ' on ' . now()->format('d M Y H:i'),
            'Ready'     => 'Ready for pickup - ' . now()->format('d M Y H:i'),
            'In Lab'    => 'Sent to lab - ' . now()->format('d M Y H:i'),
        ];

        if (isset($noteMap[$newStatus])) {
            $this->appendOrderNote($order, $noteMap[$newStatus]);
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'Collected' && !$order->collected_at) {
            $updateData['collected_at']  = now();
            $updateData['renewal_date']  = now()->addYear()->toDateString();
        }

        $order->update($updateData);
        $this->recordOrderAudit('spectacles.status_changed', $order, ['status' => $oldStatus], ['status' => $newStatus]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Order marked as {$newStatus}."]);

        if ($newStatus === 'Ready') {
            $order->load('refraction.consultation.patient');
            $patient = optional(optional($order->refraction)->consultation)->patient;
            $patientName = $patient?->name ?? 'Patient';

            \App\Services\NotificationService::sendToRoles(
                ['Secretary', 'Manager', 'Super Admin'],
                'spectacles_ready',
                'Spectacles Ready: ' . $patientName,
                'Order ' . $order->order_id . ' is ready for collection.',
                'fas fa-glasses',
                'text-info',
                route('secretary.spectacles'),
                ['order_id' => $order->id],
                Auth::id()
            );

            if ($patient?->contact) {
                $clinic = Setting::getSettings()->clinic_name ?? 'the clinic';
                $smsMsg = SmsTemplate::render('spectacles_ready', [
                    '[NAME]'     => $patient->name,
                    '[ORDER_ID]' => $order->order_id,
                    '[CLINIC]'   => $clinic,
                ]);
                if ($smsMsg) (new SmsService)->send($patient->contact, $smsMsg, $patient->id, 'spectacles_ready');
            }
            if ($patient?->email) {
                $clinic = Setting::getSettings()->clinic_name ?? 'the clinic';
                (new EmailService)->send($patient->email, new SpectaclesReadyMail(
                    $patient->name, $clinic, $order->order_id
                ));
            }
        }

    }

    /* =================== ORDER CREATION =================== */

    public function openOrderModal($refractionId)
    {
        $refraction = Refractions::with([
            'consultation.cartItems.product.category',
            'consultation.sale.items.product.category',
        ])->findOrFail($refractionId);

        if (!$this->canCreateOrderFromPos($this->posOrderSummary($refraction))) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Record a part or full payment at POS before creating a spectacle order.',
            ]);
            return;
        }

        $this->selectedRefractionId = $refractionId;
        $this->showOrderModal       = true;
        $this->pickUpDate           = now()->addDays(7)->format('Y-m-d');
        $this->selectedFrameId      = null;
        $this->selectedLensId       = null;
        $this->frameSearchTerm      = '';
        $this->lensSearchTerm       = '';
        $this->framePrice           = 0;
        $this->lensPrice            = 0;
        $this->orderNotes           = '';
        $this->labName              = '';
        $this->labReference         = '';
        $this->showFrameResults     = false;
        $this->showLensResults      = false;
        $this->resetErrorBag();
    }

    public function closeOrderModal()
    {
        $this->showOrderModal       = false;
        $this->selectedRefractionId = null;
        $this->resetErrorBag();
    }

    public function createOrder()
    {
        $this->validate([
            'pickUpDate'  => 'required|date|after_or_equal:today',
            'orderNotes'  => 'nullable|string|max:2000',
        ]);

        $refraction   = Refractions::with(['consultation.patient', 'lensOrder'])->findOrFail($this->selectedRefractionId);

        if ($refraction->lensOrder) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'An order already exists for this refraction.']);
            return;
        }

        $posSummary = $this->posOrderSummary($refraction);
        if (!$this->canCreateOrderFromPos($posSummary)) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Record a part or full payment at POS before creating a spectacle order.',
            ]);
            return;
        }

        $orderId = 'ORD-' . strtoupper(Str::random(8));

        $order = LensOrder::create([
            'user_id'            => Auth::id(),
            'refraction_id'      => $this->selectedRefractionId,
            'order_id'           => $orderId,
            'frame_model_number' => 'To be assigned',
            'frame_product_id'   => null,
            'lens_product_id'    => null,
            'frame_price'        => 0,
            'lens_price'         => 0,
            'status'             => 'Pending',
            'pickUpDate'         => $this->pickUpDate,
            'notes'              => trim($this->orderNotes ?? ''),
        ]);

        $this->recordOrderAudit('spectacles.created', $order, [], [
            'order_id'    => $order->order_id,
            'pickUpDate'  => $order->pickUpDate,
        ]);

        $this->closeOrderModal();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Order {$orderId} created for {$refraction->consultation->patient->name}!"]);
    }

    /* =================== CANCEL ORDER =================== */

    public function openCancelConfirm($orderId)
    {
        $this->cancelConfirmId = $orderId;
        $this->cancelReason    = '';
    }

    public function closeCancelConfirm()
    {
        $this->cancelConfirmId = null;
        $this->cancelReason    = '';
    }

    public function confirmCancelOrder()
    {
        if (!$this->cancelConfirmId) return;

        $cancelConfirmId = $this->cancelConfirmId;
        $cancelReason    = $this->cancelReason;

        \DB::transaction(function () use ($cancelConfirmId, $cancelReason) {
            $order = LensOrder::findOrFail($cancelConfirmId);
            $order->update(['status' => 'Cancelled']);
            $this->appendOrderNote($order, 'Cancelled - ' . ($cancelReason ?: 'no reason given') . ' - ' . (Auth::user()->name ?? 'staff'));

            if ($order->frame_product_id) Product::where('id', $order->frame_product_id)->increment('quantity');
            if ($order->lens_product_id)  Product::where('id', $order->lens_product_id)->increment('quantity');

            $this->recordOrderAudit('spectacles.cancelled', $order, [], ['reason' => $cancelReason]);
        });

        $this->closeCancelConfirm();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order cancelled and stock restored.']);
    }

    /* =================== PRINT PREVIEW =================== */

    public function openPrintPreview($orderId)
    {
        $this->printPreviewOrderId = $orderId;
        $this->showPrintPreview    = true;
        $this->autoPrint           = false;
    }

    public function directPrint($orderId)
    {
        $this->printPreviewOrderId = $orderId;
        $this->showPrintPreview    = true;
        $this->autoPrint           = true;
        $this->dispatchBrowserEvent('auto-print-job-card');
    }

    public function closePrintPreview()
    {
        $this->showPrintPreview    = false;
        $this->printPreviewOrderId = null;
        $this->autoPrint           = false;
    }

    public function downloadJobCard($orderId)
    {
        $order      = LensOrder::with([
            'refraction.consultation.patient',
            'refraction.consultation.sale.items.product.category',
            'frameProduct',
            'lensProduct',
            'user',
        ])->findOrFail($orderId);
        $appSettings = Setting::getSettings();
        $pdf        = Pdf::loadView('pdf.job-card-thermal', compact('order', 'appSettings'));
        return response()->streamDownload(fn() => print($pdf->output()), "JobCard_{$order->order_id}.pdf");
    }

    /* =================== PRODUCT SEARCH =================== */

    public function updatedFrameSearchTerm()
    {
        $this->showFrameResults = strlen($this->frameSearchTerm) >= 2;
        $this->selectedFrameId  = null;
        $this->framePrice       = 0;
    }

    public function updatedLensSearchTerm()
    {
        $this->showLensResults = strlen($this->lensSearchTerm) >= 2;
        $this->selectedLensId  = null;
        $this->lensPrice       = 0;
    }

    public function selectFrameById($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;
        $this->selectedFrameId  = $product->id;
        $this->frameSearchTerm  = $product->name;
        $this->framePrice       = $product->selling_price;
        $this->showFrameResults = false;
    }

    public function selectLensById($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;
        $this->selectedLensId  = $product->id;
        $this->lensSearchTerm  = $product->name;
        $this->lensPrice       = $product->selling_price;
        $this->showLensResults = false;
    }

    /* =================== REMINDERS =================== */

    public function sendReadyReminder($orderId): void
    {
        $order   = LensOrder::with('refraction.consultation.patient')->findOrFail($orderId);
        $patient = optional(optional($order->refraction)->consultation)->patient;
        $contact = $patient?->contact ?? '';

        $this->appendOrderNote($order, "Ready-pickup reminder sent to " . ($contact ?: 'no contact') . " – " . now()->format('d M Y H:i'));

        if ($contact) {
            $clinic  = Setting::getSettings()->clinic_name ?? 'the clinic';
            $smsMsg  = SmsTemplate::render('spectacles_reminder', [
                '[NAME]'     => $patient->name,
                '[ORDER_ID]' => $order->order_id,
                '[CLINIC]'   => $clinic,
            ]) ?: "Hello {$patient->name}, your spectacles (Order {$order->order_id}) are still waiting for collection at {$clinic}.";
            $result  = (new SmsService)->send($contact, $smsMsg);
            $type = $result['success'] ? 'success' : 'warning';
            $msg  = $result['success']
                ? 'Reminder SMS sent successfully.'
                : 'Reminder logged (SMS failed: ' . ($result['error'] ?? 'unknown') . ')';
        } else {
            $type = 'info';
            $msg  = 'Reminder logged — no contact number on record.';
        }

        $this->dispatchBrowserEvent('notify', ['type' => $type, 'message' => $msg]);
    }

    /* =================== RENEWAL DATE =================== */

    public function openRenewalEdit($orderId): void
    {
        $order                   = LensOrder::findOrFail($orderId);
        $this->renewalEditOrderId = $orderId;
        $this->renewalEditDate    = $order->renewal_date?->format('Y-m-d') ?? '';
    }

    public function saveRenewalDate(): void
    {
        $this->validate(['renewalEditDate' => 'required|date']);

        $order = LensOrder::findOrFail($this->renewalEditOrderId);
        $old   = $order->renewal_date?->toDateString();
        $order->update([
            'renewal_date'             => $this->renewalEditDate,
            'renewal_reminder_sent_at' => null,
        ]);
        $this->recordOrderAudit('spectacles.renewal_date_updated', $order, ['renewal_date' => $old], ['renewal_date' => $this->renewalEditDate]);
        $this->renewalEditOrderId = null;
        $this->renewalEditDate    = '';
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Renewal date updated.']);
    }

    public function cancelRenewalEdit(): void
    {
        $this->renewalEditOrderId = null;
        $this->renewalEditDate    = '';
    }

    /* =================== EXPORT =================== */

    public function exportCSV()
    {
        $filename = 'spectacles_' . date('Y-m-d_His') . '.csv';
        $data     = $this->getFilteredQuery()->get();

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Patient', 'Doctor', 'Frame', 'Lens Type', 'Status', 'Pickup Date', 'Lab', 'Created']);
            foreach ($data as $row) {
                $order = $row->lensOrder;
                fputcsv($file, [
                    $order?->order_id ?? '',
                    optional(optional($row->consultation)->patient)->name ?? '',
                    optional(optional($row->consultation)->doctor)->name ?? '',
                    $order?->frame_model_number ?? '',
                    $row->lensType ?? '',
                    $order?->status ?? 'Pending (no order)',
                    $order?->pickUpDate ?? '',
                    $order ? $this->extractNoteValue($order, 'Lab') : '',
                    $order?->created_at?->format('Y-m-d H:i') ?? '',
                ]);
            }
            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /* =================== QUERY BUILDER =================== */

    private function getFilteredQuery()
    {
        $query = Refractions::query()
            ->with([
                'consultation.patient',
                'consultation.doctor',
                'consultation.cartItems.product.category',
                'consultation.sale.items.product.category',
                'lensOrder',
            ])
            ->when($this->searchTerm, function ($q) {
                $term = $this->searchTerm;
                $q->where(function ($inner) use ($term) {
                    $inner->whereHas('consultation.patient', fn($p) =>
                        $p->where('name', 'like', "%{$term}%")
                    )
                    ->orWhereHas('lensOrder', fn($l) =>
                        $l->where('frame_model_number', 'like', "%{$term}%")
                          ->orWhere('order_id', 'like', "%{$term}%")
                    );
                });
            })
            ->when($this->doctorFilter, fn($q) =>
                $q->whereHas('consultation', fn($c) => $c->where('user_id', $this->doctorFilter))
            );

        if ($this->statusFilter) {
            if ($this->statusFilter === 'Pending') {
                $query->doesntHave('lensOrder');
            } elseif ($this->statusFilter === 'Ordered') {
                $query->whereHas('lensOrder', fn($l) => $l->where('status', 'Pending'));
            } else {
                $query->whereHas('lensOrder', fn($l) => $l->where('status', $this->statusFilter));
            }
        }

        if ($this->labFilter) {
            $query->whereHas('lensOrder', fn($q) =>
                $q->where('notes', 'like', "%[Lab: {$this->labFilter}%")
            );
        }

        if ($this->quickFilter) {
            $query = $this->applyQuickFilter($query);
        }

        if ($this->fromDate || $this->toDate) {
            $query->where(function ($q) {
                $q->whereHas('lensOrder', function ($l) {
                    if ($this->fromDate) $l->whereDate('created_at', '>=', $this->fromDate);
                    if ($this->toDate)   $l->whereDate('created_at', '<=', $this->toDate);
                })
                ->orDoesntHave('lensOrder');
            });
        }

        if ($this->sortField === 'pickUpDate') {
            $query->orderByRaw(
                '(SELECT pickUpDate FROM lens_orders WHERE lens_orders.refraction_id = refractions.id AND lens_orders.deleted_at IS NULL) ' . $this->sortDirection
            );
        } elseif (in_array($this->sortField, ['created_at', 'updated_at'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }

    private function applyQuickFilter($query)
    {
        return match ($this->quickFilter) {
            'today'          => $query->whereHas('lensOrder', fn($q) => $q->whereDate('created_at', today())),
            'week'           => $query->whereHas('lensOrder', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
            'overdue'        => $query->whereHas('lensOrder', fn($q) => $q->where('status', 'Ready')->whereDate('updated_at', '<=', now()->subDays(7))),
            'ready'          => $query->whereHas('lensOrder', fn($q) => $q->where('status', 'Ready')),
            'renewal_due'    => $query->whereHas('lensOrder', fn($q) =>
                                    $q->where('status', 'Collected')
                                      ->whereNotNull('renewal_date')
                                      ->whereDate('renewal_date', '<=', now()->addDays(30))
                                      ->whereNull('renewal_reminder_sent_at')
                                 ),
            default          => $query,
        };
    }

    /* =================== HELPERS =================== */

    public function orderNoteLines($notes): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $notes))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values()
            ->toArray();
    }

    public function extractNoteValue(LensOrder $order, string $key): string
    {
        foreach ($this->orderNoteLines($order->notes) as $line) {
            if (Str::startsWith($line, "[{$key}:")) {
                return trim(Str::between($line, "[{$key}:", ']'));
            }
        }
        return '';
    }

    public function posOrderSummary($refraction): array
    {
        $consultation = $refraction->consultation;

        if (!$consultation) {
            return [
                'status' => 'none',
                'label' => 'No POS order',
                'class' => 'none',
                'amount' => 0,
                'paid' => 0,
                'balance' => 0,
                'transaction' => null,
                'items' => collect(),
            ];
        }

        $isOpticalProduct = function ($item) {
            $category = strtolower((string) optional(optional($item->product)->category)->name);

            return Str::contains($category, ['frame', 'lens']);
        };

        $opticalCarts = $consultation->cartItems
            ? $consultation->cartItems->filter($isOpticalProduct)
            : collect();

        $sale = $consultation->sale;
        $saleItems = $sale && $sale->items
            ? $sale->items->filter($isOpticalProduct)
            : collect();

        if ($sale && $saleItems->isNotEmpty()) {
            $isPaid          = $sale->payment_status === 'paid';
            $opticalSubtotal = (float) $saleItems->sum('subtotal');
            if ($opticalSubtotal <= 0) {
                $opticalSubtotal = (float) $opticalCarts->sum('total');
            }
            if ($opticalSubtotal <= 0) {
                $opticalSubtotal = (float) $sale->total_amount;
            }
            $allSubtotal     = (float) ($sale->items ? $sale->items->sum('subtotal') : 0);
            // Apply the sale-level discount proportionally to optical items
            $discountRatio   = $allSubtotal > 0 && (float) $sale->total_amount > 0 ? ((float) $sale->total_amount / $allSubtotal) : 1.0;
            $amount          = round($opticalSubtotal * $discountRatio, 2);
            $paid            = (float) $sale->total_amount > 0
                ? min($amount, round(((float) $sale->amount_paid / (float) $sale->total_amount) * $amount, 2))
                : ($isPaid ? $amount : 0);
            $balance         = max(0, $amount - $paid);

            return [
                'status' => $isPaid ? 'sold' : 'partial',
                'label' => $isPaid ? 'Sold at POS' : 'Part-paid at POS',
                'class' => $isPaid ? 'sold' : 'partial',
                'amount' => $amount,
                'paid' => $paid,
                'balance' => $balance,
                'transaction' => $sale->transaction_id,
                'items' => $saleItems,
            ];
        }

        if ($opticalCarts->where('purchased', true)->isNotEmpty()) {
            $items = $opticalCarts->where('purchased', true);

            return [
                'status' => 'sold',
                'label' => 'Sold at POS',
                'class' => 'sold',
                'amount' => (float) $items->sum('total'),
                'paid' => (float) $items->sum('total'),
                'balance' => 0,
                'transaction' => null,
                'items' => $items,
            ];
        }

        if ($opticalCarts->where('purchased', false)->isNotEmpty()) {
            $items = $opticalCarts->where('purchased', false);

            return [
                'status' => 'pending',
                'label' => 'Pending at POS',
                'class' => 'pending',
                'amount' => (float) $items->sum('total'),
                'paid' => 0,
                'balance' => (float) $items->sum('total'),
                'transaction' => null,
                'items' => $items,
            ];
        }

        return [
            'status' => 'none',
            'label' => 'Not sent to POS',
            'class' => 'none',
            'amount' => 0,
            'paid' => 0,
            'balance' => 0,
            'transaction' => null,
            'items' => collect(),
        ];
    }

    public function canCreateOrderFromPos(array $posSummary): bool
    {
        return in_array($posSummary['status'] ?? null, ['partial', 'sold'], true)
            || (float) ($posSummary['paid'] ?? 0) > 0;
    }

    private function appendOrderNote(LensOrder $order, string $note): void
    {
        $order->notes = trim((string) $order->notes . "\n[" . $note . "]");
        $order->save();
    }

    private function recordOrderAudit(string $event, LensOrder $order, array $old = [], array $new = []): void
    {
        $order->loadMissing('refraction.consultation.patient');
        $patientId = optional(optional(optional($order->refraction)->consultation)->patient)->id;
        AuditTrail::record($event, $event . ' - ' . $order->order_id, $order, $old, $new, $patientId);
    }

    /* =================== RENDER =================== */

    public function render()
    {
        // Single grouped query instead of 5 individual count queries
        $statusCounts = LensOrder::selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $stats = [
            'pending'     => Refractions::doesntHave('lensOrder')->count(),
            'ordered'     => $statusCounts->get('Pending', 0),
            'in_lab'      => $statusCounts->get('In Lab', 0),
            'ready'       => $statusCounts->get('Ready', 0),
            'collected'   => $statusCounts->get('Collected', 0),
            'overdue'     => LensOrder::where('status', 'Ready')->where('updated_at', '<=', now()->subDays(7))->count(),
            'renewal_due' => LensOrder::where('status', 'Collected')
                                ->whereNotNull('renewal_date')
                                ->whereDate('renewal_date', '<=', now()->addDays(30))
                                ->whereNull('renewal_reminder_sent_at')
                                ->count(),
        ];

        $spectacles = $this->getFilteredQuery()->paginate(12);
        $activeRefraction = $spectacles->firstWhere('id', $this->activeRefractionId) ?? $spectacles->first();

        $frameSearchResults = [];
        $lensSearchResults  = [];

        if ($this->showFrameResults && strlen($this->frameSearchTerm) >= 2) {
            $frameSearchResults = Product::whereHas('category', fn($q) => $q->where('name', 'LIKE', '%frame%'))
                ->where('quantity', '>', 0)
                ->where(fn($q) =>
                    $q->where('name', 'LIKE', "%{$this->frameSearchTerm}%")
                      ->orWhere('batch_number', 'LIKE', "%{$this->frameSearchTerm}%")
                )
                ->limit(10)->get();
        }

        if ($this->showLensResults && strlen($this->lensSearchTerm) >= 2) {
            $lensSearchResults = Product::whereHas('category', fn($q) => $q->where('name', 'LIKE', '%lens%'))
                ->where('quantity', '>', 0)
                ->where(fn($q) =>
                    $q->where('name', 'LIKE', "%{$this->lensSearchTerm}%")
                      ->orWhere('batch_number', 'LIKE', "%{$this->lensSearchTerm}%")
                )
                ->limit(10)->get();
        }

        $printOrder = null;
        if ($this->showPrintPreview && $this->printPreviewOrderId) {
            $printOrder = LensOrder::with([
                'refraction.consultation.patient',
                'refraction.consultation.sale.items.product.category',
                'frameProduct',
                'user',
            ])->find($this->printPreviewOrderId);
        }

        return view('livewire.secretary.spectacle-component', [
            'spectacles'         => $spectacles,
            'activeRefraction'   => $activeRefraction,
            'stats'              => $stats,
            'frameSearchResults' => $frameSearchResults,
            'lensSearchResults'  => $lensSearchResults,
            'printOrder'         => $printOrder,
            'appSettings'        => Setting::getSettings(),
            'doctors'            => User::whereIn('id', \App\Models\Consultations::whereNotNull('user_id')->select('user_id'))->orderBy('name')->get(['id', 'name']),
            'labs'               => LensOrder::whereNotNull('notes')->get()->map(fn($o) => $this->extractNoteValue($o, 'Lab'))->filter()->unique()->values(),
        ])->layout('layouts.secretary.secretary-layout');
    }
}
