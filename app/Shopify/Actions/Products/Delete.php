<?php

namespace App\Shopify\Actions\Products;

use Shopify\Webhooks\Handler;
use App\Model\ShopifyOauthToken;
use Illuminate\Support\Facades\Log;
use App\Jobs\Shopify\Product\Delete as DeleteJob;

class Delete implements Handler
{
    public function handle(string $topic, string $shop, array $requestBody): void
    {
        Log::channel('job')->info($shop . " topic -> " . $topic);
        $seller = ShopifyOauthToken::where('domain', $shop)->first()->shop->seller;
        DeleteJob::dispatchSync($seller->id, $requestBody);
    }
}
