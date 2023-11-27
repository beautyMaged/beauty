<?php

namespace App\Services\Zid;

use App\Model\Shop;
use App\Providers\ZidUser;
use App\Providers\ZidOauth;
use App\Model\ZidOauthToken;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Support\Traits\ForwardsCalls;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AuthService
{
    use ForwardsCalls;

    /**
     * @var ZidOauth
     */
    protected $provider;

    /**
     * @var ZidOauthToken
     */
    public $token;

    public function __construct()
    {
        $this->provider = new ZidOauth([
            'clientId'     => config('services.zid.client_id'),
            'clientSecret' => config('services.zid.client_secret'),
            'redirectUri'  => route('seller.zid.oauth.callback'),
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

    public function forToken(ZidOauthToken $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return ZidOauth
     */
    public function getProvider(): ZidOauth
    {
        return $this->provider;
    }

    public function getStoreDetail()
    {
        return $this->provider->getResourceOwner(new AccessToken($this->token->toArray()));
    }

    /**
     * Get A new access token via refresh token.
     *
     * @return \League\OAuth2\Client\Token\AccessToken|\League\OAuth2\Client\Token\AccessTokenInterface
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function getNewAccessToken()
    {
        if ($this->token->hasExpired()) {
            return new AccessToken($this->token->toArray());
        }

        // let's request a new access token via refresh token.
        $token = $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $this->token->refresh_token
        ]);

        // lets update user tokens
        $this->token->update([
            'access_token'  => $token->getToken(),
            'expires_in'    => $token->getExpires(),
            'refresh_token' => $token->getRefreshToken()
        ]);

        return $token;
    }

    public function request(string $method, string $url, array $options = [])
    {
        // you need always to check the token before made a request
        // If the token expired, lets request a new one and save it to the database
        $this->getNewAccessToken();

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
     * @return ResourceOwnerInterface|ZidUser
     */
    public function getResourceOwner(?AccessToken $token)
    {
        return $this->provider->getResourceOwner($token ?: new AccessToken($this->token->toArray()));
    }
}
