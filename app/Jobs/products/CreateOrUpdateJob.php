<?php

namespace App\Jobs\products;

use App\CPU\BackEndHelper;
use App\CPU\ImageManager;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\Tag;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateOrUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;
    private $user_id;

    /**
     * Create a new job instance.
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shop = Shop::where('name' , $this->product['vendor'])->first();
        $this->user_id = $shop->seller_id;
        try {
            $p = \App\Model\Product::where('slug', $this->product['id'])->first();
            if (!$p) {
                $p = new \App\Model\Product();
                $p->user_id = $this->user_id;
                $p->added_by = "seller";
                $category = json_decode('[{"id":"36","position":0}]', true);
                $p->category_ids = json_encode($category);
                $p->brand_id = 13;
                $p->slug = Str::slug($this->product['id']);
                $p->colors = json_encode([]);
                $p->attributes = json_encode([]);
                $p->request_status = 1;
                $p->status = 1;
                $p->discount_type = 'flat';
                $p->tax_type = 'percent';
            }
            $p->name = $this->product['title'];
            $p->details = $this->product['body_html'];
            $choice_options = [];
            foreach ($this->product['options'] as $key => $option) {
                array_push(
                    $choice_options,
                    ['name' => 'choice_'.$key, 'title' => $option['name'], 'options' => $option['values']]
                );
            }
            $p->choice_options = json_encode($choice_options);
            $variations = [];
            foreach ($this->product['variants'] as $variant) {
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
            $p->unit_price = BackEndHelper::currency_to_usd($this->product['variants'][0]['price']);
            $p->purchase_price = BackEndHelper::currency_to_usd($this->product['variants'][0]['price']);
            $p->current_stock = $this->product['variants'][0]['inventory_quantity'] ?? 0;
            $p->color_image = json_encode([]);
            $images = [];
            if (isset($this->product['images']) && isset($this->product['image'])) {
                foreach ($this->product['images'] as $image) {
                    array_push($images, ['cdn' => $image['src']]);
//                    $image_name = ImageManager::upload('product/', 'png', $image['src']);
//                    $images[] = $image_name;
                }
                $p->images = json_encode($images);

//                $thumbnail = ImageManager::upload('product/thumbnail/', 'png', $this->product['image']['src']);
//                $p->thumbnail = $thumbnail;
                $p->thumbnail = json_encode(['cdn' => $this->product['image']['src']]);
            }
            $p->save();

            $tag_ids = [];
            if ($this->product['tags'] != null) {
                $tags = explode(",", $this->product['tags']);
            }
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
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            echo $e->getMessage();
        }
    }
}
