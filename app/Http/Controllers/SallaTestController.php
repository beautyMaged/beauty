<?php

namespace App\Http\Controllers;

use App\Model\Shop;
use App\Model\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Services\Salla\AuthService as SallaAuthService;

class SallaTestController extends Controller
{
    public $meta;
    public $salla;
    private $baseUrl = 'https://api.salla.dev/admin/v2/';
    private $seller;
    private $products;

    public function __construct()
    {
        $this->meta = json_decode(json_encode([
            'id' => 17,
            'page' => 0
        ]));
        $this->salla = new SallaAuthService();
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
            $product['tax'] = $salla['tax']['amount'];
            $product['thumbnail'] = json_encode(['cdn' =>  $salla['main_image']]);
            $product['color_image'] = '[]';
            $product['purchase_price'] = $salla['price']['amount'];
            $product['unit_price'] = $salla['price']['amount'];
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
                'price' => isset($sku['sale_price']) ? $sku['sale_price']['amount'] : $sku['price']['amount'],
                'qty' => $sku['stock_quantity']
            ], $salla['skus']));
            return $product;
        }, $this->products);
    }

    public function __invoke()
    {
        $shop = Shop::find($this->meta->id);
        $seller = $shop->seller;
        if ($shop && $this->salla->forShop($shop) && !$this->salla->token->hasExpired()) {
            $products = $this->salla->request('GET', $this->baseUrl . 'products?' . Arr::query([
                'page' => $this->meta->page,
                'per_page' => 1
            ]));
            if ($products['success']) {


                $this->seller = $seller->id;
                $this->products = $products['data'];

                Product::upsert(
                    $this->map(),
                    ['id', 'remote_id']
                );



                if ($products['pagination']['currentPage'] != $products['pagination']['totalPages'])
                    return $this->nextPage();
            }
        }
    }
}
