<?php

namespace App\Http\Livewire\Cashier;

use App\Models\RefundLog;
use App\Models\Sales;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SalesRecordsComponent extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $fromDate;
    public $toDate;
    public $filterRefunded = null;
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    public $selectedSale;

    public $initiatingRefundSale = null;
    public $initiateRefundReason = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'initiateRefundReason' => 'required|string|min:10|max:500',
    ];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        'filterRefunded' => ['except' => null],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        abort_if(!auth()->user()?->hasRole(['Secretary', 'Cashier', 'Manager', 'Super Admin']), 403);
        $this->normalizeFilters();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFromDate()
    {
        $this->normalizeFilters();
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->normalizeFilters();
        $this->resetPage();
    }

    public function updatedFilterRefunded()
    {
        $this->normalizeFilters();
        $this->resetPage();
    }

    private function normalizeFilters()
    {
        if (!$this->fromDate) {
            $this->fromDate = Carbon::today()->format('Y-m-d');
        }
        if (!$this->toDate) {
            $this->toDate = Carbon::today()->format('Y-m-d');
        }

        $this->fromDate = $this->normalizeDate($this->fromDate);
        $this->toDate = $this->normalizeDate($this->toDate);

        if ($this->filterRefunded === '' || $this->filterRefunded === 'null') {
            $this->filterRefunded = null;
        } elseif ($this->filterRefunded !== null) {
            $this->filterRefunded = (int) $this->filterRefunded;
        }

        if (!in_array($this->sortColumn, ['created_at', 'total_amount', 'transaction_id'], true)) {
            $this->sortColumn = 'created_at';
        }

        if (!in_array($this->sortDirection, ['asc', 'desc'], true)) {
            $this->sortDirection = 'desc';
        }
    }

    private function normalizeDate($date)
    {
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return Carbon::today()->format('Y-m-d');
        }
    }

    private function dateRange()
    {
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to = Carbon::parse($this->toDate)->endOfDay();

        if ($from->gt($to)) {
            $oldFrom = $from;
            $from = $to->copy()->startOfDay();
            $to = $oldFrom->copy()->endOfDay();
        }

        return [$from, $to];
    }

    public function sortBy($column)
    {
        if (!in_array($column, ['created_at', 'total_amount', 'transaction_id'], true)) {
            return;
        }

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function toggleRefundFilter()
    {
        $this->normalizeFilters();

        if ($this->filterRefunded === null) $this->filterRefunded = 0;
        elseif ($this->filterRefunded === 0) $this->filterRefunded = 1;
        else $this->filterRefunded = null;

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchTerm', 'filterRefunded', 'sortColumn', 'sortDirection']);
        $this->fromDate = Carbon::today()->format('Y-m-d');
        $this->toDate = Carbon::today()->format('Y-m-d');
        $this->resetPage();
    }

    public function viewSale($saleId)
    {
        \Log::info('viewSale called for sale ID: ' . $saleId);
        
        try {
            $this->selectedSale = Sales::select('id', 'transaction_id', 'total_amount', 'is_refunded', 'patient_id', 'user_id', 'created_at')
                ->with([
                    'items:id,sale_id,product_id,dispensed_quantity,selling_price,subtotal',
                    'items.product:id,name',
                    'patient:id,name,contact,pxnumber',
                    'user:id,name'
                ])
                ->findOrFail($saleId);
            
            \Log::info('Sale found, dispatching modal event');
            
            $this->dispatchBrowserEvent('show-viewSaleModal-form');
            
        } catch (\Exception $e) {
            \Log::error('Error in viewSale: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error loading sale details'
            ]);
        }
    }

    public function printReceipt($saleId)
    {
        \Log::info('printReceipt called for sale ID: ' . $saleId);
        
        try {
            $sale = Sales::select('id', 'transaction_id', 'total_amount', 'is_refunded', 'patient_id', 'user_id', 'created_at')
                ->with([
                    'items:id,sale_id,product_id,dispensed_quantity,selling_price,subtotal',
                    'items.product:id,name',
                    'patient:id,name,contact,pxnumber',
                    'user:id,name'
                ])
                ->findOrFail($saleId);
            
            \Log::info('Sale found for printing');
            
            // Convert to array for JavaScript
            $saleData = [
                'id' => $sale->id,
                'transaction_id' => $sale->transaction_id,
                'total_amount' => $sale->total_amount,
                'created_at' => $sale->created_at->toISOString(),
                'patient' => $sale->patient ? [
                    'name' => $sale->patient->name,
                    'contact' => $sale->patient->contact,
                    'pxnumber' => $sale->patient->pxnumber,
                ] : null,
                'user' => $sale->user ? [
                    'name' => $sale->user->name,
                ] : null,
                'items' => $sale->items->map(function($item) {
                    return [
                        'quantity' => $item->dispensed_quantity,
                        'selling_price' => $item->selling_price,
                        'subtotal' => $item->subtotal,
                        'product' => [
                            'name' => $item->product->name ?? 'Unknown',
                        ]
                    ];
                })->toArray()
            ];
            
            \Log::info('Dispatching alert-receipt event with data');
            
            $this->dispatchBrowserEvent('alert-receipt', [
                'sale' => $saleData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in printReceipt: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Unable to print receipt. Please try again or contact support.',
            ]);
        }
    }

    public function exportCSV()
    {
        $this->normalizeFilters();
        [$fromDate, $toDate] = $this->dateRange();

        $fileName = 'Sales_Report_' . $this->fromDate . '_to_' . $this->toDate . '.csv';
        
        $query = Sales::select('id', 'transaction_id', 'total_amount', 'is_refunded', 'patient_id', 'user_id', 'created_at')
            ->with(['patient:id,name', 'user:id,name'])
            ->whereBetween('created_at', [$fromDate, $toDate]);

        if ($this->filterRefunded !== null) {
            $query->where('is_refunded', $this->filterRefunded);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function($q) {
                $q->where('transaction_id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('patient', function($p) {
                      $p->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Date', 'Transaction ID', 'Patient', 'Cashier', 'Amount', 'Status']);

            $query->chunkById(500, function($sales) use ($file) {
                foreach ($sales as $sale) {
                    fputcsv($file, [
                        $sale->created_at->format('Y-m-d H:i'),
                        $sale->transaction_id,
                        $sale->patient->name ?? 'Walk-in',
                        $sale->user->name ?? 'System',
                        currency() . ' ' . number_format($sale->total_amount, 2),
                        $sale->is_refunded ? 'Refunded' : 'Paid'
                    ]);
                }
            });
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function initiateRefund($saleId)
    {
        $sale = Sales::findOrFail($saleId);

        abort_if($sale->is_refunded, 422, 'This sale has already been refunded.');

        $alreadyPending = RefundLog::where('sale_id', $saleId)
            ->whereIn('status', [RefundLog::STATUS_PENDING, RefundLog::STATUS_APPROVED])
            ->exists();

        if ($alreadyPending) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'warning',
                'message' => 'A refund request for this sale is already awaiting approval.',
            ]);
            return;
        }

        $this->initiatingRefundSale   = $sale;
        $this->initiateRefundReason   = '';
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('show-initiateRefundModal');
    }

    public function submitRefundRequest()
    {
        $this->validate(['initiateRefundReason' => 'required|string|min:10|max:500']);

        $sale = $this->initiatingRefundSale;

        RefundLog::create([
            'sale_id'      => $sale->id,
            'status'       => RefundLog::STATUS_PENDING,
            'initiated_by' => auth()->id(),
            'reason'       => $this->initiateRefundReason,
            'initiated_at' => now(),
        ]);

        NotificationService::sendToRoles(
            ['Manager', 'Super Admin'],
            'refund_requested',
            'Refund Request Submitted',
            auth()->user()->name . " requested a refund for transaction #{$sale->transaction_id}.",
            'fas fa-undo',
            'text-warning',
            route('admin.refund-approvals'),
            null,
            auth()->id()
        );

        $this->initiatingRefundSale = null;
        $this->initiateRefundReason = '';
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('hide-initiateRefundModal');
        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => "Refund request for #{$sale->transaction_id} submitted. Awaiting manager approval.",
        ]);
    }

    public function cancelRefundInitiation()
    {
        $this->initiatingRefundSale = null;
        $this->initiateRefundReason = '';
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('hide-initiateRefundModal');
    }

    public function render()
    {
        $this->normalizeFilters();
        [$fromDate, $toDate] = $this->dateRange();

        $query = Sales::select('id', 'transaction_id', 'total_amount', 'is_refunded', 'patient_id', 'user_id', 'created_at')
            ->with([
                'items:id,sale_id,product_id,dispensed_quantity,selling_price,subtotal',
                'items.product:id,name',
                'patient:id,name',
                'user:id,name',
                'pendingRefundLog:id,sale_id,status',
            ])
            ->whereBetween('created_at', [$fromDate, $toDate]);

        if ($this->filterRefunded !== null) {
            $query->where('is_refunded', $this->filterRefunded);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function($q) {
                $q->where('transaction_id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('patient', function($p) {
                      $p->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        $totalSales = Sales::whereBetween('created_at', [$fromDate, $toDate])
            ->where('is_refunded', false)
            ->sum('total_amount');

        if (!empty($this->searchTerm)) {
            $totalSales = Sales::whereBetween('created_at', [$fromDate, $toDate])
                ->where('is_refunded', false)
                ->where(function($q) {
                    $q->where('transaction_id', 'like', '%' . $this->searchTerm . '%')
                      ->orWhereHas('patient', function($p) {
                          $p->where('name', 'like', '%' . $this->searchTerm . '%');
                      });
                })
                ->sum('total_amount');
        }

        $layout = request()->routeIs('admin.*')
            ? 'layouts.admin.admin-layout'
            : 'layouts.secretary.secretary-layout';

        return view('livewire.cashier.sales-records-component', [
            'sales' => $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(10),
            'totalSales' => $totalSales
        ])->layout($layout);
    }
}
