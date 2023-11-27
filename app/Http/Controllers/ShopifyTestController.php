<?php

namespace App\Http\Controllers;

use App\Model\Shop;
use Shopify\Context;
use App\Model\Product;
use Shopify\Clients\Rest;
use Shopify\Clients\RestResponse;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Shopify\Auth\FileSessionStorage;
use App\CPU\BackEndHelper;


class ShopifyTestController extends Controller
{
    public $meta;
    private $seller;
    private $products;

    public function __construct()
    {
        $this->meta = json_decode(json_encode([
            'id' => 22,
            'page' => ["limit" => 5],
        ]));
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
            $product['thumbnail'] = json_encode(['cdn' => $shopify['image']['src']]);
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

    public function __invoke()
    {
        $shop = Shop::find($this->meta->id);
        $seller = $shop->seller;
        if ($shop) {
            Context::initialize(
                apiKey: config('services.shopify.client_id'),
                apiSecretKey: config('services.shopify.client_secret'),
                scopes: config('services.shopify.scopes'),
                hostName: env('APP_URL'),
                sessionStorage: new FileSessionStorage(),
            );
            $token = $shop->token;
            $client = new Rest($token->domain, $token->access_token);
            /** @var RestResponse */
            $products = $client->get('products', [], (array) $this->meta->page);
            if ($products->getStatusCode() == 200) {
                $this->seller = $seller->id;
                $this->products = $products->getDecodedBody()['products'];
                Product::upsert(
                    $this->map(),
                    ['user_id', 'remote_id']
                );
                $info = $products->getPageInfo();
                dd($info);
            }
        }
    }
}
