<?php

namespace App\Jobs\Salla\Product;

use App\Model\Product;
use App\CPU\BackEndHelper;
use Illuminate\Support\Str;
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
        return array_map(function ($salla) {
            $product = [];
            $product['remote_id'] = $salla['id'];
            $product['user_id'] = $this->seller;
            $product['added_by'] = 'seller';
            $product['name'] = $salla['name'];
            $product['details'] = $salla['description'];
            $product['slug'] = Str::slug($salla['name'], '-') . '-' . Str::random(6);
            $product['collection'] = array_reduce(
                $salla['categories'],
                fn ($carry, $category) => $carry . '-' . $category['name'],
                $salla['brand']
            );
            $product['current_stock'] = $salla['quantity'];
            $product['request_status'] = 1;
            $product['status'] = 1;
            $product['discount_type'] = 'flat';
            $product['tax_type'] = 'flat';
            $product['tax'] = BackEndHelper::currency_to_usd($salla['tax']['amount']);
            $product['thumbnail'] = json_encode(['cdn' =>  $salla['main_image']]);
            $product['color_image'] = '[]';
            $product['colors'] = '[]';
            $product['attributes'] = '[]';
            $product['purchase_price'] = BackEndHelper::currency_to_usd($salla['regular_price']['amount']);
            $product['unit_price'] = BackEndHelper::currency_to_usd($salla['regular_price']['amount']);
            $product['discount'] = isset($salla['sale_price']) ? BackEndHelper::currency_to_usd($salla['regular_price']['amount'] - $salla['sale_price']['amount']) : null;
            $product['images'] = json_encode(array_map(fn ($image) => [
                'cdn' => $image['url']
            ], $salla['images']));
            $product['choice_options'] = json_encode(array_map(fn ($option) => [
                'type' => $option['type'],
                'title' => $option['name'],
                'options' => array_map(fn ($value) => $value['name'], $option['values'])
            ], $salla['options']));
            $product['variation'] = json_encode(array_map(fn ($sku) => [
                'sku' => $sku['sku'],
                'price' => isset($sku['sale_price']) ? BackEndHelper::currency_to_usd($sku['sale_price']['amount']) : BackEndHelper::currency_to_usd($sku['price']['amount']),
                'qty' => $sku['stock_quantity']
            ], $salla['skus']));
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
