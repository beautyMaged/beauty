<?php

namespace App\Jobs\Shopify;

use App\Model\Shop;
use Shopify\Context;
use Shopify\Clients\Rest;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\FileSessionStorage;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Shopify\Product\UpdateOrCreate;

class SyncProducts
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

    public function nextPage($info)
    {
        $this->meta->page = $info->getNextPageQuery();
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
        Log::channel('job')->info('syncShopifyProducts: ' . json_encode([
            'shop' => $shop->id,
            'meta' => $this->meta
        ]));
        if ($shop) {
            $token = $shop->token;
            Context::initialize(
                apiKey: config('services.shopify.client_id'),
                apiSecretKey: config('services.shopify.client_secret'),
                scopes: config('services.shopify.scopes'),
                hostName: env('APP_URL'),
                sessionStorage: new FileSessionStorage(),
            );
            $client = new Rest($token->domain, $token->access_token);
            /** @var RestResponse */
            $products = $client->get('products', [], (array) $this->meta->page);
            if ($products->getStatusCode() == 200) {
                UpdateOrCreate::dispatchSync($seller->id, $products->getDecodedBody()['products']);
                $info = $products->getPageInfo();
                if ($info && $info->hasNextPage())
                    return $this->nextPage($info);
            }
        }
        $this->clearCronJob();
    }
}
