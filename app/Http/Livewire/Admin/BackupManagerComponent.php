<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BackupManagerComponent extends Component
{
    public bool   $isRunning   = false;
    public string $lastResult  = '';
    public array  $copyResults = [];

    // Extra destination management
    public string $newPath    = '';
    public array  $extraPaths = [];

    // Folder browser
    public bool   $browserOpen    = false;
    public string $browserPath    = '';
    public array  $browserDirs    = [];
    public array  $browserDrives  = [];

    // Create new folder (inside browser)
    public bool   $creatingFolder = false;
    public string $newFolderName  = '';

    protected $rules = [
        'newPath' => 'required|string|max:500',
    ];

    protected $messages = [
        'newPath.required' => 'Please enter a folder path.',
    ];

    public function mount(): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $this->extraPaths = Setting::getSettings()->backup_extra_paths ?? [];
    }

    public function openBrowser(): void
    {
        $this->browserDrives  = $this->getWindowsDrives();
        $this->browserPath    = '';
        $this->browserDirs    = [];
        $this->browserOpen    = true;
        $this->creatingFolder = false;
        $this->newFolderName  = '';
    }

    public function closeBrowser(): void
    {
        $this->browserOpen    = false;
        $this->creatingFolder = false;
        $this->newFolderName  = '';
    }

    public function browserNavigate(string $path): void
    {
        if (!is_dir($path)) return;

        $this->browserPath    = $path;
        $this->browserDirs    = $this->listSubdirectories($path);
        $this->creatingFolder = false;
        $this->newFolderName  = '';
    }

    public function browserUp(): void
    {
        if (!$this->browserPath) return;

        $this->creatingFolder = false;
        $this->newFolderName  = '';

        $parent = dirname($this->browserPath);

        if ($parent === $this->browserPath) {
            $this->browserPath = '';
            $this->browserDirs = [];
            return;
        }

        $this->browserNavigate($parent);
    }

    public function selectCurrentFolder(): void
    {
        if (!$this->browserPath || !is_dir($this->browserPath)) return;

        $this->newPath     = $this->browserPath;
        $this->browserOpen = false;
        $this->addPath();
    }

    public function toggleCreateFolder(): void
    {
        $this->creatingFolder = !$this->creatingFolder;
        $this->newFolderName  = '';
        $this->resetErrorBag('newFolderName');
    }

    public function createFolder(): void
    {
        $name = trim($this->newFolderName);

        if (!$name || !$this->browserPath) return;

        if (preg_match('/[\/\\\\:*?"<>|]/', $name)) {
            $this->addError('newFolderName', 'Name cannot contain: \\ / : * ? " < > |');
            return;
        }

        $newPath = rtrim($this->browserPath, '/\\') . DIRECTORY_SEPARATOR . $name;

        if (is_dir($newPath)) {
            $this->addError('newFolderName', 'A folder with that name already exists.');
            return;
        }

        if (!@mkdir($newPath, 0755, true)) {
            $this->addError('newFolderName', 'Could not create folder — check permissions.');
            return;
        }

        $this->creatingFolder = false;
        $this->newFolderName  = '';
        $this->browserNavigate($newPath);
    }

    private function getWindowsDrives(): array
    {
        $wmicInfo = $this->getDriveInfoFromWmic();
        $drives   = [];

        foreach (range('A', 'Z') as $letter) {
            $path = $letter . ':\\';
            if (!is_dir($path)) continue;

            $info  = $wmicInfo[$path] ?? [];
            $type  = $info['type']  ?? 3;
            $label = $info['label'] ?? '';
            $free  = $info['free']  ?? 0;
            $size  = $info['size']  ?? 0;

            [$icon, $iconColor, $typeLabel] = match ($type) {
                2       => ['fas fa-usb',           '#0f9d58', 'Removable Drive'],
                4       => ['fas fa-network-wired', '#9c27b0', 'Network Drive'],
                5       => ['fas fa-compact-disc',  '#ff9800', 'CD / DVD Drive'],
                default => ['fas fa-hdd',           '#4285f4', 'Local Disk'],
            };

            $drives[] = [
                'path'      => $path,
                'letter'    => $letter . ':',
                'label'     => $label ?: $typeLabel,
                'icon'      => $icon,
                'iconColor' => $iconColor,
                'typeLabel' => $typeLabel,
                'free'      => $free,
                'size'      => $size,
                'freeHuman' => $free > 0 ? $this->humanFileSize($free) : '',
                'sizeHuman' => $size > 0 ? $this->humanFileSize($size) : '',
            ];
        }

        return $drives;
    }

    private function getDriveInfoFromWmic(): array
    {
        $info = [];
        try {
            $raw = @shell_exec('wmic logicaldisk get Caption,DriveType,FreeSpace,Size,VolumeName /format:csv 2>nul');
            if (!$raw) return $info;

            $lines = array_values(array_filter(array_map('trim', explode("\n", $raw))));
            if (count($lines) < 2) return $info;

            $headers = array_flip(array_map('trim', str_getcsv($lines[0])));

            for ($i = 1; $i < count($lines); $i++) {
                if (empty($lines[$i])) continue;
                $cols    = str_getcsv($lines[$i]);
                $caption = trim($cols[$headers['Caption']] ?? '');
                if (!$caption) continue;

                $info[$caption . '\\'] = [
                    'type'  => (int) ($cols[$headers['DriveType']]  ?? 3),
                    'free'  => (int) ($cols[$headers['FreeSpace']]  ?? 0),
                    'size'  => (int) ($cols[$headers['Size']]       ?? 0),
                    'label' => trim($cols[$headers['VolumeName']]   ?? ''),
                ];
            }
        } catch (\Throwable) {}

        return $info;
    }

    private function listSubdirectories(string $path): array
    {
        $dirs = [];
        try {
            $items = @scandir($path);
            if (!$items) return [];

            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $full = rtrim($path, '\/') . DIRECTORY_SEPARATOR . $item;
                if (is_dir($full) && !str_starts_with($item, '$')) {
                    $dirs[] = ['name' => $item, 'path' => $full];
                }
            }
        } catch (\Throwable) {}

        usort($dirs, fn ($a, $b) => strcmp($a['name'], $b['name']));
        return $dirs;
    }

    public function runBackup(): void
    {
        $this->isRunning   = true;
        $this->lastResult  = '';
        $this->copyResults = [];

        try {
            Artisan::call('backup:run --only-db --disable-notifications');
            $output = Artisan::output();

            $this->lastResult = str_contains($output, 'Backup completed') ? 'success' : 'warning';

            if ($this->lastResult === 'success' && !empty($this->extraPaths)) {
                Artisan::call('backup:copy-to-drives');
                $this->copyResults = cache('backup_copy_results', []);
            }
        } catch (\Throwable $e) {
            $this->lastResult = 'error';
        }

        $this->isRunning = false;
    }

    public function cleanBackups(): void
    {
        try {
            Artisan::call('backup:prune-custom');
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Backups pruned — latest kept per day/week/month/year.']);
        } catch (\Throwable $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Pruning failed: ' . $e->getMessage()]);
        }
    }

    public function deleteBackup(string $path): void
    {
        if (Storage::disk('backups')->exists($path)) {
            Storage::disk('backups')->delete($path);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Backup deleted.']);
        }
    }

    public function addPath(): void
    {
        $this->validateOnly('newPath');

        $path = rtrim(trim($this->newPath), '/\\');

        if (!is_dir($path)) {
            $this->addError('newPath', 'Directory not found. Make sure the drive is connected and the path exists.');
            return;
        }

        if (!is_writable($path)) {
            $this->addError('newPath', 'Directory is not writable. Check folder permissions.');
            return;
        }

        if (in_array($path, $this->extraPaths)) {
            $this->addError('newPath', 'This path is already in the list.');
            return;
        }

        $this->extraPaths[] = $path;
        $this->saveDestinations();
        $this->newPath = '';
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Backup destination added.']);
    }

    public function removePath(int $index): void
    {
        unset($this->extraPaths[$index]);
        $this->extraPaths = array_values($this->extraPaths);
        $this->saveDestinations();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Destination removed.']);
    }

    private function saveDestinations(): void
    {
        Setting::getSettings()->update([
            'backup_extra_paths' => !empty($this->extraPaths) ? $this->extraPaths : null,
        ]);
    }

    public function render()
    {
        $backupName = config('backup.backup.name');
        $disk       = Storage::disk('backups');

        $files = collect($disk->files($backupName))
            ->map(fn ($path) => [
                'path'          => $path,
                'name'          => basename($path),
                'size'          => $disk->size($path),
                'size_human'    => $this->humanFileSize($disk->size($path)),
                'last_modified' => $disk->lastModified($path),
            ])
            ->sortByDesc('last_modified')
            ->values();

        return view('livewire.admin.backup-manager-component', [
            'backups'   => $files,
            'totalSize' => $this->humanFileSize($files->sum('size')),
        ])->layout('layouts.admin.admin-layout');
    }

    private function humanFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
