<?php

namespace App\Jobs;

use App\Helpers\Shopify;
use App\Traits\RequestTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateWebhooks implements ShouldQueue
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
            $webhooks = config('custom.webhooks');
            foreach($webhooks as $topic => $url) {
                $body = [
                    'webhook' => [
                        'topic' => $topic,
                        'address' => env('APP_URL').'/admin/webhooks/'.$url,
                        'format' => 'json'
                    ]
                ];
                $response = $this->sendRequestToShopify('POST', $endpoint, $headers, $body);
                Log::info('Response for topic '.$topic);
                Log::info($response['statusCode']);
                Log::info($response['body']);
            }
        } catch(\Exception $e) {
            Log::info('here in create webhooks ' . $e->getMessage().' '.$e->getLine());
            echo $e->getMessage();
        }
    }
}
