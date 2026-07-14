<?php

namespace App\Http\Livewire\Admin;

use App\Models\RefundLog;
use App\Models\Sales;
use App\Services\NotificationService;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class RefundApprovalsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab   = 'pending'; // pending | approved | history
    public string $search      = '';
    public string $fromDate    = '';
    public string $toDate      = '';


    // Reject modal state
    public ?int    $rejectingId      = null;
    public string  $rejectionReason  = '';
    public bool    $showRejectModal  = false;

    protected $queryString = [
        'activeTab' => ['except' => 'pending'],
        'search'    => ['except' => ''],
        'fromDate'  => ['except' => ''],
        'toDate'    => ['except' => ''],
        'page'      => ['except' => 1],
    ];

    protected $rules = [
        'rejectionReason' => 'required|string|min:5|max:500',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        abort_if(
            !$user?->hasAnyRole(['Manager', 'Super Admin']) && !$user?->can('manage billing'),
            403
        );
    }

    public function updatedSearch(): void   { $this->resetPage(); }
    public function updatedFromDate(): void { $this->resetPage(); }
    public function updatedToDate(): void   { $this->resetPage(); }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ── Approve ───────────────────────────────────────────────────────────────

    public function confirmApprove(int $id): void
    {
        $log = RefundLog::where('status', RefundLog::STATUS_PENDING)
            ->findOrFail($id);

        $log->update([
            'status'      => RefundLog::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        if ($log->initiated_by) {
            NotificationService::send(
                $log->initiated_by,
                'refund_approved',
                'Refund Request Approved',
                "Your refund request for transaction #{$log->sale->transaction_id} was approved by " . auth()->user()->name . '.',
                'fas fa-check-circle',
                'text-success',
                route('cashier.sales-records')
            );
        }

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => "Refund request approved.",
        ]);
    }

    // ── Reject ────────────────────────────────────────────────────────────────

    public function openRejectModal(int $id): void
    {
        $this->rejectingId     = $id;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
        $this->resetErrorBag();
    }

    public function closeRejectModal(): void
    {
        $this->rejectingId     = null;
        $this->rejectionReason = '';
        $this->showRejectModal = false;
        $this->resetErrorBag();
    }

    public function confirmReject(): void
    {
        $this->validate(['rejectionReason' => 'required|string|min:5|max:500']);

        $log = RefundLog::where('status', RefundLog::STATUS_PENDING)
            ->findOrFail($this->rejectingId);

        $log->update([
            'status'           => RefundLog::STATUS_REJECTED,
            'rejected_by'      => auth()->id(),
            'rejected_at'      => now(),
            'rejection_reason' => $this->rejectionReason,
        ]);

        if ($log->initiated_by) {
            NotificationService::send(
                $log->initiated_by,
                'refund_rejected',
                'Refund Request Rejected',
                "Your refund request for transaction #{$log->sale->transaction_id} was rejected. Reason: {$this->rejectionReason}",
                'fas fa-times-circle',
                'text-danger',
                route('cashier.sales-records')
            );
        }

        $this->closeRejectModal();
        $this->dispatchBrowserEvent('notify', [
            'type'    => 'info',
            'message' => "Refund request rejected.",
        ]);
    }

    // ── Process ───────────────────────────────────────────────────────────────

    public function process(int $id): void
    {
        $log = RefundLog::with('sale.items.product')
            ->where('status', RefundLog::STATUS_APPROVED)
            ->findOrFail($id);

        $sale = $log->sale;

        if (!$sale) {
            $this->dispatchBrowserEvent('close-processing-modal');
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Sale record not found for this refund.']);
            return;
        }

        try {
            DB::transaction(function () use ($log, $sale) {
                // Pessimistic locks prevent two concurrent requests from double-processing
                $lockedSale = \App\Models\Sales::lockForUpdate()->findOrFail($sale->id);
                $lockedLog  = RefundLog::lockForUpdate()->findOrFail($log->id);

                if ($lockedSale->is_refunded) {
                    throw new \RuntimeException('This sale has already been refunded.');
                }
                if ($lockedLog->status !== RefundLog::STATUS_APPROVED) {
                    throw new \RuntimeException('This refund is no longer in approved status.');
                }

                $lockedSale->update([
                    'is_refunded'   => true,
                    'refund_reason' => $log->reason,
                    'refunded_at'   => now(),
                    'refunded_by'   => auth()->id(),
                ]);

                foreach ($sale->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->dispensed_quantity);
                    }
                }

                $cartIds = $sale->items->pluck('cart_id')->filter()->unique();
                if ($cartIds->isNotEmpty()) {
                    \App\Models\Cart::whereIn('id', $cartIds)->update(['status' => 'refunded']);
                }

                $lockedLog->update([
                    'status'       => RefundLog::STATUS_PROCESSED,
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            $this->dispatchBrowserEvent('close-processing-modal');
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $e->getMessage()]);
            return;
        }

        if ($log->initiated_by) {
            NotificationService::send(
                $log->initiated_by,
                'refund_processed',
                'Refund Processed',
                "The refund for transaction #{$sale->transaction_id} has been executed by " . auth()->user()->name . '.',
                'fas fa-check-double',
                'text-success',
                route('cashier.sales-records')
            );
        }

        $this->dispatchBrowserEvent('close-processing-modal');
        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => "Refund for #{$sale->transaction_id} processed. Stock restored.",
        ]);
    }

    // ── Query ─────────────────────────────────────────────────────────────────

    private function buildQuery()
    {
        $with = [
            'sale:id,transaction_id,total_amount',
            'initiator:id,name',
            'approvedBy:id,name',
            'processedBy:id,name',
            'rejectedBy:id,name',
        ];

        // Load full sale detail only for the pending tab — these power the JS preview modal.
        if ($this->activeTab === 'pending') {
            $with = array_merge($with, [
                'sale.items:id,sale_id,product_id,dispensed_quantity,selling_price,subtotal',
                'sale.items.product:id,name',
                'sale.patient:id,name,contact',
            ]);
        }

        return RefundLog::with($with)
            ->when($this->activeTab === 'pending',  fn ($q) => $q->pending())
            ->when($this->activeTab === 'approved', fn ($q) => $q->approved())
            ->when($this->activeTab === 'history',  fn ($q) => $q->whereIn('status', [RefundLog::STATUS_PROCESSED, RefundLog::STATUS_REJECTED]))
            ->when($this->search, fn ($q) =>
                $q->whereHas('sale', fn ($s) => $s->where('transaction_id', 'like', '%' . $this->search . '%'))
                  ->orWhere('reason', 'like', '%' . $this->search . '%')
            )
            ->when($this->fromDate, fn ($q) => $q->whereDate('created_at', '>=', $this->fromDate))
            ->when($this->toDate,   fn ($q) => $q->whereDate('created_at', '<=', $this->toDate))
            ->orderBy('created_at', 'desc');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.refund-approvals-component', [
            'logs'         => $this->buildQuery()->paginate(15),
            'pendingCount' => RefundLog::pendingCount(),
        ])->layout('layouts.admin.admin-layout');
    }
}
