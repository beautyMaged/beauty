<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait RequestTrait
{


    public function sendRequestToShopify($method, $endpoint, $headers, $requestBody = null)
    {
        try {
            $client = new Client();
            if ($method == 'GET' || $method == 'DELETE') {
                $response = $client->request($method, $endpoint, ['headers' => $headers]);
            } else {
                $response = $client->request($method, $endpoint, ['headers' => $headers, 'json' => $requestBody]);
            }
            return [
                'statusCode' => $response->getStatusCode(),
                'body' => json_decode($response->getBody(), true)
            ];
        } catch (Exception $e) {
            return [
                'statusCode' => $e->getCode(),
                'message' => $e->getMessage(),
                'body' => null
            ];
        }
    }

}
