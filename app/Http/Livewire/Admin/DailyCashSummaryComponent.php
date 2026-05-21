<?php

namespace App\Http\Livewire\Admin;

use App\Models\PaymentTransaction;
use App\Models\Sales;
use Carbon\Carbon;
use Livewire\Component;

class DailyCashSummaryComponent extends Component
{
    public $reportDate;
    public $chartLoaded = false;

    public function mount()
    {
        $this->reportDate = Carbon::today()->toDateString();
    }

    public function print()
    {
        $this->dispatchBrowserEvent('print-page');
    }

    public function loadPaymentChart(): void
    {
        $this->chartLoaded = true;
        $this->dispatchPaymentChart();
    }

    public function updatedReportDate(): void
    {
        if ($this->chartLoaded) {
            $this->dispatchPaymentChart();
        }
    }

    protected function dispatchPaymentChart(): void
    {
        $start    = Carbon::parse($this->reportDate)->startOfDay();
        $end      = Carbon::parse($this->reportDate)->endOfDay();
        $payments = PaymentTransaction::whereBetween('created_at', [$start, $end])
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('payment_method')
            ->get();

        $methodColors = ['cash' => '#28a745', 'card' => '#007bff', 'momo' => '#fd7e14', 'code' => '#6f42c1'];
        $methodLabels = ['cash' => 'Cash', 'card' => 'Card', 'momo' => 'Mobile Money', 'code' => 'Hubtel Wallet'];

        $labels = [];
        $data   = [];
        $colors = [];
        $counts = [];

        foreach ($payments as $p) {
            $labels[] = $methodLabels[$p->payment_method] ?? strtoupper($p->payment_method);
            $data[]   = round((float) $p->total, 2);
            $colors[] = $methodColors[$p->payment_method] ?? '#6c757d';
            $counts[] = (int) $p->cnt;
        }

        $this->dispatchBrowserEvent('update-payment-chart', compact('labels', 'data', 'colors', 'counts'));
    }

    public function render()
    {
        $start = Carbon::parse($this->reportDate)->startOfDay();
        $end = Carbon::parse($this->reportDate)->endOfDay();

        $payments = PaymentTransaction::whereBetween('created_at', [$start, $end])
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        $sales = Sales::whereBetween('created_at', [$start, $end])->get();

        return view('livewire.admin.daily-cash-summary-component', [
            'payments' => $payments,
            'salesCount' => $sales->count(),
            'grossSales' => $sales->sum('total_amount'),
            'amountPaid' => $sales->sum('amount_paid'),
            'outstandingCreated' => $sales->sum(fn ($sale) => max(0, (float) $sale->total_amount - (float) $sale->amount_paid)),
            'refundsCount' => $sales->where('is_refunded', true)->count(),
            'refundsTotal' => $sales->where('is_refunded', true)->sum('total_amount'),
        ])->layout('layouts.admin.admin-layout');
    }
}
