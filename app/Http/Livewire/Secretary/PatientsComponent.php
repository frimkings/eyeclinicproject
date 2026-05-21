<?php

namespace App\Http\Livewire\Secretary;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PatientsComponent extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Form State Properties
    public $state = [];
    public $nameSearch = ''; // Autocomplete for the registration form
    public $suggestions = []; 
    
    // UI State Properties
    public $isEditing = false;
    public $activeTab = 'today'; 
    public $birthdaysTodayCount = 0;
    
    // Unified Table Search Property
    public $pxSearch = ''; // Single input for Name or PX Number
    public $fromDate = null;
    public $toDate = null;

    // Bulk Action Properties
    public $selectedPatients = [];
    public $selectAll = false;

    // CSV Import
    public $showImportPanel = false;
    public $importFile = null;
    public $importResults = null;

    public function mount()
    {

      if (!Auth::user()->hasAnyRole(['Secretary', 'Super Admin'])) {
        return redirect()->route('dashboard')->with('error', 'Access Denied: You do not have the required permissions for the Registry Hub.');
    }
        $this->resetForm();
        $this->setDefaultDates();
    }

    public function setDefaultDates()
    {
        $this->fromDate = Carbon::today()->startOfMonth()->toDateString();
        $this->toDate = Carbon::today()->toDateString();
    }

    /**
     * Resets pagination when the unified search query changes
     */
    public function updatedPxSearch()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatingPage()
    {
        $this->clearSelection();
    }

    public function resetFilters()
    {
        $this->pxSearch = '';
        $this->setDefaultDates();
        $this->resetPage();
        $this->clearSelection();
    }

    public function getGenderStatsProperty()
    {
        $query = $this->applyFilters(Patient::query());
        
        return [
            'male' => (clone $query)->where('gender', 'Male')->count(),
            'female' => (clone $query)->where('gender', 'Female')->count(),
            'other' => (clone $query)->where('gender', 'Other')->count(),
        ];
    }

    public function generateWhatsAppLink($name, $contact)
    {
        $phone = $this->formatPhoneForWhatsApp($contact);
        $message = "Hello " . $name . ", this is Vision Space Eye Center.";
        return "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($message);
    }

    public function generateBirthdayWhatsAppLink($name, $contact)
    {
        $phone = $this->formatPhoneForWhatsApp($contact);
        $message = "Happy Birthday, " . $name . "! 🎂 On behalf of the entire team at Vision Space Eye Center, we wish you a very Happy Birthday. Wishing you a healthy and happy year ahead!";
        return "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($message);
    }

    public function generateCallLink($contact)
    {
        $phone = $this->formatPhoneForCall($contact);
        return $phone ? 'tel:' . $phone : '#';
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPatients = $this->applyFilters(Patient::query())
                ->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedPatients = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedPatients = [];
        $this->selectAll = false;
    }

    public function archiveSelected()
    {
        Patient::whereIn('id', $this->selectedPatients)->delete();
        $this->clearSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Selected records archived!']);
    }

    public function exportRegistry()
    {
        $query = $this->applyFilters(Patient::query());
        return $this->downloadCSV($query->get(), 'Patient_Registry_Report');
    }

    public function exportSelected()
    {
        if (empty($this->selectedPatients)) return;
        $patients = Patient::whereIn('id', $this->selectedPatients)->get();
        return $this->downloadCSV($patients, 'Selected_Patients_Export');
    }

    private function downloadCSV($patients, $filename)
    {
        $fileName = $filename . '_' . date('Y-m-d') . '.csv';
        $callback = function() use($patients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['PX Number', 'Name', 'Email', 'Contact', 'DOB', 'Gender', 'Civil Status', 'Occupation', 'Address', 'Registered Date']);
            
            foreach ($patients as $px) {
                fputcsv($file, [
                    $px->pxnumber, 
                    $px->name, 
                    $px->email, 
                    $px->contact, 
                    $px->dob, 
                    $px->gender, 
                    $px->civil_status, 
                    $px->occupation, 
                    $px->address, 
                    $px->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };
        return response()->streamDownload($callback, $fileName);
    }

    public function importCsv()
    {
        $this->validate([
            'importFile' => 'required|file|max:4096|mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel',
        ], [
            'importFile.required' => 'Please choose a CSV file.',
            'importFile.mimetypes' => 'The file must be a valid CSV file.',
            'importFile.max' => 'CSV file must be under 4 MB.',
        ]);

        $handle = fopen($this->importFile->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $data = $this->mapImportRow($header, $row);

            $payload = [
                'name' => trim((string) ($data['name'] ?? $data['full_name'] ?? '')),
                'email' => trim((string) ($data['email'] ?? $data['email_address'] ?? '')),
                'contact' => trim((string) ($data['contact'] ?? $data['phone'] ?? $data['phone_number'] ?? '')),
                'dob' => $this->normalizeImportDate($data['dob'] ?? $data['birthday'] ?? $data['date_of_birth'] ?? null),
                'gender' => $this->normalizeGender($data['gender'] ?? ''),
                'civil_status' => trim((string) ($data['civil_status'] ?? $data['marital_status'] ?? '')),
                'occupation' => trim((string) ($data['occupation'] ?? '')),
                'address' => trim((string) ($data['address'] ?? '')),
            ];

            if ($payload['name'] === '' && $payload['contact'] === '') {
                $skipped++;
                continue;
            }

            $validator = Validator::make($payload, [
                'name' => 'required|string|max:255',
                'contact' => 'required|string|max:50',
                'email' => 'nullable|email|max:255',
                'dob' => 'required|date|date_format:Y-m-d|before:today',
                'gender' => 'required|in:Male,Female,Other',
                'address' => 'required|string|max:1000',
                'occupation' => 'nullable|string|max:255',
                'civil_status' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                $errors[] = "Row {$rowNum}: " . $validator->errors()->first();
                $skipped++;
                continue;
            }

            if (Patient::where('contact', $payload['contact'])->where('name', $payload['name'])->exists()) {
                $skipped++;
                continue;
            }

            Patient::create(array_merge($validator->validated(), [
                'pxnumber' => 'PX-' . mt_rand(1000, 9999) . '-' . date('y'),
                'user_id' => Auth::id(),
            ]));
            $imported++;
        }

        fclose($handle);

        $this->importFile = null;
        $this->importResults = compact('imported', 'skipped', 'errors');
        $this->showImportPanel = true;
        $this->clearSelection();
        $this->resetPage();
    }

    public function clearImport()
    {
        $this->importFile = null;
        $this->importResults = null;
        $this->showImportPanel = false;
        $this->resetValidation('importFile');
    }

    public function downloadTemplate()
    {
        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'contact', 'dob', 'gender', 'civil_status', 'occupation', 'address']);
            fputcsv($file, ['Ama Patient', 'ama@example.com', '0244123456', '1995-06-20', 'Female', 'Single', 'Teacher', 'Kumasi']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'patients_import_template.csv');
    }

    public function resetForm()
    {
        $this->state = [
            'id' => null, 'pxnumber' => '', 'name' => '', 'contact' => '', 'email' => '', 
            'dob' => '', 'gender' => '', 'address' => '', 'occupation' => '',
            'civil_status' => ''
        ];
        $this->nameSearch = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function saveEntry()
    {
        $this->state['name'] = $this->nameSearch;
        $validatedData = Validator::make($this->state, [
            'name' => 'required|string|max:255',
            'contact' => 'required',
            'email' => 'nullable|email',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required',
            'occupation' => 'nullable',
            'civil_status' => 'nullable',
        ])->validate();

        if ($this->isEditing) {
            Patient::findOrFail($this->state['id'])->update($validatedData);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Profile Updated!']);
        } else {
            $validatedData['pxnumber'] = 'PX-' . mt_rand(1000, 9999) . '-' . date('y');
            $validatedData['user_id'] = Auth::id();
            Patient::create($validatedData);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'New Patient Saved!']);
        }
        $this->resetForm();
    }

    /**
     * Applies the Unified Search Logic
     */
    private function applyFilters($query)
    {
        // One input searches across two columns
        if ($this->pxSearch) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->pxSearch}%")
                  ->orWhere('pxnumber', 'like', "%{$this->pxSearch}%");
            });
        }
        
        // Date filter is ignored if a search string is present to allow finding old records
        if (!$this->pxSearch) {
            if ($this->fromDate && $this->toDate) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->fromDate)->startOfDay(), 
                    Carbon::parse($this->toDate)->endOfDay()
                ]);
            }
        }

        if ($this->activeTab === 'birthdays') {
            $query->whereMonth('dob', Carbon::now()->month)->whereDay('dob', Carbon::now()->day);
        }
        return $query;
    }

    private function mapImportRow($header, array $row): array
    {
        if (!is_array($header) || empty($header)) {
            return [];
        }

        $mapped = [];
        foreach ($header as $index => $column) {
            $key = strtolower(trim((string) $column));
            $key = str_replace([' ', '-', '.'], '_', $key);
            $mapped[$key] = $row[$index] ?? null;
        }

        return $mapped;
    }

    private function normalizeImportDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeGender($value): string
    {
        $value = strtolower(trim((string) $value));

        return match ($value) {
            'm', 'male' => 'Male',
            'f', 'female' => 'Female',
            'other', 'o' => 'Other',
            default => '',
        };
    }

    private function formatPhoneForWhatsApp($contact): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $contact);

        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '233' . substr($phone, 1);
        }

        if (str_starts_with($phone, '233')) {
            return $phone;
        }

        return $phone;
    }

    private function formatPhoneForCall($contact): string
    {
        $phone = preg_replace('/[^0-9+]/', '', (string) $contact);
        return $phone;
    }

    public function edit(Patient $patient)
    {
        $this->state = $patient->toArray();
        $this->nameSearch = $patient->name;
        $this->isEditing = true;
    }

    public function updatedNameSearch($value)
    {
        if (strlen($value) < 2) { $this->suggestions = []; return; }
        $this->suggestions = Patient::where('name', 'like', '%' . $value . '%')->limit(5)->get()->toArray();
    }

    public function selectPatient($id)
    {
        $p = Patient::find($id);
        if ($p) {
            $this->state = $p->toArray();
            $this->nameSearch = $p->name;
            $this->isEditing = true;
            $this->suggestions = [];
        }
    }

    public function render()
    {
        // Birthday count is stable within a day — compute once, cache for the session
        if ($this->birthdaysTodayCount === 0) {
            $this->birthdaysTodayCount = Patient::whereMonth('dob', Carbon::now()->month)
                ->whereDay('dob', Carbon::now()->day)
                ->count();
        }

        $query = $this->applyFilters(Patient::query());
        return view('livewire.secretary.patients-component', [
            'patients' => $query->latest()->paginate(10)
        ])->layout('layouts.secretary.secretary-layout');
    }
}
