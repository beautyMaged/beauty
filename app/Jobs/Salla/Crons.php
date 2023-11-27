<?php

namespace App\Jobs\Salla;

use App\Model\Cron;
use Illuminate\Foundation\Bus\Dispatchable;

class Crons
{
    use Dispatchable;

    public function handle()
    {
        $cron = Cron::where('batch', 'salla')->first();
        if ($cron)
            "App\\Jobs\\Salla\\{$cron->job}"::dispatchSync($cron, $cron->meta ? json_decode($cron->meta) : null);
    }
}
