<?php

namespace App\Jobs\Salla;

use App\Model\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Jobs\Salla\Product\UpdateOrCreate;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Salla\AuthService as SallaAuthService;

class SyncProducts
{
    use Dispatchable;

    private $meta;
    private $cron;
    private $salla;
    private $baseUrl = 'https://api.salla.dev/admin/v2/';
    public function __construct($cron, $meta)
    {
        $this->meta = $meta;
        $this->cron = $cron;
        $this->salla = new SallaAuthService();
    }

    public function clearCronJob()
    {
        $this->cron->delete();
    }

    public function nextPage()
    {
        $this->meta->page++;
        $this->cron->meta = json_encode($this->meta);
        $this->cron->save();
    }

    public function setRunning()
    {
        $this->meta->running = true;
        $this->cron->meta = json_encode($this->meta);
        $this->cron->save();
    }

    public function isRunning()
    {
        return isset($this->meta->running);
    }

    public function handle()
    {
        if ($this->isRunning())
            return;
        $this->setRunning();

        $shop = Shop::find($this->meta->id);
        $seller = $shop->seller;
        Log::channel('job')->info('syncSallaProducts: ' . json_encode([
            'shop' => $shop,
            'meta' => $this->meta
        ]));
        if ($shop && $this->salla->forShop($shop) && !$this->salla->token->hasExpired()) {
            $products = $this->salla->request('GET', $this->baseUrl . 'products?' . Arr::query([
                'page' => $this->meta->page,
                'per_page' => env('SALLA_PER_PAGE', 65)
            ]));
            if ($products['success']) {
                UpdateOrCreate::dispatchSync($seller->id, $products['data']);
                if ($products['pagination']['currentPage'] != $products['pagination']['totalPages'])
                    return $this->nextPage();
            }
        }
        $this->clearCronJob();
    }
}
