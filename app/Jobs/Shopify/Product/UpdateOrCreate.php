<?php

namespace App\Jobs\Shopify\Product;

use App\Model\Product;
use App\CPU\BackEndHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateOrCreate
{
    use Dispatchable;

    private $seller;
    private $products;

    public function __construct($seller, $products)
    {
        $this->seller = $seller;
        $this->products = $products;
    }

    private function map()
    {
        return array_map(function ($shopify) {
            $product = [];
            $product['added_by'] = 'seller';
            $product['request_status'] = 1;
            $product['status'] = 1;
            $product['discount_type'] = 'flat';
            $product['tax_type'] = 'flat';
            $product['color_image'] = '[]';
            $product['colors'] = '[]';
            $product['attributes'] = '[]';
            $product['collection'] = '';
            // array_reduce(
            //     $shopify['categories'],
            //     fn ($carry, $category) => $carry . '-' . $category['name'],
            //     $shopify['brand']
            // );
            $product['remote_id'] = $shopify['id'];
            $product['user_id'] = $this->seller;
            $product['name'] = $shopify['title'];
            $product['details'] = $shopify['body_html'];
            $product['slug'] = Str::slug($shopify['title'], '-') . '-' . Str::random(6);
            $product['current_stock'] = $shopify['variants'][0]['inventory_quantity'] ?? 0;
            $product['thumbnail'] = $shopify['image'] ? json_encode(['cdn' => $shopify['image']['src']]) : null;
            $product['purchase_price'] = BackEndHelper::currency_to_usd($shopify['variants'][0]['price']);
            $product['unit_price'] = BackEndHelper::currency_to_usd($shopify['variants'][0]['price']);
            $product['images'] = json_encode(array_map(fn ($image) => [
                'cdn' => $image['src']
            ], $shopify['images']));
            $product['choice_options'] = json_encode(array_map(fn ($option) => [
                'title' => $option['name'],
                'options' => $option['values']
            ], $shopify['options']));
            $product['variation'] = json_encode(array_map(fn ($sku) => [
                'type' => $sku['title'],
                'sku' => $sku['sku'],
                'price' => BackEndHelper::currency_to_usd($sku['price']),
                'qty' => $sku['inventory_quantity']
            ], $shopify['variants']));
            return $product;
        }, $this->products);
    }

    public function handle()
    {
        Product::upsert(
            $this->map(),
            ['user_id', 'remote_id']
        );
    }
}
