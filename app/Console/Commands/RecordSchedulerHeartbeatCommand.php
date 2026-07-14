<?php

namespace App\Console\Commands;

use App\Models\SystemHealthStatus;
use Illuminate\Console\Command;

class RecordSchedulerHeartbeatCommand extends Command
{
    protected $signature = 'system:health-heartbeat';

    protected $description = 'Record a heartbeat proving the Laravel scheduler is being executed.';

    public function handle(): int
    {
        SystemHealthStatus::record('scheduler', [
            'host' => gethostname() ?: null,
            'source' => 'schedule:run',
        ]);

        $this->info('Scheduler heartbeat recorded.');

        return self::SUCCESS;
    }
}
