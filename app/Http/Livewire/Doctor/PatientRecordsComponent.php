<?php

namespace App\Http\Livewire\Doctor;

use App\Models\Consultations;
use App\Models\Appointments;
use App\Models\Cart;
use App\Models\AuditTrail;
use App\Models\ConsultationNote;
use App\Models\PatientDocument;
use App\Models\Referral;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\CashierPatientClearance;
use App\Models\Product;
use App\Models\Refractions;
use App\Enums\ProductFrequency;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;
use App\Http\Livewire\Traits\HasAppointmentBooking;
use App\Models\Setting;

class PatientRecordsComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    use HasAppointmentBooking;

    protected $paginationTheme = 'bootstrap';

    // Active Tab Management
    public $activeTab = 'history';

    // Search
    public $searchTerm = null;

    // Patient & Clearance
    public $patient;
    public $clearance;
    
    // Consultation state
    public $state = [];
    public $consultation;
    public $consultationID;
    public $isEditMode = false;
    public $clinicalAddendum = '';

    // Product management
    public $availableProducts = [];
    public $selectedProductId;
    public $productQuantity;
    public $lensProducts = [];
    public $productPrice;
    public $productsList = [];
    
    // Product search (INLINE SEARCH)
    public $productSearch = '';
    public $searchResults = [];
    public $selectedProduct = null;
    public $diagnosisSearch = '';
    public $selectedDiagnoses = [];

    // Patient document uploads
    public $documentFiles = [];
    public $documentType = 'fundus_photo';
    public $documentTitle = '';
    public $documentNotes = '';
    public $documentConsultationId = '';
    public $documentUploadKey = 0;
    public $selectedVisitSummaries = [];
    public $consultationHistoryExpanded = false;
    
    // Refraction properties
    public $refractionOD, $refractionOS, $notes, $lensType, $pd;
    public $refractionOD_distance_va, $refractionOD_ADD, $refractionOD_near_va;
    public $refractionOS_distance_va, $refractionOS_ADD, $refractionOS_near_va;
    public $refractionnotes;
    public $checkRefraction;
