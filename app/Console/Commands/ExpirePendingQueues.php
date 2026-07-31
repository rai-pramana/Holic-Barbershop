<?php

namespace App\Console\Commands;

use App\Models\Queue;
use Illuminate\Console\Command;

class ExpirePendingQueues extends Command
{
    protected $signature = 'queues:expire-pending';
    protected $description = 'Expire pending queues that have passed their expiry time (60 minutes)';

    public function handle(): void
    {
        $count = Queue::expirePending();
        $this->info("Expired {$count} pending queue(s).");
    }
}
