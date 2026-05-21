<?php

namespace App\Http\Livewire\Admin;

use App\Models\LensOrder;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SpectacleRenewalApprovalsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $filterStatus = 'pending';

    public $confirmId     = null;
    public $confirmAction = null; // 'approve' | 'reject'
    public string $noteInput = '';

    public function mount(): void
    {
        abort_if(!Auth::user()?->hasRole('Super Admin'), 403);
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openConfirm($id, string $action): void
    {
        $this->confirmId     = $id;
        $this->confirmAction = $action;
        $this->noteInput     = '';
    }

    public function cancelConfirm(): void
    {
        $this->confirmId     = null;
        $this->confirmAction = null;
        $this->noteInput     = '';
    }

    public function execute(): void
    {
        abort_if(!Auth::user()?->hasRole('Super Admin'), 403);

        $order = LensOrder::with('refraction.consultation.patient')->findOrFail($this->confirmId);

        if ($this->confirmAction === 'approve') {
            $order->update([
                'renewal_approval_status' => 'approved',
                'renewal_approved_by'     => Auth::id(),
                'renewal_actioned_at'     => now(),
            ]);

            // Send SMS immediately on approval
            $s = Setting::getSettings();
            $patient = optional(optional($order->refraction)->consultation)->patient;

            if ($patient?->contact && ($s->sms_enabled ?? true)) {
                $msg = SmsTemplate::render('spectacle_renewal', [
                    '[NAME]'   => $patient->name,
                    '[DATE]'   => $order->renewal_date?->format('d M Y') ?? '—',
                    '[CLINIC]' => $s->clinic_name ?? 'the clinic',
                ]);

                if ($msg) {
                    $result = (new SmsService)->send($patient->contact, $msg, $patient->id, 'spectacle_renewal');
                    if ($result['success']) {
                        $order->update(['renewal_reminder_sent_at' => now()]);
                    }
                }
            }

            $patientName = optional(optional($order->refraction)->consultation)->patient?->name ?? 'Patient';
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Approved and SMS sent to {$patientName}."]);
        } else {
            $order->update([
                'renewal_approval_status' => 'rejected',
                'renewal_approved_by'     => Auth::id(),
                'renewal_actioned_at'     => now(),
            ]);

            $patientName = optional(optional($order->refraction)->consultation)->patient?->name ?? 'Patient';
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => "Reminder for {$patientName} rejected — no SMS will be sent."]);
        }

        $this->cancelConfirm();
    }

    public function requeue($id): void
    {
        abort_if(!Auth::user()?->hasRole('Super Admin'), 403);

        LensOrder::findOrFail($id)->update([
            'renewal_approval_status' => 'pending',
            'renewal_approved_by'     => null,
            'renewal_actioned_at'     => null,
        ]);

        $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => 'Reminder re-queued for approval.']);
    }

    public static function pendingCount(): int
    {
        return LensOrder::where('renewal_approval_status', 'pending')->count();
    }

    public function render()
    {
        $s = Setting::getSettings();
        $clinic = $s->clinic_name ?? 'the clinic';

        $orders = LensOrder::with(['refraction.consultation.patient', 'renewalApprovedBy'])
            ->where('status', 'Collected')
            ->whereNotNull('renewal_approval_status')
            ->when($this->filterStatus, fn($q) => $q->where('renewal_approval_status', $this->filterStatus))
            ->latest('renewal_actioned_at')
            ->orderByDesc('renewal_date')
            ->paginate(15);

        $counts = [
            'pending'  => LensOrder::where('renewal_approval_status', 'pending')->count(),
            'approved' => LensOrder::where('renewal_approval_status', 'approved')->count(),
            'rejected' => LensOrder::where('renewal_approval_status', 'rejected')->count(),
        ];

        // Build message previews for visible orders
        $previews = [];
        foreach ($orders as $order) {
            $patient = optional(optional($order->refraction)->consultation)->patient;
            $previews[$order->id] = SmsTemplate::render('spectacle_renewal', [
                '[NAME]'   => $patient?->name ?? '[NAME]',
                '[DATE]'   => $order->renewal_date?->format('d M Y') ?? '[DATE]',
                '[CLINIC]' => $clinic,
            ]) ?? '—';
        }

        return view('livewire.admin.spectacle-renewal-approvals-component', compact('orders', 'counts', 'previews'));
    }
}
