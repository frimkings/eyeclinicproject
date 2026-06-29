<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupDiagnostics
{
    public static function check(): array
    {
        $configured = trim((string) env('DB_DUMP_BINARY_PATH', ''));
        $resolved = MysqlDumpPath::resolve($configured);
        $executable = MysqlDumpPath::executable($resolved);
        $backupRoot = storage_path('app/backups');

        return [
            'configured_path' => $configured ?: 'Auto-detect',
            'resolved_path' => $resolved ?: 'Not found',
            'executable' => $executable ?: 'Not found',
            'binary_exists' => $executable !== null,
            'binary_version' => $executable ? static::binaryVersion($executable) : 'mysqldump.exe was not found.',
            'server_version' => static::serverVersion(),
            'backup_root' => $backupRoot,
            'backup_root_writable' => is_dir($backupRoot) && is_writable($backupRoot),
            'backup_disk_readable' => static::backupDiskReadable(),
            'last_error' => cache('backup_last_error'),
            'candidates' => MysqlDumpPath::candidatePaths(),
        ];
    }

    private static function binaryVersion(string $executable): string
    {
        try {
            $process = new Process([$executable, '--version']);
            $process->setTimeout(10);
            $process->run();

            return trim($process->getOutput() ?: $process->getErrorOutput()) ?: 'Version output unavailable.';
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private static function serverVersion(): string
    {
        try {
            $row = DB::selectOne('select version() as version');

            return (string) ($row->version ?? 'Unknown');
        } catch (\Throwable $e) {
            return 'Unavailable: ' . $e->getMessage();
        }
    }

    private static function backupDiskReadable(): bool
    {
        try {
            Storage::disk('backups')->files(config('backup.backup.name'));

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
