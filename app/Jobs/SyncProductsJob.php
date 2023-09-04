<?php

namespace App\Jobs;

use App\CPU\BackEndHelper;
use App\Helpers\Shopify;
use App\Model\Product;
use App\Model\Tag;
use App\Traits\RequestTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use RequestTrait;

    private Shopify $shopify;
    private $totalNumberOfProducts = 0;
    private $count = 0;
    private $sinceId = 7842677194999;
    private $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($host, $accessToken, $apiKey, $apiSecret, $user_id)
    {
        $this->shopify = new Shopify($host, $accessToken, $apiKey, $apiSecret);
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $headers = $this->shopify->getStoreUrlHeaders();

        if ($this->totalNumberOfProducts === 0) {
            $endpoint = $this->shopify->getStoreURL('products/count.json');
            $response = $this->sendRequestToShopify('GET', $endpoint, $headers);
            $this->totalNumberOfProducts = $response['body']['count'];
        }

        try {
            $products = [];
            do {
                $endpoint = $this->shopify->getStoreURL(
                    ($this->sinceId != 0) ? 'products.json?limit=10&since_id=' . $this->sinceId : 'products.json?limit=10'
                );
                $response = $this->sendRequestToShopify('GET', $endpoint, $headers);
                $products = $response['statusCode'] == 200 ? $response['body']['products'] ?? null : null;
                foreach ($products as $product) {
                    $this->saveProduct($product);
                    $this->sinceId = $product['id'];
                    $this->count++;
                }
            } while ($products !== null && count($products) > 0 && $this->count < $this->totalNumberOfProducts);
        } catch (\Exception $e) {
            echo $e->getMessage();
            if ($this->count < $this->totalNumberOfProducts) {
                $this->handle();
            }
        }
    }


    private function saveProduct($product)
    {
        $p = new Product();
        $p->user_id = $this->user_id;
        $p->added_by = "seller";
        $p->name = $product['title'];
        //        $p->slug = Str::slug($product['title'], '-') . '-' . Str::random(6);
        $p->slug = Str::slug($product['id']);
        $category = json_decode('[{"id":"37","position":0}]', true);
        $p->category_ids = json_encode($category);
        $p->collection = $product['tags'];
        $p->brand_id = 13;
        $p->details = $product['body_html'];
        $p->colors = json_encode([]);

        $choice_options = [];
        foreach ($product['options'] as $key => $option) {
            array_push(
                $choice_options,
                ['name' => 'choice_' . $key, 'title' => $option['name'], 'options' => $option['values']]
            );
        }
        $p->choice_options = json_encode($choice_options);
        $variations = [];
        foreach ($product['variants'] as $variant) {
            array_push(
                $variations,
                [
                    'type' => $variant['title'],
                    'sku' => $variant['sku'],
                    'price' => BackEndHelper::currency_to_usd($variant['price']),
                    'qty' => $variant['inventory_quantity']
                ]
            );
        }
        $p->variation = json_encode($variations);
        $p->unit_price = BackEndHelper::currency_to_usd($product['variants'][0]['price']);
        $p->purchase_price = BackEndHelper::currency_to_usd($product['variants'][0]['price']);
        $p->attributes = json_encode([]);
        $p->current_stock = $product['variants'][0]['inventory_quantity'] ?? 0;
        $p->request_status = 1;
        $p->status = 1;
        $p->discount_type = 'flat';
        $p->tax_type = 'percent';
        $p->color_image = json_encode([]);

        $images = [];
        foreach ($product['images'] as $image) {
            array_push($images, ['cdn' => $image['src']]);
            //            $image_name = ImageManager::upload('product/', 'png', $image['src']);
            //            $images[] = $image_name;
        }
        $p->images = json_encode($images);

        //        $thumbnail = ImageManager::upload('product/thumbnail/', 'png', $product['image']['src']);
        //        $p->thumbnail = $thumbnail;
        $p->thumbnail = json_encode(['cdn' => isset($product['image']) ? $product['image']['src'] : []]);
        $p->save();

        $tag_ids = [];
        if ($product['tags'] != null)
            $tags = explode(",", $product['tags']);
        if (isset($tags)) {
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => trim($value)]
                );
                $tag->save();
                $tag_ids[] = $tag->id;
            }
        }
        $p->tags()->sync($tag_ids);
    }
}
