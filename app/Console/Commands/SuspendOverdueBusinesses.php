<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use Carbon\Carbon;

class SuspendOverdueBusinesses extends Command
{
    protected $signature = 'businesses:check-overdue';
    protected $description = 'Deactivate businesses with overdue payments';

    public function handle()
    {
        $today = Carbon::now()->startOfDay();

        $suspended = Business::where('is_active', true)
            ->whereDate('next_payment_due', '<', $today)
            ->update(['is_active' => false]);

        $this->info("Suspended $suspended business(es).");
    }
}