<?php

namespace App\Http\Livewire\Secretary;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointments;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Http\Livewire\Traits\HasAppointmentBooking;
use App\Services\SmsService;
use App\Services\EmailService;
use App\Models\SmsTemplate;
use App\Mail\AppointmentConfirmationMail;
class AppointmentsComponent extends Component
{
    use WithPagination;
    use HasAppointmentBooking;
    // --- Appointment Intake Properties ---
    public $title;
    public $recall_category;
    public $reminder_channel = 'whatsapp';
    public $patient_id;
    public $scheduled_at;
    public $notes;
    public $patientSearch = '';
    public $selectedPatientName = '';

    // --- UI & Navigation Properties ---
    public $search = '';
    public $activeFilter = 'schedule';
    public $scheduleView = 'calendar';
    public $editingAppointmentId = null;
    public $isEditModalOpen = false;
    public $newAppointmentStatus = 'Pending';
    public $selectedScheduleDate;
    
    // --- Selection Properties ---
    public $selectedAppointments = []; 
    public $selectAll = false;

    // --- Range Filtering Properties ---
    public $startDate;
    public $endDate;
    public $statusFilter = 'All';
    public $dailyAppointmentLimit = 30;
    public $calendarStartDate;

    // --- Quick Filter & Cancel ---
    public $quickFilter       = '';
    public $cancelReason      = '';
    public $cancellingId      = null;
    public $isCancelModalOpen = false;

    // --- Missed Follow-up ---
    public $missedActionId  = null;
    public $rescheduleDate  = '';
    public $rescheduleTime  = '09:00';


public $recallCategories = [
    'Routine Review',
    'Glaucoma Review',
    'Diabetic Review',
    'Post-op Review',
    'Glasses Pickup',
];


    public $clinic_name = '';
    public $clinic_link = '';
    public $whatsapp_template = "Hello [NAME], this is a reminder for your appointment ([REASON]) on [DATE] at [TIME]. \n\nLocation: [LINK]";
    public $sms_template = "Hello [NAME], reminder: [REASON] appointment on [DATE] at [TIME]. Location: [LINK]";
    public $showReminderPreview = false;
    public $previewReminderMessage = '';
    public $previewReminderPhone = '';
    public $previewReminderChannel = 'whatsapp';
    public $previewAppointmentId = null;

    protected $rules = [
        'title' => 'required|string|min:3',
        'recall_category' => 'nullable|string|max:100',
        'reminder_channel' => 'required|in:none,sms,whatsapp,both',
        'patient_id' => 'required|exists:patients,id,deleted_at,NULL',
        'scheduled_at' => 'required|date|after_or_equal:today',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'scheduled_at.after_or_equal' => 'Appointment date cannot be in the past.',
    ];

    public function mount()
    {
        if (!in_array('Walk-in Visit', $this->appointmentReasons, true)) {
            array_unshift($this->appointmentReasons, 'Walk-in Visit');
        }

        $settings = \App\Models\Setting::getSettings();
        $this->clinic_name = $settings->clinic_name ?? 'VISION SPACE EYE CENTER';
        $this->clinic_link = $settings->clinic_link ?? '';

        $this->startDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $this->calendarStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->selectedScheduleDate = Carbon::today()->format('Y-m-d');
        $this->markPastAppointmentsAsMissed();
    }

    public function updatedActiveFilter()
    {
        $this->resetSelection();
        $this->quickFilter = '';
        $this->resetPage();
    }

    public function updatedScheduleView()
    {
        $this->resetSelection();
        $this->resetPage();
    }

    public function updatedSearch() 
    { 
        $this->resetPage(); 
    }

    public function previousCalendarWeek(): void
    {
        $this->calendarStartDate = Carbon::parse($this->calendarStartDate)->subMonthNoOverflow()->startOfMonth()->format('Y-m-d');
    }

    public function nextCalendarWeek(): void
    {
        $this->calendarStartDate = Carbon::parse($this->calendarStartDate)->addMonthNoOverflow()->startOfMonth()->format('Y-m-d');
    }

    public function goToCurrentCalendarWeek(): void
    {
        $this->calendarStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
    }

    public function previousScheduleDay(): void
    {
        $this->selectedScheduleDate = Carbon::parse($this->selectedScheduleDate)->subDay()->format('Y-m-d');
    }

