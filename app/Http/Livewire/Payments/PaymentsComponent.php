<?php

namespace App\Http\Livewire\Payments;

use App\Models\AuditTrail;
use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\OrderPayment;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $methodFilter = '';
    public string $typeFilter = '';
    public string $collectionDate = '';
    public int $perPage = 15;

    public string $orderId = '';
    public string $paymentMode = 'full';
    public string $amount = '';
    public string $paymentMethod = 'cash';
    public string $reference = '';

    public ?int $refundPaymentId = null;
    public string $refundAmount = '';
    public string $refundReason = '';

    public ?int $printPaymentId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'methodFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->collectionDate = now()->toDateString();
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedDateFrom(): void { $this->resetPage(); }
    public function updatedDateTo(): void { $this->resetPage(); }
    public function updatedMethodFilter(): void { $this->resetPage(); }
    public function updatedTypeFilter(): void { $this->resetPage(); }

    public function updatedOrderId(): void
    {
        $this->syncAmountWithMode();
    }

    public function updatedPaymentMode(): void
    {
        $this->syncAmountWithMode();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'methodFilter', 'typeFilter']);
        $this->resetPage();
    }

    public function recordPayment(): void
    {
        $this->validate([
            'orderId' => ['required', 'exists:orders,id'],
            'paymentMode' => ['required', Rule::in(['full', 'part'])],
            'paymentMethod' => ['required', Rule::in(array_keys($this->paymentMethods()))],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);

        $order = Order::with(['customer', 'plan'])->findOrFail((int) $this->orderId);
        $balance = $this->balanceDue($order);

        if ($balance <= 0) {
            throw ValidationException::withMessages([
                'orderId' => 'This order is already fully paid.',
            ]);
        }

        if ($this->paymentMode === 'full') {
            $this->amount = number_format($balance, 2, '.', '');
        }

        $this->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $balance],
        ]);

        $payment = DB::transaction(function () use ($order) {
            $amount = round((float) $this->amount, 2);
            $payment = OrderPayment::create([
                'order_id' => $order->id,
                'receipt_number' => OrderPayment::nextReceiptNumber(),
                'type' => 'payment',
                'amount' => $amount,
                'payment_method' => $this->paymentMethod,
                'reference' => $this->reference ?: null,
                'paid_at' => now(),
                'received_by' => Auth::id(),
                'notes' => $this->paymentMode === 'full' ? 'Full payment recorded.' : 'Part payment recorded.',
            ]);

            $this->refreshOrderPaymentStatus($order);
            $this->logOrder($order, 'payment_recorded', "Payment {$payment->receipt_number} recorded.", [
                'amount' => $amount,
                'method' => $this->paymentMethod,
                'receipt_number' => $payment->receipt_number,
            ]);
            AuditTrail::record('payment.recorded', "Recorded {$payment->receipt_number} for order {$order->order_number}", $payment, [], $payment->toArray(), null, true);

            return $payment;
        });

        $this->printPaymentId = $payment->id;
        $this->resetPaymentForm();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Payment recorded.']);
        $this->dispatchBrowserEvent('print-payment-receipt');
    }

    public function openRefundModal(int $paymentId): void
    {
        $payment = OrderPayment::with('refunds')->where('type', 'payment')->findOrFail($paymentId);
        $refundable = $this->refundableAmount($payment);

        if ($refundable <= 0) {
            $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => 'This payment is already fully refunded.']);
            return;
        }

        $this->refundPaymentId = $payment->id;
        $this->refundAmount = number_format($refundable, 2, '.', '');
        $this->refundReason = '';
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-payment-refund-modal');
    }

    public function recordRefund(): void
    {
        $payment = OrderPayment::with('order')->where('type', 'payment')->findOrFail((int) $this->refundPaymentId);
        $refundable = $this->refundableAmount($payment);

        $this->validate([
            'refundAmount' => ['required', 'numeric', 'min:0.01', 'max:' . $refundable],
            'refundReason' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $refund = DB::transaction(function () use ($payment) {
            $refundAmount = round((float) $this->refundAmount, 2);
            $refund = OrderPayment::create([
                'order_id' => $payment->order_id,
                'receipt_number' => OrderPayment::nextReceiptNumber('refund'),
                'type' => 'refund',
                'refunded_payment_id' => $payment->id,
                'amount' => $refundAmount,
                'payment_method' => $payment->payment_method,
                'reference' => $payment->receipt_number,
                'refund_reason' => $this->refundReason,
                'paid_at' => now(),
                'received_by' => Auth::id(),
                'notes' => 'Refund recorded.',
            ]);

            $order = $payment->order;
            $this->refreshOrderPaymentStatus($order);
            $this->logOrder($order, 'refund_recorded', "Refund {$refund->receipt_number} recorded.", [
                'amount' => $refundAmount,
                'original_receipt' => $payment->receipt_number,
                'reason' => $this->refundReason,
            ]);
            AuditTrail::record('payment.refunded', "Recorded refund {$refund->receipt_number}", $refund, [], $refund->toArray(), null, true);

            return $refund;
        });

        $this->printPaymentId = $refund->id;
        $this->dispatchBrowserEvent('hide-payment-refund-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Refund recorded.']);
        $this->dispatchBrowserEvent('print-payment-receipt');
    }

    public function printReceipt(int $paymentId): void
    {
        $this->printPaymentId = $paymentId;
        $this->dispatchBrowserEvent('print-payment-receipt');
    }

    public function syncAmountWithMode(): void
    {
        if ($this->paymentMode !== 'full' || !$this->orderId) {
            return;
        }

        $order = Order::find((int) $this->orderId);
        if ($order) {
            $this->amount = number_format($this->balanceDue($order), 2, '.', '');
        }
    }

    public function paymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Bank Transfer',
            'credit' => 'Credit',
        ];
    }

    public function balanceDue(Order $order): float
    {
        return max(0, (float) ($order->total_amount ?? $order->total ?? 0) - (float) $order->paid_amount);
    }

    public function refundableAmount(OrderPayment $payment): float
    {
        return max(0, (float) $payment->amount - (float) $payment->refunds()->where('type', 'refund')->sum('amount'));
    }

    public function getPrintPaymentProperty(): ?OrderPayment
    {
        return $this->printPaymentId
            ? OrderPayment::with(['order.customer', 'order.plan', 'receiver'])->find($this->printPaymentId)
            : null;
    }

    public function getReceiptSettingsProperty(): array
    {
        try {
            $setting = Setting::getSettings();
        } catch (\Throwable $e) {
            $setting = null;
        }

        return [
            'name' => 'JumpWash',
            'address' => $setting->clinic_address ?? $setting->address ?? 'Address not set',
            'phone' => $setting->clinic_contact ?? $setting->phone ?? 'Phone not set',
        ];
    }

    private function resetPaymentForm(): void
    {
        $this->orderId = '';
        $this->paymentMode = 'full';
        $this->amount = '';
        $this->paymentMethod = 'cash';
        $this->reference = '';
        $this->resetValidation();
    }

    private function refreshOrderPaymentStatus(Order $order): void
    {
        $paid = (float) $order->payments()->where('type', 'payment')->sum('amount')
            - (float) $order->payments()->where('type', 'refund')->sum('amount');
        $total = (float) ($order->total_amount ?? $order->total ?? 0);
        $paid = max(0, min($paid, $total));
        $latestMethod = $order->payments()
            ->where('type', 'payment')
            ->latest('paid_at')
            ->value('payment_method');

        $order->update([
            'paid_amount' => round($paid, 2),
            'payment_status' => $this->paymentStatus($paid, $total),
            'payment_method' => $paid > 0 ? $latestMethod : null,
        ]);
    }

    private function paymentStatus(float $paidAmount, float $total): string
    {
        if ($paidAmount <= 0) {
            return 'unpaid';
        }

        return $paidAmount >= $total ? 'paid' : 'partial';
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
        $payments = OrderPayment::with(['order.customer', 'order.plan', 'receiver', 'refunds'])
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('receipt_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('order', fn ($orderQuery) => $orderQuery->where('order_number', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('order.customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->methodFilter, fn ($query) => $query->where('payment_method', $this->methodFilter))
            ->when($this->typeFilter, fn ($query) => $query->where('type', $this->typeFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('paid_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('paid_at', '<=', $this->dateTo))
            ->latest('paid_at')
            ->paginate($this->perPage);

        $openOrders = Order::with(['customer', 'plan'])
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->where('status', '!=', 'Cancelled')
            ->latest()
            ->limit(100)
            ->get();

        $dailyPayments = OrderPayment::query()
            ->whereDate('paid_at', $this->collectionDate ?: now()->toDateString())
            ->where('type', 'payment')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $dailyRefunds = (float) OrderPayment::query()
            ->whereDate('paid_at', $this->collectionDate ?: now()->toDateString())
            ->where('type', 'refund')
            ->sum('amount');

        return view('livewire.payments.payments-component', [
            'payments' => $payments,
            'openOrders' => $openOrders,
            'paymentMethods' => $this->paymentMethods(),
            'dailyPayments' => $dailyPayments,
            'dailyRefunds' => $dailyRefunds,
            'dailyGross' => (float) $dailyPayments->sum('total'),
            'printPayment' => $this->printPayment,
            'receiptSettings' => $this->receiptSettings,
        ])->layout($this->layoutForUser());
    }
}
