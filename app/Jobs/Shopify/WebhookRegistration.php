<?php

namespace App\Jobs\Shopify;

use Shopify\Context;
use Shopify\Webhooks\Registry;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\FileSessionStorage;
use Illuminate\Foundation\Bus\Dispatchable;

class WebhookRegistration
{
    use Dispatchable;

    private $meta;
    private $cron;
    public function __construct($cron, $meta)
    {
        $this->meta = $meta;
        $this->cron = $cron;
    }

    public function clearCronJob()
    {
        $this->cron->delete();
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

        Log::channel('job')->info('ShopifyWebhookRegistration: ' . json_encode([$this->meta]));
        Context::initialize(
            apiKey: config('services.shopify.client_id'),
            apiSecretKey: config('services.shopify.client_secret'),
            scopes: config('services.shopify.scopes'),
            hostName: env('APP_URL'),
            sessionStorage: new FileSessionStorage(),
        );
        foreach ($this->meta->topics as $topic) {
            Registry::register(
                'seller/shopify/webhook',
                $topic,
                $this->meta->domain,
                $this->meta->token,
            );
            sleep(1);
        }

        $this->clearCronJob();
    }
}
