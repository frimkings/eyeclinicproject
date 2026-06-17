<?php

namespace App\Http\Livewire\Orders;

use App\Models\AuditTrail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\OrderPayment;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewOrderComponent extends Component
{
    use WithFileUploads;

    public string $customerMode = 'existing';
    public string $customerSearch = '';
    public string $customerId = '';
    public string $customerName = '';
    public string $customerPhone = '';
    public string $customerEmail = '';
    public string $customerAddress = '';

    public string $planId = '';
    public int $pieces = 1;
    public array $selectedAddOns = [];
    public string $serviceType = 'Walk-in';
    public string $zone = '';
    public string $notes = '';
    public $photo;

    public bool $recordPayment = false;
    public string $paidAmount = '';
    public string $paymentMethod = 'cash';
    public string $paymentReference = '';

    public bool $showReceiptPreview = false;
    public ?int $savedOrderId = null;

    public function mount(): void
    {
        $this->pieces = 1;
    }

    public function updated($name): void
    {
        if ($this->savedOrderId || in_array($name, ['showReceiptPreview', 'customerSearch'], true)) {
            return;
        }

        $this->showReceiptPreview = false;
    }

    public function updatedCustomerMode(): void
    {
        $this->customerId = '';
        $this->customerName = '';
        $this->customerPhone = '';
        $this->customerEmail = '';
        $this->customerAddress = '';
        $this->resetValidation();
    }

    public function updatedServiceType(string $value): void
    {
        if ($value === 'Walk-in') {
            $this->zone = '';
        }
    }

    public function previewReceipt(): void
    {
        $this->validateOrder();
        $this->showReceiptPreview = true;
    }

    public function saveOrder(): void
    {
        $this->validateOrder();

        if (!$this->showReceiptPreview) {
            $this->showReceiptPreview = true;
            $this->dispatchBrowserEvent('notify', [
                'type' => 'info',
                'message' => 'Review the receipt preview before saving.',
            ]);
            return;
        }

        $totals = $this->totals;
        $paidAmount = $this->paymentAmount($totals['total']);

        $order = DB::transaction(function () use ($totals, $paidAmount) {
            $customer = $this->resolveCustomer();
            $photoPath = $this->photo ? $this->photo->store('order-photos', 'public') : null;
            $order = Order::create([
                'order_number' => Order::nextOrderNumber(),
                'user_id' => Auth::id(),
                'customer_id' => $customer->id,
                'plan_id' => (int) $this->planId,
                'service_type' => $this->serviceType,
                'zone' => $this->serviceType === 'Walk-in' ? null : $this->zone,
                'zone_fee' => $totals['zone_fee'],
                'pieces' => $this->pieces,
                'add_ons' => $this->selectedAddOnRows(),
                'add_ons_total' => $totals['add_ons_total'],
                'subtotal' => $totals['subtotal'],
                'total' => $totals['total'],
                'total_amount' => $totals['total'],
                'paid_amount' => $paidAmount,
                'payment_status' => $this->paymentStatus($paidAmount, $totals['total']),
                'payment_method' => $paidAmount > 0 ? $this->paymentMethod : null,
                'status' => 'Pending',
                'notes' => $this->notes ?: null,
                'clothing_photo_path' => $photoPath,
                'loyalty_stamps_awarded' => $this->pieces,
            ]);

            if ($paidAmount > 0) {
                OrderPayment::create([
                    'order_id' => $order->id,
                    'receipt_number' => OrderPayment::nextReceiptNumber(),
                    'type' => 'payment',
                    'amount' => $paidAmount,
                    'payment_method' => $this->paymentMethod,
                    'reference' => $this->paymentReference ?: null,
                    'paid_at' => now(),
                    'received_by' => Auth::id(),
                    'notes' => 'Payment recorded during order creation.',
                ]);
            }

            $customer->increment('loyalty_stamps', $this->pieces);

            OrderActivityLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'event' => 'created',
                'description' => "Order {$order->order_number} created.",
                'properties' => [
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'loyalty_stamps_awarded' => $order->loyalty_stamps_awarded,
                ],
            ]);

            AuditTrail::record('order.created', "Created order {$order->order_number}", $order, [], $order->toArray(), null, true);

            return $order;
        });

        $this->savedOrderId = $order->id;
        $this->photo = null;
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => "Order {$order->order_number} saved.",
        ]);
    }

    public function resetOrderForm(): void
    {
        $this->reset([
            'customerMode',
            'customerSearch',
            'customerId',
            'customerName',
            'customerPhone',
            'customerEmail',
            'customerAddress',
            'planId',
            'selectedAddOns',
            'serviceType',
            'zone',
            'notes',
            'photo',
            'recordPayment',
            'paidAmount',
            'paymentMethod',
            'paymentReference',
            'showReceiptPreview',
            'savedOrderId',
        ]);
        $this->pieces = 1;
        $this->customerMode = 'existing';
        $this->serviceType = 'Walk-in';
        $this->paymentMethod = 'cash';
        $this->resetValidation();
    }

    public function getTotalsProperty(): array
    {
        $plan = $this->selectedPlan();
        $pieces = max(1, (int) $this->pieces);
        $packageTotal = $plan ? ((float) $plan->price * $pieces) : 0.0;
        $addOnsTotal = collect($this->selectedAddOnRows())->sum('line_total');
        $subtotal = $packageTotal + $addOnsTotal;
        $zoneFee = $this->serviceType === 'Walk-in' ? 0.0 : (float) ($this->zones()[$this->zone] ?? 0);

        return [
            'package_total' => round($packageTotal, 2),
            'add_ons_total' => round($addOnsTotal, 2),
            'subtotal' => round($subtotal, 2),
            'zone_fee' => round($zoneFee, 2),
            'total' => round($subtotal + $zoneFee, 2),
        ];
    }

    public function getSavedOrderProperty(): ?Order
    {
        if (!$this->savedOrderId) {
            return null;
        }

        return Order::with(['customer', 'plan', 'payments'])->find($this->savedOrderId);
    }

    public function addOnOptions(): array
    {
        return [
            'stain_removal' => ['name' => 'Stain removal', 'price' => 20.00],
            'express_service' => ['name' => 'Express service', 'price' => 50.00],
            'fragrance_finish' => ['name' => 'Fragrance finish', 'price' => 10.00],
            'protective_packaging' => ['name' => 'Protective packaging', 'price' => 15.00],
        ];
    }

    public function zones(): array
    {
        return [
            'Zone 1' => 0.00,
            'Zone 2' => 15.00,
            'Zone 3' => 30.00,
        ];
    }

    private function validateOrder(): void
    {
        $rules = [
            'customerMode' => ['required', Rule::in(['existing', 'new'])],
            'customerId' => ['required_if:customerMode,existing', 'nullable', 'exists:customers,id'],
            'customerName' => ['required_if:customerMode,new', 'nullable', 'string', 'max:255'],
            'customerPhone' => ['nullable', 'string', 'max:40'],
            'customerEmail' => ['nullable', 'email', 'max:255'],
            'customerAddress' => ['nullable', 'string', 'max:1000'],
            'planId' => ['required', 'exists:plans,id'],
            'pieces' => ['required', 'integer', 'min:1', 'max:999'],
            'selectedAddOns' => ['array'],
            'selectedAddOns.*' => ['string', Rule::in(array_keys($this->addOnOptions()))],
            'serviceType' => ['required', Rule::in(['Walk-in', 'Pickup', 'Delivery'])],
            'zone' => ['required_unless:serviceType,Walk-in', 'nullable', Rule::in(array_keys($this->zones()))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'recordPayment' => ['boolean'],
            'paidAmount' => ['nullable', 'numeric', 'min:0', 'max:' . $this->totals['total']],
            'paymentMethod' => ['required_if:recordPayment,true', 'nullable', Rule::in(['cash', 'mobile_money', 'bank_transfer', 'credit'])],
            'paymentReference' => ['nullable', 'string', 'max:120'],
        ];

        $this->validate($rules, [
            'customerId.required_if' => 'Select an existing customer or switch to new customer.',
            'customerName.required_if' => 'Enter the customer name.',
            'zone.required_unless' => 'Select a zone for pickup or delivery.',
        ]);

        if ($this->recordPayment && $this->paymentAmount($this->totals['total']) <= 0) {
            throw ValidationException::withMessages([
                'paidAmount' => 'Enter the amount paid.',
            ]);
        }
    }

    private function resolveCustomer(): Customer
    {
        if ($this->customerMode === 'existing') {
            return Customer::findOrFail((int) $this->customerId);
        }

        return Customer::create([
            'customer_number' => Customer::nextNumber(),
            'name' => trim($this->customerName),
            'phone' => $this->customerPhone ?: null,
            'email' => $this->customerEmail ?: null,
            'address' => $this->customerAddress ?: null,
        ]);
    }

    private function selectedPlan(): ?Plan
    {
        return $this->planId ? Plan::find((int) $this->planId) : null;
    }

    public function selectedAddOnRows(): array
    {
        $options = $this->addOnOptions();
        $pieces = max(1, (int) $this->pieces);

        return collect($this->selectedAddOns)
            ->filter(fn ($key) => isset($options[$key]))
            ->map(function ($key) use ($options, $pieces) {
                $price = (float) $options[$key]['price'];

                return [
                    'key' => $key,
                    'name' => $options[$key]['name'],
                    'price' => $price,
                    'quantity' => $pieces,
                    'line_total' => round($price * $pieces, 2),
                ];
            })
            ->values()
            ->all();
    }

    private function paymentAmount(float $total): float
    {
        if (!$this->recordPayment) {
            return 0.0;
        }

        return round(min((float) ($this->paidAmount ?: 0), $total), 2);
    }

    private function paymentStatus(float $paidAmount, float $total): string
    {
        if ($paidAmount <= 0) {
            return 'unpaid';
        }

        return $paidAmount >= $total ? 'paid' : 'partial';
    }

    private function layoutForUser(): string
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole(['Super Admin', 'Manager']) => 'layouts.admin.admin-layout',
            default => 'layouts.secretary.secretary-layout',
        };
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->customerSearch, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('name', 'like', '%' . $this->customerSearch . '%')
                        ->orWhere('phone', 'like', '%' . $this->customerSearch . '%')
                        ->orWhere('customer_number', 'like', '%' . $this->customerSearch . '%');
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        $plans = Plan::active()->ordered()->get();

        return view('livewire.orders.new-order-component', [
            'customers' => $customers,
            'plans' => $plans,
            'addOns' => $this->addOnOptions(),
            'zones' => $this->zones(),
            'totals' => $this->totals,
            'savedOrder' => $this->savedOrder,
        ])->layout($this->layoutForUser());
    }
}
