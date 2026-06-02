<div class="p-4">
    @php
        $isExpired = isset($licenseInfo['days_left']) && $licenseInfo['days_left'] <= 0;
        $effectiveTier = ($licenseInfo['tier'] === 'pro' && $isExpired) ? 'free' : $licenseInfo['tier'];
    @endphp

    {{-- Trial banner --}}
    @if($licenseInfo['tier'] === 'trial')
        @php $tDays = $licenseInfo['trial_days_left'] ?? 0; @endphp
        @if($tDays <= 7)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="fas fa-hourglass-half mr-2"></i>
                <div>
                    <strong>Trial ending soon.</strong>
                    Your free trial expires on <strong>{{ $licenseInfo['trial_expires'] }}</strong>
                    ({{ $tDays }} day{{ $tDays === 1 ? '' : 's' }} left).
                    Enter a license key below to keep Pro access.
                </div>
            </div>
        @else
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="fas fa-gift mr-2"></i>
                <div>
                    <strong>Free Trial Active.</strong>
                    All Pro features are unlocked until <strong>{{ $licenseInfo['trial_expires'] }}</strong>
                    ({{ $tDays }} day{{ $tDays === 1 ? '' : 's' }} remaining).
                </div>
            </div>
        @endif
    @endif

    {{-- Pro license expiry banners --}}
    @if(isset($licenseInfo['days_left']))
        @if($licenseInfo['days_left'] <= 0)
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="fas fa-times-circle mr-2"></i>
                <div>
                    <strong>License Expired.</strong>
                    Your license expired on {{ $licenseInfo['expires'] }}. Pro features are disabled.
                    Enter a new key below to renew.
                </div>
            </div>
        @elseif($licenseInfo['days_left'] <= 30)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <div>
                    <strong>License expiring soon.</strong>
                    Your license expires on <strong>{{ $licenseInfo['expires'] }}</strong>
                    ({{ $licenseInfo['days_left'] }} day{{ $licenseInfo['days_left'] === 1 ? '' : 's' }} remaining).
                    Contact your supplier to renew.
                </div>
            </div>
        @endif
    @endif

    {{-- Result banner --}}
    @if($resultType === 'success')
        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ $resultMsg }}
        </div>
    @elseif($resultType === 'error')
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fas fa-times-circle mr-2"></i> {{ $resultMsg }}
        </div>
    @endif

    <div class="row">

        {{-- Left: current license status --}}
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-id-card mr-1"></i> License Status
                </div>
                <div class="card-body">

                    {{-- Tier badge --}}
                    <div class="d-flex align-items-center mb-3">
                        <span class="mr-2 text-muted small">Tier:</span>
                        @if($effectiveTier === 'pro')
                            <span class="badge badge-success px-3 py-2" style="font-size:0.95rem;">
                                <i class="fas fa-crown mr-1"></i> PRO
                            </span>
                        @elseif($effectiveTier === 'trial')
                            <span class="badge badge-info px-3 py-2" style="font-size:0.95rem;">
                                <i class="fas fa-gift mr-1"></i> TRIAL
                            </span>
                        @else
                            <span class="badge badge-secondary px-3 py-2" style="font-size:0.95rem;">
                                FREE
                            </span>
                        @endif
                    </div>

                    @if($effectiveTier === 'trial')
                        <div class="mb-2">
                            <span class="text-muted small">Trial expires:</span>
                            <strong class="ml-1">{{ $licenseInfo['trial_expires'] }}</strong>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small">Days remaining:</span>
                            <span class="ml-1">{{ $licenseInfo['trial_days_left'] }} day{{ $licenseInfo['trial_days_left'] === 1 ? '' : 's' }}</span>
                        </div>
                    @endif

                    @if($licenseInfo['tier'] === 'pro')
                        <div class="mb-2">
                            <span class="text-muted small">Clinic:</span>
                            <strong class="ml-1">{{ $licenseInfo['clinic'] ?? '—' }}</strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Issued:</span>
                            <span class="ml-1">{{ $licenseInfo['issued'] ?? '—' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small">Expires:</span>
                            <span class="ml-1">
                                {{ $licenseInfo['expires'] ?? '—' }}
                                @if(isset($licenseInfo['days_left']) && $licenseInfo['days_left'] > 0)
                                    <span class="text-muted small">({{ $licenseInfo['days_left'] }} days left)</span>
                                @endif
                            </span>
                        </div>
                    @endif

                    {{-- Installation ID --}}
                    <hr>
                    <div class="mb-1">
                        <span class="text-muted small d-block mb-1">
                            Installation ID
                            <span class="text-muted" style="font-size:0.8rem;">
                                — send this to your supplier when purchasing or renewing
                            </span>
                        </span>
                        <div class="input-group input-group-sm">
                            <input type="text"
                                   id="installation-id-field"
                                   class="form-control font-monospace"
                                   value="{{ $licenseInfo['installation_id'] ?? '' }}"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="copyInstallId()"
                                        title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Right: feature list + activation --}}
        <div class="col-md-7 mb-4">

            {{-- Activate / renew key --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-key mr-1"></i>
                    @if($effectiveTier === 'pro') Renew License Key
                    @elseif($effectiveTier === 'trial') Activate License Key (Trial Active)
                    @else Activate License Key
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group mb-2">
                        <textarea wire:model.defer="licenseKey"
                                  class="form-control font-monospace"
                                  rows="3"
                                  placeholder="EYECLINIC-PRO-..."></textarea>
                    </div>
                    <button wire:click="activate" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading wire:target="activate">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Activating…
                        </span>
                        <span wire:loading.remove wire:target="activate">
                            <i class="fas fa-check mr-1"></i> Activate
                        </span>
                    </button>
                </div>
            </div>

            {{-- Feature list --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-list-check mr-1"></i> Feature Access
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <tbody>
                            @php
                                $isPro = in_array($effectiveTier, ['pro', 'trial']);
                                $features = [
                                    'Appointments'              => $isPro,
                                    'Referrals'                 => $isPro,
                                    'Outstanding Balances'      => $isPro,
                                    'Manual Backup'             => $isPro,
                                    'SMS Campaigns'             => $isPro,
                                    'Advanced Reporting'        => $isPro,
                                    'Inventory & Stock'         => $isPro,
                                    'Supplier Management'       => $isPro,
                                    'Approval Workflows'        => $isPro,
                                    'Audit Trail & Login History' => $isPro,
                                    'Scheduled Auto-Backups'    => $isPro,
                                    'Expense Tracking'          => $isPro,
                                    'Financial Report Delivery' => $isPro,
                                    'Spectacle Renewal & Lens Debt' => $isPro,
                                    'Unlimited Users'           => $isPro,
                                ];
                            @endphp
                            @foreach($features as $name => $enabled)
                                <tr>
                                    <td class="pl-3">{{ $name }}</td>
                                    <td class="text-right pr-3">
                                        @if($enabled)
                                            <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-lock"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyInstallId() {
            var field = document.getElementById('installation-id-field');
            var btn = field.closest('.input-group').querySelector('button');
            var orig = btn.innerHTML;

            function flash() {
                btn.innerHTML = '<i class="fas fa-check text-success"></i>';
                setTimeout(function() { btn.innerHTML = orig; }, 1500);
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(field.value).then(flash);
            } else {
                field.select();
                document.execCommand('copy');
                flash();
            }
        }
    </script>
</div>
