<?php

namespace App\Providers;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class ZidOauth extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $base_url = 'https://oauth.zid.sa';

    protected $headers = [];


    public function getAccessToken2($grant, array $options = [])
    {
        $grant = $this->verifyGrant($grant);

        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
        ];

        if (!empty($this->pkceCode)) {
            $params['code_verifier'] = $this->pkceCode;
        }

        $params   = $grant->prepareRequestParameters($params, $options);
        $request  = $this->getAccessTokenRequest($params);
        dd($params, $request);
        $response = $this->getParsedResponse($request);
        if (false === is_array($response)) {
            echo 'error';
        }
        $prepared = $this->prepareAccessTokenResponse($response);
        $token    = $this->createAccessToken($prepared, $grant);

        return $token;
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->base_url . '/oauth/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param  array  $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->base_url . '/oauth/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken  $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.zid.dev/app/v1/managers/account/profile';
    }

    /**
     * @param  array  $headers
     *
     * @return ZidOauth
     */
    public function setHeaders(array $headers): ZidOauth
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     *
     * The provided scope will be used if you don't give any scope
     * and this scope will be used to grab user accounts public information
     *
     * @var array List of scopes that will be used for authentication.
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return ',';
    }

    /**
     * Check a provider response for errors.
     *
     * @param  ResponseInterface  $response
     * @param  array|string  $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (empty($data['error'])) {
            return;
        }

        $error = $data['error']['message'] ?? $data['error_description'] ?? null;
        throw new IdentityProviderException(
            $error,
            $response->getStatusCode(),
            $data
        );
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param  array  $response
     * @param  AccessToken  $token
     *
     * @return ZidUser
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ZidUser($response);
    }

    /**
     * @param  string  $method
     * @param  string  $url
     * @param  string|AccessToken  $token
     * @param  array  $options
     *
     * @return array|mixed|string
     * @throws IdentityProviderException
     */
    public function fetchResource(string $method, string $url, $token, array $options = [])
    {
        if ($token instanceof AccessToken) {
            $token = $token->getToken();
        }

        $request = $this->getAuthenticatedRequest($method, $url, $token, $options);

        return $this->getParsedResponse($request);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * Typically this is used to set 'Accept' or 'Content-Type' headers.
     *
     * @return array
     */
    protected function getDefaultHeaders()
    {
        return $this->headers;
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return [
            'X-Manager-Token' => $token
        ];
    }
}
