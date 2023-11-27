<?php

namespace App\Jobs\Shopify;

use App\Model\Cron;
use Illuminate\Foundation\Bus\Dispatchable;

class Crons
{
    use Dispatchable;

    public function handle()
    {
        $cron = Cron::where('batch', 'shopify')->first();
        if ($cron)
            "App\\Jobs\\Shopify\\{$cron->job}"::dispatchSync($cron, $cron->meta ? json_decode($cron->meta) : null);
    }
}
