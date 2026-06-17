<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Plans <span class="badge badge-secondary">{{ $plans->total() }}</span></h1>
                    <small class="text-muted">Manage plan names, semester prices, and active dropdown options.</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Plans</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card plans-admin-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 font-weight-bold">Plan Directory</h5>
                        <small class="text-muted">Active plans can be used in plan selection dropdowns.</small>
                    </div>
                    <button type="button" wire:click.prevent="openPlanModal" class="btn btn-primary">
                        <i class="fa fa-plus-circle mr-1"></i> Add Plan
                    </button>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="text"
                                    wire:model.debounce.300ms="searchTerm"
                                    class="form-control"
                                    placeholder="Search plan, period, or description...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Plan</th>
                                    <th class="text-right">Price</th>
                                    <th>Period</th>
                                    <th class="text-center">Sort</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="text-muted">
                                @forelse ($plans as $plan)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $plan->name }}</div>
                                            <small class="text-muted">{{ $plan->description ?: 'No description provided' }}</small>
                                        </td>
                                        <td class="text-right font-weight-bold">GHS {{ number_format((float) $plan->price, 2) }}</td>
                                        <td>{{ $plan->billing_period }}</td>
                                        <td class="text-center">{{ $plan->sort_order }}</td>
                                        <td>
                                            <button type="button"
                                                wire:click="togglePlanStatus({{ $plan->id }})"
                                                class="btn btn-xs {{ $plan->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                                <i class="fa {{ $plan->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} mr-1"></i>
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="text-right">
                                            <button type="button"
                                                wire:click.prevent="editPlanModal({{ $plan->id }})"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Edit plan">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button type="button"
                                                wire:click.prevent="confirmPlanDeletion({{ $plan->id }})"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete plan">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fa fa-layer-group fa-3x mb-3 d-block"></i>
                                            No plans found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="planModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updatePlan' : 'createPlan' }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planModalLabel">
                            {{ $showEditModal ? 'Edit Plan' : 'Add New Plan' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="plan-name">Name <span class="text-danger">*</span></label>
                            <input type="text"
                                wire:model.defer="state.name"
                                class="form-control @error('state.name') is-invalid @enderror"
                                id="plan-name"
                                placeholder="e.g. Fresher Plan">
                            @error('state.name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="plan-price">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">GHS</span>
                                    </div>
                                    <input type="number"
                                        wire:model.defer="state.price"
                                        class="form-control @error('state.price') is-invalid @enderror"
                                        id="plan-price"
                                        min="0"
                                        step="0.01">
                                    @error('state.price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="plan-period">Billing Period <span class="text-danger">*</span></label>
                                <input type="text"
                                    wire:model.defer="state.billing_period"
                                    class="form-control @error('state.billing_period') is-invalid @enderror"
                                    id="plan-period"
                                    placeholder="semester">
                                @error('state.billing_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="plan-sort-order">Sort Order</label>
                            <input type="number"
                                wire:model.defer="state.sort_order"
                                class="form-control @error('state.sort_order') is-invalid @enderror"
                                id="plan-sort-order"
                                min="0"
                                step="1">
                            @error('state.sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="plan-description">Description</label>
                            <textarea wire:model.defer="state.description"
                                class="form-control @error('state.description') is-invalid @enderror"
                                id="plan-description"
                                rows="3"
                                placeholder="Optional note for staff"></textarea>
                            @error('state.description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model.defer="state.is_active" class="custom-control-input" id="plan-active">
                            <label class="custom-control-label" for="plan-active">Active in plan dropdowns</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i>
                            {{ $showEditModal ? 'Update Plan' : 'Save Plan' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .plans-admin-card {
            border: 1px solid #dfe5ee;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
        }

        .plans-admin-card th {
            font-size: .75rem;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .btn-xs {
            font-size: .75rem;
            padding: .15rem .45rem;
        }
    </style>

    <script>
        window.addEventListener('show-plan-modal', event => {
            $('#planModal').modal('show');
        });

        window.addEventListener('hide-plan-modal', event => {
            $('#planModal').modal('hide');
        });

        window.addEventListener('show-plan-delete-confirmation', event => {
            Swal.fire({
                title: 'Delete plan?',
                text: 'This plan will be archived and removed from active use.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    @this.call('confirmPlanDelete');
                }
            });
        });
    </script>
</div>
