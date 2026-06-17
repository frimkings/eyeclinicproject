<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuditTrail;
use App\Models\Expense;
use App\Services\LicenseService;
use App\Support\Feature;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ExpensesComponent extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public string $fromDate    = '';
    public string $toDate      = '';
    public string $categoryId  = '';
    public string $search      = '';
    public int    $perPage     = 15;

    // Form state
    public bool    $showModal  = false;
    public bool    $isEditing  = false;
    public ?int    $expenseId  = null;
    public         $receiptFile = null;

    // Category management
    public bool    $showCategoryPanel   = false;
    public bool    $showCategoryModal   = false;
    public bool    $isEditingCategory   = false;
    public ?int    $categoryEditId      = null;

    public array $categoryState = [
        'name'        => '',
        'section'     => 'operating_expense',
        'color'       => '#6c757d',
        'description' => '',
        'is_active'   => true,
    ];

    public array $state = [
        'expense_category_id' => '',
        'expense_date'        => '',
        'description'         => '',
        'amount'              => '',
        'reference'           => '',
        'notes'               => '',
    ];

    protected $queryString = [
        'fromDate'   => ['except' => ''],
        'toDate'     => ['except' => ''],
        'categoryId' => ['except' => ''],
        'search'     => ['except' => ''],
    ];

    public function mount(): void
    {
        // Fix #12: generic 403 — don't leak feature flag names to unauthenticated/low-role users
        abort_if(!LicenseService::has(Feature::EXPENSE_TRACKING), 403);
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin', 'Cashier']), 403);

        $this->fromDate = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate   = Carbon::now()->toDateString();
        $this->state['expense_date'] = today()->toDateString();
    }

    public function updatedFromDate(): void   { $this->resetPage(); }
    public function updatedToDate(): void     { $this->resetPage(); }
    public function updatedCategoryId(): void { $this->resetPage(); }
    public function updatedSearch(): void     { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->fromDate   = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate     = Carbon::now()->toDateString();
        $this->categoryId = '';
        $this->search     = '';
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $expense = Expense::findOrFail($id);

        $this->expenseId = $id;
        $this->isEditing = true;
        $this->state = [
            'expense_category_id' => $expense->expense_category_id ?? null,
            'expense_date'        => $expense->expense_date->format('Y-m-d'),
            'description'         => $expense->description,
            'amount'              => $expense->amount,
            'reference'           => $expense->reference ?? '',
            'notes'               => $expense->notes ?? '',
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validateForm();
        $data['recorded_by'] = auth()->id();

        if ($this->isEditing) {
            $expense = Expense::findOrFail($this->expenseId);
            if ($this->receiptFile) {
                if ($expense->receipt_path) {
                    Storage::disk('public')->delete($expense->receipt_path);
                }
                $data['receipt_path'] = $this->receiptFile->store('expense-receipts', 'public');
            }
            $old = $expense->only(array_keys($data));
            $expense->update($data);
            AuditTrail::record('expense.updated', "Updated expense: {$expense->description} (" . currency() . " {$expense->amount})", $expense, $old, $data);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Expense updated.']);
        } else {
            if ($this->receiptFile) {
                $data['receipt_path'] = $this->receiptFile->store('expense-receipts', 'public');
            }
            $expense = Expense::create($data);
            AuditTrail::record('expense.created', "Recorded expense: {$expense->description} (" . currency() . " {$expense->amount})", $expense, [], $data);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Expense recorded.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteReceipt(int $id): void
    {
        $expense = Expense::findOrFail($id);
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->update(['receipt_path' => null]);
        AuditTrail::record('expense.receipt_deleted', "Removed receipt from: {$expense->description}", $expense, force: true);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Receipt removed.']);
    }

    public function confirmDelete(int $id): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);

        $this->dispatchBrowserEvent('show-delete-confirmation', [
            'id'     => $id,
            'method' => 'deleteExpense',
        ]);
    }

    public function deleteExpense(int $id): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);

        $expense = Expense::findOrFail($id);
        AuditTrail::record('expense.deleted', "Deleted expense: {$expense->description} (" . currency() . " {$expense->amount})", $expense, $expense->toArray(), []);
        $expense->delete();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Expense deleted.']);
    }

    public function exportCsv()
    {
        [$from, $to] = $this->dateRange();

        $fileName = 'Expenses_' . $from->toDateString() . '_to_' . $to->toDateString() . '.csv';

        $expenses = $this->buildQuery()
            ->with('category', 'recorder')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        // Fix #2: sanitize values against CSV formula injection
        $sanitize = static function ($v): string {
            $v = (string) $v;
            return ($v !== '' && preg_match('/^[=+\-@\t\r]/', $v)) ? "'" . $v : $v;
        };

        $callback = function () use ($expenses, $sanitize) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['#', 'Date', 'Category', 'Description', 'Amount', 'Reference', 'Notes', 'Recorded By']);

            foreach ($expenses as $expense) {
                fputcsv($file, array_map($sanitize, [
                    $expense->id,
                    $expense->expense_date->format('Y-m-d'),
                    optional($expense->category)->name ?? '—',
                    $expense->description,
                    number_format($expense->amount, 2),
                    $expense->reference ?? '',
                    $expense->notes ?? '',
                    optional($expense->recorder)->name ?? '—',
                ]));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Category management ──────────────────────────────────────────────

    public function openCreateCategory(): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);
        $this->resetCategoryForm();
        $this->showCategoryModal = true;
    }

    public function openEditCategory(int $id): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);
        $cat = ExpenseCategory::findOrFail($id);
        $this->categoryEditId      = $id;
        $this->isEditingCategory   = true;
        $this->categoryState = [
            'name'        => $cat->name,
            'section'     => $cat->section ?? ExpenseCategory::OPERATING,
            'color'       => $cat->color ?? '#6c757d',
            'description' => $cat->description ?? '',
            'is_active'   => (bool) $cat->is_active,
        ];
        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);

        $data = $this->validate([
            'categoryState.name'        => ['required', 'string', 'max:100',
                Rule::unique('expense_categories', 'name')->ignore($this->categoryEditId)],
            'categoryState.section'     => 'required|in:operating_expense,non_operating_expense',
            'categoryState.color'       => 'required|string|max:20',
            'categoryState.description' => 'nullable|string|max:255',
            'categoryState.is_active'   => 'boolean',
        ], [], [
            'categoryState.name'    => 'category name',
            'categoryState.section' => 'section',
            'categoryState.color'   => 'color',
        ])['categoryState'];

        if ($this->isEditingCategory) {
            ExpenseCategory::findOrFail($this->categoryEditId)->update($data);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Category updated.']);
        } else {
            ExpenseCategory::create($data);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Category created.']);
        }

        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    public function toggleCategoryActive(int $id): void
    {
        abort_if(!auth()->user()?->hasAnyRole(['Manager', 'Super Admin']), 403);
        $cat = ExpenseCategory::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
    }

    private function resetCategoryForm(): void
    {
        $this->resetErrorBag();
        $this->categoryEditId    = null;
        $this->isEditingCategory = false;
        $this->categoryState = [
            'name'        => '',
            'section'     => 'operating_expense',
            'color'       => '#6c757d',
            'description' => '',
            'is_active'   => true,
        ];
    }

    // ── Expense form helpers ─────────────────────────────────────────────

    private function dateRange(): array
    {
        $from = Carbon::parse($this->fromDate ?: now()->startOfMonth())->startOfDay();
        $to   = Carbon::parse($this->toDate   ?: now())->endOfDay();

        if ($from->gt($to)) {
            [$a, $b] = [$from->copy(), $to->copy()];
            $from = ($a->lt($b) ? $a : $b)->startOfDay();
            $to   = ($a->lt($b) ? $b : $a)->endOfDay();
        }

        return [$from, $to];
    }

    private function buildQuery()
    {
        [$from, $to] = $this->dateRange();

        return Expense::with('category')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->when($this->categoryId, fn ($q) => $q->where('expense_category_id', $this->categoryId))
            ->when($this->search, fn ($q) => $q->where(function ($inner) {
                $inner->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('reference', 'like', '%' . $this->search . '%');
            }))
            ->orderByDesc('expense_date')
            ->orderByDesc('id');
    }

    private function validateForm(): array
    {
        $data = $this->validate([
            'state.expense_category_id' => 'nullable|exists:expense_categories,id',
            'state.expense_date'        => 'required|date',
            'state.description'         => 'required|string|max:255',
            'state.amount'              => 'required|numeric|min:0.01',
            'state.reference'           => 'nullable|string|max:100',
            'state.notes'               => 'nullable|string|max:1000',
            'receiptFile'               => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ], [], [
            'state.expense_category_id' => 'category',
            'state.expense_date'        => 'date',
            'state.description'         => 'description',
            'state.amount'              => 'amount',
            'state.reference'           => 'reference',
        ])['state'];

        if (($data['expense_category_id'] ?? '') === '') {
            $data['expense_category_id'] = null;
        }

        return $data;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->expenseId   = null;
        $this->isEditing   = false;
        $this->receiptFile = null;
        $this->state = [
            'expense_category_id' => '',
            'expense_date'        => today()->toDateString(),
            'description'         => '',
            'amount'              => '',
            'reference'           => '',
            'notes'               => '',
        ];
    }

    public function render()
    {
        [$from, $to] = $this->dateRange();

        $expenses       = $this->buildQuery()->paginate($this->perPage);
        $categories     = ExpenseCategory::where('is_active', true)->orderBy('name')->get();
        $allCategories  = ExpenseCategory::orderBy('name')->get();
        $sectionLabels  = ExpenseCategory::sectionLabels();

        $totalInRange = $this->buildQuery()->sum('amount');
        $todayTotal   = Expense::whereDate('expense_date', today())->sum('amount');

        $topCategories = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $editingReceiptUrl = ($this->isEditing && $this->expenseId)
            ? optional(Expense::find($this->expenseId))->receipt_url
            : null;
        $canUseIncomeStatement = LicenseService::has(Feature::ADVANCED_REPORTS)
            && (auth()->user()?->hasRole('Super Admin') || auth()->user()?->can('manage billing'));

        return view('livewire.admin.expenses-component', compact(
            'expenses', 'categories', 'allCategories', 'sectionLabels',
            'totalInRange', 'todayTotal', 'topCategories', 'editingReceiptUrl',
            'canUseIncomeStatement'
        ))->layout('layouts.admin.admin-layout');
    }
}
