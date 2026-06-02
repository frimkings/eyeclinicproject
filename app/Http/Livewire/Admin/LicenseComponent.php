<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuditTrail;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LicenseComponent extends Component
{
    public string $licenseKey  = '';
    public string $resultType  = ''; // 'success' | 'error'
    public string $resultMsg   = '';
    public array  $licenseInfo = [];

    public function mount(): void
    {
        // Fix #12: generic 403 — don't leak feature/role names in error messages
        abort_if(!Auth::user()->hasRole('Super Admin'), 403);
        $this->licenseInfo = LicenseService::info();
    }

    public function activate(): void
    {
        $key = trim($this->licenseKey);

        if (empty($key)) {
            $this->resultType = 'error';
            $this->resultMsg  = 'Please paste a license key before clicking Activate.';
            return;
        }

        LicenseService::clearCache();
        $result = LicenseService::activate($key);

        if ($result['ok']) {
            $this->resultType  = 'success';
            $this->resultMsg   = $result['message'];
            $this->licenseKey  = '';
            $this->licenseInfo = LicenseService::info();
            // Fix #8 force=true: license changes must always be audited
            AuditTrail::record('license.activated', "License activated successfully. Tier: {$this->licenseInfo['tier']}", null, [], [], null, true);
        } else {
            $this->resultType = 'error';
            $this->resultMsg  = $result['message'];
            // Fix #9: log failed activation attempts so brute-force is visible
            AuditTrail::record('license.activation_failed', 'License activation attempt failed.', null, [], [], null, true);
        }
    }

    public function render()
    {
        return view('livewire.admin.license-component')
            ->layout('layouts.admin.admin-layout');
    }
}