public $editingAppointmentId = null;
public $isEditingAppointment = false;

    // Frequency & Eye editing
    public $editingFrequencyIndex = null;

    // Appointment section visibility
    public $showAppointmentSection = false;

    // VA notation preference loaded from clinic settings ('6m' or '20ft')
    public string $vaNotation = '6m';

    // ===================================
    // DIAGNOSIS METHODS
    // ===================================

    public function getDiagnosisResultsProperty()
    {
        if (strlen($this->diagnosisSearch) < 2) return [];
        
        return \App\Models\Diagnosis::where('name', 'like', '%' . $this->diagnosisSearch . '%')
            ->limit(10)
            ->get();
    }

    public function addDiagnosis($id, $name)
    {
        if ($this->consultationFieldsLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Diagnosis is locked for this consultation. Addenda remain available.']);
            return;
        }

        if (!collect($this->selectedDiagnoses)->contains('id', $id)) {
            $this->selectedDiagnoses[] = ['id' => $id, 'name' => $name];
        }
        $this->diagnosisSearch = '';
    }

    public function removeDiagnosis($index)
    {
        if ($this->consultationFieldsLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Diagnosis is locked for this consultation. Addenda remain available.']);
            return;
        }

        if (isset($this->selectedDiagnoses[$index])) {
            unset($this->selectedDiagnoses[$index]);
            $this->selectedDiagnoses = array_values($this->selectedDiagnoses);
        }
    }
    
    // ===================================
    // MOUNT & INITIALIZATION
    // ===================================

    public function mount(CashierPatientClearance $clearance)
    {
        if (auth()->user()->hasRole('Doctor')) {
            abort_unless($clearance->patient()->exists(), 403);
        }

        $this->clearance = $clearance;
        $this->patient = $clearance->patient;
        $this->loadAvailableProducts();
        $this->vaNotation = Setting::getSettings()->va_notation ?? '6m';

        // Initialize appointment booking
        $this->initializeAppointmentBooking();
    }
    
    public function getCanStartConsultationProperty()
    {
        if (!$this->clearance) {
            return false;
        }
        
        return $this->clearance->payment_status === 'Paid'
               && $this->clearance->doctor_status === false;
    }

    public function getConsultationFieldsLockedProperty(): bool
    {
        if (!$this->isEditMode || !$this->consultation) {
            return false;
        }

        return (int) $this->consultation->user_id !== (int) Auth::id()
            || $this->consultation->created_at->lt(now()->subHours(24));
    }

    public function getConsultationEditLockReasonProperty(): ?string
    {
        if (!$this->consultationFieldsLocked) {
            return null;
        }

        if ((int) $this->consultation->user_id !== (int) Auth::id()) {
            return 'Only the doctor who created this consultation can edit the clinical fields.';
        }

        return 'Clinical fields are locked 24 hours after the consultation was created.';
    }

    public function switchTab($tab)
    {
        if ($tab === 'refraction' && $this->consultationID) {
            $this->loadRefractionData($this->consultationID);
        }
        
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab === 'consultation') {
            $this->dispatchBrowserEvent('init-odq-select');
            $this->dispatchBrowserEvent('sync-odq-select', [
                'values' => $this->normalizeOdq($this->state['odq'] ?? []),
            ]);
        }

        if ($tab === 'history') {
            $this->dispatchBrowserEvent('render-clinical-trend-charts');
        }
    }

    public function toggleConsultationHistory()
    {
        $this->consultationHistoryExpanded = !$this->consultationHistoryExpanded;
    }

    public function resetForm()
    {
        $this->state = [];
        $this->isEditMode = false;
        $this->consultation = null;
        $this->consultationID = null;
        $this->clinicalAddendum = '';
        $this->resetValidation();
        $this->productsList = [];
        $this->resetProductForm();
        $this->showAppointmentSection = false;
        $this->resetAppointmentForm();
    }

    public function loadAvailableProducts()
    {
        $this->availableProducts = Product::with('category')
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function resetProductForm()
    {
        $this->selectedProductId = null;
        $this->productQuantity = null;
        $this->productPrice = null;
        $this->productSearch = '';
        $this->searchResults = [];
        $this->selectedProduct = null;
        $this->editingFrequencyIndex = null;
        $this->resetValidation(['selectedProductId', 'productQuantity', 'productPrice']);
    }

    public function uploadPatientDocument()
    {
        $this->validate([
            'documentFiles' => 'required|array|min:1|max:10',
            'documentFiles.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'documentType' => 'required|in:fundus_photo,oct,visual_field,referral_letter,other',
            'documentTitle' => 'nullable|string|max:150',
            'documentNotes' => 'nullable|string|max:1000',
            'documentConsultationId' => 'nullable|exists:consultations,id',
        ]);

        $uploadedCount = 0;

        foreach ($this->documentFiles as $index => $file) {
            $path = $file->store('patient-documents/' . $this->patient->id, 'public');
            $baseTitle = $this->documentTitle ?: $this->documentTypeLabel($this->documentType);
            $title = count($this->documentFiles) > 1
                ? $baseTitle . ' ' . ($index + 1)
                : $baseTitle;

            $document = PatientDocument::create([
                'patient_id' => $this->patient->id,
                'consultation_id' => $this->documentConsultationId ?: null,
                'uploaded_by' => Auth::id(),
                'document_type' => $this->documentType,
                'title' => $title,
                'notes' => $this->documentNotes ?: null,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            AuditTrail::record(
                'document.uploaded',
                'Uploaded patient document: ' . $document->title,
                $document,
                [],
                ['document_type' => $document->document_type, 'title' => $document->title],
                $this->patient->id
            );

            $uploadedCount++;
        }

        $this->reset(['documentFiles', 'documentTitle', 'documentNotes', 'documentConsultationId']);
        $this->documentType = 'fundus_photo';
        $this->documentUploadKey++;
        $this->resetValidation(['documentFiles', 'documentFiles.*', 'documentType', 'documentTitle', 'documentNotes', 'documentConsultationId']);

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $uploadedCount . ' document(s) uploaded successfully.']);
    }

    public function deletePatientDocument($documentId)
    {
        abort_unless(Auth::user()?->hasRole('Super Admin'), 403);

        $document = PatientDocument::where('patient_id', $this->patient->id)->findOrFail($documentId);
        $oldValues = $document->only(['document_type', 'title', 'file_path']);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        AuditTrail::record(
            'document.deleted',
            'Deleted patient document: ' . ($oldValues['title'] ?? 'Untitled'),
            null,
            $oldValues,
            [],
            $this->patient->id
        );

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Document deleted successfully.']);
    }

    private function documentTypeLabel($type): string
    {
        return [
            'fundus_photo' => 'Fundus Photo',
            'oct' => 'OCT',
            'visual_field' => 'Visual Field',
            'referral_letter' => 'Referral Letter',
            'other' => 'Other Document',
        ][$type] ?? 'Document';
    }

   
    public function toggleAppointmentSection()
    {
        if ($this->consultationFieldsLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Follow-up appointment changes are locked for this consultation. Addenda remain available.']);
            return;
        }

        $this->showAppointmentSection = !$this->showAppointmentSection;
        
        if ($this->showAppointmentSection && $this->patient) {
            // Auto-prefill patient when opening
            $this->appointmentPatientId = $this->patient->id;
            $this->appointmentSelectedPatientName = $this->patient->name;
            $this->appointmentTitle = 'Follow-up Visit';
            $this->appointmentRecallCategory = 'Routine Review';
            $this->appointmentReminderChannel = 'whatsapp';
        }
    }



 public function bookAppointmentFromConsultation()
{
    if ($this->consultationFieldsLocked) {
        $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Follow-up appointment changes are locked for this consultation.']);
        return;
    }

    // Validate appointment data
    $this->validate([
        'appointmentTitle' => 'required|string|min:3',
        'appointmentRecallCategory' => 'nullable|string|max:100',
        'appointmentScheduledAt' => 'required|date|after_or_equal:today',
        'appointmentPatientId' => 'required|exists:patients,id',
        'appointmentReminderChannel' => 'required|in:none,sms,whatsapp,both',
    ], [
        'appointmentTitle.required' => 'Please enter a reason for the appointment',
        'appointmentScheduledAt.required' => 'Please select a date and time',
        'appointmentScheduledAt.after_or_equal' => 'Appointment date must be today or in the future',
    ]);

    try {
        // Create appointment
        $appointment = \App\Models\Appointments::create([
            'patient_id' => $this->appointmentPatientId,
            'user_id' => Auth::id(),
            'title' => $this->appointmentTitle,
            'recall_category' => $this->appointmentRecallCategory ?: $this->appointmentTitle,
            'scheduled_at' => Carbon::parse($this->appointmentScheduledAt),
            'notes' => $this->appointmentNotes,
            'reminder_channel' => $this->appointmentReminderChannel ?: 'whatsapp',
            'reminder_status' => 'not_sent',
            'status' => 'Pending',
        ]);

        // SUCCESS - Show notification
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Appointment scheduled for ' . Carbon::parse($this->appointmentScheduledAt)->format('M d, Y \a\t h:i A')]);

        // Reset ONLY appointment form fields (NOT entire form)
        $this->appointmentTitle = null;
        $this->appointmentRecallCategory = null;
        $this->appointmentScheduledAt = null;
        $this->appointmentNotes = null;
        $this->appointmentReminderChannel = 'whatsapp';
        $this->appointmentPatientSearch = '';
        $this->appointmentSearchablePatients = [];
        $this->editingAppointmentId = null;
        $this->isEditingAppointment = false;

        // Close the appointment section
        $this->showAppointmentSection = false;

        // Emit event
        $this->emit('appointmentCreated', $appointment->id);

        // Log success
        \Log::info('Appointment created successfully', [
            'appointment_id' => $appointment->id,
            'patient_id' => $this->appointmentPatientId,
        ]);

    } catch (\Exception $e) {
        $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to create appointment: ' . $e->getMessage()]);
        \Log::error('Appointment Booking Error', [
            'error' => $e->getMessage(),
        ]);
    }
    

}

/**
 * Update existing appointment
 * ⭐⭐⭐ GUARANTEED TO STAY ON CONSULTATION TAB ⭐⭐⭐
 */
public function updateAppointment()
{
    if ($this->consultationFieldsLocked) {
        $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Follow-up appointment changes are locked for this consultation.']);
        return;
    }

    // Validate appointment data
    $this->validate([
        'appointmentTitle' => 'required|string|min:3',
        'appointmentRecallCategory' => 'nullable|string|max:100',
        'appointmentScheduledAt' => 'required|date|after_or_equal:today',
        'appointmentPatientId' => 'required|exists:patients,id',
        'appointmentReminderChannel' => 'required|in:none,sms,whatsapp,both',
    ], [
        'appointmentTitle.required' => 'Please enter a reason for the appointment',
        'appointmentScheduledAt.required' => 'Please select a date and time',
        'appointmentScheduledAt.after_or_equal' => 'Appointment date must be today or in the future',
    ]);

    try {
        $appointment = \App\Models\Appointments::findOrFail($this->editingAppointmentId);
        
        // Update appointment
        $appointment->update([
            'patient_id' => $this->appointmentPatientId,
            'title' => $this->appointmentTitle,
            'recall_category' => $this->appointmentRecallCategory ?: $this->appointmentTitle,
            'scheduled_at' => Carbon::parse($this->appointmentScheduledAt),
            'notes' => $this->appointmentNotes,
            'reminder_channel' => $this->appointmentReminderChannel ?: 'whatsapp',
        ]);

        // SUCCESS - Show notification
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Appointment updated for ' . Carbon::parse($this->appointmentScheduledAt)->format('M d, Y \a\t h:i A')]);

        // Reset ONLY appointment form fields (NOT entire form)
        $this->appointmentTitle = null;
        $this->appointmentRecallCategory = null;
        $this->appointmentScheduledAt = null;
        $this->appointmentNotes = null;
        $this->appointmentReminderChannel = 'whatsapp';
        $this->appointmentPatientSearch = '';
        $this->appointmentSearchablePatients = [];
        $this->editingAppointmentId = null;
        $this->isEditingAppointment = false;

        // Close the appointment section
        $this->showAppointmentSection = false;

        // Emit event
        $this->emit('appointmentUpdated', $appointment->id);

        // Log success
        \Log::info('Appointment updated successfully', [
            'appointment_id' => $appointment->id,
        ]);

    } catch (\Exception $e) {
        $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to update appointment: ' . $e->getMessage()]);
        \Log::error('Appointment Update Error', [
            'error' => $e->getMessage(),
        ]);
    }
    
    // ⭐ NO REDIRECT - Stay on current tab
}

/**
 * Cancel appointment editing
 * ⭐ DOES NOT CHANGE activeTab ⭐
 */
public function cancelAppointmentEdit()
{
    $this->appointmentTitle = null;
    $this->appointmentRecallCategory = null;
    $this->appointmentScheduledAt = null;
    $this->appointmentNotes = null;
    $this->appointmentReminderChannel = 'whatsapp';
    $this->appointmentPatientSearch = '';
    $this->appointmentSearchablePatients = [];
    $this->editingAppointmentId = null;
    $this->isEditingAppointment = false;
    $this->showAppointmentSection = false;
   
}





    public function updatedProductSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = Product::with('category')
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('batch_number', 'like', "%{$value}%");
                })
                ->where('quantity', '>', 0)
                ->orderBy('name')
                ->limit(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($productId)
    {
        $product = Product::with('category')->find($productId);
        
        if (!$product) {
            return;
        }

        if ($product->quantity <= 0) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Product out of stock']);
            return;
        }

        $existingIndex = collect($this->productsList)->search(
            fn($item) => $item['product_id'] == $product->id && !$this->isLockedPrescriptionItem($item)
        );

        if ($existingIndex !== false) {
            if (!$this->isLockedPrescriptionItem($this->productsList[$existingIndex])) {
                $currentQty = $this->productsList[$existingIndex]['quantity'];
                $newQty = $currentQty + 1;
                $availableQuantity = $this->availableQuantityForPrescriptionItem($this->productsList[$existingIndex]);
                
                if ($newQty > $availableQuantity) {
                    $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => "Only {$availableQuantity} units available in stock"]);
                    return;
                }

                $this->productsList[$existingIndex]['quantity'] = $newQty;
                $this->productsList[$existingIndex]['total'] = $newQty * $this->productsList[$existingIndex]['price'];
            } else {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Product already sold or dispensed. Cannot modify.']);
            }
        } else {
            $isDrug = $this->isDrugProduct($product);
            $availableQuantity = $this->availableQuantityForPrescriptionItem(['product_id' => $product->id]);

            if ($availableQuantity < 1) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => "Only {$availableQuantity} units available in stock"]);
                return;
            }

            $this->productsList[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'batch_number' => $product->batch_number ?? null,
                'category_name' => $product->category->name ?? null,
                'is_drug' => $isDrug,
                'quantity' => 1,
                'price' => $product->selling_price,
                'total' => $product->selling_price,
                'eye' => $isDrug ? 'Both Eyes' : null,
                'frequency' => null,
                'is_dispensed' => false,
                'purchased' => false,
            ];
        }

        $this->productSearch = '';
        $this->searchResults = [];
    }

    public function clearProductSelection()
    {
        $this->selectedProductId = null;
        $this->selectedProduct = null;
        $this->productPrice = null;
        $this->productSearch = '';
        $this->searchResults = [];
    }

    // ===================================
    // EYE & FREQUENCY EDITING (IN TABLE)
    // ===================================

    public function updateEye($index, $value)
    {
        if (!isset($this->productsList[$index])) return;

        if ($this->isLockedPrescriptionItem($this->productsList[$index])) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This item has already been sold or dispensed.']);
            return;
        }

        if (!$this->isDrugPrescriptionItem($this->productsList[$index])) {
            $this->productsList[$index]['eye'] = null;
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Eye selection is only required for drug prescriptions.']);
            return;
        }

        try {
            $this->productsList[$index]['eye'] = $value;

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Eye updated!']);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Invalid selection.']);
        }
    }

    public function editFrequency($index)
    {
        if (isset($this->productsList[$index])) {
            if ($this->isLockedPrescriptionItem($this->productsList[$index])) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Cannot edit sold or dispensed items']);
                return;
            }

            if (!$this->isDrugPrescriptionItem($this->productsList[$index])) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Eye and frequency are only required for drug prescriptions.']);
                return;
            }
            
            $this->editingFrequencyIndex = $index;
        }
    }

    public function updateFrequency($index, $value)
    {
        if (!isset($this->productsList[$index])) return;

        if ($this->isLockedPrescriptionItem($this->productsList[$index])) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'This item has already been sold or dispensed.']);
            return;
        }

        if (!$this->isDrugPrescriptionItem($this->productsList[$index])) {
            $this->productsList[$index]['frequency'] = null;
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Frequency is only required for drug prescriptions.']);
            return;
        }

        try {
            $this->productsList[$index]['frequency'] = $value;
            $this->editingFrequencyIndex = null;

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Frequency updated!']);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Invalid selection.']);
        }
    }

    public function cancelFrequencyEdit()
    {
        $this->editingFrequencyIndex = null;
    }

    public function getClinicalTrendDataProperty(): array
    {
        $records = Consultations::where('patient_id', $this->clearance->patient_id)
            ->where(function ($query) {
                $query->whereNotNull('IOPOD')
                    ->orWhereNotNull('IOPOS')
                    ->orWhereNotNull('vaOD6m')
                    ->orWhereNotNull('vaOS6m');
            })
            ->orderBy('created_at')
            ->get(['created_at', 'IOPOD', 'IOPOS', 'vaOD6m', 'vaOS6m']);

        $latest = $records->last();
        $hasVaData = $records->contains(fn ($record) =>
            $this->visualAcuityDecimal($record->vaOD6m) !== null
            || $this->visualAcuityDecimal($record->vaOS6m) !== null
        );
        $hasIopData = $records->contains(fn ($record) =>
            !is_null($record->IOPOD) || !is_null($record->IOPOS)
        );

        return [
            'labels' => $records->map(fn ($record) => $record->created_at->format('d M y'))->toArray(),
            'iop' => [
                'od' => $records->map(fn ($record) => is_null($record->IOPOD) ? null : (float) $record->IOPOD)->toArray(),
                'os' => $records->map(fn ($record) => is_null($record->IOPOS) ? null : (float) $record->IOPOS)->toArray(),
            ],
            'va' => [
                'od' => $records->map(fn ($record) => $this->visualAcuityDecimal($record->vaOD6m))->toArray(),
                'os' => $records->map(fn ($record) => $this->visualAcuityDecimal($record->vaOS6m))->toArray(),
                'odRaw' => $records->map(fn ($record) => $record->vaOD6m ?: null)->toArray(),
                'osRaw' => $records->map(fn ($record) => $record->vaOS6m ?: null)->toArray(),
            ],
            'summary' => [
                'visits' => $records->count(),
                'latestDate' => $latest ? $latest->created_at->format('d M, Y') : null,
                'latestIopOd' => $latest && !is_null($latest->IOPOD) ? (float) $latest->IOPOD : null,
                'latestIopOs' => $latest && !is_null($latest->IOPOS) ? (float) $latest->IOPOS : null,
                'latestVaOd' => $latest ? ($latest->vaOD6m ?: null) : null,
                'latestVaOs' => $latest ? ($latest->vaOS6m ?: null) : null,
                'hasVaData' => $hasVaData,
                'hasIopData' => $hasIopData,
                'highIopCount' => $records->filter(fn ($record) =>
                    ((float) $record->IOPOD > 21) || ((float) $record->IOPOS > 21)
                )->count(),
            ],
        ];
    }

    // Returns the VA lookup table for the given notation.
    // '6m'  → 6-metre Snellen (6/6, 6/12 …) + qualitative values
    // '20ft' → 20-foot Snellen (20/20, 20/40 …) + qualitative values
    // 'both' → all entries combined (used internally for converting stored values)
    public static function vaLogMarTable(string $notation = '6m'): array
    {
        $sixMetre = [
            '6/3'   => -0.30, '6/4'   => -0.18, '6/5'   => -0.08,
            '6/6'   =>  0.00, '6/7.5' =>  0.10, '6/9'   =>  0.18,
            '6/12'  =>  0.30, '6/15'  =>  0.40, '6/18'  =>  0.48,
            '6/24'  =>  0.60, '6/30'  =>  0.70, '6/36'  =>  0.78,
            '6/48'  =>  0.90, '6/60'  =>  1.00,
        ];
        $twentyFoot = [
            '20/10'  => -0.30, '20/13'  => -0.18, '20/16'  => -0.08,
            '20/20'  =>  0.00, '20/25'  =>  0.10, '20/30'  =>  0.18,
            '20/40'  =>  0.30, '20/50'  =>  0.40, '20/60'  =>  0.48,
            '20/80'  =>  0.60, '20/100' =>  0.70, '20/120' =>  0.78,
            '20/160' =>  0.90, '20/200' =>  1.00,
        ];
        $qualitative = [
            'CF'  =>  1.70,
            'HM'  =>  2.30,
            'LP'  =>  2.70,
            'NLP' =>  null,
        ];

        return match ($notation) {
            '20ft'  => $twentyFoot + $qualitative,
            'both'  => $sixMetre + $twentyFoot + $qualitative,
            default => $sixMetre + $qualitative,
        };
    }

    private function visualAcuityDecimal($value): ?float
    {
        $value = trim((string) $value);
        if ($value === '') return null;

        $table = self::vaLogMarTable('both');

        // Exact lookup (case-insensitive)
        foreach ($table as $label => $logmar) {
            if (strcasecmp($value, $label) === 0) return $logmar;
        }

        // Already a bare LogMAR number (e.g. "0.30", "-0.18")
        if (is_numeric($value)) return (float) $value;

        // Unlisted Snellen fraction: compute LogMAR = log10(denominator/numerator)
        if (preg_match('/(\d+(?:\.\d+)?)\s*\/\s*(\d+(?:\.\d+)?)/', $value, $m)) {
            $num = (float) $m[1];
            $den = (float) $m[2];
            return ($num > 0 && $den > 0) ? round(log10($den / $num), 2) : null;
        }

        return null;
    }

    public function getEyeDiseaseRiskFlagsProperty(): array
    {
        $records = Consultations::with('diagnoses')
            ->where('patient_id', $this->clearance->patient_id)
            ->latest()
            ->limit(12)
            ->get();

        $latest = $records->first();
        $allText = $this->clinicalTextForRisk($records);
        $diagnosisText = $records->flatMap(fn ($record) => $record->diagnoses->pluck('name'))->implode(' ');
        $latestIopOd = $latest && !is_null($latest->IOPOD) ? (float) $latest->IOPOD : null;
        $latestIopOs = $latest && !is_null($latest->IOPOS) ? (float) $latest->IOPOS : null;
        $latestCdrOd = $this->extractFirstNumber($latest->cdrOD ?? null);
        $latestCdrOs = $this->extractFirstNumber($latest->cdrOS ?? null);
        $highIopCount = $records->filter(fn ($record) =>
            ((float) $record->IOPOD > 21) || ((float) $record->IOPOS > 21)
        )->count();

        return [
            [
                'name' => 'Glaucoma',
                'level' => $this->containsAny($diagnosisText, ['glaucoma'])
                    || $highIopCount > 0
                    || max($latestCdrOd ?? 0, $latestCdrOs ?? 0) >= 0.6
                    || (is_numeric($latestCdrOd) && is_numeric($latestCdrOs) && abs($latestCdrOd - $latestCdrOs) >= 0.2)
                    ? 'warning' : 'low',
                'reasons' => array_values(array_filter([
                    $this->containsAny($diagnosisText, ['glaucoma']) ? 'Glaucoma diagnosis recorded' : null,
                    $highIopCount > 0 ? "{$highIopCount} visit(s) with IOP above 21 mmHg" : null,
                    max($latestCdrOd ?? 0, $latestCdrOs ?? 0) >= 0.6 ? 'Large CDR recorded' : null,
                    (is_numeric($latestCdrOd) && is_numeric($latestCdrOs) && abs($latestCdrOd - $latestCdrOs) >= 0.2) ? 'CDR asymmetry recorded' : null,
                ])),
            ],
            [
                'name' => 'Cataract',
                'level' => $this->containsAny($allText, ['cataract', 'lens opacity', 'nuclear sclerosis', 'cortical opacity', 'posterior subcapsular', 'psc'])
                    ? 'warning' : 'low',
                'reasons' => $this->containsAny($allText, ['cataract', 'lens opacity', 'nuclear sclerosis', 'cortical opacity', 'posterior subcapsular', 'psc'])
                    ? ['Cataract or lens opacity terms found in clinical records']
                    : [],
            ],
            [
                'name' => 'Diabetic Eye Disease',
                'level' => $this->containsAny($allText, ['diabetic', 'diabetes', 'retinopathy', 'npdr', 'pdr', 'macular edema', 'macular oedema', 'dme'])
                    ? 'warning' : 'low',
                'reasons' => $this->containsAny($allText, ['diabetic', 'diabetes', 'retinopathy', 'npdr', 'pdr', 'macular edema', 'macular oedema', 'dme'])
                    ? ['Diabetes or retinopathy terms found in clinical records']
                    : [],
            ],
        ];
    }

    public function getUrgentReferralReasonsProperty(): array
    {
        $text = strtolower(implode(' ', array_filter([
            $this->state['chiefComplaint'] ?? '',
            $this->state['others'] ?? '',
            $this->state['notes'] ?? '',
            implode(' ', $this->normalizeOdq($this->state['odq'] ?? [])),
        ])));

        $rules = [
            'Sudden vision loss' => ['sudden vision loss', 'sudden loss of vision', 'acute vision loss', 'vision loss', 'loss of vision'],
            'Ocular trauma' => ['trauma', 'injury', 'hit eye', 'foreign body', 'chemical injury', 'chemical burn'],
            'Severe eye pain' => ['severe pain', 'severe eye pain', 'painful red eye'],
            'Flashes or floaters' => ['flashes', 'floaters', 'flashes and floaters'],
        ];

        $reasons = [];
        foreach ($rules as $label => $needles) {
            if ($this->containsAny($text, $needles)) {
                $reasons[] = $label;
            }
        }

        $iopOd = $this->state['IOPOD'] ?? null;
        $iopOs = $this->state['IOPOS'] ?? null;
        if ((is_numeric($iopOd) && (float) $iopOd >= 35) || (is_numeric($iopOs) && (float) $iopOs >= 35)) {
            $reasons[] = 'Very high IOP';
        }

        return array_values(array_unique($reasons));
    }

    public function createUrgentReferralDraft()
    {
        $reasons = $this->urgentReferralReasons;

        if (empty($reasons)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'No urgent referral red flag is currently detected.']);
            return;
        }

        $diagnosis = collect($this->selectedDiagnoses)->pluck('name')->filter()->implode(', ');

        $referral = Referral::create([
            'letter_type' => 'referral',
            'referred_by' => Auth::id(),
            'patient_id' => $this->patient->id,
            'referral_to' => 'Ophthalmologist / Emergency Eye Unit',
            'referral_date' => now()->toDateString(),
            'patient_name' => $this->patient->name,
            'patient_age_sex' => trim(($this->patient->age ?? 'N/A') . ' / ' . ($this->patient->gender ?? 'N/A')),
            'patient_contact' => $this->patient->contact,
            'complaint' => $this->state['chiefComplaint'] ?? null,
            'va_od' => $this->state['vaOD6m'] ?? null,
            'va_os' => $this->state['vaOS6m'] ?? null,
            'anterior_segment' => trim(implode(' | ', array_filter([
                $this->state['corneaOD'] ?? null,
                $this->state['corneaOS'] ?? null,
                $this->state['lensOD'] ?? null,
                $this->state['lensOS'] ?? null,
            ]))),
            'posterior_segment' => trim(implode(' | ', array_filter([
                $this->state['fundusOD'] ?? null,
                $this->state['fundusOS'] ?? null,
                $this->state['cdrOD'] ?? null,
                $this->state['cdrOS'] ?? null,
            ]))),
            'iop' => trim('OD: ' . ($this->state['IOPOD'] ?? 'N/A') . ', OS: ' . ($this->state['IOPOS'] ?? 'N/A')),
            'diagnosis' => $diagnosis ?: null,
            'reason_for_referral' => 'URGENT: ' . implode(', ', $reasons),
            'management' => $this->state['notes'] ?? null,
            'status' => 'draft',
        ]);

        AuditTrail::record(
            'referral.urgent_draft_created',
            'Created urgent referral draft #' . $referral->id,
            $referral,
            [],
            ['reasons' => $reasons],
            $this->patient->id
        );

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Urgent referral draft created. Open Referrals to complete or print it.']);
    }

    private function clinicalTextForRisk($records): string
    {
        return strtolower($records->map(function ($record) {
            return implode(' ', array_filter([
                $record->chiefComplaint,
                $record->others,
                implode(' ', $record->odq ?? []),
                $record->lensOD,
                $record->lensOS,
                $record->fundusOD,
                $record->fundusOS,
                $record->cdrOD,
                $record->cdrOS,
                $record->notes,
                $record->diagnoses->pluck('name')->implode(' '),
            ]));
        })->implode(' '));
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        return Str::contains(strtolower($haystack), array_map('strtolower', $needles));
    }

    private function extractFirstNumber($value): ?float
    {
        if (preg_match('/\d+(?:\.\d+)?/', (string) $value, $matches)) {
            return (float) $matches[0];
        }

        return null;
    }

    // ===================================
    // PRESCRIPTION MANAGEMENT
    // ===================================

    public function removeProduct($index)
    {
        if (isset($this->productsList[$index])) {
            if ($this->isLockedPrescriptionItem($this->productsList[$index])) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Cannot remove sold or dispensed items']);
                return;
            }
            
            unset($this->productsList[$index]);
            $this->productsList = array_values($this->productsList);
        }
    }

    public function updateProductQuantity($index, $newQuantity)
    {
        if (!isset($this->productsList[$index])) {
            return;
        }

        if ($this->isLockedPrescriptionItem($this->productsList[$index])) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Cannot edit sold or dispensed items']);
            return;
        }

        $newQuantity = max(1, (int) $newQuantity);

        $availableQuantity = $this->availableQuantityForPrescriptionItem($this->productsList[$index]);

        if ($newQuantity > $availableQuantity) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => "Only {$availableQuantity} units available in stock"]);
            return;
        }

        $this->productsList[$index]['quantity'] = $newQuantity;
        $this->productsList[$index]['total'] = $newQuantity * $this->productsList[$index]['price'];
    }

    public function clearPrescription()
    {
        $this->productsList = array_values(
            array_filter($this->productsList, fn($item) => $this->isLockedPrescriptionItem($item))
        );
        
        $this->resetProductForm();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Prescription cleared (dispensed items kept)']);
    }

    public function savePrescription()
    {
        if (!$this->consultationID) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Please create or select a consultation first before saving prescription']);
            return;
        }

        if (empty($this->productsList)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Please add at least one product to the prescription']);
            return;
        }

        if (!$this->validatePrescriptionItems()) {
            return;
        }

        DB::beginTransaction();
        try {
            $consultation = Consultations::findOrFail($this->consultationID);

            $existingCartIds = Cart::where('consultation_id', $this->consultationID)->pluck('id')->toArray();
            $updatedCartIds = [];

            foreach ($this->productsList as $index => $productItem) {
                $isDrug = $this->isDrugPrescriptionItem($productItem);

                if (isset($productItem['cart_id'])) {
                    $cartItem = Cart::find($productItem['cart_id']);
                    if ($cartItem && !$cartItem->is_dispensed && !$cartItem->purchased) {
                        $cartItem->update([
                            'quantity' => $productItem['quantity'],
                            'price' => $productItem['price'],
                            'total' => $productItem['total'],
                            'eye' => $isDrug ? ($productItem['eye'] ?? 'Both Eyes') : null,
                            'frequency' => $isDrug ? ($productItem['frequency'] ?? null) : null,
                        ]);
                        $updatedCartIds[] = $cartItem->id;
                    } elseif ($cartItem && ($cartItem->is_dispensed || $cartItem->purchased)) {
                        $updatedCartIds[] = $cartItem->id;
                    }
                } else {
                    $newCartItem = Cart::create([
                        'patient_id' => $this->clearance->patient_id,
                        'dispensed_by' => Auth::id(),
                        'consultation_id' => $this->consultationID,
                        'product_id' => $productItem['product_id'],
                        'quantity' => $productItem['quantity'],
                        'price' => $productItem['price'],
                        'total' => $productItem['total'],
                        'eye' => $isDrug ? ($productItem['eye'] ?? 'Both Eyes') : null,
                        'frequency' => $isDrug ? ($productItem['frequency'] ?? null) : null,
                        'status' => 'pending',
                        'is_dispensed' => false,
                        'purchased' => false,
                    ]);
                    
                    $this->productsList[$index]['cart_id'] = $newCartItem->id;
                    $updatedCartIds[] = $newCartItem->id;
                }
            }

            $cartItemsToDelete = array_diff($existingCartIds, $updatedCartIds);
            Cart::whereIn('id', $cartItemsToDelete)
                ->where('is_dispensed', false)
                ->where('purchased', false)
                ->delete();

            $consultation->update([
                'prescribed_products' => $this->productsList,
            ]);

            AuditTrail::record(
                'prescription.updated',
                'Updated prescription for Consultation #' . $consultation->id,
                $consultation,
                [],
                ['items' => count($this->productsList), 'total' => $this->calculateTotal()],
                $this->patient->id
            );

            DB::commit();

            $this->reloadProductsListFromDatabase();

            $dispensedCount = collect($this->productsList)->where('is_dispensed', true)->count();
            $msg = $dispensedCount > 0
                ? 'Prescription saved. ' . $dispensedCount . ' item(s) already dispensed by pharmacy.'
                : 'Prescription saved successfully to Consultation #' . $this->consultationID;
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $msg]);

            $this->resetProductForm();
            $this->activeTab = 'refraction';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to save prescription: ' . $e->getMessage()]);
        }
    }

    private function reloadProductsListFromDatabase()
    {
        if (!$this->consultationID) {
            return;
        }

        $consultation = Consultations::with('cartItems.product.category', 'user', 'patient')->find($this->consultationID);
        
        if ($consultation && $consultation->cartItems) {
            $cartItemIds = $consultation->cartItems->pluck('id')->toArray();
            $refundedCartIds = [];
            if (!empty($cartItemIds)) {
                $refundedCartIds = \App\Models\SaleItem::whereIn('cart_id', $cartItemIds)
                    ->whereHas('sale', fn($q) => $q->where('is_refunded', true))
                    ->pluck('cart_id')
                    ->flip()
                    ->toArray();
            }

            $this->productsList = $consultation->cartItems->map(function($cartItem) use ($refundedCartIds) {
                $isDrug = $this->isDrugProduct($cartItem->product);

                return [
                    'cart_id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'name' => $cartItem->product->name,
                    'batch_number' => $cartItem->product->batch_number ?? null,
                    'category_name' => $cartItem->product->category->name ?? null,
                    'is_drug' => $isDrug,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => ($cartItem->is_dispensed || $cartItem->purchased) ? 0 : $cartItem->total,
                    'eye' => $isDrug ? ($cartItem->eye ?? 'Both Eyes') : null,
                    'frequency' => $isDrug ? $cartItem->frequency : null,
                    'is_dispensed' => $cartItem->is_dispensed,
                    'purchased' => $cartItem->purchased,
                    'dispensed_at' => $cartItem->dispensed_at,
                    'status' => $cartItem->status,
                    'is_refunded' => isset($refundedCartIds[$cartItem->id]),
                ];
            })->toArray();
        }
    }

    public function refreshPrescriptionStatus()
    {
        $this->reloadProductsListFromDatabase();
        
        $dispensedCount = collect($this->productsList)->where('is_dispensed', true)->count();
        $heldCount = collect($this->productsList)->where('purchased', true)->where('is_dispensed', false)->count();
        $pendingCount = collect($this->productsList)->where('is_dispensed', false)->where('purchased', false)->count();

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Status refreshed: {$dispensedCount} dispensed, {$heldCount} on hold, {$pendingCount} pending"]);
    }

    public function calculateTotal()
    {
        return collect($this->productsList)
            ->reject(fn ($item) => $this->isLockedPrescriptionItem($item))
            ->sum(fn ($item) => (float) ($item['total'] ?? 0));
    }

    // ===================================
    // CONSULTATION MANAGEMENT
    // ===================================

    public function startNewConsultation()
    {
        if (!$this->clearance || $this->clearance->payment_status !== 'Paid') {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Payment required before creating consultation']);
            return;
        }

        $this->resetForm();
        $this->activeTab = 'consultation';
        $this->dispatchBrowserEvent('init-odq-select');
        $this->dispatchBrowserEvent('sync-odq-select', ['values' => []]);
    }

    public function editConsultation($consultationId)
    {
        $this->consultation = Consultations::with(['cartItems.product.category', 'diagnoses', 'addenda.user'])->findOrFail($consultationId);
        $this->consultationID = $this->consultation->id;
        $this->isEditMode = true;
        $this->clinicalAddendum = '';

        $this->state = $this->consultation->toArray();

        $editCartIds = $this->consultation->cartItems->pluck('id')->toArray();
        $editRefundedCartIds = [];
        if (!empty($editCartIds)) {
            $editRefundedCartIds = \App\Models\SaleItem::whereIn('cart_id', $editCartIds)
                ->whereHas('sale', fn($q) => $q->where('is_refunded', true))
                ->pluck('cart_id')
                ->flip()
                ->toArray();
        }

        $this->productsList = $this->consultation->cartItems->map(function($cartItem) use ($editRefundedCartIds) {
            $isDrug = $this->isDrugProduct($cartItem->product);

            return [
                'cart_id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'name' => $cartItem->product->name,
                'batch_number' => $cartItem->product->batch_number ?? null,
                'category_name' => $cartItem->product->category->name ?? null,
                'is_drug' => $isDrug,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total' => ($cartItem->is_dispensed || $cartItem->purchased) ? 0 : $cartItem->total,
                'eye' => $isDrug ? ($cartItem->eye ?? 'Both Eyes') : null,
                'frequency' => $isDrug ? $cartItem->frequency : null,
                'is_dispensed' => $cartItem->is_dispensed,
                'purchased' => $cartItem->purchased,
                'dispensed_at' => $cartItem->dispensed_at,
                'status' => $cartItem->status,
                'is_refunded' => isset($editRefundedCartIds[$cartItem->id]),
            ];
        })->toArray();

        $this->selectedDiagnoses = $this->consultation->diagnoses->map(function($diag) {
            return [
                'id' => $diag->id,
                'name' => $diag->name
            ];
        })->toArray();

        $this->activeTab = 'consultation';
        $this->dispatchBrowserEvent('init-odq-select');
        $this->dispatchBrowserEvent('sync-odq-select', [
            'values' => $this->normalizeOdq($this->state['odq'] ?? []),
        ]);
    }

    private function validateConsultation()
    {
        return Validator::make([
            'chiefComplaint' => $this->state['chiefComplaint'] ?? null,
            'IOPOD'          => $this->state['IOPOD'] ?? null,
            'IOPOS'          => $this->state['IOPOS'] ?? null,
            'diagnoses'      => $this->selectedDiagnoses,
        ], [
            'chiefComplaint' => 'required|string',
            'IOPOD'          => 'nullable|numeric|min:0|max:80',
            'IOPOS'          => 'nullable|numeric|min:0|max:80',
            'diagnoses'      => 'required|array|min:1',
        ])->validate();
    }

    private function isDrugProduct(?Product $product): bool
    {
        if (!$product || !$product->category) {
            return false;
        }

        return in_array(strtolower(trim($product->category->name)), ['drug', 'drugs'], true);
    }

    private function isDrugPrescriptionItem(array $item): bool
    {
        if (array_key_exists('is_drug', $item)) {
            return (bool) $item['is_drug'];
        }

        $categoryName = strtolower(trim((string) ($item['category_name'] ?? '')));

        if ($categoryName !== '') {
            return in_array($categoryName, ['drug', 'drugs'], true);
        }

        $product = Product::with('category')->find($item['product_id'] ?? null);

        return $this->isDrugProduct($product);
    }

    private function isLockedPrescriptionItem(array $item): bool
    {
        return (bool) ($item['is_dispensed'] ?? false) || (bool) ($item['purchased'] ?? false);
    }

    private function availableQuantityForPrescriptionItem(array $item): int
    {
        $product = Product::find($item['product_id'] ?? null);

        if (!$product) {
            return 0;
        }

        return max(0, (int) $product->quantity);
    }

    private function validatePrescriptionItems(): bool
    {
        foreach ($this->productsList as $item) {
            if (!$this->isLockedPrescriptionItem($item) && $this->isDrugPrescriptionItem($item)) {
                if (empty($item['frequency']) || empty($item['eye'])) {
                    $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Please set eye and frequency for all drug prescription items']);
                    return false;
                }
            }
        }

        return true;
    }

    private function normalizeOdq($odq): array
    {
        if (is_string($odq)) {
            $odq = array_filter(array_map('trim', explode(',', $odq)));
        }

        if (!is_array($odq)) {
            return [];
        }

        return collect($odq)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique(fn ($item) => strtolower($item))
            ->values()
            ->toArray();
    }
    
    public function createConsultation()
    {
        $validatedData = $this->validateConsultation();

        if (!$this->validatePrescriptionItems()) {
            return;
        }

        DB::beginTransaction();
        try {
            $consultationData = [
                'clearance_id' => $this->clearance->id,
                'patient_id'   => $this->clearance->patient_id,
                'user_id'      => Auth::id(),
                'chiefComplaint' => $validatedData['chiefComplaint'],
                'IOPOD'          => $validatedData['IOPOD'],
                'IOPOS'          => $validatedData['IOPOS'],
                'prescribed_products' => $this->productsList,
                'others'        => $this->state['others'] ?? null,
                'odq'           => $this->normalizeOdq($this->state['odq'] ?? []),
                'vaOD6m'        => $this->state['vaOD6m'] ?? null,
                'vaOS6m'        => $this->state['vaOS6m'] ?? null,
                'lidsOD'        => $this->state['lidsOD'] ?? null,
                'lidsOS'        => $this->state['lidsOS'] ?? null,
                'conjunctivaOD' => $this->state['conjunctivaOD'] ?? null,
                'conjunctivaOS' => $this->state['conjunctivaOS'] ?? null,
                'corneaOD'      => $this->state['corneaOD'] ?? null,
                'corneaOS'      => $this->state['corneaOS'] ?? null,
                'irisOD'        => $this->state['irisOD'] ?? null,
                'irisOS'        => $this->state['irisOS'] ?? null,
                'pupilOD'       => $this->state['pupilOD'] ?? null,
                'pupilOS'       => $this->state['pupilOS'] ?? null,
                'lensOD'        => $this->state['lensOD'] ?? null,
                'lensOS'        => $this->state['lensOS'] ?? null,
                'vitreousOD'    => $this->state['vitreousOD'] ?? null,
                'vitreousOS'    => $this->state['vitreousOS'] ?? null,
                'fundusOD'      => $this->state['fundusOD'] ?? null,
                'fundusOS'      => $this->state['fundusOS'] ?? null,
                'cdrOD'         => $this->state['cdrOD'] ?? null,
                'cdrOS'         => $this->state['cdrOS'] ?? null,
                'notes'         => $this->state['notes'] ?? null,
                'drugs'         => $this->state['drugs'] ?? null,
            ];

            $consultation = Consultations::create($consultationData);
            
            $diagnosisIds = collect($this->selectedDiagnoses)->pluck('id')->toArray();
            $consultation->diagnoses()->sync($diagnosisIds);
            
            $this->consultationID = $consultation->id;

            foreach ($this->productsList as $productItem) {
                $isDrug = $this->isDrugPrescriptionItem($productItem);

                $cartItem = Cart::create([
                    'patient_id' => $this->clearance->patient_id,
                    'dispensed_by' => Auth::id(),
                    'consultation_id' => $consultation->id,
                    'product_id' => $productItem['product_id'],
                    'quantity' => $productItem['quantity'],
                    'price' => $productItem['price'],
                    'total' => $productItem['total'],
                    'eye' => $isDrug ? ($productItem['eye'] ?? 'Both Eyes') : null,
                    'frequency' => $isDrug ? ($productItem['frequency'] ?? null) : null,
                    'status' => 'pending',
                    'is_dispensed' => false,
                    'purchased' => false,
                ]);

                foreach ($this->productsList as $index => $item) {
                    if ((int) ($item['product_id'] ?? 0) === (int) $productItem['product_id']
                        && empty($this->productsList[$index]['cart_id'])) {
                        $this->productsList[$index]['cart_id'] = $cartItem->id;
                        break;
                    }
                }
            }

            $consultation->update(['prescribed_products' => $this->productsList]);

            $this->clearance->update(['doctor_status' => true]);

            AuditTrail::record(
                'consultation.created',
                'Created consultation #' . $consultation->id,
                $consultation,
                [],
                ['chiefComplaint' => $consultation->chiefComplaint, 'items' => count($this->productsList)],
                $this->patient->id
            );

            DB::commit();

            $this->checkAndUpdateClearance();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Consultation saved successfully.']);

            $savedConsultationId = $consultation->id;
            $this->resetForm();
            $this->consultationID = $savedConsultationId;  // preserve so Prescription tab works
            $this->activeTab = 'prescription';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to create consultation: ' . $e->getMessage()]);
            \Log::error('Consultation error: ' . $e->getMessage());
        }
    }

    public function updateConsultation()
    {
        if ($this->consultationFieldsLocked) {
            $this->addClinicalAddendum();
            return;
        }

        $validatedData = $this->validateConsultation();

        if (!$this->validatePrescriptionItems()) {
            return;
        }

        DB::beginTransaction();
        try {
            // Strip relationship/ownership keys — never allow mass-overwrite of these
            $safeState = array_diff_key($this->state, array_flip([
                'patient_id', 'user_id', 'clearance_id', 'id', 'created_at', 'updated_at',
            ]));

            $updateData = array_merge($safeState, [
                'chiefComplaint' => $validatedData['chiefComplaint'],
                'IOPOD'          => $validatedData['IOPOD'],
                'IOPOS'          => $validatedData['IOPOS'],
                'odq'            => $this->normalizeOdq($this->state['odq'] ?? []),
            ]);

            $oldValues = $this->consultation->only([
                'chiefComplaint', 'IOPOD', 'IOPOS', 'vaOD6m', 'vaOS6m', 'notes', 'others', 'odq',
            ]);

            $this->consultation->update($updateData);

            $diagnosisIds = collect($this->selectedDiagnoses)->pluck('id')->toArray();
            $this->consultation->diagnoses()->sync($diagnosisIds);

            $existingCartIds = $this->consultation->cartItems->pluck('id')->toArray();
            $updatedCartIds = [];

            foreach ($this->productsList as $index => $productItem) {
                $isDrug = $this->isDrugPrescriptionItem($productItem);

                if (isset($productItem['cart_id'])) {
                    $cartItem = Cart::find($productItem['cart_id']);
                    
                    if ($cartItem && !$cartItem->is_dispensed && !$cartItem->purchased) {
                        $cartItem->update([
                            'quantity' => $productItem['quantity'],
                            'price' => $productItem['price'],
                            'total' => $productItem['total'],
                            'eye' => $isDrug ? ($productItem['eye'] ?? 'Both Eyes') : null,
                            'frequency' => $isDrug ? ($productItem['frequency'] ?? null) : null,
                        ]);
                        $updatedCartIds[] = $cartItem->id;
                    } elseif ($cartItem && ($cartItem->is_dispensed || $cartItem->purchased)) {
                        $updatedCartIds[] = $cartItem->id;
                    }
                } else {
                    $newCartItem = Cart::create([
                        'patient_id' => $this->clearance->patient_id,
                        'dispensed_by' => Auth::id(),
                        'consultation_id' => $this->consultation->id,
                        'product_id' => $productItem['product_id'],
                        'quantity' => $productItem['quantity'],
                        'price' => $productItem['price'],
                        'total' => $productItem['total'],
                        'eye' => $isDrug ? ($productItem['eye'] ?? 'Both Eyes') : null,
                        'frequency' => $isDrug ? ($productItem['frequency'] ?? null) : null,
                        'status' => 'pending',
                        'is_dispensed' => false,
                        'purchased' => false,
                    ]);
                    $updatedCartIds[] = $newCartItem->id;
                    $this->productsList[$index]['cart_id'] = $newCartItem->id;
                }
            }

            $cartItemsToDelete = array_diff($existingCartIds, $updatedCartIds);
            Cart::whereIn('id', $cartItemsToDelete)
                ->where('is_dispensed', false)
                ->where('purchased', false)
                ->delete();

            $this->consultation->update([
                'prescribed_products' => $this->productsList,
            ]);

            AuditTrail::record(
                'consultation.updated',
                'Updated consultation #' . $this->consultation->id,
                $this->consultation,
                $oldValues,
                [
                    'chiefComplaint' => $this->consultation->chiefComplaint,
                    'IOPOD' => $this->consultation->IOPOD,
                    'IOPOS' => $this->consultation->IOPOS,
                    'items' => count($this->productsList),
                ],
                $this->patient->id
            );

            DB::commit();

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Consultation updated successfully.']);

            $savedConsultationId = $this->consultation->id;
            $this->resetForm();
            $this->consultationID = $savedConsultationId;  // preserve so Prescription tab works
            $this->activeTab = 'prescription';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to update: ' . $e->getMessage()]);
            \Log::error('Consultation Update Error: ' . $e->getMessage());
        }
    }

    private function addClinicalAddendum(): void
    {
        $this->validate([
            'clinicalAddendum' => 'required|string|min:2',
        ]);

        DB::beginTransaction();
        try {
            $note = ConsultationNote::create([
                'consultation_id' => $this->consultation->id,
                'patient_id' => $this->consultation->patient_id,
                'user_id' => Auth::id(),
                'note_type' => 'clinical_addendum',
                'note' => $this->clinicalAddendum,
            ]);

            AuditTrail::record(
                'consultation.addendum_added',
                'Added clinical addendum to consultation #' . $this->consultation->id,
                $note,
                [],
                ['note' => $note->note, 'note_type' => $note->note_type],
                $this->patient->id
            );

            DB::commit();

            $this->consultation->load('addenda.user');
            $this->clinicalAddendum = '';
            $this->resetValidation(['clinicalAddendum']);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Clinical addendum added successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to add clinical addendum: ' . $e->getMessage()]);
        }
    }
    
    private function checkAndUpdateClearance()
    {
        $availableClearance = CashierPatientClearance::where('patient_id', $this->patient->id)
            ->where('payment_status', 'Paid')
            ->where('doctor_status', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($availableClearance) {
            $this->clearance = $availableClearance;
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Consultation saved. Switched to available clearance.']);
        } else {
            $this->clearance->refresh();
            $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => 'Consultation saved. No unused clearance available - tabs locked.']);
        }
    }

    public function cancelAndGoBack()
    {
        $this->resetForm();
        $this->activeTab = 'history';
    }

    // ===================================
    // REFRACTION MANAGEMENT
    // ===================================

    public function loadRefractionData($consultationId)
    {
        $consultation = Consultations::with('refraction')->findOrFail($consultationId);
        $this->consultationID = $consultation->id;
        $this->consultation = $consultation;

        if ($consultation->refraction) {
            $this->state = array_merge($this->state, $consultation->refraction->toArray());
        }

        $this->activeTab = 'refraction';
    }

    public function saveRefraction()
    {
        if ($this->consultationFieldsLocked) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Only the doctor who created this consultation can edit refraction data.']);
            return;
        }

        $this->validate([
            'state.pd' => 'required|numeric',
            'state.lensType' => 'required|string',
            'state.refractionOD' => 'required|string',
            'state.refractionOD_distance_va' => 'required|string',
            'state.refractionOS_distance_va' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $refractionData = [
                'consultation_id' => $this->consultationID,
                'user_id' => Auth::id(),
                'pd' => $this->state['pd'],
                'lensType' => $this->state['lensType'],
                'refractionOD' => $this->state['refractionOD'],
                'refractionOS' => $this->state['refractionOS'] ?? null,
                'refractionOD_distance_va' => $this->state['refractionOD_distance_va'],
                'refractionOD_ADD' => $this->state['refractionOD_ADD'] ?? null,
                'refractionOD_near_va' => $this->state['refractionOD_near_va'] ?? null,
                'refractionOS_distance_va' => $this->state['refractionOS_distance_va'],
                'refractionOS_ADD' => $this->state['refractionOS_ADD'] ?? null,
                'refractionOS_near_va' => $this->state['refractionOS_near_va'] ?? null,
                'refractionnotes' => $this->state['refractionnotes'] ?? null,
            ];

            Refractions::updateOrCreate(
                ['consultation_id' => $this->consultationID],
                $refractionData
            );

            DB::commit();

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Refraction data saved successfully']);
            $this->activeTab = 'history';
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Failed to save refraction: ' . $e->getMessage()]);
        }
    }

    public function printRefraction($consultationId)
    {
        $consultation = Consultations::with(['refraction', 'patient'])->findOrFail($consultationId);
        
        if (!$consultation->refraction) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'No refraction data available']);
            return;
        }

        $html = view('doctor.refraction-print', [
            'consultation' => $consultation,
            'refraction' => $consultation->refraction,
            'patient' => $consultation->patient
        ])->render();

        $this->dispatchBrowserEvent('printRefraction', ['html' => $html]);
    }

    public function updatedStateLensType($value)
    {
        if (!empty($value)) {
            $this->lensProducts = Product::whereHas('category', function($query) {
                $query->where('name', 'Lenses');
            })
            ->where('selling_price', '>', 0)
            ->where('quantity', '>', 0)
            ->get();
        } else {
            $this->lensProducts = [];
        }
    }

    public function selectLensProduct($productId)
    {
        $product = Product::find($productId);
        
        if ($product) {
            $this->selectProduct($productId);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Lens product added to prescription list.']);
        }
    }
    
    public function render()
    {
        $patientRecords = Consultations::where('patient_id', $this->clearance->patient_id)
            ->with(['diagnoses', 'user'])
            ->when($this->searchTerm, fn($query) =>
                $query->where(function ($searchQuery) {
                    $searchQuery->where('chiefComplaint', 'like', "%{$this->searchTerm}%")
                        ->orWhere('notes', 'like', "%{$this->searchTerm}%");
                })
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $searchResults = collect($this->searchResults);

        $patientDocuments = PatientDocument::with(['uploadedBy', 'consultation'])
            ->where('patient_id', $this->patient->id)
            ->latest()
            ->get();

        $auditTrails = AuditTrail::with('user')
            ->where('patient_id', $this->patient->id)
            ->latest()
            ->limit(25)
            ->get();

        $upcomingAppointment = Appointments::where('patient_id', $this->patient->id)
            ->where('scheduled_at', '>=', now())
            ->whereNotIn('status', ['Seen', 'Missed', 'Done', 'Cancelled', 'Canceled'])
            ->orderBy('scheduled_at')
            ->first();

        $clinicalTrendData = $this->clinicalTrendData;
        $eyeDiseaseRiskFlags = $this->eyeDiseaseRiskFlags;

        return view('livewire.doctor.patient-records-component', compact('patientRecords', 'searchResults', 'clinicalTrendData', 'eyeDiseaseRiskFlags', 'patientDocuments', 'auditTrails', 'upcomingAppointment'))
            ->layout('layouts.doctor.doctor-layout');
    }
}
