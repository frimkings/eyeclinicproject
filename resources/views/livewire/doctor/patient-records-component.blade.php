<div class="emr-root">

    {{-- Premium Patient Profile Header --}}
    <div class="px-header">
        <div class="px-header__inner">

            {{-- LEFT: Avatar + Identity --}}
            <div class="px-header__identity">
                <div class="px-avatar">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
                <div class="px-header__name-block">
                    <div class="px-header__name">{{ $patient->name }}</div>
                    <div class="px-header__meta">
                        <span class="px-id">{{ $patient->pxnumber }}</span>
                        <span class="px-divider">·</span>
                        @if($patient->gender === 'Male')
                            <span class="px-badge px-badge--gender"><i class="fas fa-mars"></i> Male</span>
                        @elseif($patient->gender === 'Female')
                            <span class="px-badge px-badge--gender-f"><i class="fas fa-venus"></i> Female</span>
                        @else
                            <span class="px-badge">{{ $patient->gender }}</span>
                        @endif
                        <span class="px-badge px-badge--age">{{ \Carbon\Carbon::parse($patient->dob)->age }} yrs</span>
                        @php
                            $hasUnusedClearance = \App\Models\CashierPatientClearance::where('patient_id', $patient->id)
                                ->where('payment_status', 'Paid')
                                ->where('doctor_status', false)
                                ->exists();
                        @endphp
                        @if($hasUnusedClearance)
                            <span class="px-badge px-badge--ready"><i class="fas fa-check-circle"></i> Ready</span>
                        @elseif($clearance && $clearance->payment_status === 'Paid')
                            <span class="px-badge px-badge--used"><i class="fas fa-check-double"></i> Used</span>
                        @elseif($clearance)
                            <span class="px-badge px-badge--warn"><i class="fas fa-exclamation-triangle"></i> Unpaid</span>
                        @else
                            <span class="px-badge px-badge--danger"><i class="fas fa-times-circle"></i> No Clearance</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CENTER: Demographics --}}
            <div class="px-header__demographics">
                <div class="px-demo-item">
                    <span class="px-demo-label">DOB</span>
                    <span class="px-demo-value">{{ \Carbon\Carbon::parse($patient->dob)->format('d M, Y') }}</span>
                </div>
                <div class="px-demo-sep"></div>
                <div class="px-demo-item">
                    <span class="px-demo-label">Occupation</span>
                    <span class="px-demo-value"><i class="fas fa-briefcase" style="color:#22C55E;font-size:10px;"></i> {{ $patient->occupation}}</span>
                </div>
                <div class="px-demo-sep"></div>
                <div class="px-demo-item">
                    <span class="px-demo-label">Total Visits</span>
                    <span class="px-demo-value px-demo-value--highlight">{{ $patientRecords->total() }}</span>
                </div>
            </div>

            {{-- RIGHT: Actions --}}
            <div class="px-header__actions">
                @if($this->canStartConsultation)
                    <button wire:click="startNewConsultation" class="px-btn px-btn--primary">
                        <i class="fas fa-plus"></i> New Visit
                    </button>
                @else
                    <button disabled class="px-btn px-btn--ghost"
                        title="{{ !$clearance ? 'No clearance' : ($clearance->payment_status !== 'Paid' ? 'Payment required' : 'Clearance used') }}">
                        <i class="fas fa-ban"></i> {{ !$clearance ? 'No Clearance' : ($clearance->payment_status !== 'Paid' ? 'Unpaid' : 'Used') }}
                    </button>
                @endif
                @if($clearance)
                    <a href="{{ route('doctor.medical-record.pdf', ['patient' => $patient, 'clearance' => $clearance]) }}"
                        class="px-btn px-btn--ghost" title="Download Medical Record PDF">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                @else
                    <button disabled class="px-btn px-btn--ghost" title="No clearance available">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                @endif
                <a href="{{ route('doctor.patient-timeline', $patient) }}"
                    class="px-btn px-btn--icon" title="View Clinical Timeline">
                    <i class="fas fa-stream"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="emr-main-card">
        <div class="emr-tab-bar">
            <button class="emr-tab {{ $activeTab === 'history' ? 'emr-tab--active' : '' }}"
                wire:click.prevent="switchTab('history')">
                <i class="fas fa-history"></i> History
                <span class="emr-tab-badge">{{ $patientRecords->total() }}</span>
            </button>
            <button class="emr-tab {{ $activeTab === 'consultation' ? 'emr-tab--active' : '' }}"
                wire:click.prevent="switchTab('consultation')">
                <i class="fas fa-stethoscope"></i> Consultation
                @if($isEditMode)
                    <span class="emr-tab-dot"></span>
                @endif
            </button>
            <button class="emr-tab {{ $activeTab === 'prescription' ? 'emr-tab--active' : '' }}"
                wire:click.prevent="switchTab('prescription')">
                <i class="fas fa-prescription"></i> Prescription
                @if(count($productsList) > 0)
                    <span class="emr-tab-badge emr-tab-badge--red">{{ count($productsList) }}</span>
                @endif
            </button>
            <button class="emr-tab {{ $activeTab === 'refraction' ? 'emr-tab--active' : '' }}"
                wire:click.prevent="switchTab('refraction')">
                <i class="fas fa-glasses"></i> Refraction
            </button>
            <button class="emr-tab {{ $activeTab === 'bills' ? 'emr-tab--active' : '' }}"
                wire:click.prevent="switchTab('bills')">
                <i class="fas fa-folder-open"></i> Clinical Documents
                @if($patientDocuments->count() > 0)
                    <span class="emr-tab-badge">{{ $patientDocuments->count() }}</span>
                @endif
            </button>
        </div>

        <div class="emr-tab-content">
            {{-- TAB 1: CONSULTATION HISTORY --}}
            @if($activeTab === 'history')
                <div class="history-tab">

                    {{-- Header + Search --}}
                    <div class="history-header">
                        <div class="history-header__title">
                            <i class="fas fa-clipboard-list" style="color:#2563EB;"></i>
                            Patient Consultation History
                        </div>
                        <div style="position:relative;">
                            <div class="search-box">
                                <i class="fas fa-search search-box__icon"></i>
                                <input type="text" wire:model="searchTerm" class="search-box__input" placeholder="Search records...">
                            </div>
                        </div>
                    </div>

                    <div id="clinical-trend-data" data-trends='@json($clinicalTrendData)' style="display:none;"></div>

                    {{-- Charts Row --}}
                    <div class="metrics-row">
                        {{-- VA Trend Chart --}}
                        <div class="metrics-card">
                            <div class="metrics-card__header">
                                <div class="metrics-card__title">
                                    <i class="fas fa-chart-line" style="color:#2563EB;"></i>
                                    Visual Acuity Trend
                                </div>
                                <span class="metrics-card__subtitle">6m decimal equivalent · LogMAR scale</span>
                            </div>
                            <div class="metrics-card__body--chart">
                                @if($clinicalTrendData['summary']['hasVaData'] ?? false)
                                    <canvas id="visualAcuityTrendChart"></canvas>
                                @else
                                    <div class="chart-empty">
                                        <i class="fas fa-eye"></i>
                                        <span>No visual acuity data recorded yet</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Clinical Snapshot KPIs --}}
                        <div class="metrics-card">
                            <div class="metrics-card__header">
                                <div class="metrics-card__title">
                                    <i class="fas fa-tachometer-alt" style="color:#EF4444;"></i>
                                    Latest Snapshot
                                </div>
                            </div>
                            <div class="metrics-card__body">
                                <div class="kpi-grid">
                                    <div class="kpi-item">
                                        <div class="kpi-label">VA OD</div>
                                        <div class="kpi-value">{{ $clinicalTrendData['summary']['latestVaOd'] ?? '—' }}</div>
                                    </div>
                                    <div class="kpi-item">
                                        <div class="kpi-label">VA OS</div>
                                        <div class="kpi-value">{{ $clinicalTrendData['summary']['latestVaOs'] ?? '—' }}</div>
                                    </div>
                                    <div class="kpi-item">
                                        <div class="kpi-label">IOP OD</div>
                                        <div class="kpi-value {{ (($clinicalTrendData['summary']['latestIopOd'] ?? 0) > 21) ? 'kpi-value--danger' : ((($clinicalTrendData['summary']['latestIopOd'] ?? 0) > 0) ? 'kpi-value--safe' : '') }}">
                                            {{ $clinicalTrendData['summary']['latestIopOd'] ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="kpi-item">
                                        <div class="kpi-label">IOP OS</div>
                                        <div class="kpi-value {{ (($clinicalTrendData['summary']['latestIopOs'] ?? 0) > 21) ? 'kpi-value--danger' : ((($clinicalTrendData['summary']['latestIopOs'] ?? 0) > 0) ? 'kpi-value--safe' : '') }}">
                                            {{ $clinicalTrendData['summary']['latestIopOs'] ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                                @if(($clinicalTrendData['summary']['highIopCount'] ?? 0) > 0)
                                    <div class="kpi-alert">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $clinicalTrendData['summary']['highIopCount'] }} visit(s) with IOP &gt; 21 mmHg
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- IOP Trend --}}
                    <div class="metrics-card mb-section">
                        <div class="metrics-card__header">
                            <div class="metrics-card__title">
                                <i class="fas fa-chart-area" style="color:#22C55E;"></i>
                                IOP Trend
                            </div>
                            <span class="metrics-card__subtitle">mmHg · Normal &lt; 21</span>
                        </div>
                        <div class="metrics-card__body--chart-sm">
                            @if($clinicalTrendData['summary']['hasIopData'] ?? false)
                                <canvas id="iopTrendChart"></canvas>
                            @else
                                <div class="chart-empty">
                                    <i class="fas fa-chart-line"></i>
                                    <span>No IOP data recorded yet</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Eye Disease Risk Flags --}}
                    <div class="section-card mb-section">
                        <div class="section-card__header">
                            <div class="section-card__title">
                                <i class="fas fa-flag" style="color:#EF4444;"></i>
                                Eye Disease Risk Flags
                            </div>
                            <span class="metrics-card__subtitle">Calculated from recent visits</span>
                        </div>
                        <div class="section-card__body">
                            <div class="risk-grid">
                                @foreach($eyeDiseaseRiskFlags as $flag)
                                    <div class="risk-card risk-card--{{ $flag['level'] }}">
                                        <div class="risk-card__head">
                                            <span class="risk-card__name">
                                                @if($flag['name'] === 'Glaucoma')
                                                    <i class="fas fa-eye"></i>
                                                @elseif($flag['name'] === 'Cataract')
                                                    <i class="fas fa-circle-notch"></i>
                                                @else
                                                    <i class="fas fa-tint"></i>
                                                @endif
                                                {{ $flag['name'] }}
                                            </span>
                                            @if($flag['level'] === 'warning')
                                                <span class="risk-badge risk-badge--review">Review</span>
                                            @elseif($flag['level'] === 'urgent')
                                                <span class="risk-badge risk-badge--urgent">Urgent</span>
                                            @else
                                                <span class="risk-badge risk-badge--clear">No flag</span>
                                            @endif
                                        </div>
                                        @if(!empty($flag['reasons']))
                                            <ul class="risk-card__reasons">
                                                @foreach($flag['reasons'] as $reason)
                                                    <li>{{ $reason }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="risk-card__empty">No obvious risk marker found in recorded data.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Consultation Visits --}}
                    <div class="section-card mb-section">
                        <div class="section-card__header">
                            <div class="section-card__title">
                                <i class="fas fa-list" style="color:#2563EB;"></i>
                                Consultation Visits
                                <span class="section-badge">{{ $patientRecords->total() }}</span>
                            </div>
                            <div class="section-card__actions">
                                @php $selectedSummaryIds = collect($selectedVisitSummaries)->filter()->implode(','); @endphp
                                <a href="{{ $selectedSummaryIds ? route('doctor.visit-summaries.download', ['ids' => $selectedSummaryIds]) : '#' }}"
                                    class="px-btn px-btn--ghost {{ $selectedSummaryIds ? '' : 'disabled' }}"
                                    title="Download selected visit summaries">
                                    <i class="fas fa-file-download"></i> Download Selected
                                    @if(count($selectedVisitSummaries) > 0)
                                        <span class="section-badge">{{ count($selectedVisitSummaries) }}</span>
                                    @endif
                                </a>
                                <button class="px-btn px-btn--ghost" wire:click="toggleConsultationHistory"
                                    aria-expanded="{{ ($consultationHistoryExpanded || count($selectedVisitSummaries) > 0) ? 'true' : 'false' }}">
                                    <i class="fas fa-chevron-down"></i> Show / Hide
                                </button>
                            </div>
                        </div>
                        <div id="consultationHistoryCollapse" class="collapse {{ ($consultationHistoryExpanded || count($selectedVisitSummaries) > 0) ? 'show' : '' }}">
                            <div class="modern-table-wrap">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th style="width:42px;"></th>
                                            <th>Date</th>
                                            <th>Chief Complaint</th>
                                            <th>Diagnosis</th>
                                            <th>Doctor</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patientRecords as $record)
                                            <tr>
                                                <td><input type="checkbox" class="modern-checkbox" wire:model="selectedVisitSummaries" value="{{ $record->id }}" title="Select for combined PDF"></td>
                                                <td>
                                                    <div class="visit-date">{{ $record->created_at->format('d M, Y') }}</div>
                                                    <div class="visit-time">{{ $record->created_at->format('h:i A') }}</div>
                                                </td>
                                                <td class="visit-complaint">{{ Str::limit($record->chiefComplaint, 60) }}</td>
                                                <td>
                                                    @forelse($record->diagnoses as $diag)
                                                        <span class="diag-chip">{{ $diag->name }}</span>
                                                    @empty
                                                        <span class="text-muted-sm">No diagnosis</span>
                                                    @endforelse
                                                </td>
                                                <td class="visit-doctor">{{ $record->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="action-group">
                                                        <button wire:click="editConsultation({{ $record->id }})" class="action-btn" title="Edit Consultation"><i class="fas fa-edit"></i></button>
                                                        <button wire:click="loadRefractionData({{ $record->id }})" class="action-btn action-btn--green" title="View/Edit Refraction"><i class="fas fa-glasses"></i></button>
                                                        <button wire:click="printRefraction({{ $record->id }})" class="action-btn action-btn--slate" title="Print Prescription"><i class="fas fa-print"></i></button>
                                                        <a href="{{ route('doctor.visit-summary.print', $record) }}" target="_blank" class="action-btn" title="Visit Summary"><i class="fas fa-file-medical-alt"></i></a>
                                                        <a href="{{ route('doctor.prescription.print', $record) }}" target="_blank" class="action-btn action-btn--amber" title="Drug Prescription"><i class="fas fa-prescription"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="table-empty">
                                                    <i class="fas fa-folder-open"></i>
                                                    <div>No consultation records found</div>
                                                    <small>Start by creating a new consultation for this patient.</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-wrap">{{ $patientRecords->links() }}</div>
                        </div>
                    </div>

                    {{-- Audit Trail --}}
                    <div class="section-card">
                        <div class="section-card__header">
                            <div class="section-card__title">
                                <i class="fas fa-history" style="color:#F59E0B;"></i>
                                Recent Audit Trail
                                <span class="section-badge">{{ $auditTrails->count() }}</span>
                            </div>
                            <button class="px-btn px-btn--ghost" type="button"
                                data-bs-toggle="collapse" data-bs-target="#recentAuditTrailCollapse">
                                <i class="fas fa-chevron-down"></i> Show / Hide
                            </button>
                        </div>
                        <div id="recentAuditTrailCollapse" class="collapse">
                            <div class="audit-timeline">
                                @forelse($auditTrails as $audit)
                                    <div class="audit-item">
                                        <div class="audit-icon">
                                            @if($audit->event === 'created') <i class="fas fa-plus"></i>
                                            @elseif($audit->event === 'updated') <i class="fas fa-pen"></i>
                                            @elseif($audit->event === 'deleted') <i class="fas fa-trash"></i>
                                            @else <i class="fas fa-circle" style="font-size:6px;"></i>
                                            @endif
                                        </div>
                                        <div class="audit-body">
                                            <div class="audit-meta">
                                                <span class="audit-user">{{ $audit->user->name ?? 'System' }}</span>
                                                <span class="audit-event">{{ $audit->event }}</span>
                                                <span class="audit-time">{{ $audit->created_at->format('d M Y · h:i A') }}</span>
                                            </div>
                                            <div class="audit-desc">{{ $audit->description }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="audit-empty">No audit activity recorded yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            {{-- TAB 2: CONSULTATION FORM --}}
            @if($activeTab === 'consultation')
                @php
                    $consultationFieldsLocked = $this->consultationFieldsLocked;
                    $consultationEditLockReason = $this->consultationEditLockReason;
                @endphp
                <div class="tab-content-wrapper">
                    {{-- Consultation Form (NEW or EDIT) - ALWAYS ACCESSIBLE --}}
                    <div class="consultation-titlebar d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1 text-primary font-weight-bold">
                                <i class="fas fa-stethoscope"></i>
                                {{ $isEditMode ? 'Edit Consultation Record' : 'New Consultation Record' }}
                            </h5>
                            @if($isEditMode && $consultation)
                                <small class="text-muted">
                                    Created by {{ $consultation->user->name ?? 'N/A' }} on {{ $consultation->created_at->format('d M Y h:i A') }}
                                </small>
                            @else
                                <small class="text-muted">Capture complaint, exam findings, diagnosis, and management plan.</small>
                            @endif
                        </div>
                        <button wire:click="cancelAndGoBack" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </button>
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($consultationFieldsLocked)
                        <div class="consultation-lock-panel mb-3">
                            <div class="d-flex align-items-start">
                                <div class="consultation-lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">Limited editing mode</div>
                                    <div>{{ $consultationEditLockReason }}</div>
                                    <small>Original clinical fields stay unchanged. Add a signed clinical addendum instead.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php
                        $urgentReferralReasons = $this->urgentReferralReasons;
                    @endphp

                    @if(!empty($urgentReferralReasons))
                        <div class="urgent-referral-panel mb-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div class="mb-2">
                                    <div class="font-weight-bold">
                                        <i class="fas fa-exclamation-triangle"></i> Urgent referral red flag detected
                                    </div>
                                    <div class="small">Detected: {{ implode(', ', $urgentReferralReasons) }}</div>
                                </div>
                                <button type="button"
                                    wire:click="createUrgentReferralDraft"
                                    wire:loading.attr="disabled"
                                    wire:target="createUrgentReferralDraft"
                                    class="btn btn-danger btn-sm">
                                    <span wire:loading.remove wire:target="createUrgentReferralDraft">
                                        <i class="fas fa-paper-plane"></i> Create Urgent Referral Draft
                                    </span>
                                    <span wire:loading wire:target="createUrgentReferralDraft">
                                        <i class="fas fa-spinner fa-spin"></i> Creating
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $isEditMode ? 'updateConsultation' : 'createConsultation' }}">
                        {{-- Chief Complaint & History --}}
                        <div class="card border mb-3 {{ $consultationFieldsLocked ? 'consultation-section-locked' : '' }}">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 font-weight-bold">
                                    Patient History
                                    @if($consultationFieldsLocked)
                                        <span class="badge badge-secondary ml-2"><i class="fas fa-lock"></i> Locked</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Chief Complaint <span
                                                class="text-danger">*</span></label>
                                        <textarea wire:model.defer="state.chiefComplaint"
                                            class="form-control @error('state.chiefComplaint') is-invalid @enderror"
                                            rows="3" placeholder="Enter patient's main complaint"
                                            {{ $consultationFieldsLocked ? 'disabled' : '' }}></textarea>
                                        @error('state.chiefComplaint') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Other History</label>
                                        <textarea wire:model.defer="state.others" class="form-control" rows="3"
                                            placeholder="Additional medical history"
                                            {{ $consultationFieldsLocked ? 'disabled' : '' }}></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3" wire:ignore>
                                        @php
                                            $odqOptions = [
                                                'Discharge',
                                                'Tearing',
                                                'Itching',
                                                'Pain',
                                                'Severe Eye Pain',
                                                'Redness',
                                                'Photophobia',
                                                'Blurred Vision',
                                                'Sudden Vision Loss',
                                                'Trauma',
                                                'Headache',
                                                'Floaters',
                                                'Flashes',
                                                'Double Vision',
                                                'Foreign Body Sensation',
                                                'Dry Eyes',
                                            ];
                                            $selectedOdq = collect($state['odq'] ?? [])->filter()->values()->toArray();
                                            $allOdqOptions = collect($odqOptions)->merge($selectedOdq)->unique()->values();
                                        @endphp
                                        <label class="font-weight-bold">ODQ</label>
                                        <select id="patient-records-odq"
                                            class="form-control odq-select2"
                                            multiple="multiple"
                                            data-placeholder="Select or type ODQ options"
                                            {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                            @foreach($allOdqOptions as $option)
                                                <option value="{{ $option }}" {{ in_array($option, $selectedOdq, true) ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Choose multiple options or type a new one and press Enter.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Examination --}}
                        <div class="card border mb-3 {{ $consultationFieldsLocked ? 'consultation-section-locked' : '' }}">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 font-weight-bold">
                                    Examination
                                    @if($consultationFieldsLocked)
                                        <span class="badge badge-secondary ml-2"><i class="fas fa-lock"></i> Locked</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2 font-weight-bold border-bottom pb-2">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-5 text-center">OD (Right Eye)</div>
                                    <div class="col-md-5 text-center">OS (Left Eye)</div>
                                </div>

                                @php
                                    $examMap = [
                                        'vaOD6m' => 'vaOS6m',
                                        'lidsOD' => 'lidsOS',
                                        'conjunctivaOD' => 'conjunctivaOS',
                                        'corneaOD' => 'corneaOS',
                                        'irisOD' => 'irisOS',
                                        'pupilOD' => 'pupilOS',
                                        'lensOD' => 'lensOS',
                                        'vitreousOD' => 'vitreousOS',
                                        'fundusOD' => 'fundusOS',
                                        'cdrOD' => 'cdrOS',
                                        'IOPOD' => 'IOPOS'
                                    ];
                                    $examLabels = [
                                        'vaOD6m' => 'V/A (6m)',
                                        'lidsOD' => 'Lids',
                                        'conjunctivaOD' => 'Conjunctiva',
                                        'corneaOD' => 'Cornea',
                                        'irisOD' => 'Iris',
                                        'pupilOD' => 'Pupil',
                                        'lensOD' => 'Lens',
                                        'vitreousOD' => 'Vitreous',
                                        'fundusOD' => 'Fundus',
                                        'cdrOD' => 'C.D.R',
                                        'IOPOD' => 'IOP'
                                    ];
                                @endphp
                                @foreach($examMap as $odKey => $osKey)
                                    <div class="row mb-2">
                                        <div class="col-md-2 text-right d-flex align-items-center justify-content-end">
                                            <small class="font-weight-bold">{{ $examLabels[$odKey] }}</small>
                                        </div>

                                        {{-- Right Eye (OD) --}}
                                        <div class="col-md-5">
                                            @if(Str::contains($odKey, 'IOP'))
                                                <input type="number" step="0.1" min="0" max="80"
                                                    wire:model.defer="state.{{ $odKey }}"
                                                    class="form-control form-control-sm @error('state.' . $odKey) is-invalid @enderror"
                                                    placeholder="mmHg"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                            @elseif(Str::startsWith($odKey, 'va'))
                                                <select wire:model.defer="state.{{ $odKey }}"
                                                    class="form-control form-control-sm"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                                    <option value="">— select —</option>
                                                    @foreach(\App\Http\Livewire\Doctor\PatientRecordsComponent::vaLogMarTable($vaNotation) as $label => $logmar)
                                                        <option value="{{ $label }}">{{ $label }}{{ $logmar !== null ? ' ('.($logmar >= 0 ? '+' : '').$logmar.')' : ' (NM)' }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" wire:model.defer="state.{{ $odKey }}"
                                                    class="form-control form-control-sm"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                            @endif
                                        </div>

                                        {{-- Left Eye (OS) --}}
                                        <div class="col-md-5">
                                            @if(Str::contains($osKey, 'IOP'))
                                                <input type="number" step="0.1" min="0" max="80"
                                                    wire:model.defer="state.{{ $osKey }}"
                                                    class="form-control form-control-sm @error('state.' . $osKey) is-invalid @enderror"
                                                    placeholder="mmHg"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                            @elseif(Str::startsWith($osKey, 'va'))
                                                <select wire:model.defer="state.{{ $osKey }}"
                                                    class="form-control form-control-sm"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                                    <option value="">— select —</option>
                                                    @foreach(\App\Http\Livewire\Doctor\PatientRecordsComponent::vaLogMarTable($vaNotation) as $label => $logmar)
                                                        <option value="{{ $label }}">{{ $label }}{{ $logmar !== null ? ' ('.($logmar >= 0 ? '+' : '').$logmar.')' : ' (NM)' }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" wire:model.defer="state.{{ $osKey }}"
                                                    class="form-control form-control-sm"
                                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

<div class="card border mb-3">
    <div class="card-header bg-light">
        <h6 class="mb-0 font-weight-bold">Diagnosis & Management</h6>
    </div>
    <div class="card-body">
        <div class="row">

            {{-- LEFT COLUMN: Diagnosis Search, Selected Tags & Clinical Notes --}}
            <div class="col-md-6 border-right">
                {{-- Diagnosis Section --}}
                <div class="mb-3 {{ $consultationFieldsLocked ? 'consultation-section-locked rounded p-2' : '' }}">
                    <label class="font-weight-bold">
                        Final Diagnoses <span class="text-danger">*</span>
                        @if($consultationFieldsLocked)
                            <span class="badge badge-secondary ml-2"><i class="fas fa-lock"></i> Locked</span>
                        @endif
                    </label>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search for a diagnosis..."
                            wire:model.debounce.300ms="diagnosisSearch"
                            {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                    </div>

                    {{-- Search Results Dropdown (Floating) --}}
                    @if(!empty($diagnosisSearch) && !$consultationFieldsLocked)
                        <div class="list-group position-absolute w-100 shadow-lg"
                            style="z-index: 1000; max-height: 200px; overflow-y: auto; left: 15px; width: calc(100% - 30px);">
                            @forelse($this->diagnosisResults as $diag)
                                <button type="button"
                                    wire:click="addDiagnosis({{ $diag->id }}, '{{ addslashes($diag->name) }}')"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                    {{ $diag->name }}
                                    <i class="fas fa-plus-circle text-success"></i>
                                </button>
                            @empty
                                <div class="list-group-item text-muted">No diagnosis found.</div>
                            @endforelse
                        </div>
                    @endif

                    {{-- Selected Diagnoses Tags Area --}}
                    <div class="mt-2 d-flex flex-wrap" style="gap: 5px;">
                        @foreach($selectedDiagnoses as $index => $diag)
                            <span class="badge badge-info p-2 shadow-sm">
                                <i class="fas fa-stethoscope mr-1"></i> {{ $diag['name'] }}
                                <button type="button" wire:click="removeDiagnosis({{ $index }})"
                                    class="btn btn-xs text-white ml-2 p-0" style="line-height: 1;"
                                    {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </span>
                        @endforeach
                    </div>
                    @error('selectedDiagnoses') 
                        <small class="text-danger d-block mt-1">Select at least one diagnosis.</small> 
                    @enderror
                </div>

                {{-- Clinical Notes - Under Diagnosis --}}
                <div class="form-group mb-0 clinical-notes-active">
                    <label class="font-weight-bold">
                        Clinical Notes
                        @if($consultationFieldsLocked)
                            <span class="badge badge-info ml-2"><i class="fas fa-plus-circle"></i> Addendum</span>
                        @endif
                    </label>

                    @if($consultationFieldsLocked)
                        <div class="clinical-original-note mb-3">
                            <div class="clinical-original-note__label">Original note</div>
                            <div class="clinical-original-note__body">
                                {{ filled($consultation->notes ?? null) ? $consultation->notes : 'No original clinical note was recorded.' }}
                            </div>
                        </div>

                        @if($consultation && $consultation->addenda->count() > 0)
                            <div class="clinical-addenda-list mb-3">
                                @foreach($consultation->addenda as $addendum)
                                    <div class="clinical-addendum-item">
                                        <div class="clinical-addendum-item__meta">
                                            <span><i class="fas fa-user-md"></i> {{ $addendum->user->name ?? 'Unknown user' }}</span>
                                            <span>{{ $addendum->created_at->format('d M Y h:i A') }}</span>
                                        </div>
                                        <div class="clinical-addendum-item__note">{{ $addendum->note }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <textarea wire:model.defer="clinicalAddendum"
                            class="form-control clinical-notes-textarea @error('clinicalAddendum') is-invalid @enderror"
                            rows="5"
                            placeholder="Add a signed addendum, management update, or follow-up note..."></textarea>
                        @error('clinicalAddendum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-success">Addenda are stored separately with the author and timestamp. They do not overwrite the original note.</small>
                    @else
                        <textarea wire:model.defer="state.notes" class="form-control clinical-notes-textarea" rows="8"
                            placeholder="Enter additional clinical observations, management plans, or notes..."></textarea>
                        @if($consultation && $consultation->addenda->count() > 0)
                            <div class="clinical-addenda-list mt-3">
                                @foreach($consultation->addenda as $addendum)
                                    <div class="clinical-addendum-item">
                                        <div class="clinical-addendum-item__meta">
                                            <span><i class="fas fa-user-md"></i> {{ $addendum->user->name ?? 'Unknown user' }}</span>
                                            <span>{{ $addendum->created_at->format('d M Y h:i A') }}</span>
                                        </div>
                                        <div class="clinical-addendum-item__note">{{ $addendum->note }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN: Next Visit Date & Appointment Booking --}}
            <div class="col-md-6">
             

                {{-- ⭐ APPOINTMENT BOOKING SECTION - Right Column ⭐ --}}
                <div class="card border mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-calendar-check text-primary"></i> Upcoming Appointment
                        </h6>
                    </div>
                    <div class="card-body py-3">
                        @if($upcomingAppointment)
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $upcomingAppointment->title }}</div>
                                    <div class="small text-muted">
                                        {{ $upcomingAppointment->scheduled_at->format('M d, Y') }} at {{ $upcomingAppointment->scheduled_at->format('h:i A') }}
                                    </div>
                                    @if($upcomingAppointment->notes)
                                        <div class="small text-muted mt-2">{{ $upcomingAppointment->notes }}</div>
                                    @endif
                                </div>
                                <span class="badge badge-info">{{ $upcomingAppointment->status }}</span>
                            </div>
                        @else
                            <div class="text-muted small">No upcoming appointment booked for this patient.</div>
                        @endif
                    </div>
                </div>

                <div class="border-top pt-3 {{ $consultationFieldsLocked ? 'consultation-section-locked rounded p-2' : '' }}">
                    @if($consultationFieldsLocked)
                        <div class="small text-muted mb-2"><i class="fas fa-lock"></i> Follow-up booking is locked for this visit.</div>
                    @endif
                    <div class="mb-2">
                        <button type="button" 
                                wire:click="toggleAppointmentSection" 
                                class="btn btn-sm {{ $showAppointmentSection ? 'btn-warning' : 'btn-outline-primary' }} btn-block font-weight-bold shadow-sm"
                                {{ $consultationFieldsLocked ? 'disabled' : '' }}>
                            <i class="fas {{ $showAppointmentSection ? 'fa-minus-circle' : 'fa-calendar-plus' }}"></i>
                            {{ $showAppointmentSection ? 'Hide Appointment Form' : 'Schedule Follow-up Appointment' }}
                        </button>
                    </div>

                    @if($showAppointmentSection)
                        <div class="border rounded p-3 bg-light" style="animation: slideDown 0.3s ease-out;">
                            @include('components.appointment-booking-form', [
                                'patientLocked' => true,
                                'showQuickBook' => true
                            ])
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

                        <div class="consultation-actions text-right">
                            <button type="button" wire:click="cancelAndGoBack" class="btn btn-secondary px-4">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn {{ $consultationFieldsLocked ? 'btn-success' : 'btn-primary' }} px-5" wire:loading.attr="disabled">
                                <span wire:loading.remove
                                    wire:target="{{ $isEditMode ? 'updateConsultation' : 'createConsultation' }}">
                                    <i class="fas fa-save"></i>
                                    {{ $consultationFieldsLocked ? 'Add Clinical Addendum' : ($isEditMode ? 'Update' : 'Save') . ' Consultation' }}
                                </span>
                                <span wire:loading
                                    wire:target="{{ $isEditMode ? 'updateConsultation' : 'createConsultation' }}">
                                    <i class="fas fa-spinner fa-spin"></i> Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif


   {{-- TAB 3: PRESCRIPTION WITH EYE LATERALITY --}}
@if($activeTab === 'prescription')
    <div class="tab-content-wrapper">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-prescription"></i> Prescription Management
                </h5>
                @if($consultationID)
                    <small class="text-success">
                        <i class="fas fa-check-circle"></i> Linked to Consultation #{{ $consultationID }}
                    </small>
                @else
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Create or select a consultation to link prescription
                    </small>
                @endif
            </div>
            @if(count($productsList) > 0 && $consultationID)
                <button wire:click="clearPrescription" onclick="return confirm('Clear all prescription items?')"
                    class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i> Clear All
                </button>
            @endif
        </div>

        {{-- 2-COLUMN LAYOUT: Search Left, List Right --}}
        <div class="row">
            {{-- LEFT COLUMN: Search Bar ONLY (25%) --}}
            <div class="col-md-3">
                <div class="card border shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-search"></i> Quick Add Product
                        </h6>
                    </div>
                    <div class="card-body">
                        {{-- Product Search --}}
                        <label class="font-weight-bold mb-2">Search Product</label>
                        <div class="position-relative mb-3">
                            <input type="text" wire:model.debounce.300ms="productSearch"
                                class="form-control form-control-lg" placeholder="Type product name..."
                                autocomplete="off">
                            <i class="fas fa-search position-absolute"
                                style="right: 15px; top: 15px; color: #999;"></i>

                            {{-- Search Results --}}
                            @if($productSearch && strlen($productSearch) >= 2)
                                <div class="search-results-dropdown">
                                    @if($searchResults->count() > 0)
                                        <ul class="list-group shadow">
                                            @foreach($searchResults as $product)
                                                <li class="list-group-item list-group-item-action"
                                                    wire:click="selectProduct({{ $product->id }})" style="cursor: pointer;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="flex-grow-1">
                                                            <strong class="d-block">{{ $product->name }}</strong>
                                                            <small class="text-muted">
                                                                <i class="fas fa-warehouse"></i> Stock:
                                                                <strong>{{ $product->quantity }}</strong>
                                                            </small>
                                                        </div>
                                                        <span class="badge badge-primary badge-pill px-3 py-2">
                                                            {{ currency() }} {{ number_format($product->selling_price, 2) }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-barcode"></i> {{ $product->batch_number ?? 'N/A' }}
                                                    </small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="search-no-results">
                                            <p class="text-muted mb-0 text-center py-3">
                                                <i class="fas fa-search"></i> No products found
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- QUICK TIPS --}}
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <strong>Quick Add:</strong><br>
                                1. Search & click product<br>
                                2. Set eye & frequency for drugs<br>
                                3. Save prescription
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Products List with Eye & Frequency (75%) --}}
            <div class="col-md-9">
                <div class="card border shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-list-alt"></i> Prescription Items
                            <span class="badge badge-primary ml-2">{{ count($productsList) }}</span>
                        </h6>

                        @if(count($productsList) > 0)
                            <div>
                                @php
                                    $refundedCount  = collect($productsList)->filter(fn($i) => ($i['status'] ?? null) === 'refunded')->count();
                                    $dispensedCount = collect($productsList)->where('is_dispensed', true)->filter(fn($i) => ($i['status'] ?? null) !== 'refunded')->count();
                                    $heldCount = collect($productsList)->where('purchased', true)->where('is_dispensed', false)->filter(fn($i) => ($i['status'] ?? null) !== 'refunded')->count();
                                    $pendingCount = count($productsList) - $dispensedCount - $heldCount - $refundedCount;
                                    $missingData = collect($productsList)->where('is_dispensed', false)->where('purchased', false)->filter(function($item) {
                                        $categoryName = strtolower(trim($item['category_name'] ?? ''));
                                        $isDrug = ($item['is_drug'] ?? false) || in_array($categoryName, ['drug', 'drugs'], true);
                                        return $isDrug && (empty($item['frequency']) || empty($item['eye']));
                                    })->count();
                                @endphp

                                <span class="badge badge-success px-2 py-1">
                                    <i class="fas fa-check"></i> {{ $dispensedCount }} Dispensed
                                </span>
                                <span class="badge badge-warning px-2 py-1 ml-1">
                                    <i class="fas fa-clock"></i> {{ $pendingCount }} Pending
                                </span>
                                @if($heldCount > 0)
                                    <span class="badge badge-info px-2 py-1 ml-1">
                                        <i class="fas fa-pause-circle"></i> {{ $heldCount }} On Hold
                                    </span>
                                @endif
                                @if($refundedCount > 0)
                                    <span class="badge badge-danger px-2 py-1 ml-1">
                                        <i class="fas fa-undo"></i> {{ $refundedCount }} Refunded
                                    </span>
                                @endif
                                @if($missingData > 0)
                                    <span class="badge badge-danger px-2 py-1 ml-1">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $missingData }} Incomplete
                                    </span>
                                @endif

                                {{-- Refresh button --}}
                                <button wire:click="refreshPrescriptionStatus"
                                    class="btn btn-sm btn-outline-info ml-1" title="Refresh status"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="refreshPrescriptionStatus">
                                        <i class="fas fa-sync-alt"></i>
                                    </span>
                                    <span wire:loading wire:target="refreshPrescriptionStatus">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-0">
                        @if(count($productsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="24%">Product</th>
                                            <th width="7%">Qty</th>
                                            <th width="13%">Eye <small class="text-muted">(Drugs)</small></th>
                                            <th width="20%">Frequency <small class="text-muted">(Drugs)</small></th>
                                            <th width="10%">Price</th>
                                            <th width="10%">Total</th>
                                            <th width="5%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($productsList as $index => $item)
                                            @php
                                                $isDispensed = $item['is_dispensed'] ?? false;
                                                $isPurchased = $item['purchased'] ?? false;
                                                $isRefunded  = $item['is_refunded'] ?? false;
                                                $isLocked    = $isDispensed || $isPurchased || $isRefunded;
                                                $isEditingFreq = $editingFrequencyIndex === $index;
                                                $categoryName = strtolower(trim($item['category_name'] ?? ''));
                                                $isDrug = ($item['is_drug'] ?? false) || in_array($categoryName, ['drug', 'drugs'], true);
                                                $billableLineTotal = $isLocked ? 0 : (float) ($item['total'] ?? 0);
                                            @endphp
                                            <tr class="prescription-row {{ $isLocked ? 'prescription-row--dispensed table-secondary' : '' }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                
                                                {{-- PRODUCT NAME --}}
                                                <td>
                                                    <strong class="{{ $isLocked ? 'text-muted' : '' }}">{{ $item['name'] }}</strong>
                                                    @if($item['batch_number'] ?? false)
                                                        <br><small class="text-muted">Batch: {{ $item['batch_number'] }}</small>
                                                    @endif
                                                    @if($item['category_name'] ?? false)
                                                        <br><small class="badge badge-light border">{{ $item['category_name'] }}</small>
                                                    @endif
                                                    @if($isRefunded)
                                                        <br>
                                                        <small class="badge badge-danger mt-1">
                                                            <i class="fas fa-undo"></i> Refunded
                                                        </small>
                                                    @elseif($isDispensed)
                                                        <br>
                                                        <small class="badge badge-secondary mt-1">
                                                            <i class="fas fa-check-circle"></i> Dispensed
                                                            @if(isset($item['dispensed_at']))
                                                                {{ \Carbon\Carbon::parse($item['dispensed_at'])->format('d M') }}
                                                            @endif
                                                        </small>
                                                    @elseif($isPurchased)
                                                        <br>
                                                        <small class="badge badge-info mt-1">
                                                            <i class="fas fa-pause-circle"></i> On hold
                                                        </small>
                                                    @endif
                                                </td>
                                                
                                                {{-- QUANTITY --}}
                                                <td>
                                                    @if(!$isLocked)
                                                        <input type="number"
                                                            wire:model.lazy="productsList.{{ $index }}.quantity"
                                                            wire:change="updateProductQuantity({{ $index }}, $event.target.value)"
                                                            class="form-control form-control-sm text-center" min="1"
                                                            style="width: 50px;">
                                                    @else
                                                        <span class="badge badge-secondary px-2">
                                                            {{ $item['quantity'] }}
                                                        </span>
                                                    @endif
                                                </td>
                                                
                                                {{-- ⭐ EYE LATERALITY COLUMN --}}
                                                <td>
                                                    @if(!$isDrug)
                                                        <small class="text-muted">Not applicable</small>
                                                    @elseif($isEditingFreq && !$isLocked)
                                                        {{-- EDITING MODE: Show eye dropdown --}}
                                                        <select class="form-control form-control-sm"
                                                                wire:change="updateEye({{ $index }}, $event.target.value)">
                                                            <option value="">-- Eye --</option>
                                                            @foreach(\App\Enums\EyeLaterality::cases() as $eye)
                                                                <option value="{{ $eye->value }}" {{ ($item['eye'] ?? '') === $eye->value ? 'selected' : '' }}>
                                                                    {{ $eye->label() }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        {{-- DISPLAY MODE: Show eye badge --}}
                                                        @if($item['eye'] ?? false)
                                                            @php
                                                                $eyeEnum = \App\Enums\EyeLaterality::tryFrom($item['eye']);
                                                            @endphp
                                                            @if($eyeEnum)
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div>
                                                                        <span class="badge {{ $eyeEnum->badgeClass() }} px-2 py-1">
                                                                            {{ $eyeEnum->abbreviation() }}
                                                                        </span>
                                                                        <small class="d-block text-muted">{{ $eyeEnum->label() }}</small>
                                                                    </div>
                                                                    @if(!$isLocked)
                                                                        <button type="button" 
                                                                                wire:click="editFrequency({{ $index }})"
                                                                                class="btn btn-xs btn-outline-primary">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <small class="text-muted">{{ $item['eye'] }}</small>
                                                            @endif
                                                        @else
                                                            {{-- No eye set --}}
                                                            @if(!$isLocked)
                                                                <button type="button" 
                                                                        wire:click="editFrequency({{ $index }})"
                                                                        class="btn btn-xs btn-warning w-100">
                                                                    <i class="fas fa-eye"></i> Set
                                                                </button>
                                                            @else
                                                                <small class="text-muted fst-italic">N/A</small>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                
                                                {{-- ⭐ FREQUENCY COLUMN --}}
                                                <td>
                                                    @if(!$isDrug)
                                                        <small class="text-muted">Not applicable</small>
                                                    @elseif($isEditingFreq && !$isLocked)
                                                        {{-- EDITING MODE: Show frequency dropdown --}}
                                                        <div class="d-flex align-items-center">
                                                            <select class="form-control form-control-sm mr-1"
                                                                    wire:change="updateFrequency({{ $index }}, $event.target.value)"
                                                                    style="width: 100%;">
                                                                <option value="">-- Select --</option>
                                                                @foreach(\App\Enums\ProductFrequency::options() as $value => $label)
                                                                    <option value="{{ $value }}" {{ ($item['frequency'] ?? '') === $value ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button type="button" 
                                                                    wire:click="cancelFrequencyEdit"
                                                                    class="btn btn-xs btn-outline-secondary">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        {{-- DISPLAY MODE: Show frequency or "Not set" button --}}
                                                        @if($item['frequency'] ?? false)
                                                            @php
                                                                $freqEnum = \App\Enums\ProductFrequency::tryFrom($item['frequency']);
                                                            @endphp
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    @if($freqEnum)
                                                                        <span class="badge {{ $freqEnum->badgeClass() }} px-2">
                                                                            {{ $freqEnum->abbreviation() }}
                                                                        </span>
                                                                        <small class="d-block text-muted">{{ $item['frequency'] }}</small>
                                                                    @else
                                                                        <small class="text-muted">{{ $item['frequency'] }}</small>
                                                                    @endif
                                                                </div>
                                                                @if(!$isLocked)
                                                                    <button type="button" 
                                                                            wire:click="editFrequency({{ $index }})"
                                                                            class="btn btn-xs btn-outline-primary">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @else
                                                            {{-- No frequency set --}}
                                                            @if(!$isLocked)
                                                                <button type="button" 
                                                                        wire:click="editFrequency({{ $index }})"
                                                                        class="btn btn-sm btn-warning w-100">
                                                                    <i class="fas fa-plus-circle"></i> Set
                                                                </button>
                                                            @else
                                                                <small class="text-muted fst-italic">Not set</small>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                
                                                <td><small>{{ currency() }} {{ number_format($item['price'], 2) }}</small></td>
                                                <td>
                                                    <strong class="{{ $isLocked ? 'text-muted' : '' }}">{{ currency() }} {{ number_format($billableLineTotal, 2) }}</strong>
                                                    @if($isLocked && (float) ($item['total'] ?? 0) > 0)
                                                        <small class="d-block text-muted">{{ $isRefunded ? 'refunded' : 'already dispensed' }}</small>
                                                    @endif
                                                </td>
                                                
                                                {{-- ACTION --}}
                                                <td class="text-center">
                                                    @if(!$isLocked)
                                                        <button wire:click="removeProduct({{ $index }})"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Remove?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <i class="fas fa-lock text-muted" title="{{ $isRefunded ? 'Refunded' : ($isDispensed ? 'Dispensed' : 'On hold') }}"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th colspan="6" class="text-right">Grand Total:</th>
                                            <th>
                                                <h5 class="mb-0 text-success">
                                                    {{ currency() }} {{ number_format($this->calculateTotal(), 2) }}
                                                </h5>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-prescription-bottle fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No prescription items yet</h5>
                                <p class="text-muted">Search for products on the left and click to add</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if(count($productsList) > 0)
                    <div class="mt-3 d-flex justify-content-between">
                        <button wire:click="clearPrescription" onclick="return confirm('Clear all items?')"
                            class="btn btn-outline-danger btn-lg">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button wire:click="savePrescription" class="btn btn-primary btn-lg px-5"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="savePrescription">
                                <i class="fas fa-save"></i> Save Prescription
                            </span>
                            <span wire:loading wire:target="savePrescription">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif


            {{-- TAB 4: REFRACTION --}}
            @if($activeTab === 'refraction')
                <div class="tab-content-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="fas fa-glasses"></i> Refraction Details
                        </h5>
                        <button wire:click="cancelAndGoBack" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                    </div>

                    @if(!$consultationID)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Tip:</strong> Select a consultation from history to load or save refraction data.
                        </div>
                    @endif

                    <form wire:submit.prevent="saveRefraction">
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 font-weight-bold">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="font-weight-bold">P.D (mm) <span class="text-danger">*</span></label>
                                        <input type="number" wire:model.defer="state.pd"
                                            class="form-control @error('state.pd') is-invalid @enderror" step="0.1">
                                        @error('state.pd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Lens Type & Coating *</label>
                                        <select wire:model="state.lensType" class="form-control">
                                            <option value="">Select Type...</option>

                                            <optgroup label="Single Vision (SV)">
                                                <option value="SV Clear">SV Clear (Standard)</option>
                                                <option value="SV Hard Coat">SV Hard Coat (Scratch Resistant)</option>
                                                <option value="SV AR">SV with Anti-Reflective (AR)</option>
                                                <option value="SV Photo AR">SV Photochromic + AR (Transitions)</option>
                                                <option value="SV Blue AR">SV Blue Anti-Reflective</option>
                                                <option value="SV Blue Block">SV Blue Block (UV420/No Glare)</option>
                                                <option value="SV Blue Block Photo">SV Blue Block + Photochromic</option>
                                                <option value="SV Special Order">SV Special Order</option>
                                            </optgroup>

                                            <optgroup label="Bifocal">
                                                <option value="Bifocal Clear">Bifocal Clear</option>
                                                <option value="Bifocal AR">Bifocal with AR</option>
                                                <option value="Bifocal Photo AR">Bifocal Photochromic + AR</option>
                                                <option value="Bifocal Blue Block">Bifocal Blue Block</option>
                                                <option value="Special Order Bifocal">Special Order Bifocal</option>
                                            </optgroup>

                                            <optgroup label="Progressive">
                                                <option value="Progressive Clear">Progressive Clear</option>
                                                <option value="Progressive AR">Progressive with AR</option>
                                                <option value="Progressive Photo AR">Progressive Photochromic + AR</option>
                                                <option value="Progressive Blue Block">Progressive Blue Block</option>
                                                <option value="Progressive Blue Block Photo">Progressive Blue Block + Photo</option>
                                                <option value="Special Order Progressive">Special Order Progressive</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    @if(count($lensProducts) > 0)
                                        <div class="col-md-6">
                                            <label>Available Lenses & Price</label>
                                            <select class="form-control" wire:change="selectLensProduct($event.target.value)">
                                                <option value="">Choose a lens to add to prescription...</option>
                                                @foreach($lensProducts as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name }} - {{ currency() }} {{ number_format($product->selling_price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 font-weight-bold">Refraction Measurements</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th width="80">Eye</th>
                                                <th>Rx</th>
                                                <th width="150">Distance VA</th>
                                                <th width="120">ADD</th>
                                                <th width="150">Near VA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold text-center">OD</td>
                                                <td>
                                                    <input type="text" wire:model.defer="state.refractionOD"
                                                        class="form-control">
                                                </td>
                                                <td>
                                                    @php $vaOpts = \App\Http\Livewire\Doctor\PatientRecordsComponent::vaLogMarTable(); @endphp
                                                    <select wire:model.defer="state.refractionOD_distance_va" class="form-control form-control-sm">
                                                        <option value="">—</option>
                                                        @foreach($vaOpts as $lbl => $lm)
                                                            <option value="{{ $lbl }}">{{ $lbl }}{{ $lm !== null ? ' ('.($lm >= 0 ? '+' : '').$lm.')' : ' (NM)' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" wire:model.defer="state.refractionOD_ADD"
                                                        class="form-control form-control-sm" placeholder="+2.50">
                                                </td>
                                                <td>
                                                    <select wire:model.defer="state.refractionOD_near_va" class="form-control form-control-sm">
                                                        <option value="">—</option>
                                                        @foreach($vaOpts as $lbl => $lm)
                                                            <option value="{{ $lbl }}">{{ $lbl }}{{ $lm !== null ? ' ('.($lm >= 0 ? '+' : '').$lm.')' : ' (NM)' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-center">OS</td>
                                                <td>
                                                    <input type="text" wire:model.defer="state.refractionOS"
                                                        class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <select wire:model.defer="state.refractionOS_distance_va" class="form-control form-control-sm">
                                                        <option value="">—</option>
                                                        @foreach($vaOpts as $lbl => $lm)
                                                            <option value="{{ $lbl }}">{{ $lbl }}{{ $lm !== null ? ' ('.($lm >= 0 ? '+' : '').$lm.')' : ' (NM)' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" wire:model.defer="state.refractionOS_ADD"
                                                        class="form-control form-control-sm" placeholder="+2.50">
                                                </td>
                                                <td>
                                                    <select wire:model.defer="state.refractionOS_near_va" class="form-control form-control-sm">
                                                        <option value="">—</option>
                                                        @foreach($vaOpts as $lbl => $lm)
                                                            <option value="{{ $lbl }}">{{ $lbl }}{{ $lm !== null ? ' ('.($lm >= 0 ? '+' : '').$lm.')' : ' (NM)' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <label class="font-weight-bold">Notes</label>
                                    <textarea wire:model.defer="state.refractionnotes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" wire:click="cancelAndGoBack" class="btn btn-secondary px-4">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            @if($consultationID)
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save"></i> Save Refraction
                                </button>
                            @else
                                <button type="button" disabled class="btn btn-secondary px-5" title="Select a consultation first">
                                    <i class="fas fa-save"></i> Save Refraction
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            {{-- TAB 5: BILLS --}}
            @if($activeTab === 'bills')
                <div class="tab-content-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1 text-primary font-weight-bold">
                                <i class="fas fa-file-medical"></i> Clinical Documents
                            </h5>
                            <small class="text-muted">Upload and review fundus photos, OCT, visual fields, referral letters, and reports.</small>
                        </div>
                        <span class="badge badge-secondary px-3 py-2">{{ $patientDocuments->count() }} files</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-5 mb-3">
                            <div class="card border h-100">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-paperclip text-primary"></i> Attach Clinical Document 
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="uploadPatientDocument">
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold small">Document Type</label>
                                                <select wire:model="documentType" class="form-control form-control-sm">
                                                    <option value="fundus_photo">Fundus Photo</option>
                                                    <option value="oct">OCT</option>
                                                    <option value="visual_field">Visual Field</option>
                                                    <option value="referral_letter">Referral Letter</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                @error('documentType') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold small">Visit</label>
                                                <select wire:model="documentConsultationId" class="form-control form-control-sm">
                                                    <option value="">General patient file</option>
                                                    @foreach($patientRecords as $record)
                                                        <option value="{{ $record->id }}">{{ $record->created_at->format('d M Y') }}</option>
                                                    @endforeach
                                                </select>
                                                @error('documentConsultationId') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="font-weight-bold small">Title</label>
                                            <input type="text" wire:model.defer="documentTitle" class="form-control form-control-sm"
                                                placeholder="e.g. Left eye OCT macula">
                                            @error('documentTitle') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="font-weight-bold small">File</label>
                                            <input type="file"
                                                wire:model="documentFiles"
                                                wire:key="patient-document-upload-{{ $documentUploadKey }}"
                                                class="form-control form-control-sm"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                multiple>
                                            <small class="text-muted">Select one or more JPG, PNG, PDF, DOC, DOCX files. Max 10MB each, 10 files per upload.</small>
                                            @error('documentFiles') <div><small class="text-danger">{{ $message }}</small></div> @enderror
                                            @error('documentFiles.*') <div><small class="text-danger">{{ $message }}</small></div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="font-weight-bold small">Notes</label>
                                            <textarea wire:model.defer="documentNotes" class="form-control form-control-sm" rows="2"></textarea>
                                            @error('documentNotes') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm"
                                            wire:loading.attr="disabled" wire:target="uploadPatientDocument,documentFiles">
                                            <span wire:loading.remove wire:target="uploadPatientDocument,documentFiles">
                                                <i class="fas fa-upload"></i> Upload
                                            </span>
                                            <span wire:loading wire:target="uploadPatientDocument,documentFiles">
                                                <i class="fas fa-spinner fa-spin"></i> Uploading
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 mb-3">
                            <div class="card border h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-folder-open text-success"></i> Attached Images & Documents
                                    </h6>
                                    <span class="badge badge-secondary">{{ $patientDocuments->count() }}</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Title</th>
                                                    <th>Visit</th>
                                                    <th>Uploaded</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($patientDocuments as $document)
                                                    <tr>
                                                        <td><span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</span></td>
                                                        <td>
                                                            <a href="{{ $document->url }}" target="_blank" class="font-weight-bold">
                                                                {{ $document->title }}
                                                            </a>
                                                            <small class="d-block text-muted">{{ $document->original_name }}</small>
                                                        </td>
                                                        <td>
                                                            {{ optional($document->consultation)->created_at ? $document->consultation->created_at->format('d M Y') : 'General' }}
                                                        </td>
                                                        <td>
                                                            <small>{{ $document->created_at->format('d M Y') }}</small>
                                                            <small class="d-block text-muted">{{ $document->uploadedBy->name ?? 'System' }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ $document->url }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(auth()->user()->hasRole('Super Admin'))
                                                                <button wire:click="deletePatientDocument({{ $document->id }})"
                                                                    onclick="return confirm('Delete this document?')"
                                                                    class="btn btn-xs btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-xs btn-outline-secondary" disabled
                                                                    title="Only Super Admin can delete uploads">
                                                                    <i class="fas fa-lock"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-4">
                                                            No attached documents yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    function initOdqSelect() {
        var $select = $('#patient-records-odq');
        if (!$select.length || typeof $.fn.select2 === 'undefined') return;

        // Destroy first so a replaced element (parent re-rendered) always gets
        // a fresh Select2 instance instead of showing as a plain listbox.
        if ($select.data('select2')) {
            $select.select2('destroy');
        }

        $select.select2({
            tags: true,
            width: '100%',
            theme: 'classic',
            tokenSeparators: [','],
            placeholder: $select.data('placeholder') || 'Select or type ODQ options'
        });

        // Use .off first so re-initialisation never stacks duplicate handlers.
        $select.off('change.patientOdq').on('change.patientOdq', function () {
            @this.set('state.odq', $(this).val() || []);
        });
    }

    function syncOdqSelect(values) {
        var $select = $('#patient-records-odq');
        if (!$select.length) return;

        initOdqSelect();

        values.forEach(function (value) {
            if (!$select.find('option[value="' + CSS.escape(value) + '"]').length) {
                $select.append(new Option(value, value, false, false));
            }
        });

        $select.val(values).trigger('change.select2');
    }

    document.addEventListener('livewire:load', function () {
        initOdqSelect();

        // Re-apply after every Livewire component update (handles parent
        // container being re-rendered which bypasses wire:ignore).
        Livewire.hook('message.processed', function () {
            setTimeout(initOdqSelect, 60);
        });

        window.addEventListener('init-odq-select', function () {
            setTimeout(initOdqSelect, 60);
        });

        window.addEventListener('sync-odq-select', function (e) {
            setTimeout(function () { syncOdqSelect(e.detail.values || []); }, 60);
        });
    });
}());
</script>

{{-- Modern EMR Design System --}}
<style>
    /* ── Root ─────────────────────────────────────────── */
    .emr-root { background: #F8FAFC; }

    /* ── Patient Header ───────────────────────────────── */
    .px-header {
        background: #fff;
        border-bottom: 1px solid #E2E8F0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .px-header__inner {
        display: flex;
        align-items: center;
        padding: 11px 22px;
        gap: 18px;
        min-height: 62px;
    }
    .px-avatar {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2563EB 0%, #60A5FA 100%);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .px-header__identity { display: flex; align-items: center; gap: 11px; flex-shrink: 0; }
    .px-header__name { font-size: 15px; font-weight: 700; color: #0F172A; line-height: 1.2; letter-spacing: -0.2px; }
    .px-header__meta { display: flex; align-items: center; gap: 5px; margin-top: 4px; flex-wrap: wrap; }
    .px-id { font-size: 11px; color: #94A3B8; font-family: 'Courier New', monospace; }
    .px-divider { color: #CBD5E1; font-size: 11px; }
    .px-badge {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 10px; font-weight: 600;
        padding: 2px 7px; border-radius: 99px;
        background: #F1F5F9; color: #475569;
    }
    .px-badge--gender  { background: #EFF6FF; color: #2563EB; }
    .px-badge--gender-f { background: #FDF2F8; color: #DB2777; }
    .px-badge--age     { background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; }
    .px-badge--ready   { background: #F0FDF4; color: #16A34A; }
    .px-badge--used    { background: #F1F5F9; color: #64748B; }
    .px-badge--warn    { background: #FFFBEB; color: #D97706; }
    .px-badge--danger  { background: #FEF2F2; color: #DC2626; }

    .px-header__demographics {
        display: flex; align-items: center; flex: 1;
        padding: 0 14px;
        border-left: 1px solid #E2E8F0;
        border-right: 1px solid #E2E8F0;
        margin: 0 2px;
        overflow: hidden;
    }
    .px-demo-item { display: flex; flex-direction: column; padding: 0 14px; white-space: nowrap; }
    .px-demo-sep  { width: 1px; height: 26px; background: #E2E8F0; flex-shrink: 0; }
    .px-demo-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; }
    .px-demo-value { font-size: 13px; font-weight: 500; color: #334155; margin-top: 1px; }
    .px-demo-value--highlight { color: #2563EB; font-size: 16px; font-weight: 700; }

    .px-header__actions { display: flex; align-items: center; gap: 7px; flex-shrink: 0; }

    /* ── Buttons ──────────────────────────────────────── */
    .px-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 13px; border-radius: 8px;
        font-size: 12px; font-weight: 600;
        border: none; cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap; line-height: 1.4;
    }
    .px-btn--primary { background: #2563EB; color: #fff; }
    .px-btn--primary:hover { background: #1D4ED8; color: #fff; text-decoration: none; }
    .px-btn--ghost { background: #fff; color: #475569; border: 1px solid #E2E8F0; }
    .px-btn--ghost:hover { background: #F8FAFC; color: #334155; border-color: #CBD5E1; text-decoration: none; }
    .px-btn--icon { background: #F1F5F9; color: #475569; padding: 7px 10px; border: 1px solid #E2E8F0; }
    .px-btn--icon:hover { background: #E2E8F0; color: #334155; text-decoration: none; }
    .px-btn.disabled, .px-btn[disabled] { opacity: 0.42; cursor: not-allowed; pointer-events: none; }

    /* ── Tab Bar ──────────────────────────────────────── */
    .emr-main-card { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .emr-tab-bar {
        display: flex; align-items: flex-end;
        padding: 0 20px;
        border-bottom: 1px solid #E2E8F0;
        background: #fff;
        overflow-x: auto; gap: 1px;
    }
    .emr-tab {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 11px 14px 12px;
        font-size: 12px; font-weight: 500;
        color: #64748B;
        border: none; background: transparent;
        cursor: pointer;
        transition: color 0.15s, background 0.15s;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        white-space: nowrap;
    }
    .emr-tab:hover { color: #2563EB; background: #F8FAFC; }
    .emr-tab--active { color: #2563EB; font-weight: 600; border-bottom-color: #2563EB; }
    .emr-tab-badge {
        font-size: 10px; font-weight: 600;
        padding: 1px 6px; border-radius: 99px;
        background: #F1F5F9; color: #64748B;
    }
    .emr-tab-badge--red { background: #FEE2E2; color: #DC2626; }
    .emr-tab-dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #22C55E;
        margin-left: 2px;
        vertical-align: middle;
    }
    .emr-tab-content { background: #F8FAFC; padding: 22px; }

    /* ── History Tab ──────────────────────────────────── */
    .history-header {
        display: flex; align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .history-header__title {
        display: flex; align-items: center; gap: 8px;
        font-size: 14px; font-weight: 700; color: #0F172A;
    }
    .search-box { position: relative; width: 270px; }
    .search-box__icon {
        position: absolute; left: 11px; top: 50%;
        transform: translateY(-50%);
        color: #94A3B8; font-size: 12px; pointer-events: none;
    }
    .search-box__input {
        width: 100%; padding: 8px 12px 8px 32px;
        border: 1px solid #E2E8F0; border-radius: 9px;
        font-size: 13px; color: #334155; background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .search-box__input:focus {
        outline: none; border-color: #93C5FD;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    }
    .search-box__input::placeholder { color: #94A3B8; }

    /* ── Metric Cards ─────────────────────────────────── */
    .metrics-row {
        display: grid;
        grid-template-columns: 1fr 290px;
        gap: 14px; margin-bottom: 14px;
    }
    .metrics-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .mb-section { margin-bottom: 14px; }
    .metrics-card__header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; border-bottom: 1px solid #F1F5F9;
    }
    .metrics-card__title { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: #0F172A; }
    .metrics-card__subtitle { font-size: 11px; color: #94A3B8; }
    .metrics-card__body { padding: 14px 16px; }
    .metrics-card__body--chart { padding: 10px 14px 12px; height: 215px; position: relative; }
    .metrics-card__body--chart canvas { width: 100% !important; height: 100% !important; }
    .metrics-card__body--chart-sm { padding: 10px 14px 12px; height: 148px; position: relative; }
    .metrics-card__body--chart-sm canvas { width: 100% !important; height: 100% !important; }
    .chart-empty {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        height: 100%; gap: 8px;
        color: #CBD5E1; font-size: 13px;
    }
    .chart-empty i { font-size: 26px; }

    /* ── KPI Grid ─────────────────────────────────────── */
    .kpi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
    .kpi-item {
        background: #F8FAFC; border: 1px solid #F1F5F9;
        border-radius: 10px; padding: 11px 12px; text-align: center;
    }
    .kpi-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; margin-bottom: 5px; }
    .kpi-value { font-size: 20px; font-weight: 700; color: #0F172A; line-height: 1; letter-spacing: -0.5px; }
    .kpi-value--safe   { color: #16A34A; }
    .kpi-value--warn   { color: #D97706; }
    .kpi-value--danger { color: #DC2626; }
    .kpi-alert {
        background: #FFFBEB; border: 1px solid #FDE68A;
        border-radius: 8px; color: #92400E;
        font-size: 11px; font-weight: 500;
        padding: 7px 10px;
        display: flex; align-items: center; gap: 5px;
    }

    /* ── Section Cards ────────────────────────────────── */
    .section-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .section-card__header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; border-bottom: 1px solid #F1F5F9;
    }
    .section-card__title { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: #0F172A; }
    .section-card__subtitle { font-size: 11px; color: #94A3B8; }
    .section-card__actions { display: flex; align-items: center; gap: 7px; }
    .section-card__body { padding: 16px; }
    .section-badge {
        font-size: 10px; font-weight: 600;
        padding: 1px 7px; border-radius: 99px;
        background: #E2E8F0; color: #475569; margin-left: 2px;
    }

    /* ── Risk Flags ───────────────────────────────────── */
    .risk-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
    .risk-card { border-radius: 12px; border: 1px solid #E2E8F0; border-left-width: 4px; padding: 14px 15px; }
    .risk-card--low     { border-left-color: #22C55E; background: #F0FDF4; }
    .risk-card--warning { border-left-color: #F59E0B; background: #FFFBEB; }
    .risk-card--urgent  { border-left-color: #EF4444; background: #FEF2F2; }
    .risk-card__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 9px; }
    .risk-card__name { font-size: 13px; font-weight: 600; color: #0F172A; display: flex; align-items: center; gap: 5px; }
    .risk-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px; }
    .risk-badge--clear  { background: #DCFCE7; color: #16A34A; }
    .risk-badge--review { background: #FEF3C7; color: #B45309; }
    .risk-badge--urgent { background: #FEE2E2; color: #DC2626; }
    .risk-card__reasons { margin: 0; padding-left: 15px; font-size: 12px; color: #475569; line-height: 1.5; }
    .risk-card__empty   { font-size: 12px; color: #94A3B8; margin: 0; line-height: 1.5; }

    /* ── Modern Table ─────────────────────────────────── */
    .modern-table-wrap { overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .modern-table thead tr { background: #F8FAFC; border-bottom: 2px solid #E2E8F0; }
    .modern-table thead th {
        padding: 9px 13px; text-align: left;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em; color: #64748B;
    }
    .modern-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.1s; }
    .modern-table tbody tr:hover { background: #F8FAFC; }
    .modern-table tbody tr:last-child { border-bottom: none; }
    .modern-table tbody td { padding: 11px 13px; color: #334155; vertical-align: middle; }
    .modern-checkbox { cursor: pointer; width: 14px; height: 14px; accent-color: #2563EB; }
    .visit-date  { font-weight: 600; color: #0F172A; font-size: 13px; }
    .visit-time  { font-size: 11px; color: #94A3B8; margin-top: 2px; }
    .visit-complaint { color: #475569; }
    .visit-doctor    { font-size: 12px; color: #64748B; }
    .diag-chip {
        display: inline-block;
        background: #EFF6FF; color: #1D4ED8;
        font-size: 11px; font-weight: 500;
        padding: 2px 8px; border-radius: 99px;
        margin: 2px 2px 2px 0;
        border: 1px solid #BFDBFE;
    }
    .text-muted-sm { font-size: 12px; color: #94A3B8; font-style: italic; }
    .table-empty { text-align: center; padding: 48px 0; color: #CBD5E1; }
    .table-empty i   { display: block; font-size: 30px; margin-bottom: 10px; }
    .table-empty div { font-size: 14px; font-weight: 500; color: #94A3B8; margin-bottom: 5px; }
    .table-empty small { font-size: 12px; color: #CBD5E1; }
    .pagination-wrap { padding: 12px 16px; border-top: 1px solid #F1F5F9; }

    /* ── Action Buttons ───────────────────────────────── */
    .action-group { display: flex; gap: 4px; }
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 7px;
        font-size: 11px;
        border: 1px solid #E2E8F0; background: #fff; color: #64748B;
        cursor: pointer; text-decoration: none;
        transition: all 0.15s;
    }
    .action-btn:hover         { background: #EFF6FF; color: #2563EB; border-color: #BFDBFE; text-decoration: none; }
    .action-btn--green:hover  { background: #F0FDF4; color: #16A34A; border-color: #BBF7D0; }
    .action-btn--slate:hover  { background: #F1F5F9; color: #334155; border-color: #CBD5E1; }
    .action-btn--amber:hover  { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }

    /* ── Audit Timeline ───────────────────────────────── */
    .audit-timeline { padding: 4px 0; }
    .audit-item { display: flex; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #F8FAFC; align-items: flex-start; }
    .audit-item:last-child { border-bottom: none; }
    .audit-icon {
        width: 28px; height: 28px; border-radius: 50%;
        background: #F1F5F9; color: #64748B;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; flex-shrink: 0; margin-top: 1px;
    }
    .audit-body { flex: 1; min-width: 0; }
    .audit-meta { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; margin-bottom: 3px; }
    .audit-user  { font-size: 12px; font-weight: 600; color: #0F172A; }
    .audit-event {
        font-size: 9px; font-weight: 700;
        padding: 1px 6px; border-radius: 4px;
        background: #E2E8F0; color: #475569;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .audit-time  { font-size: 11px; color: #94A3B8; margin-left: auto; }
    .audit-desc  { font-size: 12px; color: #64748B; line-height: 1.4; }
    .audit-empty { padding: 20px 16px; text-align: center; font-size: 13px; color: #94A3B8; }

    /* ── Search Dropdown ──────────────────────────────── */
    .search-results-dropdown {
        position: absolute; top: 100%; left: 0; right: 0;
        z-index: 1050; max-height: 350px; overflow-y: auto;
        background: white;
        border: 1px solid #E2E8F0; border-radius: 10px;
        margin-top: 4px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    }
    .search-results-dropdown .list-group-item {
        border-left: none; border-right: none; border-color: #F1F5F9;
        padding: 10px 14px; transition: background-color 0.15s;
        cursor: pointer; font-size: 13px;
    }
    .search-results-dropdown .list-group-item:hover { background-color: #F8FAFC; }
    .search-no-results {
        padding: 16px; text-align: center;
        background: white; border: 1px solid #E2E8F0;
        border-radius: 10px; margin-top: 4px;
        font-size: 13px; color: #94A3B8;
    }
    .search-results-dropdown::-webkit-scrollbar { width: 6px; }
    .search-results-dropdown::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }

    /* ── Legacy: Consultation/Prescription Tabs ───────── */
    .clinical-chart-body { height: 180px; min-height: 180px; max-height: 180px; overflow: hidden; position: relative; }
    .clinical-chart-body--short { height: 145px; min-height: 145px; max-height: 145px; position: relative; }
    .clinical-chart-body canvas { display: block; height: 100% !important; max-height: 100% !important; width: 100% !important; }
    .urgent-referral-panel {
        background: #fff1f2; border: 1px solid #f3a6ad;
        border-left: 4px solid #dc3545; border-radius: 8px;
        color: #5f1018; padding: 12px 14px;
    }
    .consultation-titlebar { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; }
    .consultation-lock-panel {
        background: #fff8e1; border: 1px solid #f3d88b;
        border-left: 4px solid #f0ad4e; border-radius: 8px;
        color: #574111; padding: 12px 14px;
    }
    .consultation-lock-icon {
        align-items: center; background: #f0ad4e; border-radius: 50%;
        color: #fff; display: inline-flex;
        height: 34px; justify-content: center;
        margin-right: 12px; min-width: 34px; width: 34px;
    }
    .consultation-section-locked { background: #f8f9fa; border-color: #d8dee6 !important; color: #6c757d; }
    .consultation-section-locked .card-header { background: #eef1f4 !important; }
    .consultation-section-locked input:disabled,
    .consultation-section-locked select:disabled,
    .consultation-section-locked textarea:disabled { background: #eef1f4; border-color: #d4d9df; color: #6c757d; cursor: not-allowed; }
    .clinical-notes-active { background: #f5fff7; border: 1px solid #b7e4c7; border-radius: 8px; padding: 12px; }
    .clinical-notes-textarea { border-color: #8fd19e; }
    .clinical-original-note { background: #ffffff; border: 1px solid #d7eadc; border-radius: 6px; padding: 10px 12px; }
    .clinical-original-note__label { color: #64748b; font-size: 11px; font-weight: 700; letter-spacing: .02em; text-transform: uppercase; }
    .clinical-original-note__body { color: #0f172a; font-size: 13px; margin-top: 4px; white-space: pre-line; }
    .clinical-addenda-list { display: grid; gap: 8px; max-height: 220px; overflow-y: auto; }
    .clinical-addendum-item { background: #fff; border-left: 3px solid #16a34a; border-radius: 6px; box-shadow: 0 1px 2px rgba(15, 23, 42, .06); padding: 9px 11px; }
    .clinical-addendum-item__meta { color: #64748b; display: flex; flex-wrap: wrap; font-size: 11px; font-weight: 600; gap: 8px; justify-content: space-between; margin-bottom: 5px; }
    .clinical-addendum-item__note { color: #0f172a; font-size: 13px; white-space: pre-line; }
    .consultation-actions {
        background: #fff; border-top: 1px solid #e9ecef;
        margin-top: 16px; padding-top: 14px;
        position: sticky; bottom: 0; z-index: 5;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .prescription-row--dispensed { background: #eef0f2 !important; color: #6c757d; opacity: .72; }
    .prescription-row--dispensed td { border-color: #d6d8db; }
    .prescription-row--dispensed input,
    .prescription-row--dispensed select,
    .prescription-row--dispensed button { pointer-events: none; }

</style>

<script>
        let patientVaTrendChart = null;
        let patientIopTrendChart = null;

        function getPatientClinicalTrendData() {
            const dataElement = document.getElementById('clinical-trend-data');

            if (!dataElement) {
                return null;
            }

            try {
                return JSON.parse(dataElement.dataset.trends || '{}');
            } catch (error) {
                return null;
            }
        }

        function renderPatientClinicalTrendCharts() {
            if (typeof Chart === 'undefined') {
                return;
            }

            const trendData = getPatientClinicalTrendData();

            if (!trendData || !trendData.labels || trendData.labels.length === 0) {
                return;
            }

            const vaCanvas = document.getElementById('visualAcuityTrendChart');
            const iopCanvas = document.getElementById('iopTrendChart');

            if (patientVaTrendChart) {
                patientVaTrendChart.destroy();
                patientVaTrendChart = null;
            }

            if (patientIopTrendChart) {
                patientIopTrendChart.destroy();
                patientIopTrendChart = null;
            }

            if (vaCanvas) {
                patientVaTrendChart = new Chart(vaCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: trendData.labels,
                        datasets: [
                            {
                                label: 'OD',
                                data: trendData.va.od,
                                borderColor: '#2563EB',
                                backgroundColor: 'rgba(37, 99, 235, 0.07)',
                                pointBackgroundColor: '#2563EB',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: false,
                                tension: 0.3,
                                spanGaps: true
                            },
                            {
                                label: 'OS',
                                data: trendData.va.os,
                                borderColor: '#0EA5E9',
                                backgroundColor: 'rgba(14, 165, 233, 0.07)',
                                pointBackgroundColor: '#0EA5E9',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: false,
                                tension: 0.3,
                                spanGaps: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11, family: '-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif' }, boxWidth: 12, padding: 14 }
                            },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                titleColor: '#F1F5F9',
                                bodyColor: '#CBD5E1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 10,
                                callbacks: {
                                    label: function (context) {
                                        const datasetLabel = context.dataset.label;
                                        const rawValues = datasetLabel === 'OD' ? trendData.va.odRaw : trendData.va.osRaw;
                                        const rawValue = rawValues[context.dataIndex] || 'N/A';
                                        return datasetLabel + ': ' + rawValue + ' (' + context.parsed.y + ')';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: '#F1F5F9' },
                                ticks: { font: { size: 10 }, color: '#94A3B8' }
                            },
                            y: {
                                reverse: true,
                                min: -0.40,
                                max: 3.00,
                                grid: { color: '#F1F5F9' },
                                ticks: {
                                    stepSize: 0.30,
                                    font: { size: 10 },
                                    color: '#94A3B8',
                                    callback: function(val) {
                                        const map = {
                                            '-0.3': '6/3', '0': '6/6', '0.3': '6/12',
                                            '0.6': '6/24', '1': '6/60', '1.7': 'CF',
                                            '2.3': 'HM', '2.7': 'LP'
                                        };
                                        var key = parseFloat(val).toFixed(1).replace(/\.0$/, '');
                                        return map[key] !== undefined ? map[key] + ' (' + val + ')' : val;
                                    }
                                },
                                title: { display: true, text: 'LogMAR  (↑ worse  ↓ better)', font: { size: 10 }, color: '#94A3B8' }
                            }
                        }
                    }
                });
            }

            if (iopCanvas) {
                patientIopTrendChart = new Chart(iopCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: trendData.labels,
                        datasets: [
                            {
                                label: 'OD',
                                data: trendData.iop.od,
                                borderColor: '#EF4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.07)',
                                pointBackgroundColor: '#EF4444',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: false,
                                tension: 0.3,
                                spanGaps: true
                            },
                            {
                                label: 'OS',
                                data: trendData.iop.os,
                                borderColor: '#22C55E',
                                backgroundColor: 'rgba(34, 197, 94, 0.07)',
                                pointBackgroundColor: '#22C55E',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: false,
                                tension: 0.3,
                                spanGaps: true
                            },
                            {
                                label: 'IOP 21',
                                data: trendData.labels.map(function () { return 21; }),
                                borderColor: '#F59E0B',
                                borderDash: [6, 4],
                                pointRadius: 0,
                                fill: false,
                                tension: 0
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11, family: '-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif' }, boxWidth: 12, padding: 14 }
                            },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                titleColor: '#F1F5F9',
                                bodyColor: '#CBD5E1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 10
                            }
                        },
                        scales: {
                            x: { grid: { color: '#F1F5F9' }, ticks: { font: { size: 10 }, color: '#94A3B8' } },
                            y: {
                                grid: { color: '#F1F5F9' },
                                ticks: { beginAtZero: true, suggestedMax: 30, font: { size: 10 }, color: '#94A3B8' },
                                title: { display: true, text: 'IOP mmHg', font: { size: 10 }, color: '#94A3B8' }
                            }
                        }
                    }
                });
            }
        }

        // Auto-hide flash messages after 10 seconds
        document.addEventListener('DOMContentLoaded', function () {
            renderPatientClinicalTrendCharts();

            setTimeout(function () {
                document.querySelectorAll('.alert-dismissible').forEach(alert => {
                    // Fade out animation
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';

                    // Remove from DOM after fade
                    setTimeout(() => {
                        alert.querySelector('.close')?.click();
                    }, 500);
                });
            }, 10000); // 10 seconds
        });

        document.addEventListener('livewire:load', function () {
            renderPatientClinicalTrendCharts();

            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('message.processed', function () {
                    renderPatientClinicalTrendCharts();
                });
            }
        });

        window.addEventListener('render-clinical-trend-charts', function () {
            setTimeout(renderPatientClinicalTrendCharts, 100);
        });

        // Print refraction
        window.addEventListener('printRefraction', event => {
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Refraction Prescription</title>');
            printWindow.document.write('<style>body{font-family:Arial;padding:20px;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(event.detail.html);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        });
</script>
