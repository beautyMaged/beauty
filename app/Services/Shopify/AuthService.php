<?php

namespace App\Services\Shopify;

use App\Model\Shop;
use App\Model\ShopifyOauthToken;
use Illuminate\Support\Traits\ForwardsCalls;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Multidimensional\OAuth2\Client\Provider\Shopify;
use Multidimensional\OAuth2\Client\Provider\ShopifyStore;

/**
 * @mixin Salla
 */
class AuthService
{
    use ForwardsCalls;

    /**
     * @var Shopify
     */
    protected $provider;

    /**
     * @var ShopifyOauthToken
     */
    public $token;

    public function __construct($shop)
    {
        $this->provider = new Shopify([
            'shop' => $shop,
            'clientId'     => config('services.shopify.client_id'), // The client ID assigned to you by Salla
            'clientSecret' => config('services.shopify.client_secret'), // The client password assigned to you by Salla
            'redirectUri'  => route('seller.shopify.oauth.callback'), // the url for current page in your service
        ]);
    }

    /**
     * Get the token from the shop model.
     *
     * @param  Shop  $shop
     *
     * @return $this
     */
    public function forShop(Shop $shop)
    {
        $this->token = $shop->token;
        return $this->token ? $this : null;
    }

    public function forToken(ShopifyOauthToken $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return Shopify
     */
    public function getProvider(): Shopify
    {
        return $this->provider;
    }

    /**
     * Get the details of store for the current token.
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface|SallaUser
     */
    public function getStoreDetail()
    {
        return $this->provider->getResourceOwner(new AccessToken($this->token->toArray()));
    }

    public function request(string $method, string $url, array $options = [])
    {
        return $this->provider->fetchResource($method, $url, $this->token->access_token, $options);
    }

    /**
     * As shortcut to call the functions of provider class.
     *
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->forwardCallTo($this->provider, $name, $arguments);
    }

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @param  AccessToken $token
     * @return ResourceOwnerInterface|ShopifyStore
     */
    public function getResourceOwner(?AccessToken $token)
    {
        return $this->provider->getResourceOwner($token ?: new AccessToken($this->token->toArray()));
    }
}
