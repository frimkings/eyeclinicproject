<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuditTrail;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\IncomeStatementEntry;
use App\Models\IncomeStatementPeriodLock;
use App\Models\IncomeStatementTemplate;
use App\Models\SaleItem;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class IncomeStatementComponent extends Component
{
    public $fromDate;
    public $toDate;

    public $section = IncomeStatementEntry::OPERATING_EXPENSE;
    public $name = '';
    public $amount = '';
    public $percentage = '';
    public $entryDate;
    public $notes = '';
    public $selectedPreset = '';
    public $customName = '';
    public $templateSection = IncomeStatementEntry::OPERATING_EXPENSE;
    public $templateName = '';
    public $templateAmount = '';
    public $templatePercentage = '';
    public $templateNotes = '';
    public $lockNotes = '';
    public bool  $showImportModal = false;
    public array $importPreview   = [];
    public $editingEntryId = null;
    public $editingName = '';
    public $editingAmount = '';
    public $editingPercentage = '';
    public $editingDate = '';
    public $editingNotes = '';

    protected $entryPresets = [
        IncomeStatementEntry::OPERATING_EXPENSE => [
            "Doctor's Salary",
            'Nurse Salary',
            'Locum',
            'Media',
            'Electricity',
            'Internet',
            'Consumables',
            'Rent',
            'DVLA Printing',
            'Lenses Fixing',
        ],
        IncomeStatementEntry::NON_OPERATING_EXPENSE => [
            'Loans',
            'Loan Interest',
            'Bank Charges',
            'Asset Disposal Loss',
            'Other Non-operating Expense',
        ],
        IncomeStatementEntry::TAX => [
            'Tax',
            'Corporate Tax',
            'Income Tax',
        ],
    ];

    public function mount()
    {
        $user = auth()->user();
        abort_if(!$user?->hasRole('Super Admin') && !$user?->can('manage billing'), 403);
        AuditTrail::record('report.accessed', 'Accessed income statement page');
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->endOfMonth()->format('Y-m-d');
        $this->entryDate = now()->format('Y-m-d');
    }

    public function updatedFromDate()
    {
        $this->normalizeDates();
    }

    public function updatedToDate()
    {
        $this->normalizeDates();
    }

    public function updatedSection()
    {
        $this->selectedPreset = '';
        $this->name = '';
        $this->customName = '';
        $this->amount = '';
        $this->percentage = '';
    }

    public function updatedTemplateSection()
    {
        $this->templateAmount = '';
        $this->templatePercentage = '';
    }

    public function updatedSelectedPreset($value)
    {
        if ($value !== 'custom') {
            $this->name = $value;
        } else {
            $this->name = '';
        }
    }

    protected function normalizeDates()
    {
        $this->fromDate = $this->normalizeDate($this->fromDate, now()->startOfMonth());
        $this->toDate = $this->normalizeDate($this->toDate, now()->endOfMonth());
    }

    protected function normalizeDate($date, $fallback)
    {
        try {
            return Carbon::parse($date ?: $fallback)->format('Y-m-d');
        } catch (\Exception $e) {
            return Carbon::parse($fallback)->format('Y-m-d');
        }
    }

    protected function dateRange()
    {
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to = Carbon::parse($this->toDate)->endOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    public function setThisMonth()
    {
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function setLastMonth()
    {
        $this->fromDate = now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
    }

    public function saveEntry()
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This period is locked. Unlock it before changing entries.']);
            return;
        }

        if (trim($this->customName) !== '') {
            $this->name = $this->customName;
        } elseif ($this->selectedPreset) {
            $this->name = $this->selectedPreset;
        }

        $rules = [
            'section' => 'required|in:operating_expense,non_operating_expense,tax',
            'name' => 'required|string|max:255',
            'entryDate' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($this->section === IncomeStatementEntry::TAX) {
            $rules['percentage'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['amount'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        $entry = $this->matchingEntryQuery($this->section, $this->name)->latest('id')->first();
        $payload = [
            'section' => $this->section,
            'name' => $this->name,
            'amount' => $this->section === IncomeStatementEntry::TAX ? 0 : $this->amount,
            'percentage' => $this->section === IncomeStatementEntry::TAX ? $this->percentage : null,
            'entry_date' => $this->entryDate,
            'notes' => $this->notes,
            'is_active' => true,
        ];

        if ($entry) {
            $entry->update($payload);
        } else {
            IncomeStatementEntry::create($payload + ['created_by' => auth()->id()]);
        }

        $this->removeDuplicateEntries($this->section, $this->name);
        $this->resetEntryForm();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $entry ? 'Income statement line updated.' : 'Income statement line saved.']);
    }

    public function deleteEntry($entryId)
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This period is locked. Unlock it before deleting entries.']);
            return;
        }

        $entry = IncomeStatementEntry::findOrFail($entryId);
        $entry->update(['deleted_by' => auth()->id()]);
        $entry->delete();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Income statement line deleted.']);
    }

    public function editEntry($entryId)
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This period is locked. Unlock it before editing entries.']);
            return;
        }

        $entry = IncomeStatementEntry::findOrFail($entryId);
        $this->editingEntryId = $entry->id;
        $this->editingName = $entry->name;
        $this->editingAmount = $entry->amount;
        $this->editingPercentage = $entry->percentage;
        $this->editingDate = $entry->entry_date->format('Y-m-d');
        $this->editingNotes = $entry->notes;
    }

    public function cancelEdit()
    {
        $this->editingEntryId = null;
        $this->editingName = '';
        $this->editingAmount = '';
        $this->editingPercentage = '';
        $this->editingDate = '';
        $this->editingNotes = '';
        $this->resetErrorBag();
    }

    public function updateEntry()
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This period is locked. Unlock it before editing entries.']);
            return;
        }

        $entry = IncomeStatementEntry::findOrFail($this->editingEntryId);
        $rules = [
            'editingName' => 'required|string|max:255',
            'editingDate' => 'required|date',
            'editingNotes' => 'nullable|string|max:1000',
        ];

        if ($entry->section === IncomeStatementEntry::TAX) {
            $rules['editingPercentage'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['editingAmount'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        $entry->update([
            'name' => $this->editingName,
            'amount' => $entry->section === IncomeStatementEntry::TAX ? 0 : $this->editingAmount,
            'percentage' => $entry->section === IncomeStatementEntry::TAX ? $this->editingPercentage : null,
            'entry_date' => $this->editingDate,
            'notes' => $this->editingNotes,
        ]);

        $this->removeDuplicateEntries($entry->section, $this->editingName);
        $this->cancelEdit();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Income statement line updated.']);
    }

    public function saveTemplate()
    {
        $rules = [
            'templateSection' => 'required|in:operating_expense,non_operating_expense,tax',
            'templateName' => 'required|string|max:255',
            'templateNotes' => 'nullable|string|max:1000',
        ];

        if ($this->templateSection === IncomeStatementEntry::TAX) {
            $rules['templatePercentage'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['templateAmount'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        IncomeStatementTemplate::create([
            'section' => $this->templateSection,
            'name' => $this->templateName,
            'amount' => $this->templateSection === IncomeStatementEntry::TAX ? 0 : $this->templateAmount,
            'percentage' => $this->templateSection === IncomeStatementEntry::TAX ? $this->templatePercentage : null,
            'notes' => $this->templateNotes,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        $this->templateName = '';
        $this->templateAmount = '';
        $this->templatePercentage = '';
        $this->templateNotes = '';
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Recurring template saved.']);
    }

    public function applyTemplates()
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This period is locked. Unlock it before applying templates.']);
            return;
        }

        $created = 0;
        $updated = 0;

        foreach ($this->templates as $template) {
            $entry = $this->matchingEntryQuery($template->section, $template->name)->latest('id')->first();
            $payload = [
                'section' => $template->section,
                'name' => $template->name,
                'amount' => $template->amount,
                'percentage' => $template->percentage,
                'entry_date' => $this->fromDate,
                'notes' => $template->notes,
                'is_active' => true,
            ];

            if ($entry) {
                $entry->update($payload);
                $updated++;
            } else {
                IncomeStatementEntry::create($payload + ['created_by' => auth()->id()]);
                $created++;
            }

            $this->removeDuplicateEntries($template->section, $template->name);
        }

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "{$created} recurring template line(s) created, {$updated} updated."]);
    }

    protected function matchingEntryQuery($section, $name)
    {
        [$from, $to] = $this->dateRange();

        return IncomeStatementEntry::where('is_active', true)
            ->where('section', $section)
            ->where('name', $name)
            ->whereBetween('entry_date', [$from->toDateString(), $to->toDateString()]);
    }

    protected function removeDuplicateEntries($section, $name)
    {
        $entries = $this->matchingEntryQuery($section, $name)
            ->latest('id')
            ->get();

        if ($entries->count() <= 1) {
            return;
        }

        $entries->skip(1)->each(function ($entry) {
            $entry->update(['deleted_by' => auth()->id()]);
            $entry->delete();
        });
    }

    public function deleteTemplate($templateId)
    {
        IncomeStatementTemplate::findOrFail($templateId)->update(['is_active' => false]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Recurring template removed.']);
    }

    public function lockPeriod()
    {
        [$from, $to] = $this->dateRange();

        IncomeStatementPeriodLock::firstOrCreate(
            ['from_date' => $from->toDateString(), 'to_date' => $to->toDateString()],
            [
                'locked_by' => auth()->id(),
                'locked_at' => now(),
                'notes' => $this->lockNotes,
            ]
        );

        $this->lockNotes = '';
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Income statement period locked.']);
    }

    public function unlockPeriod()
    {
        if ($this->periodLock) {
            $this->periodLock->delete();
        }

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Income statement period unlocked.']);
    }

    protected function resetEntryForm()
    {
        $this->section = IncomeStatementEntry::OPERATING_EXPENSE;
        $this->name = '';
        $this->selectedPreset = '';
        $this->customName = '';
        $this->amount = '';
        $this->percentage = '';
        $this->entryDate = now()->format('Y-m-d');
        $this->notes = '';
        $this->resetErrorBag();
    }

    protected function salesLineQuery()
    {
        [$from, $to] = $this->dateRange();

        return SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.is_refunded', false)
            ->whereBetween('sales.created_at', [$from, $to]);
    }

    public function getRevenueLinesProperty()
    {
        return $this->salesLineQuery()
            ->selectRaw("COALESCE(categories.name, 'Uncategorized') as name")
            ->selectRaw('SUM(sale_items.subtotal) as amount')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('name')
            ->get();
    }

    public function getCostOfSalesLinesProperty()
    {
        return $this->salesLineQuery()
            ->selectRaw("COALESCE(categories.name, 'Uncategorized') as name")
            ->selectRaw('SUM(sale_items.dispensed_quantity * COALESCE(products.cost_price, 0)) as amount')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('name')
            ->get();
    }

    public function getEntriesProperty()
    {
        [$from, $to] = $this->dateRange();

        return IncomeStatementEntry::where('is_active', true)
            ->whereBetween('entry_date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('entry_date')
            ->orderBy('name')
            ->get();
    }

    public function getTemplatesProperty()
    {
        return IncomeStatementTemplate::where('is_active', true)
            ->orderBy('section')
            ->orderBy('name')
            ->get();
    }

    public function getPeriodLockProperty()
    {
        [$from, $to] = $this->dateRange();

        return IncomeStatementPeriodLock::with('lockedBy')
            ->whereDate('from_date', $from->toDateString())
            ->whereDate('to_date', $to->toDateString())
            ->first();
    }

    public function getIsLockedProperty()
    {
        return (bool) $this->periodLock;
    }

    public function getUncategorizedWarningsProperty()
    {
        $revenue = $this->revenueLines->firstWhere('name', 'Uncategorized');
        $cost = $this->costOfSalesLines->firstWhere('name', 'Uncategorized');

        return [
            'revenue' => $revenue ? (float) $revenue->amount : 0,
            'cost' => $cost ? (float) $cost->amount : 0,
        ];
    }

    protected function previousDateRange()
    {
        [$from, $to] = $this->dateRange();
        $days = $from->diffInDays($to) + 1;
        $previousTo = $from->copy()->subDay()->endOfDay();
        $previousFrom = $previousTo->copy()->subDays($days - 1)->startOfDay();

        return [$previousFrom, $previousTo];
    }

    protected function salesLineQueryForRange($from, $to)
    {
        return SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.is_refunded', false)
            ->whereBetween('sales.created_at', [$from, $to]);
    }

    protected function entriesForRange($from, $to)
    {
        return IncomeStatementEntry::where('is_active', true)
            ->whereBetween('entry_date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('entry_date')
            ->orderBy('name')
            ->get();
    }

    protected function statementForRange($from, $to)
    {
        $revenue = (float) $this->salesLineQueryForRange($from, $to)->sum('sale_items.subtotal');
        $costOfSales = (float) $this->salesLineQueryForRange($from, $to)
            ->selectRaw('COALESCE(SUM(sale_items.dispensed_quantity * COALESCE(products.cost_price, 0)), 0) as total')
            ->value('total');
        $entries = $this->entriesForRange($from, $to);
        $operatingExpenses = (float) $entries->where('section', IncomeStatementEntry::OPERATING_EXPENSE)->sum('amount');
        $nonOperatingExpenses = (float) $entries->where('section', IncomeStatementEntry::NON_OPERATING_EXPENSE)->sum('amount');
        $taxEntry = $entries->where('section', IncomeStatementEntry::TAX)->sortByDesc('entry_date')->first();
        $grossProfit = $revenue - $costOfSales;
        $operatingProfit = $grossProfit - $operatingExpenses;
        $profitForPeriod = $operatingProfit - $nonOperatingExpenses;
        $taxRate = $taxEntry ? (float) $taxEntry->percentage : 0;
        $taxAmount = $profitForPeriod > 0 ? $profitForPeriod * ($taxRate / 100) : 0;

        return [
            'revenue' => $revenue,
            'cost_of_sales' => $costOfSales,
            'gross_profit' => $grossProfit,
            'operating_expenses' => $operatingExpenses,
            'operating_profit' => $operatingProfit,
            'non_operating_expenses' => $nonOperatingExpenses,
            'profit_for_period' => $profitForPeriod,
            'tax_amount' => $taxAmount,
            'net_profit' => $profitForPeriod - $taxAmount,
        ];
    }

    public function getComparisonProperty()
    {
        [$previousFrom, $previousTo] = $this->previousDateRange();
        $previous = $this->statementForRange($previousFrom, $previousTo);

        return [
            'from' => $previousFrom,
            'to' => $previousTo,
            'statement' => $previous,
        ];
    }

    public function getStatementProperty()
    {
        $operatingExpenses = $this->entries->where('section', IncomeStatementEntry::OPERATING_EXPENSE);
        $nonOperatingExpenses = $this->entries->where('section', IncomeStatementEntry::NON_OPERATING_EXPENSE);
        $taxEntry = $this->entries->where('section', IncomeStatementEntry::TAX)->sortByDesc('entry_date')->first();

        $revenue = (float) $this->revenueLines->sum('amount');
        $costOfSales = (float) $this->costOfSalesLines->sum('amount');
        $grossProfit = $revenue - $costOfSales;
        $operatingExpenseTotal = (float) $operatingExpenses->sum('amount');
        $operatingProfit = $grossProfit - $operatingExpenseTotal;
        $nonOperatingExpenseTotal = (float) $nonOperatingExpenses->sum('amount');
        $profitForPeriod = $operatingProfit - $nonOperatingExpenseTotal;
        $taxRate = $taxEntry ? (float) $taxEntry->percentage : 0;
        $taxAmount = $profitForPeriod > 0 ? $profitForPeriod * ($taxRate / 100) : 0;
        $netProfit = $profitForPeriod - $taxAmount;

        return [
            'revenue' => $revenue,
            'cost_of_sales' => $costOfSales,
            'gross_profit' => $grossProfit,
            'operating_expenses' => $operatingExpenseTotal,
            'operating_profit' => $operatingProfit,
            'non_operating_expenses' => $nonOperatingExpenseTotal,
            'profit_for_period' => $profitForPeriod,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'net_profit' => $netProfit,
            'operating_lines' => $operatingExpenses,
            'non_operating_lines' => $nonOperatingExpenses,
            'tax_entry' => $taxEntry,
        ];
    }

    public function previewExpenseImport(): void
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Period is locked. Unlock it before importing.']);
            return;
        }

        [$from, $to] = $this->dateRange();

        $grouped = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        if ($grouped->isEmpty()) {
            $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => 'No expenses recorded in this period.']);
            return;
        }

        $this->importPreview = $grouped->map(fn ($row) => [
            'name'    => optional($row->category)->name ?? 'Uncategorised',
            'section' => optional($row->category)->section ?? ExpenseCategory::OPERATING,
            'label'   => ExpenseCategory::sectionLabels()[optional($row->category)->section ?? ExpenseCategory::OPERATING] ?? 'Operating Expense',
            'total'   => (float) $row->total,
        ])->values()->toArray();

        $this->showImportModal = true;
    }

    public function confirmExpenseImport(): void
    {
        if ($this->isLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Period is locked.']);
            return;
        }

        [$from, $to] = $this->dateRange();

        $grouped = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        $created = 0;
        $updated = 0;

        foreach ($grouped as $row) {
            $name    = optional($row->category)->name ?? 'Uncategorised';
            $section = optional($row->category)->section ?? ExpenseCategory::OPERATING;
            $amount  = (float) $row->total;

            $existing = $this->matchingEntryQuery($section, $name)->latest('id')->first();

            $payload = [
                'section'    => $section,
                'name'       => $name,
                'amount'     => $amount,
                'percentage' => null,
                'entry_date' => $from->toDateString(),
                'notes'      => 'Imported from Expense Tracker for period ' . $from->toDateString() . ' – ' . $to->toDateString(),
                'is_active'  => true,
            ];

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                IncomeStatementEntry::create($payload + ['created_by' => auth()->id()]);
                $created++;
            }

            $this->removeDuplicateEntries($section, $name);
        }

        $this->showImportModal = false;
        $this->importPreview   = [];

        AuditTrail::record('report.expense_import', "Imported {$created} new and {$updated} updated expense lines from Expense Tracker");

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => "{$created} line(s) created, {$updated} updated from Expense Tracker.",
        ]);
    }

    public function render()
    {
        $this->normalizeDates();

        return view('livewire.admin.income-statement-component', [
            'sections' => IncomeStatementEntry::sections(),
            'entryPresets' => $this->entryPresets,
            'revenueLines' => $this->revenueLines,
            'costOfSalesLines' => $this->costOfSalesLines,
            'statement' => $this->statement,
            'entries' => $this->entries,
            'templates' => $this->templates,
            'comparison' => $this->comparison,
            'uncategorizedWarnings' => $this->uncategorizedWarnings,
            'periodLock' => $this->periodLock,
            'isLocked' => $this->isLocked,
            'clinicSettings' => Setting::getSettings(),
        ])->layout('layouts.admin.admin-layout');
    }
}
