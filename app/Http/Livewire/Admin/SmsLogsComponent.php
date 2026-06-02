<?php

namespace App\Http\Livewire\Admin;

use App\Models\SmsLog;
use App\Models\SmsLogArchive;
use Livewire\Component;
use Livewire\WithPagination;

class SmsLogsComponent extends Component
{
    use WithPagination;

    public bool   $showArchive    = false;
    public string $search         = '';
    public string $filterStatus   = '';
    public string $filterTemplate = '';
    public string $dateFrom       = '';
    public string $dateTo         = '';

    protected $queryString = ['search', 'filterStatus', 'filterTemplate', 'dateFrom', 'dateTo'];

    public function updatingSearch(): void         { $this->resetPage(); }
    public function updatingFilterStatus(): void   { $this->resetPage(); }
    public function updatingFilterTemplate(): void { $this->resetPage(); }
    public function updatingDateFrom(): void       { $this->resetPage(); }
    public function updatingDateTo(): void         { $this->resetPage(); }

    public function toggleArchive(): void
    {
        $this->showArchive = !$this->showArchive;
        $this->search = $this->filterStatus = $this->filterTemplate = $this->dateFrom = $this->dateTo = '';
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = $this->filterStatus = $this->filterTemplate = $this->dateFrom = $this->dateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $model = $this->showArchive ? new SmsLogArchive : new SmsLog;

        $query = $model::with('patient')
            ->when($this->search, fn ($q) =>
                $q->where(fn ($q2) =>
                    $q2->where('recipient', 'like', "%{$this->search}%")
                       ->orWhereHas('patient', fn ($p) => $p->where('name', 'like', "%{$this->search}%"))
                       ->orWhere('message', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterStatus !== '', fn ($q) =>
                $q->where('success', $this->filterStatus === 'success')
            )
            ->when($this->filterTemplate, fn ($q) =>
                $q->where('template_key', $this->filterTemplate)
            )
            ->when($this->dateFrom, fn ($q) =>
                $q->whereDate('created_at', '>=', $this->dateFrom)
            )
            ->when($this->dateTo, fn ($q) =>
                $q->whereDate('created_at', '<=', $this->dateTo)
            )
            ->latest('created_at');

        $templates = $model::selectRaw('template_key')
            ->whereNotNull('template_key')
            ->distinct()
            ->orderBy('template_key')
            ->pluck('template_key');

        $totals = $this->showArchive ? null : [
            'total'   => SmsLog::count(),
            'success' => SmsLog::where('success', true)->count(),
            'failed'  => SmsLog::where('success', false)->count(),
        ];

        return view('livewire.admin.sms-logs-component', [
            'logs'        => $query->paginate(25),
            'templates'   => $templates,
            'totals'      => $totals,
            'showArchive' => $this->showArchive,
        ])->layout('layouts.admin.admin-layout');
    }
}
