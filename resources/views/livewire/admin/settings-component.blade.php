<div class="p-4 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary font-weight-bold mb-0">System Settings</h2>
            <p class="text-muted small text-uppercase font-weight-bold mb-0">Manage Clinic Branding & Contact Info</p>
        </div>
        <div class="btn-group shadow-sm">
            <button type="button" wire:click="resetToDefaults" class="btn btn-outline-secondary">
                <i class="fas fa-undo mr-1"></i> Reset Details
            </button>
        </div>
    </div>

    @if (!empty($missingSetupFields))
        <div class="alert alert-info border-0 shadow-sm">
            <div class="font-weight-bold mb-1">
                <i class="fas fa-info-circle mr-2"></i>Complete these setup fields:
            </div>
            <div>{{ implode(', ', $missingSetupFields) }}</div>
        </div>
    @endif

    <div class="row">
        {{-- Settings Form --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-primary text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-cogs mr-2"></i> Clinic Information</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateSettings">
                        {{-- Clinic Name --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">CLINIC NAME</label>
                            <input type="text" wire:model.defer="state.clinic_name" class="form-control bg-light border-0 @error('state.clinic_name') is-invalid @enderror" placeholder="e.g., BrightSight Eye Clinic">
                            @error('state.clinic_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Clinic Address --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">CLINIC ADDRESS</label>
                            <textarea wire:model.defer="state.clinic_address" class="form-control bg-light border-0 @error('state.clinic_address') is-invalid @enderror" rows="2" placeholder="e.g., 123 Visionary St., Optic City"></textarea>
                            @error('state.clinic_address') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Clinic Contact --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">CONTACT NUMBER</label>
                            <input type="text" wire:model.defer="state.clinic_contact" class="form-control bg-light border-0 @error('state.clinic_contact') is-invalid @enderror" placeholder="e.g., +123 456 7890">
                            @error('state.clinic_contact') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Clinic Email --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">EMAIL ADDRESS</label>
                            <input type="email" wire:model.defer="state.clinic_email" class="form-control bg-light border-0 @error('state.clinic_email') is-invalid @enderror" placeholder="e.g., info@brightsight.com">
                            @error('state.clinic_email') <span class="text-danger small">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Leave blank if the clinic does not use an email address.</small>
                        </div>

                        {{-- VA Notation --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">VISUAL ACUITY NOTATION</label>
                            <div class="pt-1">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" wire:model="va_notation" value="6m"
                                        id="va6m" class="custom-control-input">
                                    <label for="va6m" class="custom-control-label">6 metre &nbsp;<span class="text-muted">(6/6, 6/12…)</span></label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" wire:model="va_notation" value="20ft"
                                        id="va20ft" class="custom-control-input">
                                    <label for="va20ft" class="custom-control-label">20 foot &nbsp;<span class="text-muted">(20/20, 20/40…)</span></label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Affects all Visual Acuity dropdowns. Previously recorded values are not changed.</small>
                        </div>

                        <hr class="my-4">

                        {{-- Logo Upload --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">CLINIC LOGO</label>
                            <div class="custom-file">
                                <input type="file" wire:model="newLogo" class="custom-file-input @error('newLogo') is-invalid @enderror" id="customFile{{ $uploadInputKey }}" wire:key="clinic-logo-{{ $uploadInputKey }}">
                                <label class="custom-file-label" for="customFile{{ $uploadInputKey }}">{{ $newLogo ? $newLogo->getClientOriginalName() : 'Choose file...' }}</label>
                            </div>
                            @error('newLogo') <span class="text-danger small">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Max size: 2MB. Recommended: PNG, transparent background.</small>
                            <div wire:loading wire:target="newLogo" class="text-info small mt-2">Uploading...</div>
                        </div>

                        @if ($currentLogo)
                            <button type="button" wire:click="removeLogo" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash mr-1"></i> Remove Current Logo
                            </button>
                        @endif

                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm mt-4">
                            <i class="fas fa-save mr-2"></i> SAVE SETTINGS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-lg sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-eye mr-2"></i> Live Preview</h5>
                </div>
                <div class="card-body text-center p-4">
                    <p class="small text-muted mb-3">This is how your logo and clinic name will appear.</p>
                    
                    <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                        {{-- Logo Preview --}}
                        @if ($newLogo)
                            <img src="{{ $newLogo->temporaryUrl() }}" class="img-fluid rounded shadow-sm" style="max-height: 120px; max-width: 100%; object-fit: contain;" alt="New Logo Preview">
                        @elseif ($currentLogo)
                            <img src="{{ asset('storage/' . $currentLogo) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px; max-width: 100%; object-fit: contain;" alt="Current Clinic Logo">
                        @else
                            <div class="text-muted d-flex flex-column align-items-center justify-content-center p-4 border rounded" style="min-height: 120px;">
                                <i class="fas fa-clinic-medical fa-3x mb-2 text-primary"></i>
                                <span class="small font-weight-bold">No Logo Uploaded</span>
                            </div>
                        @endif
                    </div>

                    {{-- Clinic Name Preview --}}
                    <h4 class="text-primary font-weight-bold mb-2">{{ $state['clinic_name'] ?? 'Your Clinic Name' }}</h4>
                    <p class="text-muted mb-1">{{ !empty($state['clinic_address']) ? $state['clinic_address'] : 'Clinic Address' }}</p>
                    <p class="text-muted mb-1">
                        Contact: {{ !empty($state['clinic_contact']) ? $state['clinic_contact'] : 'N/A' }}
                        | Email: {{ !empty($state['clinic_email']) ? $state['clinic_email'] : 'N/A' }}
                    </p>

                    <small class="text-secondary mt-3 d-block">This data will reflect across all reports and user interfaces.</small>
                </div>
            </div>
        </div>
    </div>
</div>
