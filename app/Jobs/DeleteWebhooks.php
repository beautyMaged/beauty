<?php

namespace App\Jobs;

use App\Helpers\Shopify;
use App\Traits\RequestTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteWebhooks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use RequestTrait;

    private Shopify $shopify;

    /**
     * Create a new job instance.
     */
    public function __construct($shopify)
    {
        $this->shopify = $shopify;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $endpoint = $this->shopify->getStoreURL('webhooks.json');
            $headers = $this->shopify->getStoreUrlHeaders();
            $response = $this->sendRequestToShopify('GET', $endpoint, $headers);
            $webhooks = $response['body']['webhooks'];
            foreach($webhooks as $webhook) {
                $endpoint = $this->shopify->getStoreURL('webhooks/'.$webhook['id'].'.json');
                $response = $this->sendRequestToShopify('DELETE', $endpoint, $headers);
                Log::info('Response for deleting webhooks');
                Log::info($response);
            }
        } catch(\Exception $e) {
            Log::info('here in delete webhooks ' . $e->getMessage().' '.$e->getLine());
            echo $e->getMessage();
        }
    }
}
