<?php

namespace App\Http\Livewire\Admin;

use App\Models\PaymentTransaction;
use App\Models\Sales;
use Carbon\Carbon;
use Livewire\Component;

class DailyCashSummaryComponent extends Component
{
    public $reportDate;

    public function mount()
    {
        $this->reportDate = Carbon::today()->toDateString();
    }

    public function print()
    {
        $this->dispatchBrowserEvent('print-page');
    }

    public function updatedReportDate(): void
    {
        $this->dispatchBrowserEvent('update-payment-chart', $this->buildChartPayload());
    }

    protected function buildChartPayload(): array
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

        return compact('labels', 'data', 'colors', 'counts');
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

        $agg = Sales::whereBetween('created_at', [$start, $end])
            ->selectRaw('
                COUNT(*) as sales_count,
                COALESCE(SUM(total_amount), 0) as gross_sales,
                COALESCE(SUM(amount_paid), 0) as amount_paid,
                COALESCE(SUM(GREATEST(0, total_amount - amount_paid)), 0) as outstanding,
                COUNT(CASE WHEN is_refunded = 1 THEN 1 END) as refunds_count,
                COALESCE(SUM(CASE WHEN is_refunded = 1 THEN total_amount ELSE 0 END), 0) as refunds_total
            ')
            ->first();

        return view('livewire.admin.daily-cash-summary-component', [
            'chartPayload'      => $this->buildChartPayload(),
            'payments'          => $payments,
            'salesCount'        => (int) $agg->sales_count,
            'grossSales'        => $agg->gross_sales,
            'amountPaid'        => $agg->amount_paid,
            'outstandingCreated' => $agg->outstanding,
            'refundsCount'      => (int) $agg->refunds_count,
            'refundsTotal'      => $agg->refunds_total,
        ])->layout('layouts.admin.admin-layout');
    }
}
