<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Status banner --}}
            <div class="alert border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between
                        {{ $smsEnabled ? 'alert-success' : 'alert-warning' }}">
                <div>
                    @if($smsEnabled)
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>SMS Notifications Active</strong>
                        <span class="d-block small mt-1">All SMS triggers (appointments, spectacles, payments) are enabled.</span>
                    @else
                        <i class="fas fa-pause-circle mr-2"></i>
                        <strong>SMS Notifications Paused</strong>
                        <span class="d-block small mt-1">No SMS messages will be sent until notifications are resumed.</span>
                    @endif
                </div>
                <button type="button" wire:click="toggleSms"
                        wire:loading.attr="disabled" wire:target="toggleSms"
                        class="btn font-weight-bold shadow-sm ml-3 flex-shrink-0
                               {{ $smsEnabled ? 'btn-warning' : 'btn-success' }}">
                    <span wire:loading.remove wire:target="toggleSms">
                        <i class="fas {{ $smsEnabled ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                        {{ $smsEnabled ? 'Pause SMS' : 'Resume SMS' }}
                    </span>
                    <span wire:loading wire:target="toggleSms">Updating…</span>
                </button>
            </div>

            {{-- Credentials card --}}
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-primary text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-sms mr-2"></i> SMS API Configuration</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">

                        {{-- API Base URL --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">API BASE URL</label>
                            <input type="url" wire:model.defer="smsApiUrl"
                                   class="form-control bg-light border-0 @error('smsApiUrl') is-invalid @enderror"
                                   placeholder="http://dashboard.eazismspro.com/sms/api">
                            @error('smsApiUrl') <span class="text-danger small">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">
                                Paste the base URL only — without any query parameters.
                                e.g. <code>http://dashboard.eazismspro.com/sms/api</code>
                            </small>
                        </div>

                        {{-- API Key --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">API KEY</label>
                            <input type="password" wire:model.defer="smsApiKey"
                                   class="form-control bg-light border-0 @error('smsApiKey') is-invalid @enderror"
                                   placeholder="Leave blank to keep the existing key"
                                   autocomplete="new-password">
                            @error('smsApiKey') <span class="text-danger small">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Stored encrypted. Leave blank to keep the currently saved key.</small>
                        </div>

                        {{-- Sender ID --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">SENDER ID</label>
                            <input type="text" wire:model.defer="smsSenderId"
                                   class="form-control bg-light border-0 @error('smsSenderId') is-invalid @enderror"
                                   placeholder="e.g. EYECLINIC"
                                   maxlength="11">
                            @error('smsSenderId') <span class="text-danger small">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">
                                Alphanumeric sender name as registered with your provider (max 11 chars).
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm mt-2"
                                wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-2"></i> Save SMS Settings</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Test & Balance card --}}
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-secondary text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-paper-plane mr-2"></i> Test & Balance</h5>
                </div>
                <div class="card-body">

                    {{-- Test SMS --}}
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">TEST PHONE NUMBER</label>
                        <div class="input-group">
                            <input type="text" wire:model.defer="testPhone"
                                   class="form-control bg-light border-0"
                                   placeholder="e.g. 0241234567">
                            <div class="input-group-append">
                                <button type="button" wire:click="sendTest"
                                        wire:loading.attr="disabled" wire:target="sendTest"
                                        class="btn btn-outline-primary font-weight-bold">
                                    <span wire:loading.remove wire:target="sendTest"><i class="fas fa-paper-plane mr-1"></i> Send Test</span>
                                    <span wire:loading wire:target="sendTest">Sending…</span>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Sends a test message to confirm your credentials are working.</small>
                    </div>

                    <hr>

                    {{-- Balance check --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="font-weight-bold small text-muted text-uppercase">SMS Balance</div>
                            @if($balanceResult)
                                @if($balanceResult['success'])
                                    <span class="text-success font-weight-bold">
                                        {{ $balanceResult['response']['balance'] ?? json_encode($balanceResult['response']) }}
                                    </span>
                                @else
                                    <span class="text-danger small">{{ $balanceResult['error'] }}</span>
                                @endif
                            @else
                                <span class="text-muted small">Click "Check Balance" to query your account.</span>
                            @endif
                        </div>
                        <button type="button" wire:click="checkBalance"
                                wire:loading.attr="disabled" wire:target="checkBalance"
                                class="btn btn-outline-secondary btn-sm font-weight-bold">
                            <span wire:loading.remove wire:target="checkBalance"><i class="fas fa-wallet mr-1"></i> Check Balance</span>
                            <span wire:loading wire:target="checkBalance">Checking…</span>
                        </button>
                    </div>

                </div>
            </div>

            {{-- Spectacle Renewal Reminders --}}
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-info text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-redo mr-2"></i> Spectacle Renewal Reminders</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Automatically sends an SMS reminder to patients whose spectacles are due for their annual eye review.
                        The reminder fires daily at 09:00 when a patient's renewal date is within the configured number of days.
                    </p>

                    <form wire:submit.prevent="saveRenewalSettings">
                        <div class="form-group d-flex align-items-center justify-content-between">
                            <div>
                                <label class="small font-weight-bold text-muted mb-0">ENABLE RENEWAL REMINDERS</label>
                                <div class="small text-muted">Send SMS when renewal date approaches</div>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="renewalEnabled"
                                       wire:model="spectacleRenewalEnabled">
                                <label class="custom-control-label" for="renewalEnabled"></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">DAYS BEFORE RENEWAL TO SEND REMINDER</label>
                            <div class="input-group" style="max-width:200px">
                                <input type="number" wire:model.defer="spectacleRenewalReminderDays"
                                       class="form-control bg-light border-0 @error('spectacleRenewalReminderDays') is-invalid @enderror"
                                       min="1" max="90" placeholder="30">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light border-0">days</span>
                                </div>
                            </div>
                            @error('spectacleRenewalReminderDays')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Between 1 and 90 days. Default: 30 days.</small>
                        </div>

                        <button type="submit" class="btn btn-info btn-block py-2 font-weight-bold shadow-sm text-white"
                                wire:loading.attr="disabled" wire:target="saveRenewalSettings">
                            <span wire:loading.remove wire:target="saveRenewalSettings"><i class="fas fa-save mr-2"></i> Save Renewal Settings</span>
                            <span wire:loading wire:target="saveRenewalSettings">Saving…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Provider info --}}
            <div class="alert alert-info border-0 shadow-sm small mb-0">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>EazismsPro API:</strong> SMS are sent via HTTP GET —
                <code>?action=send-sms&api_key=…&to=233xx&from=SENDER&sms=…</code>.
                Phone numbers are automatically normalised to Ghana format (<code>0xx</code> → <code>233xx</code>).
            </div>

        </div>
    </div>
</div>
