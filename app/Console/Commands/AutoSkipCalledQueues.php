<?php

namespace App\Console\Commands;

use App\Models\Queue;
use Illuminate\Console\Command;

class AutoSkipCalledQueues extends Command
{
    protected $signature = 'queues:auto-skip';
    protected $description = 'Auto-skip called queues where customer did not arrive within 5 minutes';

    public function handle(): void
    {
        $count = Queue::autoSkipCalled();
        $this->info("Auto-skipped {$count} called queue(s).");
    }
}
