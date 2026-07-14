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
                                        <div class="d-flex justify-content-center" style="gap:4px;">
                                            @if($clearance->sale_id)
                                                <a href="javascript:void(0)"
                                                   onclick="window.open('{{ route('cashier.receipt.show', $clearance->sale_id) }}','_blank','width=302,height=600')"
                                                   class="btn btn-xs btn-outline-info"
                                                   title="View Receipt">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            @endif
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
                                        </div>
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
                                id="selectedServiceId"
                                onchange="toggleClearancePaymentMethod(this.value)">
                            <option value="">Select service…</option>
                            @foreach($services as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }} — {{ currency() }} {{ number_format($svc->selling_price, 2) }}</option>
                            @endforeach
                            <option value="unpaid">✗ Unpaid (no charge)</option>
                        </select>
                        @error('selectedServiceId')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Split payment section --}}
                    <div id="clearancePaymentSection" style="display:none;">
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="font-weight-bold mb-0">
                                <i class="fas fa-credit-card mr-1 text-primary"></i>Payment
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="addClearancePaymentRow()">
                                <i class="fas fa-plus mr-1"></i>Split
                            </button>
                        </div>

                        <div id="clearancePaymentRows"></div>

                        <div class="mt-2 p-2 rounded" style="background:#f8f9fa;font-size:.85rem;">
                            <div class="d-flex justify-content-between">
                                <span>Service Total</span>
                                <strong id="clr-svc-total">0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between text-success">
                                <span>Amount Entered</span>
                                <strong id="clr-entered">0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between text-danger" id="clr-balance-row">
                                <span>Remaining</span>
                                <strong id="clr-remaining">0.00</strong>
                            </div>
                        </div>
                        <div id="clr-payment-error" class="text-danger small mt-1" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i>Cancel
                    </button>
                    <button type="button" id="clearanceConfirmBtn"
                            onclick="submitClearance()"
                            class="btn btn-success">
                        <i class="fa fa-check mr-1"></i>Confirm & Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clearance Receipt Modal -->
    <div class="modal fade" id="clearanceReceiptModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light py-2">
                    <h6 class="modal-title font-weight-bold text-dark">
                        <i class="fas fa-receipt mr-1"></i>Clearance Receipt
                    </h6>
                    <button type="button" class="close"
                            onclick="$('#clearanceReceiptModal').modal('hide')">&times;</button>
                </div>
                <div class="modal-body p-3" id="clrReceiptModalContent"></div>
                <div class="modal-footer bg-light py-2 border-0">
                    <button type="button" class="btn btn-sm btn-secondary"
                            onclick="$('#clearanceReceiptModal').modal('hide')">Close</button>
                    <button onclick="printClearanceReceipt()" class="btn btn-sm btn-primary px-4">
                        <i class="fas fa-print mr-1"></i>Print Receipt
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

        var _clrPrintUrl = '';

        window.addEventListener('show-clearance-receipt-modal', function(e) {
            var d = e.detail;
            _clrPrintUrl = d.printUrl;

            var currency = '{{ currency() }}';

            // Payment rows
            var paymentHtml = '';
            if (d.payments && d.payments.length) {
                d.payments.forEach(function(p) {
                    paymentHtml +=
                        '<div class="d-flex justify-content-between text-muted" style="font-size:.85rem;">' +
                        '<span>PAID (' + p.method.toUpperCase() + ')</span>' +
                        '<span>' + currency + ' ' + p.amount + '</span>' +
                        '</div>';
                });
                if (d.payments.length > 1) {
                    paymentHtml +=
                        '<div class="d-flex justify-content-between font-weight-bold" style="font-size:.85rem;">' +
                        '<span>TOTAL PAID</span><span>' + currency + ' ' + d.amount + '</span></div>';
                }
            }

            var statusClass = d.status === 'Paid' ? 'text-success' : 'text-danger';

            var html =
                '<div class="d-flex justify-content-between mb-2">' +
                '<span class="text-muted" style="font-size:.8rem;">Patient</span>' +
                '<span class="font-weight-bold" style="font-size:.85rem;">' + d.patient + '</span>' +
                '</div>' +
                '<div class="d-flex justify-content-between mb-2">' +
                '<span class="text-muted" style="font-size:.8rem;">ID</span>' +
                '<span style="font-size:.85rem;">' + d.pxnumber + '</span>' +
                '</div>' +
                '<div class="d-flex justify-content-between mb-3">' +
                '<span class="text-muted" style="font-size:.8rem;">TXN</span>' +
                '<span class="font-weight-bold" style="font-size:.85rem;">' + d.txn + '</span>' +
                '</div>' +
                '<hr class="my-2">' +
                '<table class="table table-sm table-bordered mb-2" style="font-size:.85rem;">' +
                '<thead class="thead-light"><tr><th>Service</th><th class="text-right">Amount</th></tr></thead>' +
                '<tbody><tr><td>' + d.service + '</td><td class="text-right">' + currency + ' ' + d.amount + '</td></tr></tbody>' +
                '</table>' +
                '<div class="d-flex justify-content-between font-weight-bold mb-1">' +
                '<span>Grand Total</span><span class="text-primary">' + currency + ' ' + d.amount + '</span>' +
                '</div>' +
                (paymentHtml ? '<hr class="my-1">' + paymentHtml : '') +
                '<hr class="my-2">' +
                '<div class="d-flex justify-content-between">' +
                '<span class="font-weight-bold" style="font-size:.85rem;">Payment Status</span>' +
                '<span class="font-weight-bold ' + statusClass + '">' + d.status.toUpperCase() + '</span>' +
                '</div>';

            document.getElementById('clrReceiptModalContent').innerHTML = html;
            $('#clearanceReceiptModal').modal('show');
        });

        function printClearanceReceipt() {
            var w = window.open(_clrPrintUrl, '_blank', 'width=302,height=600');
            if (!w) {
                alert('Please allow popups for this site to print receipts.');
            }
        }

        // Service price map (populated server-side)
        var _clrPrices = {
            @foreach($services as $svc)
            {{ $svc->id }}: {{ (float) $svc->selling_price }},
            @endforeach
        };
        var _clrServiceTotal = 0;

        function toggleClearancePaymentMethod(val) {
            var section = document.getElementById('clearancePaymentSection');
            if (!section) return;
            if (val && val !== '' && val !== 'unpaid') {
                _clrServiceTotal = _clrPrices[val] || 0;
                document.getElementById('clr-svc-total').textContent = _clrServiceTotal.toFixed(2);
                section.style.display = 'block';
                // Reset rows and add one default row
                document.getElementById('clearancePaymentRows').innerHTML = '';
                addClearancePaymentRow();
                updateClearanceTotals();
            } else {
                section.style.display = 'none';
                document.getElementById('clearancePaymentRows').innerHTML = '';
            }
        }

        var _clrRowIdx = 0;
        function addClearancePaymentRow() {
            var idx = _clrRowIdx++;
            var remaining = _clrServiceTotal - getClearanceEntered();
            var amount = remaining > 0 ? remaining.toFixed(2) : '';
            var row = document.createElement('div');
            row.className = 'd-flex align-items-center mb-2';
            row.style.gap = '6px';
            row.id = 'clr-row-' + idx;
            row.innerHTML =
                '<select class="custom-select custom-select-sm clr-method" style="width:140px;" onchange="updateClearanceTotals()">' +
                    '<option value="cash">Cash</option>' +
                    '<option value="momo">Mobile Money</option>' +
                    '<option value="card">Card</option>' +
                    '<option value="cheque">Cheque</option>' +
                '</select>' +
                '<input type="number" class="form-control form-control-sm clr-amount" min="0.01" step="0.01" ' +
                       'placeholder="Amount" value="' + amount + '" oninput="updateClearanceTotals()" style="width:110px;">' +
                '<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeClearanceRow(' + idx + ')">' +
                    '<i class="fas fa-times"></i>' +
                '</button>';
            document.getElementById('clearancePaymentRows').appendChild(row);
            updateClearanceTotals();
        }

        function removeClearanceRow(idx) {
            var rows = document.getElementById('clearancePaymentRows');
            if (rows.children.length <= 1) return; // keep at least one row
            var row = document.getElementById('clr-row-' + idx);
            if (row) rows.removeChild(row);
            updateClearanceTotals();
        }

        function getClearanceEntered() {
            var total = 0;
            document.querySelectorAll('.clr-amount').forEach(function(inp) {
                total += parseFloat(inp.value) || 0;
            });
            return total;
        }

        function updateClearanceTotals() {
            var entered   = getClearanceEntered();
            var remaining = _clrServiceTotal - entered;
            document.getElementById('clr-entered').textContent   = entered.toFixed(2);
            document.getElementById('clr-remaining').textContent = Math.max(0, remaining).toFixed(2);
            document.getElementById('clr-balance-row').style.display = remaining > 0.005 ? 'flex' : 'none';
        }

        function submitClearance() {
            var svc = document.getElementById('selectedServiceId').value;
            if (!svc) return;

            if (svc !== 'unpaid') {
                var entered   = getClearanceEntered();
                var remaining = _clrServiceTotal - entered;

                if (remaining > 0.005) {
                    var err = document.getElementById('clr-payment-error');
                    err.textContent = 'Total payments (' + entered.toFixed(2) + ') are less than the service amount (' + _clrServiceTotal.toFixed(2) + ').';
                    err.style.display = 'block';
                    return;
                }
                document.getElementById('clr-payment-error').style.display = 'none';

                // Collect payments
                var payments = [];
                document.querySelectorAll('#clearancePaymentRows > div').forEach(function(row) {
                    var method = row.querySelector('.clr-method').value;
                    var amount = parseFloat(row.querySelector('.clr-amount').value) || 0;
                    if (amount > 0) payments.push({method: method, amount: amount});
                });

                @this.call('createClearance', svc, JSON.stringify(payments));
            } else {
                @this.call('createClearance', svc, '[]');
            }
        }

        // Reset when modal closes
        $('#addClearanceModal').on('hidden.bs.modal', function () {
            document.getElementById('clearancePaymentSection').style.display = 'none';
            document.getElementById('clearancePaymentRows').innerHTML = '';
            document.getElementById('selectedServiceId').value = '';
            document.getElementById('clr-payment-error').style.display = 'none';
            _clrRowIdx = 0;
        });
    </script>
</div>
