<?php

namespace App\Http\Livewire\Orders;

use App\Models\AuditTrail;
use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\OrderPayment;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $serviceTypeFilter = '';
    public int $perPage = 15;

    public ?int $selectedOrderId = null;
    public ?int $editingOrderId = null;
    public string $editPlanId = '';
    public int $editPieces = 1;
    public array $editSelectedAddOns = [];
    public string $editServiceType = 'Walk-in';
    public string $editZone = '';
    public string $editNotes = '';

    public ?int $statusOrderId = null;
    public string $newStatus = '';

    public ?int $cancelOrderId = null;
    public string $cancelReason = '';

    public ?int $paymentOrderId = null;
    public string $paymentAmount = '';
    public string $paymentMethod = 'cash';
    public string $paymentReference = '';

    public ?int $printOrderId = null;
    public string $printMode = 'receipt';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'serviceTypeFilter' => ['except' => ''],
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }
    public function updatedDateFrom(): void { $this->resetPage(); }
    public function updatedDateTo(): void { $this->resetPage(); }
    public function updatedServiceTypeFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo', 'serviceTypeFilter']);
        $this->resetPage();
    }

    public function viewOrder(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->dispatchBrowserEvent('show-order-details-modal');
    }

    public function openEditOrder(int $orderId): void
    {
        $order = Order::with('plan')->findOrFail($orderId);
        if (!$this->canEdit($order)) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => 'Orders can only be edited before washing starts.',
            ]);
            return;
        }

        $this->editingOrderId = $order->id;
        $this->editPlanId = (string) $order->plan_id;
        $this->editPieces = max(1, (int) $order->pieces);
        $this->editSelectedAddOns = collect($order->add_ons ?: [])->pluck('key')->filter()->values()->all();
        $this->editServiceType = $order->service_type ?: 'Walk-in';
        $this->editZone = $order->zone ?: '';
        $this->editNotes = $order->notes ?: '';
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-order-modal');
    }

    public function updateOrder(): void
    {
        $order = Order::findOrFail((int) $this->editingOrderId);
        if (!$this->canEdit($order)) {
            throw ValidationException::withMessages([
                'editingOrderId' => 'This order can no longer be edited because washing has started.',
            ]);
        }

        $this->validate([
            'editPlanId' => ['required', 'exists:plans,id'],
            'editPieces' => ['required', 'integer', 'min:1', 'max:999'],
            'editSelectedAddOns' => ['array'],
            'editSelectedAddOns.*' => ['string', Rule::in(array_keys($this->addOnOptions()))],
            'editServiceType' => ['required', Rule::in($this->serviceTypes())],
            'editZone' => ['required_unless:editServiceType,Walk-in', 'nullable', Rule::in(array_keys($this->zones()))],
            'editNotes' => ['nullable', 'string', 'max:2000'],
        ]);

        $old = $order->toArray();
        $totals = $this->editTotals();
        $paidAmount = min((float) $order->paid_amount, $totals['total']);

        $order->update([
            'plan_id' => (int) $this->editPlanId,
            'pieces' => $this->editPieces,
            'add_ons' => $this->editSelectedAddOnRows(),
            'add_ons_total' => $totals['add_ons_total'],
            'subtotal' => $totals['subtotal'],
            'total' => $totals['total'],
            'total_amount' => $totals['total'],
            'paid_amount' => $paidAmount,
            'payment_status' => $this->paymentStatus($paidAmount, $totals['total']),
            'service_type' => $this->editServiceType,
            'zone' => $this->editServiceType === 'Walk-in' ? null : $this->editZone,
            'zone_fee' => $totals['zone_fee'],
            'notes' => $this->editNotes ?: null,
        ]);

        $this->logOrder($order, 'updated', "Order {$order->order_number} updated.", [
            'old_total' => $old['total_amount'] ?? $old['total'] ?? null,
            'new_total' => $totals['total'],
        ]);
        AuditTrail::record('order.updated', "Updated order {$order->order_number}", $order, $old, $order->fresh()->toArray(), null, true);

        $this->dispatchBrowserEvent('hide-edit-order-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order updated.']);
    }

    public function openStatusModal(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $this->statusOrderId = $order->id;
        $this->newStatus = $order->status;
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-status-modal');
    }

    public function updateOrderStatus(): void
    {
        $this->validate([
            'newStatus' => ['required', Rule::in($this->workflowStatuses())],
        ]);

        $order = Order::findOrFail((int) $this->statusOrderId);
        $oldStatus = $order->status;
        $data = ['status' => $this->newStatus];

        if ($this->newStatus !== 'Cancelled') {
            $data['cancel_reason'] = null;
            $data['cancelled_at'] = null;
            $data['cancelled_by'] = null;
        }

        $order->update($data);
        $this->logOrder($order, 'status_updated', "Status changed from {$oldStatus} to {$order->status}.", [
            'old_status' => $oldStatus,
            'new_status' => $order->status,
        ]);
        AuditTrail::record('order.status_updated', "Updated order {$order->order_number} status to {$order->status}", $order, ['status' => $oldStatus], ['status' => $order->status], null, true);

        $this->dispatchBrowserEvent('hide-status-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order status updated.']);
    }

    public function openCancelModal(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        if ($order->status === 'Cancelled') {
            return;
        }

        $this->cancelOrderId = $order->id;
        $this->cancelReason = '';
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-cancel-modal');
    }

    public function cancelOrder(): void
    {
        $this->validate([
            'cancelReason' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $order = Order::findOrFail((int) $this->cancelOrderId);
        $oldStatus = $order->status;
        $order->update([
            'status' => 'Cancelled',
            'cancel_reason' => $this->cancelReason,
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
        ]);

        $this->logOrder($order, 'cancelled', "Order {$order->order_number} cancelled.", [
            'old_status' => $oldStatus,
            'reason' => $this->cancelReason,
        ]);
        AuditTrail::record('order.cancelled', "Cancelled order {$order->order_number}", $order, ['status' => $oldStatus], ['status' => 'Cancelled', 'reason' => $this->cancelReason], null, true);

        $this->dispatchBrowserEvent('hide-cancel-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order cancelled.']);
    }

    public function openPaymentModal(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $balance = $this->balanceDue($order);

        if ($balance <= 0) {
            $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => 'This order is already fully paid.']);
            return;
        }

        $this->paymentOrderId = $order->id;
        $this->paymentAmount = number_format($balance, 2, '.', '');
        $this->paymentMethod = 'cash';
        $this->paymentReference = '';
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-payment-modal');
    }

    public function recordBalancePayment(): void
    {
        $order = Order::findOrFail((int) $this->paymentOrderId);
        $balance = $this->balanceDue($order);

        $this->validate([
            'paymentAmount' => ['required', 'numeric', 'min:0.01', 'max:' . $balance],
            'paymentMethod' => ['required', Rule::in(['cash', 'mobile_money', 'bank_transfer', 'credit'])],
            'paymentReference' => ['nullable', 'string', 'max:120'],
        ]);

        $this->recordPayment($order, (float) $this->paymentAmount, $this->paymentMethod, $this->paymentReference ?: null);

        $this->dispatchBrowserEvent('hide-payment-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Payment recorded.']);
    }

    public function markAsPaid(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $balance = $this->balanceDue($order);
        if ($balance <= 0) {
            return;
        }

        $this->recordPayment($order, $balance, 'cash', null);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order marked as paid.']);
    }

    public function printReceipt(int $orderId): void
    {
        $this->printOrderId = $orderId;
        $this->printMode = 'receipt';
        $this->dispatchBrowserEvent('print-orders-document');
    }

    public function printTag(int $orderId): void
    {
        $this->printOrderId = $orderId;
        $this->printMode = 'tag';
        $this->dispatchBrowserEvent('print-orders-document');
    }

    public function getSelectedOrderProperty(): ?Order
    {
        return $this->selectedOrderId ? $this->orderQuery()->find($this->selectedOrderId) : null;
    }

    public function getPrintOrderProperty(): ?Order
    {
        return $this->printOrderId ? $this->orderQuery()->find($this->printOrderId) : null;
    }

    public function statusBadgeClass(string $status): string
    {
        return match ($status) {
            'Pending' => 'badge-secondary',
            'Received' => 'badge-primary',
            'Washing' => 'badge-info',
            'Drying' => 'badge-warning',
            'Ironing' => 'badge-purple',
            'Ready' => 'badge-success',
            'Out for Delivery' => 'badge-dark',
            'Delivered' => 'badge-success',
            'Cancelled' => 'badge-danger',
            default => 'badge-light',
        };
    }

    public function paymentBadgeClass(string $status): string
    {
        return match ($status) {
            'paid' => 'badge-success',
            'partial' => 'badge-warning',
            default => 'badge-danger',
        };
    }

    public function canEdit(Order $order): bool
    {
        return in_array($order->status, ['Pending', 'Received'], true);
    }

    public function balanceDue(Order $order): float
    {
        return max(0, (float) ($order->total_amount ?? $order->total ?? 0) - (float) $order->paid_amount);
    }

    public function expectedCompletionDate(Order $order): string
    {
        if (!$order->created_at) {
            return '-';
        }

        $hasExpress = collect($order->add_ons ?: [])
            ->contains(fn ($addOn) => ($addOn['key'] ?? null) === 'express_service');

        $days = $hasExpress ? 1 : 3;

        return $order->created_at->copy()->addDays($days)->format('d M Y');
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

    public function serviceTypes(): array
    {
        return ['Walk-in', 'Pickup', 'Delivery'];
    }

    public function workflowStatuses(): array
    {
        return array_values(array_filter(Order::STATUSES, fn ($status) => $status !== 'Cancelled'));
    }

    private function recordPayment(Order $order, float $amount, string $method, ?string $reference): void
    {
        DB::transaction(function () use ($order, $amount, $method, $reference) {
            OrderPayment::create([
                'order_id' => $order->id,
                'receipt_number' => OrderPayment::nextReceiptNumber(),
                'type' => 'payment',
                'amount' => round($amount, 2),
                'payment_method' => $method,
                'reference' => $reference,
                'paid_at' => now(),
                'received_by' => Auth::id(),
                'notes' => 'Balance payment recorded from orders page.',
            ]);

            $total = (float) ($order->total_amount ?? $order->total ?? 0);
            $paid = min($total, (float) $order->paid_amount + $amount);
            $order->update([
                'paid_amount' => round($paid, 2),
                'payment_method' => $method,
                'payment_status' => $this->paymentStatus($paid, $total),
            ]);

            $this->logOrder($order, 'payment_recorded', 'Payment recorded for order ' . $order->order_number . '.', [
                'amount' => round($amount, 2),
                'method' => $method,
            ]);
            AuditTrail::record('order.payment_recorded', "Recorded payment for {$order->order_number}", $order, [], ['amount' => $amount, 'method' => $method], null, true);
        });
    }

    private function paymentStatus(float $paidAmount, float $total): string
    {
        if ($paidAmount <= 0) {
            return 'unpaid';
        }

        return $paidAmount >= $total ? 'paid' : 'partial';
    }

    private function editTotals(): array
    {
        $plan = Plan::find((int) $this->editPlanId);
        $pieces = max(1, (int) $this->editPieces);
        $packageTotal = $plan ? ((float) $plan->price * $pieces) : 0.0;
        $addOnsTotal = collect($this->editSelectedAddOnRows())->sum('line_total');
        $subtotal = $packageTotal + $addOnsTotal;
        $zoneFee = $this->editServiceType === 'Walk-in' ? 0.0 : (float) ($this->zones()[$this->editZone] ?? 0);

        return [
            'package_total' => round($packageTotal, 2),
            'add_ons_total' => round($addOnsTotal, 2),
            'subtotal' => round($subtotal, 2),
            'zone_fee' => round($zoneFee, 2),
            'total' => round($subtotal + $zoneFee, 2),
        ];
    }

    private function editSelectedAddOnRows(): array
    {
        $options = $this->addOnOptions();
        $pieces = max(1, (int) $this->editPieces);

        return collect($this->editSelectedAddOns)
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

    private function logOrder(Order $order, string $event, string $description, array $properties = []): void
    {
        OrderActivityLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }

    private function orderQuery()
    {
        return Order::with(['customer', 'plan', 'payments.receiver', 'activities.user', 'cancelledBy'])
            ->latest();
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
        $orders = $this->orderQuery()
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->serviceTypeFilter, fn ($query) => $query->where('service_type', $this->serviceTypeFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->paginate($this->perPage);

        return view('livewire.orders.orders-component', [
            'orders' => $orders,
            'plans' => Plan::active()->ordered()->get(),
            'statuses' => Order::STATUSES,
            'workflowStatuses' => $this->workflowStatuses(),
            'serviceTypes' => $this->serviceTypes(),
            'addOns' => $this->addOnOptions(),
            'zones' => $this->zones(),
            'editTotals' => $this->editTotals(),
            'selectedOrder' => $this->selectedOrder,
            'printOrder' => $this->printOrder,
        ])->layout($this->layoutForUser());
    }
}
