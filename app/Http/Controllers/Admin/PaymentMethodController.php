<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.payment-method.index');
    }

    public function update(Request $request, $name)
    {
        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('type', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'cash_on_delivery'])->update([
                    'type' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'updated_at' => now()
                ]);
            }
        }
        if ($name == 'digital_payment') {
            DB::table('business_settings')->updateOrInsert(['type' => 'digital_payment'], [
                'value' => json_encode([
                    'status' => $request['status']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } elseif ($name == 'offline_payment') {
            DB::table('business_settings')->updateOrInsert(['type' => 'offline_payment'], [
                'value' => json_encode([
                    'status' => $request['status']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('type', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if (Currency::where(['code' => 'BDT'])->first() == false) {
                    Toastr::error('Please add BDT currency before enable this gateway.');
                    return back();
                }
                if ($request['status'] == 1) {
                    $request->validate([
                        'store_id' => 'required',
                        'store_password' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'ssl_commerz_payment'])->update([
                    'type' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('type', 'paypal')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if ($request['status'] == 1) {
                    $request->validate([
                        'paypal_client_id' => 'required',
                        'paypal_secret' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'paypal'])->update([
                    'type' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('type', 'stripe')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'api_key' => '',
                        'published_key' => ''
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if ($request['status'] == 1) {
                    $request->validate([
                        'api_key' => 'required',
                        'published_key' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'stripe'])->update([
                    'type' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('type', 'razor_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'razor_key' => '',
                        'razor_secret' => ''
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if (Currency::where(['code' => 'INR'])->first() == false) {
                    Toastr::error('Please add INR currency before enable this gateway.');
                    return back();
                }

                if ($request['status'] == 1) {
                    $request->validate([
                        'razor_key' => 'required',
                        'razor_secret' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'razor_pay'])->update([
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('type', 'senang_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'secret_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                if (Currency::where(['code' => 'MYR'])->first() == false) {
                    Toastr::error('Please add MYR currency before enable this gateway.');
                    return back();
                }

                if ($request['status'] == 1) {
                    $request->validate([
                        'secret_key' => 'required',
                        'merchant_id' => 'required'
                    ]);
                }

                DB::table('business_settings')->where(['type' => 'senang_pay'])->update([
                    'type' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'secret_key' => $request['secret_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('type', 'paystack')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'environment' => 'sandbox',
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if (Currency::where(['code' => 'ZAR'])->first() == false) {
                    Toastr::error('Please add ZAR currency before enable this gateway.');
                    return back();
                }

                if ($request['status'] == 1) {
                    $request->validate([
                        'publicKey' => 'required',
                        'secretKey' => 'required',
                        'paymentUrl' => 'required',
                        'merchantEmail' => 'required'
                    ]);
                }

                DB::table('business_settings')->where(['type' => 'paystack'])->update([
                    'type' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'paymob_accept') {
            DB::table('business_settings')->updateOrInsert(['type' => 'paymob_accept'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['type' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytabs') {
            DB::table('business_settings')->updateOrInsert(['type' => 'paytabs'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'profile_id' => $request['profile_id'],
                    'server_key' => $request['server_key'],
                    'base_url' => $request['base_url']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'fawry_pay') {
            DB::table('business_settings')->updateOrInsert(['type' => 'fawry_pay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'merchant_code' => $request['merchant_code'],
                    'security_key' => $request['security_key']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'mercadopago') {
            BusinessSetting::updateOrInsert(['type' => 'mercadopago'],
                ['value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'public_key' => $request['public_key'],
                    'access_token' => $request['access_token'],
                ]),
                    'updated_at' => now()]
            );
        } elseif ($name == 'flutterwave') {
            $payment = BusinessSetting::where('type', 'flutterwave')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'flutterwave',
                    'value' => json_encode([
                        'status' => 1,
                        'public_key' => '',
                        'secret_key' => '',
                        'hash' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'flutterwave'])->update([
                    'type' => 'flutterwave',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment' => $request['environment'],
                        'public_key' => $request['public_key'],
                        'secret_key' => $request['secret_key'],
                        'hash' => $request['hash'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'liqpay') {
            DB::table('business_settings')->updateOrInsert(['type' => 'liqpay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'public_key' => $request['public_key'],
                    'private_key' => $request['private_key']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytm') {
            DB::table('business_settings')->updateOrInsert(['type' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment' => $request['environment'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now()
            ]);
        }
        Toastr::success(translate('successfully_updated'));
        return back();
    }
}
