<?php

namespace App\Http\Controllers\Seller\Zid;

use App\Model\Cron;
use Illuminate\Http\Request;
use App\Services\Zid\AuthService;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;


class OAuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function redirect()
    {
        return redirect($this->service->getProvider()->getAuthorizationUrl());
        // return redirect('https://oauth.zid.sa/oauth/authorize?response_type=code&redirect_uri=https%3A%2F%2Fbeauty-t-center.com%2Fseller%2Fzid%2Foauth%2Fcallback&client_id=2656');
    }

    public function callback(Request $request)
    {
        try {
            $token = $this->service->getAccessToken('authorization_code', [
                'code' => $request->code ?? ''
            ]);

            /** @var \App\Providers\ZidUser $merchant */
            $merchant = $this->service->getResourceOwner($token);

            $shop = auth()->user()->shop;
            if ($shop && $shop->platform == 'zid') {
                $shop->token()->updateOrCreate([], [
                    'merchant'      => $merchant->getStoreId(),
                    'access_token'  => $token->getToken(),
                    'expires_in'    => $token->getExpires(),
                    'refresh_token' => $token->getRefreshToken()
                ]);
                $shop->seller()->update(['remote_id' => $merchant->getId()]);

                $cron = new Cron();
                $cron->batch = 'salla';
                $cron->job = 'SyncProducts';
                $cron->meta = json_encode([
                    'id' => $shop->id,
                    'page' => 0,
                ]);
                $cron->save();
            }

            // TODO :: change it later to https://s.salla.sa/apps before go alive
            return redirect('/seller');
        } catch (IdentityProviderException $e) {
            // Failed to get the access token or merchant details.
            // show an error message to the merchant with good UI
            return redirect('/seller')->withStatus($e->getMessage());
        }
    }
}
