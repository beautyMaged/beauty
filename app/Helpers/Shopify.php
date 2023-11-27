<?php

namespace App\Helpers;

use App\Traits\RequestTrait;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Shopify
{
    use RequestTrait;

    private $apiKey;
    private $password;
    private $host;
    private $apiSecret;
    private $apiVersion;

    public function __construct($host, $accessToken, $apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->password = $accessToken;
        $this->host = $host;
        $this->apiVersion = env('SHOPIFY_API_VERSION');
    }

    public function getStoreURL($endPoint)
    {
        return "https://" . $this->apiKey . ":" . $this->password . "@" . $this->host . ".myshopify.com/admin/api/" . $this->apiVersion . "/" . $endPoint;
    }

    public function getStoreUrlHeaders()
    {
        return [
            'Content-Type : application/json',
            'X-Shopify-Access-Token : ' . $this->apiKey
        ];
    }

    public function getStoreName()
    {
        return $this->host;
    }

    public function getStoreEmail()
    {
        $endpoint = $this->getStoreURL('shop.json');
        $headers = $this->getStoreUrlHeaders();
        $result = $this->sendRequestToShopify('GET', $endpoint, $headers);
        return $result['body']['shop']['email'];
    }


    public function makeDraftOrder($cart, $shop_name, $commission)
    {
        $client = new Client();
        $commission_id = (string)Str::uuid(); // Generates a UUID
        $lineItems = [];
        $totalPrice = 0;
        foreach ($cart as $item) {
            array_push($lineItems, [
                "title" => $item['name'],
                "price" => $item['price'],
                "quantity" => $item['quantity']
            ]);
            $totalPrice += $item['price'];
        }
        $response = $client->post(
            'https://' . $this->host . '.myshopify.com/admin/api/' . $this->apiVersion . '/draft_orders.json',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $this->password, // Replace with your actual access token
                ],
                'json' => [
                    'draft_order' => [
                        'line_items' => $lineItems
                        ,
                        "note_attributes" => [
                            [
                                "name" => "commission_id",
                                "value" => $commission_id
                            ]
                        ]
                    ]
                ]
            ]
        );
        $draft_order = json_decode($response->getBody(), true);
        DB::table('draft_orders')->insert([
            'draft_order_id' => $draft_order['draft_order']['id'],
            'shop_name' => $shop_name,
            'commission_id' => $commission_id,
            'commission_value' => ($commission * $totalPrice / 100),
            'commission_status' => 'none',
            'created_at' => now()
        ]);
        return $draft_order['draft_order']['invoice_url'];
    }

    private function makeInvoice($draft_order_id, $to, $from)
    {
        $draft_order_invoice = [
            "draft_order_invoice" => [
                "to" => $to,
                "from" => "aassrr11@hotmail.com",
                "subject" => "فاتورة شراء",
                "custom_message" => "شكراً لكم على اختياركم لنا, نفخر بثقتكم."
            ]
        ];
        $endpoint = $this->getStoreURL('draft_orders/' . $draft_order_id . '/send_invoice.json');
        $headers = $this->getStoreUrlHeaders();
        return $this->sendRequestToShopify('POST', $endpoint, $headers, json_encode($draft_order_invoice));
    }

    public function completeDraftOrder($draft_order_id)
    {
        $endpoint = $this->getStoreURL('draft_orders/' . $draft_order_id . '/complete.json');
        $headers = $this->getStoreUrlHeaders();
        return $this->sendRequestToShopify('PUT', $endpoint, $headers);
    }

    public function deleteDraftOrder($draft_order_id)
    {
        $endpoint = $this->getStoreURL('draft_orders/' . $draft_order_id . '.json');
        $headers = $this->getStoreUrlHeaders();
        return $this->sendRequestToShopify('DELETE', $endpoint, $headers);
    }

    public function createCheckout($cartItems)
    {
        $line_items = [];
        foreach ($cartItems as $item) {
            array_push($line_items, ['variant_id' => $item['slug'], 'quantity' => $item['quantity']]);
        }
        $checkoutData = [
            'checkout' => [
                'line_items' => $line_items
            ]
        ];
        \Illuminate\Support\Facades\Log::alert('shopify');
        \Illuminate\Support\Facades\Log::alert($checkoutData);
        $endpoint = $this->getStoreURL('checkouts.json');
        $headers = $this->getStoreUrlHeaders();
        $result = $this->sendRequestToShopify(
            'POST',
            $endpoint,
            $headers,
            json_encode(['checkout' => $checkoutData])
        );
        \Illuminate\Support\Facades\Log::alert($result);
        return $result['body']['checkout']['web_url'];
    }
}