    public function nextScheduleDay(): void
    {
        $this->selectedScheduleDate = Carbon::parse($this->selectedScheduleDate)->addDay()->format('Y-m-d');
    }

    public function goToTodaySchedule(): void
    {
        $this->selectedScheduleDate = Carbon::today()->format('Y-m-d');
        $this->calendarStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
    }

    public function bookOnCalendarDate(string $date): void
    {
        if (Carbon::parse($date)->isPast() && !Carbon::parse($date)->isToday()) {
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'Cannot book an appointment on a past date.']);
            return;
        }

        $time = now()->format('H:i');
        $this->scheduled_at = Carbon::parse($date . ' ' . $time)->format('Y-m-d\TH:i');
        $this->editingAppointmentId = null;
        $this->newAppointmentStatus = 'Pending';
        $this->isEditModalOpen = true;
    }

    public function rescheduleAppointment($appointmentId, string $scheduledAt): void
    {
        $scheduledAt = Carbon::parse($scheduledAt);

        if ($scheduledAt->isPast() && !$scheduledAt->isToday()) {
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'Cannot reschedule to a past date.']);
            return;
        }

        Appointments::findOrFail($appointmentId)->update([
            'scheduled_at' => $scheduledAt,
            'status' => 'Rescheduled',
        ]);

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Appointment moved to ' . $scheduledAt->format('M d, Y h:i A') . '.',
        ]);
    }

    public function openNewAppointmentModal(): void
    {
        $this->resetForm();
        $this->editingAppointmentId = null;
        $this->newAppointmentStatus = 'Pending';
        $this->scheduled_at = now()->format('Y-m-d\TH:i');
        $this->isEditModalOpen = true;
    }

    public function openWalkInModal(): void
    {
        $this->resetForm();
        $this->editingAppointmentId = null;
        $this->newAppointmentStatus = 'Arrived';
        $this->title = 'Walk-in Visit';
        $this->recall_category = 'Routine Review';
        $this->reminder_channel = 'none';
        $this->scheduled_at = now()->format('Y-m-d\TH:i');
        $this->isEditModalOpen = true;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedAppointments = $this->getFilteredQuery()
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedAppointments = [];
        }
    }

    public function resetSelection()
    {
        $this->selectedAppointments = [];
        $this->selectAll = false;
    }

    /**
     * Settings Management
     */
    public function saveSettings()
    {
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Clinic settings updated successfully.']);
        $this->activeFilter = 'schedule';
    }

  
    public function closeClinicDay()
    {
        $unfinishedStatuses = ['Pending', 'Called', 'Couldnt Answer', 'Rescheduled'];

        $count = \DB::transaction(function () use ($unfinishedStatuses) {
            $todayAppointments = Appointments::whereDate('scheduled_at', Carbon::today())
                ->whereIn('status', $unfinishedStatuses)
                ->get();

            foreach ($todayAppointments as $appointment) {
                $appointment->update([
                    'scheduled_at' => Carbon::parse($appointment->scheduled_at)->addDay(),
                    'status'       => 'Pending',
                    'notes'        => $appointment->notes . "\n[Auto-moved from " . Carbon::today()->format('M d') . "]",
                ]);
            }

            return $todayAppointments->count();
        });

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Day Closed. $count unfinished appointments moved to tomorrow."]);
    }

    /**
     * Bulk Actions
     */
    public function bulkMarkAsSeen()
    {
        if (empty($this->selectedAppointments)) return;
        Appointments::whereIn('id', $this->selectedAppointments)->update(['status' => 'Seen']);
        $count = count($this->selectedAppointments);
        $this->resetSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Successfully moved $count records to History."]);
    }

    public function bulkMarkRemindersSent()
    {
        if (empty($this->selectedAppointments)) return;
        Appointments::whereIn('id', $this->selectedAppointments)->update([
            'reminder_status' => 'sent',
            'reminder_sent_at' => now(),
        ]);

        $count = count($this->selectedAppointments);
        $this->resetSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "$count reminder(s) marked as sent."]);
    }

    public function bulkDelete()
    {
        if (empty($this->selectedAppointments)) return;
        Appointments::whereIn('id', $this->selectedAppointments)->delete();
        $count = count($this->selectedAppointments);
        $this->resetSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Successfully moved $count records to Trash."]);
    }

    /**
     * Data Export
     */
  public function exportReport()
{
    $query = $this->getFilteredQuery();
    $appointments = $query->get();
    $filename = "clinic-registry-" . now()->format('Y-m-d') . ".csv";
    
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use($appointments) {
        $file = fopen('php://output', 'w');
        // Added 'Category' to the header row
        fputcsv($file, ['Date', 'Time', 'Patient Name', 'Contact', 'Reason', 'Recall Category', 'Reminder Channel', 'Reminder Status', 'Status', 'Notes']);
        
        foreach ($appointments as $app) {
            fputcsv($file, [
                $app->scheduled_at->format('Y-m-d'),
                $app->scheduled_at->format('h:i A'),
                $app->patient->name,
                $app->patient->contact ?? 'N/A',
                $app->title,
                $app->recall_category ?? $this->getCategoryByReason($app->title),
                $app->reminder_channel ?? 'whatsapp',
                $app->reminder_status ?? 'not_sent',
                $app->status,
                $app->notes ?? ''
            ]);
        }
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}

    private function getFilteredQuery()
    {
        $query = ($this->activeFilter === 'trash') 
            ? Appointments::onlyTrashed()->with('patient') 
            : Appointments::with('patient');
        
        $query->whereHas('patient', function ($q) {
            $q->where(function ($sub) {
                $sub->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('pxnumber', 'like', '%' . $this->search . '%')
                    ->orWhere('contact', 'like', '%' . $this->search . '%');
            });
        });

        // Tabs logic
        if ($this->activeFilter === 'history') {
            $query->whereIn('status', ['Seen', 'Cancelled']);
        } elseif ($this->activeFilter === 'queue') {
            $query->whereDate('scheduled_at', Carbon::today())
                ->whereNotIn('status', ['Seen', 'Missed', 'Cancelled']);
        } elseif ($this->activeFilter === 'missed') {
            $query->where('status', 'Missed');
        } elseif ($this->activeFilter !== 'trash'
            && $this->activeFilter !== 'settings'
            && !($this->activeFilter === 'schedule' && $this->scheduleView === 'range' && $this->statusFilter !== 'All')) {
            $query->whereNotIn('status', ['Seen', 'Missed', 'Cancelled']);
        }

        switch ($this->activeFilter) {
            case 'schedule':
                if ($this->scheduleView === 'range') {
                    $query->whereBetween('scheduled_at', [
                        Carbon::parse($this->startDate)->startOfDay(),
                        Carbon::parse($this->endDate)->endOfDay(),
                    ]);
                    if ($this->statusFilter !== 'All') {
                        $query->where('status', $this->statusFilter);
                    }
                } else {
                    $query->where('scheduled_at', '>=', Carbon::today()->startOfDay());
                    // Quick filter chips
                    if ($this->quickFilter === 'today') {
                        $query->whereDate('scheduled_at', Carbon::today());
                    } elseif ($this->quickFilter === 'tomorrow') {
                        $query->whereDate('scheduled_at', Carbon::tomorrow());
                    } elseif ($this->quickFilter === 'this_week') {
                        $query->whereBetween('scheduled_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($this->quickFilter === 'next_30') {
                        $query->whereBetween('scheduled_at', [Carbon::today(), Carbon::today()->addDays(30)]);
                    }
                }
                break;
            case 'queue':
                $query->orderByRaw("FIELD(status, 'Arrived', 'With Doctor', 'Pending', 'Called', 'Couldnt Answer', 'Rescheduled', 'Done')")
                    ->orderBy('scheduled_at', 'asc');
                break;
            case 'history':
                $query->orderBy('scheduled_at', 'desc');
                break;
            case 'missed':
                $query->orderBy('scheduled_at', 'desc');
                break;
        }
        
        return $query;
    }

    public function updateStatus($id, $status)
    {
        $payload = ['status' => $status];
        $payload['missed_at'] = $status === 'Missed' ? now() : null;

        Appointments::findOrFail($id)->update($payload);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => ($status === 'Seen') ? "Moved to History." : "Status updated."]);
    }

    public function advanceQueueStatus($id, $status)
    {
        $allowed = ['Arrived', 'With Doctor', 'Done'];

        if (!in_array($status, $allowed, true)) {
            return;
        }

        $this->updateStatus($id, $status);
    }

    public function quickBookWalkIn()
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'nullable|string|min:3',
            'recall_category' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'reminder_channel' => 'required|in:none,sms,whatsapp,both',
        ]);

        $scheduledAt = now();
        $this->flashDailyLimitWarning($scheduledAt);

        Appointments::create([
            'patient_id' => $this->patient_id,
            'user_id' => Auth::id(),
            'title' => $this->title ?: 'Walk-in Visit',
            'recall_category' => $this->recall_category ?: ($this->title ?: 'Walk-in Visit'),
            'scheduled_at' => $scheduledAt,
            'notes' => $this->notes,
            'reminder_channel' => $this->reminder_channel ?: 'none',
            'reminder_status' => 'not_sent',
            'status' => 'Arrived',
        ]);

        $this->resetForm();
        $this->activeFilter = 'queue';
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Walk-in booked and added to the waiting room.']);
    }

    public function markReminderSent($id, $channel)
    {
        Appointments::findOrFail($id)->update([
            'reminder_channel' => $channel,
            'reminder_status' => 'sent',
            'reminder_sent_at' => now(),
        ]);

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => ucfirst($channel) . ' reminder marked as sent.']);
    }

    public function sendSmsNow($id): void
    {
        $appointment = Appointments::with('patient')->findOrFail($id);
        $phone = $appointment->patient->contact ?? '';

        if (empty(trim($phone))) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'No contact number on record for this patient.']);
            return;
        }

        $message = str_replace(
            ['[NAME]', '[REASON]', '[DATE]', '[TIME]', '[LINK]'],
            [
                $appointment->patient->name,
                $appointment->title,
                $appointment->scheduled_at->format('M d, Y'),
                $appointment->scheduled_at->format('h:i A'),
                $this->clinic_link,
            ],
            $this->sms_template
        );

        $result = (new SmsService)->send($phone, $message);

        if ($result['success']) {
            Appointments::findOrFail($id)->update(['reminder_status' => 'sent', 'reminder_sent_at' => now()]);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'SMS sent successfully.']);
        } else {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'SMS failed: ' . ($result['error'] ?? 'Unknown error')]);
        }
    }

    public function previewReminder($id, $channel = 'whatsapp')
    {
        $appointment = Appointments::with('patient')->findOrFail($id);
        $template = $channel === 'sms' ? $this->sms_template : $this->whatsapp_template;

        $this->previewReminderMessage = str_replace(
            ['[NAME]', '[REASON]', '[DATE]', '[TIME]', '[LINK]'],
            [
                $appointment->patient->name,
                $appointment->title,
                $appointment->scheduled_at->format('M d, Y'),
                $appointment->scheduled_at->format('h:i A'),
                $this->clinic_link,
            ],
            $template
        );
        $this->previewReminderPhone = $appointment->patient->contact ?? '';
        $this->previewReminderChannel = $channel;
        $this->previewAppointmentId = $appointment->id;
        $this->showReminderPreview = true;
    }

    public function closeReminderPreview()
    {
        $this->showReminderPreview = false;
        $this->previewReminderMessage = '';
        $this->previewReminderPhone = '';
        $this->previewAppointmentId = null;
    }

    public function markPreviewReminderSent()
    {
        if ($this->previewAppointmentId) {
            $this->markReminderSent($this->previewAppointmentId, $this->previewReminderChannel);
        }

        $this->closeReminderPreview();
    }

    public function getPreviewWhatsAppUrlProperty()
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $this->previewReminderPhone);
        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            $phone = '233' . substr($phone, 1);
        }

        return 'https://wa.me/' . $phone . '?text=' . urlencode($this->previewReminderMessage);
    }

    private function getCategoryByReason($reason): string
    {
        $reason = strtolower((string) $reason);

        if (str_contains($reason, 'glaucoma')) {
            return 'Glaucoma Review';
        }

        if (str_contains($reason, 'diabetic') || str_contains($reason, 'diabetes')) {
            return 'Diabetic Review';
        }

        if (str_contains($reason, 'post-op') || str_contains($reason, 'post op') || str_contains($reason, 'surgery')) {
            return 'Post-op Review';
        }

        if (str_contains($reason, 'glasses') || str_contains($reason, 'pickup')) {
            return 'Glasses Pickup';
        }

        return 'Routine Review';
    }

    private function markPastAppointmentsAsMissed(): void
    {
        Appointments::whereDate('scheduled_at', '<', Carbon::today())
            ->whereIn('status', ['Pending', 'Called', 'Couldnt Answer'])
            ->update([
                'status' => 'Missed',
                'missed_at' => now(),
            ]);
    }

    public function getCountsProperty()
    {
        return [
            'schedule' => Appointments::where('scheduled_at', '>=', Carbon::today()->startOfDay())->whereNotIn('status', ['Seen', 'Missed', 'Cancelled'])->count(),
            'queue'    => Appointments::whereDate('scheduled_at', Carbon::today())->whereNotIn('status', ['Seen', 'Missed', 'Cancelled'])->count(),
            'history'  => Appointments::whereIn('status', ['Seen', 'Cancelled'])->count(),
            'missed'   => Appointments::where('status', 'Missed')->count(),
            'trash'    => Appointments::onlyTrashed()->count(),
        ];
    }

    public function getCalendarWeeksProperty()
    {
        $cursor = Carbon::parse($this->calendarStartDate)->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::parse($this->calendarStartDate)->endOfMonth();
        $weeks = collect();

        while ($cursor->lte($end)) {
            $week = collect();

            foreach (range(0, 4) as $offset) {
                $week->push($cursor->copy()->addDays($offset));
            }

            $weeks->push($week);
            $cursor->addWeek();
        }

        return $weeks;
    }

    public function getCalendarAppointmentsProperty()
    {
        $start = Carbon::parse($this->calendarStartDate)->startOfMonth()->startOfDay();
        $end = Carbon::parse($this->calendarStartDate)->endOfMonth()->endOfDay();

        return Appointments::with('patient')
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereNotIn('status', ['Seen', 'Missed'])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn ($appointment) => $appointment->scheduled_at->format('Y-m-d'));
    }

    public function getDayTimeSlotsProperty()
    {
        $day = Carbon::parse($this->selectedScheduleDate);

        return collect(range(8, 17))->map(function ($hour) use ($day) {
            return $day->copy()->hour($hour)->minute(0)->second(0);
        });
    }

    public function getDayAppointmentsProperty()
    {
        return Appointments::with('patient')
            ->whereDate('scheduled_at', Carbon::parse($this->selectedScheduleDate))
            ->whereNotIn('status', ['Seen', 'Missed'])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn ($appointment) => $appointment->scheduled_at->format('H:00'));
    }

    public function getStatusSummaryProperty()
    {
        return [
            'Booked' => Appointments::whereDate('scheduled_at', Carbon::today())->whereNotIn('status', ['Seen', 'Missed'])->count(),
            'Arrived' => Appointments::whereDate('scheduled_at', Carbon::today())->where('status', 'Arrived')->count(),
            'With Doctor' => Appointments::whereDate('scheduled_at', Carbon::today())->where('status', 'With Doctor')->count(),
            'Seen' => Appointments::whereDate('scheduled_at', Carbon::today())->where('status', 'Seen')->count(),
            'Missed' => Appointments::whereDate('scheduled_at', Carbon::today())->where('status', 'Missed')->count(),
        ];
    }

    public function selectPatient($id, $name) 
    { 
        $this->patient_id = $id; 
        $this->selectedPatientName = $name; 
        $this->patientSearch = ''; 
    }

    public function clearSelectedPatient(): void
    {
        $this->patient_id = null;
        $this->selectedPatientName = '';
        $this->patientSearch = '';
    }

    public function saveAppointment()
    {
        $this->validate();
        $scheduledAt = Carbon::parse($this->scheduled_at);
        
        $payload = [
            'patient_id' => $this->patient_id, 
            'title' => $this->title, 
            'recall_category' => $this->recall_category ?: $this->title,
            'scheduled_at' => $scheduledAt,
            'notes' => $this->notes,
            'reminder_channel' => $this->reminder_channel ?: 'whatsapp',
        ];

        if ($this->editingAppointmentId) {
            $apt = Appointments::withTrashed()->findOrFail($this->editingAppointmentId);
            if ($apt->trashed()) {
                $apt->restore();
            }
            $apt->update($payload);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Record updated.']);
        } else {
            $this->flashDailyLimitWarning($scheduledAt);

            Appointments::create(array_merge($payload, [
                'user_id' => Auth::id(),
                'reminder_status' => 'not_sent',
                'status' => $this->newAppointmentStatus ?: 'Pending'
            ]));
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Booking added.']);

            $patient = \App\Models\Patient::find($this->patient_id);
            if ($patient?->contact) {
                $clinic = \App\Models\Setting::getSettings()->clinic_name ?? 'the clinic';
                $msg = SmsTemplate::render('appointment_booking', [
                    '[NAME]'   => $patient->name,
                    '[CLINIC]' => $clinic,
                    '[DATE]'   => $scheduledAt->format('M d, Y'),
                    '[TIME]'   => $scheduledAt->format('h:i A'),
                    '[REASON]' => $this->title,
                ]);
                if ($msg) (new SmsService)->send($patient->contact, $msg, $patient->id, 'appointment_booking');
            }
            if ($patient?->email) {
                (new EmailService)->send($patient->email, new AppointmentConfirmationMail(
                    $patient->name,
                    \App\Models\Setting::getSettings()->clinic_name ?? 'the clinic',
                    $scheduledAt->format('M d, Y'),
                    $scheduledAt->format('h:i A'),
                    $this->title,
                ));
            }
        }

        $this->closeModal();
    }

    public function editAppointment($id)
    {
        $this->editingAppointmentId = $id;
        $appointment = Appointments::withTrashed()->with('patient')->findOrFail($id);
        $this->patient_id = $appointment->patient_id;
        $this->selectedPatientName = $appointment->patient->name;
        $this->title = $appointment->title;
        $this->recall_category = $appointment->recall_category;
        $this->reminder_channel = $appointment->reminder_channel ?? 'whatsapp';
        $this->notes = $appointment->notes;
        $this->scheduled_at = Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i');
        $this->isEditModalOpen = true;
    }

    public function restoreAppointment($id) { Appointments::onlyTrashed()->findOrFail($id)->restore(); $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Restored.']); }

    public function closeModal() 
    { 
        $this->resetForm(); 
        $this->isEditModalOpen = false; 
        $this->editingAppointmentId = null; 
        $this->newAppointmentStatus = 'Pending';
    }

    public function resetForm() { $this->reset(['title', 'recall_category', 'patient_id', 'scheduled_at', 'notes', 'selectedPatientName', 'patientSearch']); $this->reminder_channel = 'whatsapp'; $this->newAppointmentStatus = 'Pending'; }

    private function flashDailyLimitWarning(Carbon $scheduledAt): void
    {
        $limit = max(1, (int) $this->dailyAppointmentLimit);
        $bookedCount = Appointments::whereDate('scheduled_at', $scheduledAt->toDateString())->count();

        if ($bookedCount >= $limit) {
            $message = "Daily appointment limit reached for {$scheduledAt->format('M d, Y')} ({$bookedCount}/{$limit}).";

            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => $message,
            ]);
        }
    }

    private function noShowStatsFor(Collection $appointments): array
    {
        $patientIds = $appointments->pluck('patient_id')->filter()->unique();

        if ($patientIds->isEmpty()) {
            return [];
        }

        return Appointments::whereIn('patient_id', $patientIds)
            ->where('scheduled_at', '<', now())
            ->get(['patient_id', 'status'])
            ->groupBy('patient_id')
            ->map(function ($rows) {
                $total = $rows->count();
                $missed = $rows->where('status', 'Missed')->count();
                $rate = $total > 0 ? round(($missed / $total) * 100) : 0;
                $class = $rate >= 30 ? 'danger' : ($rate >= 15 ? 'warning' : 'success');

                return compact('total', 'missed', 'rate', 'class');
            })
            ->all();
    }

    public function setQuickFilter(string $filter): void
    {
        $this->quickFilter = ($this->quickFilter === $filter) ? '' : $filter;
        $this->resetPage();
    }

    public function confirmAppointment(int $id): void
    {
        Appointments::findOrFail($id)->update(['status' => 'Confirmed']);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Appointment confirmed.']);
    }

    public function openCancelModal(int $id): void
    {
        $this->cancellingId      = $id;
        $this->cancelReason      = '';
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal(): void
    {
        $this->cancellingId      = null;
        $this->cancelReason      = '';
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelAppointment(): void
    {
        if (!$this->cancellingId) return;
        $apt = Appointments::findOrFail($this->cancellingId);
        $apt->update([
            'status' => 'Cancelled',
            'notes'  => trim($apt->notes . "\n[Cancelled: " . ($this->cancelReason ?: 'No reason given') . "]"),
        ]);
        $this->closeCancelModal();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Appointment cancelled.']);
    }

    public function dragDropReschedule(int $appointmentId, string $newDate): void
    {
        $apt = Appointments::findOrFail($appointmentId);
        $newScheduledAt = Carbon::parse($newDate . ' ' . Carbon::parse($apt->scheduled_at)->format('H:i'));

        if ($newScheduledAt->isPast() && !$newScheduledAt->isToday()) {
            $this->dispatchBrowserEvent('notify', ['type' => 'warning', 'message' => 'Cannot reschedule to a past date.']);
            return;
        }

        $apt->update(['scheduled_at' => $newScheduledAt, 'status' => 'Rescheduled']);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Moved to ' . $newScheduledAt->format('M d, Y') . '.']);
    }

    public function openMissedAction(int $id): void
    {
        $this->missedActionId = $id;
        $this->rescheduleDate = Carbon::tomorrow()->format('Y-m-d');
        $this->rescheduleTime = '09:00';
    }

    public function closeMissedAction(): void
    {
        $this->missedActionId = null;
        $this->rescheduleDate = '';
        $this->rescheduleTime = '09:00';
    }

    public function rescheduleMissed(): void
    {
        if (!$this->missedActionId) return;
        $this->validate([
            'rescheduleDate' => 'required|date|after_or_equal:today',
            'rescheduleTime' => 'required',
        ]);
        $newAt = Carbon::parse($this->rescheduleDate . ' ' . $this->rescheduleTime);
        Appointments::findOrFail($this->missedActionId)->update([
            'scheduled_at' => $newAt,
            'status'       => 'Pending',
            'missed_at'    => null,
        ]);
        $this->closeMissedAction();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Rescheduled to ' . $newAt->format('M d, Y h:i A') . '.']);
    }

    public function resolveMissed(int $id): void
    {
        Appointments::findOrFail($id)->update(['status' => 'Seen']);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Marked as resolved.']);
    }

    public function sendMissedFollowUpSms(int $id): void
    {
        $apt   = Appointments::with('patient')->findOrFail($id);
        $phone = trim($apt->patient->contact ?? '');
        if (!$phone) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'No contact number for this patient.']);
            return;
        }
        $msg    = "Hello {$apt->patient->name}, we noticed you missed your appointment on {$apt->scheduled_at->format('M d, Y')}. Please call us to reschedule.";
        $result = (new SmsService)->send($phone, $msg, $apt->patient->id);
        if ($result['success']) {
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Follow-up SMS sent.']);
        } else {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'SMS failed: ' . ($result['error'] ?? 'Unknown error')]);
        }
    }

    public function render()
    {
        $this->markPastAppointmentsAsMissed();

        $showsCalendar = $this->activeFilter === 'schedule' && $this->scheduleView === 'calendar';

        $appointments = (!in_array($this->activeFilter, ['settings'], true) && !$showsCalendar)
            ? $this->getFilteredQuery()->orderBy('scheduled_at', 'asc')->paginate(10)
            : collect();

        return view('livewire.secretary.appointments-component', [
            'appointments' => $appointments,
            'calendarWeeks' => $this->calendarWeeks,
            'calendarAppointments' => $this->calendarAppointments,
            'dayTimeSlots' => $this->dayTimeSlots,
            'dayAppointments' => $this->dayAppointments,
            'statusSummary' => $this->statusSummary,
            'noShowStats' => !in_array($this->activeFilter, ['settings'], true) && !$showsCalendar ? $this->noShowStatsFor($appointments->getCollection()) : [],
            'searchablePatients' => (strlen($this->patientSearch) >= 2)
                ? Patient::where('name', 'like', '%' . $this->patientSearch . '%')
                    ->orWhere('contact', 'like', '%' . $this->patientSearch . '%')
                    ->orWhere('pxnumber', 'like', '%' . $this->patientSearch . '%')
                    ->take(7)->get()
                : []
        ])->layout('layouts.secretary.secretary-layout');
    }
}
