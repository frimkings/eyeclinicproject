<div>
    <!-- Content Header -->
    <div class="content-header bg-gradient-info" style="padding:1.5rem 0;margin-bottom:1.5rem;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-white">
                        <i class="fas fa-cash-register mr-2"></i>Patient Clearance
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-white-50 active">Clearance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $pendingCount }}</h3>
                            <p>Pending Clearance</p>
                        </div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        <a href="#" wire:click.prevent="switchTab('pending')" class="small-box-footer">
                            View <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $clearedToday }}</h3>
                            <p>Cleared Today</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-double"></i></div>
                        <a href="#" wire:click.prevent="switchTab('cleared')" class="small-box-footer">
                            View <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $paidToday }}</h3>
                            <p>Paid Today</p>
                        </div>
                        <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                        <a href="#" wire:click.prevent="$set('statusFilter','Paid'); switchTab('cleared')" class="small-box-footer">
                            View <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $unpaidToday }}</h3>
                            <p>Unpaid Today</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                        <a href="#" wire:click.prevent="$set('statusFilter','Unpaid'); switchTab('cleared')" class="small-box-footer">
                            View <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tab Card -->
            <div class="card shadow-sm">
                <!-- Tab Header -->
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="clearanceTabs">
                        <li class="nav-item">
                            <button type="button"
                                    wire:click="switchTab('pending')"
                                    class="nav-link btn btn-link {{ $activeTab === 'pending' ? 'active' : '' }}"
                                    style="border-radius:0">
                                <i class="fas fa-hourglass-half mr-1 text-warning"></i>
                                Pending
                                <span class="badge badge-warning ml-1">{{ $pendingCount }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button"
                                    wire:click="switchTab('cleared')"
                                    class="nav-link btn btn-link {{ $activeTab === 'cleared' ? 'active' : '' }}"
                                    style="border-radius:0">
                                <i class="fas fa-check-double mr-1 text-info"></i>
                                Cleared
                                <span class="badge badge-info ml-1">{{ $clearedToday }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- ==================== PENDING TAB ==================== -->
                @if($activeTab === 'pending')
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <div class="input-group" style="max-width:300px">
                            <input wire:model.debounce.300ms="searchTerm"
                                   type="search"
                                   class="form-control form-control-sm"
                                   placeholder="Search patient name, folder, contact…">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width:50px">#</th>
                                    <th><i class="fas fa-user mr-1 text-primary"></i>Patient Name</th>
                                    <th><i class="fas fa-phone mr-1 text-secondary"></i>Contact</th>
                                    <th><i class="fas fa-folder mr-1 text-info"></i>Folder #</th>
                                    <th><i class="fas fa-venus-mars mr-1 text-purple"></i>Gender</th>
                                    <th><i class="fas fa-calendar mr-1 text-success"></i>Registered</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="opacity-50">
                                @forelse($patients as $patient)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="align-middle font-weight-bold">{{ $patient->name }}</td>
                                    <td class="align-middle">
                                        @if($patient->contact)
                                            <a href="tel:{{ $patient->contact }}" class="text-muted">
                                                <i class="fas fa-phone-alt mr-1"></i>{{ $patient->contact }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-info">{{ $patient->pxnumber }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($patient->gender === 'Male')
                                            <span class="badge badge-primary"><i class="fas fa-mars mr-1"></i>M</span>
                                        @elseif($patient->gender === 'Female')
                                            <span class="badge badge-pink" style="background:#e83e8c;color:#fff"><i class="fas fa-venus mr-1"></i>F</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($patient->created_at)->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                wire:click="openClearanceModal({{ $patient->id }})"
                                                title="Process Clearance">
                                            <i class="fas fa-check-circle mr-1"></i>Clear
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                                        <span class="text-muted">All patients have been cleared today!</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $patients->links() }}
                </div>

                @else
                <!-- ==================== CLEARED TAB ==================== -->
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3 align-items-end">
                        {{-- Search --}}
                        <div class="col-sm-6 col-md-4 mb-2">
                            <label class="small text-muted mb-1">Search Patient</label>
                            <div class="input-group input-group-sm">
                                <input wire:model.debounce.300ms="clearedSearch"
                                       type="search"
                                       class="form-control"
                                       placeholder="Name or folder number…">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        {{-- Date From --}}
                        <div class="col-sm-6 col-md-2 mb-2">
                            <label class="small text-muted mb-1">From</label>
                            <input wire:model.lazy="dateFrom"
                                   type="date"
                                   class="form-control form-control-sm"
                                   max="{{ now()->toDateString() }}">
                        </div>
                        {{-- Date To --}}
                        <div class="col-sm-6 col-md-2 mb-2">
                            <label class="small text-muted mb-1">To</label>
                            <input wire:model.lazy="dateTo"
                                   type="date"
                                   class="form-control form-control-sm"
                                   min="{{ $dateFrom }}"
                                   max="{{ now()->toDateString() }}">
                        </div>
                        {{-- Payment Status --}}
                        <div class="col-sm-6 col-md-2 mb-2">
                            <label class="small text-muted mb-1">Payment</label>
                            <select wire:model="statusFilter" class="form-control form-control-sm">
                                <option value="">All</option>
                                <option value="Paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                        {{-- Gender --}}
                        <div class="col-sm-6 col-md-1 mb-2">
                            <label class="small text-muted mb-1">Gender</label>
                            <select wire:model="genderFilter" class="form-control form-control-sm">
                                <option value="">All</option>
                                <option value="Male">M</option>
                                <option value="Female">F</option>
                            </select>
                        </div>
                        {{-- Reset --}}
                        <div class="col-sm-6 col-md-1 mb-2">
                            <label class="small text-muted mb-1 d-block">&nbsp;</label>
                            <button type="button"
                                    wire:click="$set('dateFrom','{{ now()->toDateString() }}');$set('dateTo','{{ now()->toDateString() }}');$set('statusFilter','');$set('genderFilter','');$set('clearedSearch','')"
                                    class="btn btn-sm btn-outline-secondary w-100"
                                    title="Reset filters">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width:50px">#</th>
                                    <th><i class="fas fa-user mr-1 text-primary"></i>Patient Name</th>
                                    <th><i class="fas fa-folder mr-1 text-info"></i>Folder #</th>
                                    <th><i class="fas fa-money-bill-wave mr-1 text-success"></i>Payment</th>
                                    <th><i class="fas fa-stethoscope mr-1 text-purple"></i>Doctor</th>
                                    <th><i class="fas fa-user-check mr-1 text-secondary"></i>Cleared By</th>
                                    <th><i class="far fa-clock mr-1 text-muted"></i>Time</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="opacity-50">
                                @forelse($clearances as $clearance)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $clearance->patient->name ?? '—' }}
                                        @if($clearance->patient?->contact)
                                            <br><small class="text-muted font-weight-normal">
                                                <i class="fas fa-phone-alt mr-1"></i>{{ $clearance->patient->contact }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-info">{{ $clearance->patient->pxnumber ?? '—' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($editingClearanceId === $clearance->id)
                                            <div class="d-flex align-items-center" style="gap:4px">
                                                <select wire:model="editingPaymentStatus"
                                                        class="form-control form-control-sm"
                                                        style="width:90px">
                                                    <option value="Paid">Paid</option>
                                                    <option value="Unpaid">Unpaid</option>
                                                </select>
                                                <button type="button" wire:click="saveStatus"
                                                        class="btn btn-xs btn-success" title="Save">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" wire:click="cancelEditStatus"
                                                        class="btn btn-xs btn-secondary" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div>
                                                <span class="badge badge-{{ $clearance->payment_status === 'Paid' ? 'success' : 'danger' }} cursor-pointer"
                                                      wire:click="startEditStatus({{ $clearance->id }})"
                                                      title="Click to change status"
                                                      style="cursor:pointer">
                                                    <i class="fas fa-{{ $clearance->payment_status === 'Paid' ? 'check' : 'times' }} mr-1"></i>
                                                    {{ $clearance->payment_status }}
                                                    <i class="fas fa-pencil-alt ml-1" style="font-size:.65rem"></i>
                                                </span>
                                                @if($clearance->service)
                                                    <br><small class="text-muted">{{ $clearance->service->name }}</small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($clearance->doctor_status)
                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="badge badge-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $clearance->user->name ?? 'System' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($clearance->created_at)->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($clearance->pendingRevokeLog)
                                            <span class="badge badge-warning px-2 py-1"
                                                  title="Revoke request awaiting manager approval">
                                                <i class="fas fa-hourglass-half mr-1"></i>Revoke Pending
                                            </span>
                                        @else
                                            <button type="button"
                                                    class="btn btn-xs btn-outline-danger"
                                                    wire:click="openRevokeModal({{ $clearance->id }})"
                                                    title="Request Revoke">
                                                <i class="fas fa-undo mr-1"></i>Request Revoke
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="far fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                                        <span class="text-muted">No clearances found for the selected filters.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $clearances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Clearance Modal -->
    <div id="addClearanceModal" class="modal fade" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-money-check-alt mr-2"></i>Process Payment Clearance
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="icon-box">
                            <i class="fas fa-user-check fa-2x text-info"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $patientName }}</h5>
                        <p class="text-muted small">Confirm payment status before clearing</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-concierge-bell mr-2 text-success"></i>Service
                        </label>
                        <select class="custom-select custom-select-lg"
                                wire:model.defer="selectedServiceId"
                                id="selectedServiceId">
                            <option value="">Select service…</option>
                            @foreach($services as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }} — GH₵ {{ number_format($svc->selling_price, 2) }}</option>
                            @endforeach
                            <option value="unpaid">✗ Unpaid (no charge)</option>
                        </select>
                        @error('selectedServiceId')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i>Cancel
                    </button>
                    <button type="button"
                            onclick="@this.call('createClearance', document.getElementById('selectedServiceId').value)"
                            class="btn btn-success">
                        <i class="fa fa-check mr-1"></i>Confirm & Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revoke Request Modal -->
    <div id="revokeRequestModal" class="modal fade" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-undo mr-2"></i>Request Clearance Revoke
                    </h5>
                    <button type="button" class="close text-white" onclick="@this.call('cancelRevokeRequest')"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @if($requestingRevokeName)
                        <p class="mb-3">Submitting revoke request for <strong>{{ $requestingRevokeName }}</strong>.
                            A manager or Super Admin must approve before the clearance is removed.</p>
                    @endif
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small">Reason <span class="text-danger">*</span></label>
                        <textarea wire:model.defer="revokeReason"
                                  class="form-control @error('revokeReason') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Explain why this clearance should be revoked…"></textarea>
                        @error('revokeReason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" onclick="@this.call('cancelRevokeRequest')">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="@this.call('submitRevokeRequest')">
                        <i class="fas fa-paper-plane mr-1"></i>Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
        .card { border: none; border-radius: 8px; overflow: hidden; }
        .shadow-sm { box-shadow: 0 2px 4px rgba(0,0,0,.08); }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .thead-light th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057; }
        .opacity-50 { opacity: .5; }
        .icon-box { width:70px;height:70px;margin:0 auto;border-radius:50%;background:#e3f7fb;display:flex;align-items:center;justify-content:center; }
        .btn-xs { padding:.2rem .45rem; font-size:.75rem; line-height:1.2; }
        .small-box-footer:hover { opacity:.8; }
    </style>

    <script>
        window.addEventListener('show-revokeRequestModal', () => $('#revokeRequestModal').modal('show'));
        window.addEventListener('hide-revokeRequestModal', () => $('#revokeRequestModal').modal('hide'));
    </script>
</div>
