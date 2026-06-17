<div>
    {{-- Page header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-receipt mr-2 text-danger"></i>Expense Tracker
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Expenses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Stats row --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="info-box shadow-sm mb-0">
                        <span class="info-box-icon bg-danger"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Period Total</span>
                            <span class="info-box-number">{{ currency() }} {{ number_format($totalInRange, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box shadow-sm mb-0">
                        <span class="info-box-icon bg-warning"><i class="fas fa-calendar-day"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today</span>
                            <span class="info-box-number">{{ currency() }} {{ number_format($todayTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                @if($topCategories->isNotEmpty())
                <div class="col-md-6">
                    <div class="card shadow-sm mb-0" style="min-height:58px;">
                        <div class="card-body py-2 px-3">
                            <p class="text-muted small mb-1">Top Expense Categories (Period)</p>
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                @foreach($topCategories as $tc)
                                    <span class="badge" style="background:{{ optional($tc->category)->color ?? '#6c757d' }}; font-size:12px; padding:4px 8px;">
                                        {{ optional($tc->category)->name ?? 'Uncategorised' }}:
                                        {{ currency() }}{{ number_format($tc->total, 2) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Filters + actions --}}
            <div class="card card-outline card-danger shadow-sm">
                <div class="card-header flex-wrap" style="gap:8px;">
                    <div class="d-flex align-items-center flex-wrap w-100" style="gap:8px;">
                        {{-- Date range --}}
                        <div class="input-group" style="max-width:160px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                            </div>
                            <input wire:model.lazy="fromDate" type="date" class="form-control border-left-0" title="From date">
                        </div>
                        <div class="input-group" style="max-width:160px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                            </div>
                            <input wire:model.lazy="toDate" type="date" class="form-control border-left-0" title="To date">
                        </div>

                        {{-- Category filter --}}
                        <select wire:model="categoryId" class="form-control" style="max-width:180px;">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        {{-- Search --}}
                        <div class="input-group" style="max-width:240px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input wire:model.debounce.300ms="search"
                                type="text" class="form-control border-left-0"
                                placeholder="Description or reference…">
                            @if($search)
                                <div class="input-group-append">
                                    <button wire:click="$set('search', '')" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </button>

                        <div class="ml-auto d-flex" style="gap:6px;">
                            @if($canUseIncomeStatement)
                            <a href="{{ route('admin.income-statement', ['fromDate' => $fromDate, 'toDate' => $toDate]) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-invoice-dollar mr-1"></i>Income Statement
                            </a>
                            @endif
                            <button wire:click="exportCsv" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-csv mr-1"></i>Export CSV
                            </button>
                            <button wire:click="openCreate" class="btn btn-danger btn-sm">
                                <i class="fas fa-plus mr-1"></i>Record Expense
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th class="text-right">Amount</th>
                                    <th>Recorded By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="opacity-50">
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td class="text-nowrap">{{ $expense->expense_date->format('d M Y') }}</td>
                                        <td>
                                            @if($expense->category)
                                                <span class="badge" style="background:{{ $expense->category->color }}; font-size:11px; padding:3px 7px;">
                                                    {{ $expense->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $expense->description }}</div>
                                            @if($expense->notes)
                                                <small class="text-muted">{{ Str::limit($expense->notes, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $expense->reference ?: '—' }}</td>
                                        <td class="text-right font-weight-bold">
                                            {{ currency() }} {{ number_format($expense->amount, 2) }}
                                        </td>
                                        <td>
                                            <small>{{ optional($expense->recorder)->name ?? '—' }}</small><br>
                                            <small class="text-muted">{{ $expense->created_at->format('d M, h:i A') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                @if($expense->receipt_path)
                                                <a href="{{ $expense->receipt_url }}" target="_blank"
                                                   class="btn btn-outline-secondary" title="View Receipt">
                                                    <i class="fas fa-paperclip"></i>
                                                </a>
                                                @endif
                                                <button wire:click="openEdit({{ $expense->id }})"
                                                    class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if(auth()->user()->hasAnyRole(['Manager', 'Super Admin']))
                                                <button wire:click="confirmDelete({{ $expense->id }})"
                                                    class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-receipt fa-3x mb-3 d-block text-secondary"></i>
                                            @if($search || $categoryId)
                                                <h5>No expenses match your filters</h5>
                                                <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm mt-2">
                                                    <i class="fas fa-undo mr-1"></i>Clear Filters
                                                </button>
                                            @else
                                                <h5>No expenses recorded for this period</h5>
                                                <button wire:click="openCreate" class="btn btn-danger btn-sm mt-2">
                                                    <i class="fas fa-plus mr-1"></i>Record First Expense
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($expenses->isNotEmpty())
                            <tfoot class="thead-light">
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Page Total:</td>
                                    <td class="text-right font-weight-bold text-danger">
                                        {{ currency() }} {{ number_format($expenses->sum('amount'), 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                @if($expenses->hasPages())
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        {{ $expenses->links() }}
                        <select wire:model="perPage" class="form-control form-control-sm" style="width:auto;">
                            <option value="15">15 / page</option>
                            <option value="30">30 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                @endif
            </div>

        </div>
    </section>

    {{-- Expense Categories Management --}}
    @if(auth()->user()->hasAnyRole(['Manager', 'Super Admin']))
    <div class="container-fluid pb-3">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header d-flex align-items-center" style="cursor:pointer;" wire:click="$toggle('showCategoryPanel')">
                <h6 class="card-title mb-0">
                    <i class="fas fa-tags mr-2"></i>Expense Categories
                    <small class="text-muted ml-2">— set Income Statement section per category</small>
                </h6>
                @if($canUseIncomeStatement)
                <a href="{{ route('admin.income-statement', ['fromDate' => $fromDate, 'toDate' => $toDate]) }}"
                   onclick="event.stopPropagation();"
                   class="btn btn-xs btn-outline-primary ml-auto mr-2">
                    <i class="fas fa-file-invoice-dollar mr-1"></i>Open Income Statement
                </a>
                @endif
                <i class="fas fa-chevron-{{ $showCategoryPanel ? 'up' : 'down' }} {{ $canUseIncomeStatement ? '' : 'ml-auto' }}"></i>
            </div>
            @if($showCategoryPanel)
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-end">
                    <button wire:click="openCreateCategory" class="btn btn-sm btn-secondary">
                        <i class="fas fa-plus mr-1"></i>New Category
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Category</th>
                                <th>Income Statement Section</th>
                                <th>Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allCategories as $cat)
                            <tr class="{{ $cat->is_active ? '' : 'text-muted' }}">
                                <td>
                                    <span class="badge mr-1" style="background:{{ $cat->color }}; width:14px; height:14px; display:inline-block; border-radius:50%;"></span>
                                    {{ $cat->name }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ ($cat->section ?? 'operating_expense') === 'operating_expense' ? 'primary' : 'warning' }}">
                                        {{ $sectionLabels[$cat->section ?? 'operating_expense'] ?? $cat->section }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $cat->description ?: '—' }}</td>
                                <td class="text-center">
                                    <button wire:click="toggleCategoryActive({{ $cat->id }})"
                                        class="badge badge-{{ $cat->is_active ? 'success' : 'secondary' }} border-0"
                                        style="cursor:pointer;">
                                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button wire:click="openEditCategory({{ $cat->id }})"
                                        class="btn btn-xs btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No categories yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Category Add / Edit Modal --}}
    @if($showCategoryModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-tags mr-2"></i>
                        {{ $isEditingCategory ? 'Edit Category' : 'New Category' }}
                    </h5>
                    <button wire:click="$set('showCategoryModal', false)" type="button" class="close text-white">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input wire:model.defer="categoryState.name" type="text"
                                class="form-control @error('categoryState.name') is-invalid @enderror"
                                placeholder="e.g. Staff Salaries">
                            @error('categoryState.name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Color</label>
                            <input wire:model.defer="categoryState.color" type="color"
                                class="form-control" style="height:38px; padding:2px;">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Income Statement Section <span class="text-danger">*</span></label>
                            <select wire:model.defer="categoryState.section"
                                class="form-control @error('categoryState.section') is-invalid @enderror">
                                @foreach($sectionLabels as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Determines where this category appears when imported into the Income Statement.</small>
                            @error('categoryState.section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <input wire:model.defer="categoryState.description" type="text"
                                class="form-control" placeholder="Optional note">
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input wire:model.defer="categoryState.is_active"
                                    type="checkbox" class="custom-control-input" id="catActive">
                                <label class="custom-control-label" for="catActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showCategoryModal', false)" class="btn btn-secondary">Cancel</button>
                    <button wire:click="saveCategory" class="btn btn-dark">
                        <i class="fas fa-save mr-1"></i>{{ $isEditingCategory ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Add / Edit Modal --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-receipt mr-2"></i>
                        {{ $isEditing ? 'Edit Expense' : 'Record Expense' }}
                    </h5>
                    <button wire:click="$set('showModal', false)" type="button" class="close text-white">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Date --}}
                        <div class="col-md-6 form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input wire:model.defer="state.expense_date" type="date"
                                class="form-control @error('state.expense_date') is-invalid @enderror">
                            @error('state.expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Amount --}}
                        <div class="col-md-6 form-group">
                            <label>Amount ({{ currency() }}) <span class="text-danger">*</span></label>
                            <input wire:model.defer="state.amount" type="number" step="0.01" min="0.01"
                                class="form-control @error('state.amount') is-invalid @enderror"
                                placeholder="0.00">
                            @error('state.amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Category --}}
                        <div class="col-md-12 form-group">
                            <label>Category</label>
                            <select wire:model.defer="state.expense_category_id"
                                class="form-control @error('state.expense_category_id') is-invalid @enderror">
                                <option value="">— Uncategorised —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('state.expense_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Description --}}
                        <div class="col-md-12 form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <input wire:model.defer="state.description" type="text"
                                class="form-control @error('state.description') is-invalid @enderror"
                                placeholder="e.g. Monthly rent payment">
                            @error('state.description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Reference --}}
                        <div class="col-md-6 form-group">
                            <label>Receipt / Invoice #</label>
                            <input wire:model.defer="state.reference" type="text"
                                class="form-control @error('state.reference') is-invalid @enderror"
                                placeholder="e.g. INV-2026-0042">
                            @error('state.reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Notes --}}
                        <div class="col-md-6 form-group">
                            <label>Notes</label>
                            <input wire:model.defer="state.notes" type="text"
                                class="form-control"
                                placeholder="Optional note">
                        </div>
                        {{-- Receipt / Photo --}}
                        <div class="col-md-12 form-group mb-0">
                            <label>Receipt / Photo <small class="text-muted">(JPG, PNG, PDF · max 5 MB)</small></label>
                            @if($isEditing && $editingReceiptUrl)
                                <div class="mb-2 d-flex align-items-center" style="gap:8px;">
                                    <a href="{{ $editingReceiptUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip mr-1"></i>View Current Receipt
                                    </a>
                                    <button wire:click="deleteReceipt({{ $expenseId }})" type="button"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times mr-1"></i>Remove
                                    </button>
                                </div>
                            @endif
                            <input wire:model="receiptFile" type="file" class="form-control-file"
                                   accept="image/*,application/pdf">
                            @error('receiptFile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @if($receiptFile)
                                <small class="text-success mt-1 d-block">File selected — will be saved on submit.</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button wire:click="$set('showModal', false)" type="button" class="btn btn-secondary">Cancel</button>
                    <button wire:click="save" type="button" class="btn btn-danger">
                        <i class="fas fa-save mr-1"></i>
                        {{ $isEditing ? 'Update' : 'Record Expense' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
