<?php

namespace App\Providers;

use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $data = BusinessSetting::where(['type' => 'sms_nexmo'])->first();
            $sms_nexmo = json_decode($data['value'], true);

            if ($sms_nexmo) {
                $config = array(
                    'api_key' => $sms_nexmo['nexmo_key'],
                    'api_secret' => $sms_nexmo['nexmo_secret'],
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'app' => ['name' => '', 'version' => ''],
                    'http_client' => ''
                );
                Config::set('nexmo', $config);
            }
            $timezone = BusinessSetting::where(['type' => 'timezone'])->first();
            if ($timezone) {
                Config::set('timezone', $timezone->value);
                date_default_timezone_set($timezone->value);
            }
        } catch (\Exception $ex) {}
    }
}
