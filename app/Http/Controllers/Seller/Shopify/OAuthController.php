<?php

namespace App\Http\Controllers\Seller\Shopify;

use App\Model\Cron;
use Shopify\Context;
use Illuminate\Http\Request;
use Shopify\Webhooks\Topics;
use Shopify\Webhooks\Registry;
use Shopify\Auth\FileSessionStorage;
use App\Services\Shopify\AuthService;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $service;

    public function __construct(Request $request)
    {
        $this->service = new AuthService($request->shop);
    }

    public function redirect()
    {
        return redirect($this->service->getProvider()->getAuthorizationUrl([
            'scope' => config('services.shopify.scopes')
        ]));
    }

    public function callback(Request $request)
    {
        try {
            $token = $this->service->getAccessToken('authorization_code', [
                'code' => $request->code ?? ''
            ]);

            /** @var \Multidimensional\OAuth2\Client\Provider\ShopifyStore */
            $store = $this->service->getResourceOwner($token);
            $shop = auth()->user()->shop;
            if ($shop && $shop->platform == 'shopify') {
                $shop->token()->updateOrCreate([], [
                    'domain' => $store->getDomain(),
                    'access_token' => $token->getToken(),
                ]);
                $shop->seller()->update(['remote_id' => $store->getId()]);
                Cron::insert([
                    [
                        'batch' => 'shopify',
                        'job' => 'SyncProducts',
                        'meta' => json_encode([
                            'id' => $shop->id,
                            'page' => ["limit" => env('SHOPIFY_PER_PAGE', 65)],
                        ])
                    ],
                    [
                        'batch' => 'shopify',
                        'job' => 'WebhookRegistration',
                        'meta' => json_encode([
                            'domain' => $store->getDomain(),
                            'token' => $token->getToken(),
                            'topics' => [
                                Topics::PRODUCTS_CREATE,
                                Topics::PRODUCTS_UPDATE,
                                Topics::PRODUCTS_DELETE,
                            ],
                        ])
                    ],
                ]);
            }
            return redirect('/seller');
        } catch (IdentityProviderException $e) {
            return redirect('/seller')->withStatus($e->getMessage());
        }
    }
}
