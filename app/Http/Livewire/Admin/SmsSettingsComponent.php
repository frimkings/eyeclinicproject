<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use App\Services\LicenseService;
use App\Services\SmsService;
use App\Support\Feature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class SmsSettingsComponent extends Component
{
    public string $smsApiUrl    = '';
    public string $smsApiKey    = '';   // never pre-filled; blank = keep existing
    public string $smsSenderId  = '';
    public string $testPhone    = '';
    public bool   $smsEnabled   = true;
    public ?array $balanceResult = null;

    public bool $spectacleRenewalEnabled      = true;
    public int  $spectacleRenewalReminderDays = 30;

    protected $rules = [
        'smsApiUrl'   => 'required|url|max:500',
        'smsApiKey'   => 'nullable|string|max:500',
        'smsSenderId' => 'nullable|string|max:50',
        'testPhone'   => 'nullable|string|max:20',
    ];

    protected $messages = [
        'smsApiUrl.required' => 'API endpoint URL is required.',
        'smsApiUrl.url'      => 'Please enter a valid URL.',
    ];

    public function mount(): void
    {
        abort_if(!LicenseService::has(Feature::SMS_CAMPAIGNS), 403, 'SMS campaigns require a Pro license.');
        if (!Auth::user()->hasRole('Super Admin')) {
            return;
        }

        $s = Setting::getSettings();
        $this->smsApiUrl                    = $s->sms_api_url   ?? '';
        $this->smsSenderId                  = $s->sms_sender_id ?? '';
        $this->smsEnabled                   = (bool) ($s->sms_enabled ?? true);
        $this->spectacleRenewalEnabled      = (bool) ($s->spectacle_renewal_enabled ?? true);
        $this->spectacleRenewalReminderDays = (int) ($s->spectacle_renewal_reminder_days ?? 30);
        $this->testPhone                    = '';
        // API key intentionally blank — user must re-enter to change
    }

    public function save(): void
    {
        $this->validate([
            'smsApiUrl'   => 'required|url|max:500',
            'smsSenderId' => 'nullable|string|max:50',
        ]);

        $data = [
            'sms_api_url'   => trim($this->smsApiUrl),
            'sms_sender_id' => trim($this->smsSenderId) ?: null,
        ];

        if (!empty(trim($this->smsApiKey))) {
            $data['sms_api_key'] = Crypt::encryptString(trim($this->smsApiKey));
        }

        Setting::getSettings()->update($data);

        $this->smsApiKey = '';

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => 'SMS settings saved.',
        ]);
    }

    public function toggleSms(): void
    {
        $this->smsEnabled = !$this->smsEnabled;
        Setting::getSettings()->update(['sms_enabled' => $this->smsEnabled]);

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => $this->smsEnabled ? 'SMS notifications resumed.' : 'SMS notifications paused.',
        ]);
    }

    public function saveRenewalSettings(): void
    {
        $this->validate([
            'spectacleRenewalReminderDays' => 'required|integer|min:1|max:90',
        ]);

        Setting::getSettings()->update([
            'spectacle_renewal_enabled'       => $this->spectacleRenewalEnabled,
            'spectacle_renewal_reminder_days' => $this->spectacleRenewalReminderDays,
        ]);

        $this->dispatchBrowserEvent('notify', [
            'type'    => 'success',
            'message' => 'Spectacle renewal settings saved.',
        ]);
    }

    public function sendTest(): void
    {
        $this->validateOnly('testPhone', ['testPhone' => 'required|string|max:20']);

        $s = Setting::getSettings();
        $clinicName = $s->clinic_name ?? 'The Clinic';

        $result = (new SmsService)->send(
            $this->testPhone,
            "Test SMS from {$clinicName}. Your SMS settings are working correctly."
        );

        if ($result['success']) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'success',
                'message' => 'Test SMS sent successfully.',
            ]);
        } else {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Test failed: ' . ($result['error'] ?? 'Unknown error'),
            ]);
        }
    }

    public function checkBalance(): void
    {
        $result = (new SmsService)->checkBalance();
        $this->balanceResult = $result;

        if (!$result['success']) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => 'Balance check failed: ' . ($result['error'] ?? 'Unknown error'),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.sms-settings-component')
            ->layout('layouts.admin.admin-layout');
    }
}
