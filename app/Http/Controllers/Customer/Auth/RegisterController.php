<?php

namespace App\Http\Controllers\Customer\Auth;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\SMS_module;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\PhoneOrEmailVerification;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use function App\CPU\translate;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function register()
    {
        session()->put('keep_return_url', url()->previous());
        return view('customer-view.auth.register');
    }

    public function submit(Request $request)
    {
        $request->flashExcept(['password', 'con_password']);
        $request->validate([
            'f_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'unique:users',
            'password' => 'required|min:8|same:con_password'
        ], [
            'f_name.required' => 'First name is required',
        ]);
        
        //recaptcha validation
        //        $recaptcha = Helpers::get_business_settings('recaptcha');
        //        if (isset($recaptcha) && $recaptcha['status'] == 1) {
        //            try {
        //                $request->validate([
        //                    'g-recaptcha-response' => [
        //                        function ($attribute, $value, $fail) {
        //                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
        //                            $response = $value;
        //                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
        //                            $response = \file_get_contents($url);
        //                            $response = json_decode($response);
        //                            if (!$response->success) {
        //                                $fail(\App\CPU\translate('ReCAPTCHA Failed'));
        //                            }
        //                        },
        //                    ],
        //                ]);
        //            } catch (\Exception $exception) {}
        //        } else {
        //            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
        //                \Illuminate\Support\Facades\Session::forget('default_captcha_code');
        //                return back()->withErrors(\App\CPU\translate('Captcha Failed'));
        //            }
        //        }

        $user = User::create([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'is_active' => 1,
            'password' => bcrypt($request['password'])
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');

        auth('customer')->loginUsingId($user->id);
        
        if ($phone_verification && !$user->is_phone_verified)
            return redirect(route('customer.auth.check', [$user->id]));

        if ($email_verification && !$user->is_email_verified)
            return redirect(route('customer.auth.check', [$user->id]));

        // Toastr::success(translate('registration_success_login_now'));

        return  response()->json([
            'success' => true,
            'message' => translate('registration_success_login_now')
        ]);
    }

    public static function check($id)
    {
        $user = User::find($id);

        $token = rand(1000, 9999);
        DB::table('phone_or_email_verifications')->insert([
            'phone_or_email' => $user->email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            SMS_module::send($user->phone, $token);
            $response = translate('please_check_your_SMS_for_OTP');
            Toastr::success($response);
        }

        if ($email_verification && !$user->is_email_verified) {
            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                Mail::to($user->email)->send(new \App\Mail\EmailVerification($token));
                $response = translate('check_your_email');
            } else {
                $response = translate('email_failed');
            }

            Toastr::success($response);
        }

        return view('customer-view.auth.verify', compact('user'));
    }

    public static function verify(Request $request)
    {
        Validator::make($request->all(), [
            'token' => 'required',
        ]);

        $email_status = Helpers::get_business_settings('email_verification');
        $phone_status = Helpers::get_business_settings('phone_verification');

        $user = User::find($request->id);
        $verify = PhoneOrEmailVerification::where(['phone_or_email' => $user->email, 'token' => $request['token']])->first();

        if ($email_status == 1 || ($email_status == 0 && $phone_status == 0)) {
            if (isset($verify)) {
                try {
                    $user->is_email_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success(translate('verification_done_successfully'));
            } else {
                Toastr::error(translate('Verification_code_or_OTP mismatched'));
                return redirect()->back();
            }
        } else {
            if (isset($verify)) {
                try {
                    $user->is_phone_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success('Verification Successfully Done');
            } else {
                Toastr::error('Verification code/ OTP mismatched');
            }
        }

        return redirect(route('customer.auth.login'));
    }

    public static function login_process($user, $email, $password)
    {
        if (auth('customer')->attempt(['email' => $email, 'password' => $password], true)) {
            $wish_list = Wishlist::whereHas('wishlistProduct', function ($q) {
                return $q;
            })->where('customer_id', $user->id)->pluck('product_id')->toArray();

            session()->put('wish_list', $wish_list);
            $company_name = BusinessSetting::where('type', 'company_name')->first();
            $message = 'Welcome to ' . $company_name->value . '!';
            CartManager::cart_to_db();
        } else {
            $message = 'Credentials are not matched or your account is not active!';
        }

        return $message;
    }
}
