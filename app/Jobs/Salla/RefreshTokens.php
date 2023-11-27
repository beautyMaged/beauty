<?php

namespace App\Jobs\Salla;

use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Salla\AuthService as SallaAuthService;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use App\Model\SallaOauthToken;

class RefreshTokens
{
    use Dispatchable;

    private $meta;
    private $cron;
    private $salla;
    public function __construct($cron)
    {
        $this->cron = $cron;
        $this->salla = new SallaAuthService();
    }

    public function clearCronJob()
    {
        $this->cron->delete();
    }

    public function handle()
    {
        $this->clearCronJob();
        if ($token = SallaOauthToken::find($this->meta->id)) {
            $this->salla->forToken($token);
            try {
                $this->salla->getNewAccessToken();
            } catch (IdentityProviderException $exception) {
                $token->delete();
            }
        }
    }
}
