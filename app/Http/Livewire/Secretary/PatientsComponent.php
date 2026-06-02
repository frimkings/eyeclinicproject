<?php

namespace App\Http\Livewire\Secretary;

use App\Models\AuditTrail;
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

    public $state = [];
    public $nameSearch = '';
    public $suggestions = [];

    public $isEditing = false;
    public $activeTab = 'today';
    public $birthdaysTodayCount = 0;

    public $pxSearch      = '';
    public $fromDate      = null;
    public $toDate        = null;
    public $insurerFilter = '';

    public $selectedPatients = [];
    public $selectAll = false;

    public $showImportPanel = false;
    public $importFile = null;
    public $importResults = null;

    public function mount()
    {
        if (!Auth::user()->hasAnyRole(['Secretary', 'Super Admin'])) {
            return redirect()->route('dashboard')->with('error', 'Access Denied.');
        }
        $this->resetForm();
        $this->setDefaultDates();
    }

    // Fix #1/#3: re-check role on every Livewire AJAX request, not just mount()
    public function hydrate()
    {
        if (!Auth::user()->hasAnyRole(['Secretary', 'Super Admin'])) {
            abort(403);
        }
    }

    public function setDefaultDates()
    {
        $this->fromDate = Carbon::today()->startOfMonth()->toDateString();
        $this->toDate = Carbon::today()->toDateString();
    }

    public function updatedPxSearch()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedInsurerFilter()
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
        $this->pxSearch      = '';
        $this->insurerFilter = '';
        $this->setDefaultDates();
        $this->resetPage();
        $this->clearSelection();
    }

    public function getGenderStatsProperty()
    {
        $query = $this->applyFilters(Patient::query());

        return [
            'male'   => (clone $query)->where('gender', 'Male')->count(),
            'female' => (clone $query)->where('gender', 'Female')->count(),
            'other'  => (clone $query)->where('gender', 'Other')->count(),
        ];
    }

    // Fix #10: urlencode phone so a stored number like "0244&text=injected"
    // cannot rewrite the WhatsApp URL parameters.
    public function generateWhatsAppLink($name, $contact)
    {
        $phone   = $this->formatPhoneForWhatsApp($contact);
        $message = "Hello " . $name . ", this is Vision Space Eye Center.";
        return "https://api.whatsapp.com/send?phone=" . urlencode($phone) . "&text=" . urlencode($message);
    }

    public function generateBirthdayWhatsAppLink($name, $contact)
    {
        $phone   = $this->formatPhoneForWhatsApp($contact);
        $message = "Happy Birthday, " . $name . "! 🎂 On behalf of the entire team at Vision Space Eye Center, we wish you a very Happy Birthday. Wishing you a healthy and happy year ahead!";
        return "https://api.whatsapp.com/send?phone=" . urlencode($phone) . "&text=" . urlencode($message);
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
        // Fix #1: resolve IDs against the DB so forged/injected IDs are silently ignored
        $validIds = Patient::whereIn('id', $this->selectedPatients)->pluck('id')->all();
        $count    = count($validIds);
        Patient::whereIn('id', $validIds)->delete();
        // Fix #8 force=true: bulk-delete must be logged even if license is downgraded
        AuditTrail::record('patient.archived', "Archived {$count} patient record(s).", null, [], [], null, true);
        $this->clearSelection();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Selected records archived!']);
    }

    // Fix #5: pass the query builder; downloadCSV streams with chunkById
    public function exportRegistry()
    {
        $query = $this->applyFilters(Patient::query());
        // Fix #8 force=true: exports are security-critical — always audit
        AuditTrail::record('patient.exported', 'Exported patient registry.', null, [], [], null, true);
        return $this->downloadCSV($query, 'Patient_Registry_Report');
    }

    public function exportSelected()
    {
        if (empty($this->selectedPatients)) return;
        // Fix #1: resolve against DB so forged IDs in the public property are ignored
        $query = Patient::whereIn('id', $this->selectedPatients);
        AuditTrail::record('patient.exported', 'Exported ' . count($this->selectedPatients) . ' selected patient(s).', null, [], [], null, true);
        return $this->downloadCSV($query, 'Selected_Patients_Export');
    }

    // Fix #2 + #5: accepts a query Builder, streams with chunkById (no unbounded get()),
    // and sanitizes every value against CSV formula injection.
    private function downloadCSV($query, $filename)
    {
        $fileName = $filename . '_' . date('Y-m-d') . '.csv';
        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['PX Number', 'Name', 'Email', 'Contact', 'DOB', 'Gender', 'Civil Status', 'Occupation', 'Address', 'Registered Date']);
            $query->chunkById(500, function ($patients) use ($file) {
                foreach ($patients as $px) {
                    fputcsv($file, array_map([$this, 'sanitizeCsvValue'], [
                        $px->pxnumber,
                        $px->name,
                        $px->email,
                        $px->contact,
                        $px->dob,
                        $px->gender,
                        $px->civil_status,
                        $px->occupation,
                        $px->address,
                        $px->created_at->format('Y-m-d'),
                    ]));
                }
            });
            fclose($file);
        };
        return response()->streamDownload($callback, $fileName);
    }

    // Fix #2: prefix formula-trigger characters so Excel/Sheets won't execute them
    private function sanitizeCsvValue($value): string
    {
        $value = (string) $value;
        if ($value !== '' && preg_match('/^[=+\-@\t\r]/', $value)) {
            return "'" . $value;
        }
        return $value;
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

        // Fix #11: content-sniff — reject binary files even if MIME was spoofed
        $firstBytes = file_get_contents($this->importFile->getRealPath(), false, null, 0, 512);
        if ($firstBytes !== false && !mb_check_encoding($firstBytes, 'UTF-8') && !mb_check_encoding($firstBytes, 'ASCII')) {
            $this->addError('importFile', 'The file does not appear to be a valid text/CSV file.');
            return;
        }

        $handle  = fopen($this->importFile->getRealPath(), 'r');
        $header  = fgetcsv($handle);
        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $rowNum   = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $data = $this->mapImportRow($header, $row);

            $payload = [
                'name'         => trim((string) ($data['name'] ?? $data['full_name'] ?? '')),
                'email'        => trim((string) ($data['email'] ?? $data['email_address'] ?? '')),
                'contact'      => trim((string) ($data['contact'] ?? $data['phone'] ?? $data['phone_number'] ?? '')),
                'dob'          => $this->normalizeImportDate($data['dob'] ?? $data['birthday'] ?? $data['date_of_birth'] ?? null),
                'gender'       => $this->normalizeGender($data['gender'] ?? ''),
                'civil_status' => trim((string) ($data['civil_status'] ?? $data['marital_status'] ?? '')),
                'occupation'   => trim((string) ($data['occupation'] ?? '')),
                'address'      => trim((string) ($data['address'] ?? '')),
            ];

            if ($payload['name'] === '' && $payload['contact'] === '') {
                $skipped++;
                continue;
            }

            $validator = Validator::make($payload, [
                'name'         => 'required|string|max:255',
                'contact'      => 'required|string|max:50',
                'email'        => 'nullable|email|max:255',
                'dob'          => 'required|date|date_format:Y-m-d|before:today',
                'gender'       => 'required|in:Male,Female,Other',
                'address'      => 'required|string|max:1000',
                'occupation'   => 'nullable|string|max:255',
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
                // Fix #4: random_int() is cryptographically secure; mt_rand() is not
                'pxnumber' => 'PX-' . random_int(1000, 9999) . '-' . date('y'),
                'user_id'  => Auth::id(),
            ]));
            $imported++;
        }

        fclose($handle);

        $this->importFile    = null;
        $this->importResults = compact('imported', 'skipped', 'errors');
        $this->showImportPanel = true;
        $this->clearSelection();
        $this->resetPage();
    }

    public function clearImport()
    {
        $this->importFile    = null;
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
            'civil_status' => '',
            'insurer_id' => '', 'insurance_member_id' => '', 'insurance_policy_number' => '',
        ];
        $this->nameSearch = '';
        $this->isEditing  = false;
        $this->resetValidation();
    }

    public function saveEntry()
    {
        $this->state['name'] = $this->nameSearch;
        $validatedData = Validator::make($this->state, [
            'name'         => 'required|string|max:255',
            'contact'      => 'required',
            'email'        => 'nullable|email',
            'dob'          => 'required|date|before:today',
            'gender'       => 'required|in:Male,Female,Other',
            'address'      => 'required',
            'occupation'               => 'nullable',
            'civil_status'             => 'nullable',
            'insurer_id'               => 'nullable|exists:insurers,id',
            'insurance_member_id'      => 'nullable|string|max:60',
            'insurance_policy_number'  => 'nullable|string|max:60',
        ])->validate();

        if ($this->isEditing) {
            $patient = Patient::findOrFail($this->state['id']);
            $old     = $patient->only(array_keys($validatedData));
            $patient->update($validatedData);
            AuditTrail::record('patient.updated', "Updated patient profile: {$patient->name} ({$patient->pxnumber})", $patient, $old, $validatedData, $patient->id);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Profile Updated!']);
        } else {
            // Fix #4: random_int() instead of mt_rand()
            $validatedData['pxnumber'] = 'PX-' . random_int(1000, 9999) . '-' . date('y');
            $validatedData['user_id']  = Auth::id();
            $patient = Patient::create($validatedData);
            AuditTrail::record('patient.created', "Registered new patient: {$patient->name} ({$patient->pxnumber})", $patient, [], [], $patient->id);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'New Patient Saved!']);
        }
        $this->resetForm();
    }

    private function applyFilters($query)
    {
        if ($this->pxSearch) {
            $term = $this->pxSearch;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('pxnumber', 'like', "%{$term}%")
                  ->orWhere('insurance_member_id', 'like', "%{$term}%")
                  ->orWhere('insurance_policy_number', 'like', "%{$term}%")
                  ->orWhereHas('insurer', fn ($i) => $i->where('name', 'like', "%{$term}%"));
            });
        }

        if (!$this->pxSearch) {
            if ($this->fromDate && $this->toDate) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->fromDate)->startOfDay(),
                    Carbon::parse($this->toDate)->endOfDay(),
                ]);
            }
        }

        if ($this->insurerFilter) {
            $query->where('insurer_id', $this->insurerFilter);
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
            $key          = strtolower(trim((string) $column));
            $key          = str_replace([' ', '-', '.'], '_', $key);
            $mapped[$key] = $row[$index] ?? null;
        }
        return $mapped;
    }

    private function normalizeImportDate($value): ?string
    {
        if (!$value) return null;
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
            'm', 'male'    => 'Male',
            'f', 'female'  => 'Female',
            'other', 'o'   => 'Other',
            default        => '',
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
        return preg_replace('/[^0-9+]/', '', (string) $contact);
    }

    public function edit(Patient $patient)
    {
        $this->state      = $patient->toArray();
        $this->nameSearch = $patient->name;
        $this->isEditing  = true;
    }

    public function updatedNameSearch($value)
    {
        if (strlen($value) < 2) { $this->suggestions = []; return; }
        $this->suggestions = Patient::where('name', 'like', '%' . $value . '%')->limit(5)->get()->toArray();
    }

    // Fix #7: type-hint int; use findOrFail so an invalid/forged ID throws 404
    // rather than silently loading nothing and potentially resetting the form state.
    public function selectPatient(int $id)
    {
        $p = Patient::findOrFail($id);
        $this->state      = $p->toArray();
        $this->nameSearch = $p->name;
        $this->isEditing  = true;
        $this->suggestions = [];
    }

    public function render()
    {
        if ($this->birthdaysTodayCount === 0) {
            $this->birthdaysTodayCount = Patient::whereMonth('dob', Carbon::now()->month)
                ->whereDay('dob', Carbon::now()->day)
                ->count();
        }

        $query = $this->applyFilters(Patient::query());
        return view('livewire.secretary.patients-component', [
            'patients' => $query->latest()->paginate(10),
            'insurers' => \App\Models\Insurer::where('active', true)->orderBy('name')->get(['id', 'name', 'scheme_type']),
        ])->layout('layouts.secretary.secretary-layout');
    }
}
