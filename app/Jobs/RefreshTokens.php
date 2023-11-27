<?php

namespace App\Jobs;

use App\Model\Cron;
use App\Model\SallaOauthToken;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshTokens
{
    use Dispatchable;

    public function handle()
    {
        $crons = SallaOauthToken::select('id')->get()->map(fn($token) => [
            'batch' => 'salla',
            'job' => 'SallaRefreshTokens',
            'metta' => json_encode(['id' => $token->id]),
        ]);
        
        Cron::insert($crons->toArray());
    }
}
