<?php

namespace App\Http\Livewire;

use App\Models\PaymentTransaction;
use App\Models\AuditTrail;
use App\Models\Cart;
use App\Models\Sales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OutstandingBalancesComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchQuery = '';
    public $perPage = 15;

    public $showModal = false;
    public $selectedSaleId = null;
    public $collectAmount = '';
    public $paymentMethod = 'cash';
    public $paymentNotes = '';

    // Payment history modal
    public $showHistoryModal = false;
    public $historyForSaleId = null;

    protected $rules = [
        'collectAmount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|in:cash,momo,card,cheque',
    ];

    public function mount()
    {
        abort_if(!auth()->user()?->hasRole(['Secretary', 'Cashier', 'Manager', 'Super Admin']), 403);
    }

    public function getBalancesProperty()
    {
        return Sales::with(['patient', 'user', 'items.product'])
            ->where('payment_status', 'partial')
            ->when($this->searchQuery, fn ($q) =>
                $q->where(fn ($s) =>
                    $s->where('transaction_id', 'like', '%' . $this->searchQuery . '%')
                        ->orWhereHas('patient', fn ($p) =>
                            $p->where('name', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('pxnumber', 'like', '%' . $this->searchQuery . '%')
                        )
                )
            )
            ->latest()
            ->paginate($this->perPage);
    }

    public function getSelectedSaleProperty()
    {
        if (!$this->selectedSaleId) {
            return null;
        }

        return Sales::with(['patient', 'items.product', 'paymentTransactions.collectedBy'])
            ->find($this->selectedSaleId);
    }

    public function getHistoryForSaleProperty()
    {
        if (!$this->historyForSaleId) {
            return null;
        }

        return Sales::with(['patient', 'paymentTransactions.collectedBy'])
            ->find($this->historyForSaleId);
    }

    public function openHistory(int $saleId): void
    {
        $this->historyForSaleId = $saleId;
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal(): void
    {
        $this->showHistoryModal = false;
        $this->historyForSaleId = null;
    }

    public function switchToCollectFromHistory(): void
    {
        $id = $this->historyForSaleId;
        $this->closeHistoryModal();
        $this->openCollect($id);
    }

    public function openCollect($saleId): void
    {
        $sale = Sales::where('payment_status', 'partial')->findOrFail($saleId);
        $balance = max(0, (float) $sale->total_amount - (float) $sale->amount_paid);

        $this->selectedSaleId = $sale->id;
        $this->collectAmount = number_format($balance, 2, '.', '');
        $this->paymentMethod = 'cash';
        $this->paymentNotes = '';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedSaleId = null;
        $this->collectAmount = '';
        $this->paymentNotes = '';
        $this->resetErrorBag();
    }

    public function collectPayment(): void
    {
        $this->validate();

        $sale = Sales::with('items.product')->findOrFail($this->selectedSaleId);
        $amount = (float) $this->collectAmount;
        $balance = max(0, (float) $sale->total_amount - (float) $sale->amount_paid);
        $willFullyPay = round((float) $sale->amount_paid + $amount, 2) >= round((float) $sale->total_amount, 2);

        if ($amount > $balance) {
            $this->addError('collectAmount', 'Amount exceeds remaining balance of ' . currency() . ' ' . number_format($balance, 2));
            return;
        }

        DB::transaction(function () use ($sale, $amount) {
            PaymentTransaction::create([
                'sale_id' => $sale->id,
                'amount' => $amount,
                'payment_method' => $this->paymentMethod,
                'notes' => $this->paymentNotes ?: null,
                'collected_by' => Auth::id(),
            ]);

            $newAmountPaid = round((float) $sale->amount_paid + $amount, 2);
            $fullyPaid = $newAmountPaid >= round((float) $sale->total_amount, 2);

            $sale->update([
                'amount_paid' => $newAmountPaid,
                'payment_status' => $fullyPaid ? 'paid' : 'partial',
                'profit' => $fullyPaid ? $this->recalculateProfit($sale) : $sale->profit,
            ]);

            if ($fullyPaid) {
                $sale->items()->update([
                    'dispensed_quantity' => DB::raw('prescribed_quantity'),
                    'subtotal' => DB::raw('prescribed_quantity * selling_price'),
                ]);

                $cartIds = $sale->items->pluck('cart_id')->filter()->unique()->values();

                if ($cartIds->isNotEmpty()) {
                    Cart::whereIn('id', $cartIds)
                        ->where('is_dispensed', false)
                        ->update([
                            'purchased' => true,
                            'is_dispensed' => true,
                            'dispensed_at' => now(),
                            'dispensed_by' => Auth::id(),
                            'status' => 'completed',
                        ]);
                } elseif ($sale->patient_id) {
                    $productIds = $sale->items->pluck('product_id')->filter()->unique()->values();

                    Cart::where('patient_id', $sale->patient_id)
                        ->whereIn('product_id', $productIds)
                        ->where('purchased', true)
                        ->where('is_dispensed', false)
                        ->update([
                            'is_dispensed' => true,
                            'dispensed_at' => now(),
                            'dispensed_by' => Auth::id(),
                            'status' => 'completed',
                        ]);
                }
            }

            AuditTrail::record(
                $fullyPaid ? 'payment.completed' : 'payment.updated',
                ($fullyPaid ? 'Completed balance payment' : 'Collected part payment') . ' for sale ' . $sale->transaction_id,
                $sale,
                ['amount_paid' => $sale->amount_paid, 'payment_status' => $sale->payment_status],
                ['amount_paid' => $newAmountPaid, 'payment_status' => $fullyPaid ? 'paid' : 'partial', 'amount_collected' => $amount],
                $sale->patient_id
            );
        });

        $this->closeModal();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Payment of ' . currency() . ' ' . number_format($amount, 2) . ' recorded successfully.',
        ]);

        if ($willFullyPay) {
            $this->dispatchBrowserEvent('print-released-receipt', [
                'url' => route('cashier.receipt.show', $sale->id),
            ]);
        }
    }

    private function recalculateProfit(Sales $sale): float
    {
        $grossProfit = (float) $sale->items->sum(function ($item) {
            $cost = $item->product->cost_price ?? 0;

            return ((float) $item->selling_price - (float) $cost) * (int) $item->prescribed_quantity;
        });

        return max(0, $grossProfit - (float) $sale->discount_amount);
    }

    public function updatedSearchQuery(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.outstanding-balances', [
            'balances'       => $this->balances,
            'selectedSale'   => $this->selectedSale,
            'historyForSale' => $this->historyForSale,
        ])->layout('layouts.admin.admin-layout');
    }
}
