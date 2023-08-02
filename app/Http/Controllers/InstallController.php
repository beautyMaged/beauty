<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Model\BusinessSetting;
use App\Traits\ActivationClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class InstallController extends Controller
{
    use ActivationClass;

    public function step0()
    {
        return view('installation.step0');
    }

    public function step1()
    {
        $permission['curl_enabled'] = function_exists('curl_version');
        $permission['db_file_write_perm'] = is_writable(base_path('.env'));
        $permission['routes_file_write_perm'] = is_writable(base_path('app/Providers/RouteServiceProvider.php'));
        return view('installation.step1', compact('permission'));
    }

    public function step2()
    {
        return view('installation.step2');
    }

    public function step3()
    {
        return view('installation.step3');
    }

    public function step4()
    {
        return view('installation.step4');
    }

    public function step5()
    {
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        return view('installation.step5');
    }

    public function purchase_code(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzE0NDg1OTc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);

        $post = [
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'purchase_key' => $request['purchase_key'],
            'domain' => preg_replace("#^[^:/.]*[:/]+#i", "", url('/')),
        ];
        $response = $this->dmvf($post);

        return redirect($response . '?token=' . bcrypt('step_3'));
    }

    public function system_settings(Request $request)
    {
        DB::table('admins')->insertOrIgnore([
            'name' => $request['admin_name'],
            'email' => $request['admin_email'],
            'admin_role_id' => 1,
            'password' => bcrypt($request['admin_password']),
            'phone' => $request['admin_phone'],
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'currency_model'], [
            'value' => $request['currency_model']
        ]);

        DB::table('admin_wallets')->insert([
            'admin_id' => 1,
            'withdrawn' => 0,
            'commission_earned' => 0,
            'inhouse_earning' => 0,
            'delivery_charge_earned' => 0,
            'pending_amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'product_brand'], [
            'value' => 1
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'digital_product'], [
            'value' => 1
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_expected_delivery_date_message'], [
            'value' => json_encode([
                'status' => 0,
                'message' => ''
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_canceled'], [
            'value' => json_encode([
                'status' => 0,
                'message' => ''
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'offline_payment'], [
            'value' => json_encode([
                'status' => 0
            ])
        ]);

        $refund_policy = BusinessSetting::where(['type' => 'refund-policy'])->first();
        if ($refund_policy) {
            $refund_value = json_decode($refund_policy['value'], true);
            if(!isset($refund_value['status'])){
                BusinessSetting::where(['type' => 'refund-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $refund_policy['value'],
                    ]),
                ]);
            }
        }elseif(!$refund_policy){
            BusinessSetting::insert([
                'type' => 'refund-policy',
                'value' => json_encode([
                    'status' => 1,
                    'content' => '',
                ]),
            ]);
        }

        $return_policy = BusinessSetting::where(['type' => 'return-policy'])->first();
        if ($return_policy) {
            $return_value = json_decode($return_policy['value'], true);
            if(!isset($return_value['status'])){
                BusinessSetting::where(['type' => 'return-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $return_policy['value'],
                    ]),
                ]);
            }
        }elseif(!$return_policy){
            BusinessSetting::insert([
                'type' => 'return-policy',
                'value' => json_encode([
                    'status' => 1,
                    'content' => '',
                ]),
            ]);
        }

        $cancellation_policy = BusinessSetting::where(['type' => 'cancellation-policy'])->first();
        if ($cancellation_policy) {
            $cancellation_value = json_decode($cancellation_policy['value'], true);
            if(!isset($cancellation_value['status'])){
                BusinessSetting::where(['type' => 'cancellation-policy'])->update([
                    'value' => json_encode([
                        'status' => 1,
                        'content' => $cancellation_policy['value'],
                    ]),
                ]);
            }
        }elseif(!$cancellation_policy){
            BusinessSetting::insert([
                'type' => 'cancellation-policy',
                'value' => json_encode([
                    'status' => 1,
                    'content' => '',
                ]),
            ]);
        }

        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_close'], [
            'type' => 'temporary_close',
            'value' => json_encode([
                'status' => 0,
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'vacation_add'], [
            'type' => 'vacation_add',
            'value' => json_encode([
                'status' => 0,
                'vacation_start_date' => null,
                'vacation_end_date' => null,
                'vacation_note' => null
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'cookie_setting'], [
            'type' => 'cookie_setting',
            'value' => json_encode([
                'status' => 0,
                'cookie_text' => null
            ])
        ]);

        DB::table('colors')
            ->whereIn('id', [16,38,93])
            ->delete();

        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        //sleep(5);
        return view('installation.step6');
    }

    public function database_installation(Request $request)
    {
        if (self::check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {

            $key = base64_encode(random_bytes(32));
            $output = 'APP_NAME=6valley' . time() . '
                    APP_ENV=live
                    APP_KEY=base64:' . $key . '
                    APP_DEBUG=false
                    APP_INSTALL=true
                    APP_LOG_LEVEL=debug
                    APP_MODE=live
                    APP_URL=' . URL::to('/') . '

                    DB_CONNECTION=mysql
                    DB_HOST=' . $request->DB_HOST . '
                    DB_PORT=3306
                    DB_DATABASE=' . $request->DB_DATABASE . '
                    DB_USERNAME=' . $request->DB_USERNAME . '
                    DB_PASSWORD=' . $request->DB_PASSWORD . '

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=60
                    QUEUE_DRIVER=sync

                    AWS_ENDPOINT=
                    AWS_ACCESS_KEY_ID=
                    AWS_SECRET_ACCESS_KEY=
                    AWS_DEFAULT_REGION=us-east-1
                    AWS_BUCKET=

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    PUSHER_APP_ID=
                    PUSHER_APP_KEY=
                    PUSHER_APP_SECRET=
                    PUSHER_APP_CLUSTER=mt1

                    PURCHASE_CODE=' . session('purchase_key') . '
                    BUYER_USERNAME=' . session('username') . '
                    SOFTWARE_ID=MzE0NDg1OTc=

                    SOFTWARE_VERSION=' . SOFTWARE_VERSION . '
                    ';
            $file = fopen(base_path('.env'), 'w');
            fwrite($file, $output);
            fclose($file);

            $path = base_path('.env');
            if (file_exists($path)) {
                return redirect('step4');
            } else {
                session()->flash('error', 'Database error!');
                return redirect('step3');
            }
        } else {
            session()->flash('error', 'Database error!');
            return redirect('step3');
        }
    }

    public function import_sql()
    {
        try {
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Your database is not clean, do you want to clean database then import?');
            return back();
        }
    }

    public function force_import_sql()
    {
        try {
            Artisan::call('db:wipe');
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Check your database permission!');
            return back();
        }
    }

    function check_database_connection($db_host = "", $db_name = "", $db_user = "", $db_pass = "")
    {

        if (@mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
            return true;
        } else {
            return false;
        }
    }
}
