<?php

namespace App\Http\Controllers\Customer\Auth;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\PhraseBuilder;

class LoginController extends Controller
{
    public $company_name;

    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function captcha($tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if (Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function login()
    {
        session()->put('keep_return_url', url()->previous());
        return view('customer-view.auth.login');
    }

    public function submit(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:8'
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
        //                Session::forget('default_captcha_code');
        //                return back()->withErrors(\App\CPU\translate('Captcha Failed'));
        //            }
        //        }

        $remember = ($request['remember']) ? true : false;

        $user = User::where(['phone' => $request->user_id])->orWhere(['email' => $request->user_id])->first();

        if (isset($user) == false) {
            Toastr::error('Credentials do not match or account has been suspended.');
            return back()->withInput();
        }

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }
        if ($email_verification && !$user->is_email_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }
        if (isset($user) && $user->is_active && auth('customer')->attempt(['email' => $user->email, 'password' => $request->password], $remember)) {
            //            $wish_list = Wishlist::whereHas('wishlistProduct',function($q){
            //                return $q;
            //            })->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray();
            //
            //            session()->put('wish_list', $wish_list);
            Toastr::info('Welcome to ' . Helpers::get_business_settings('company_name') . '!');
            CartManager::cart_to_db();
            return redirect(session('keep_return_url'));
        }

        Toastr::error('Credentials do not match or account has been suspended.');
        return back()->withInput();
    }

    public function submitFromModal(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'password_log' => 'required|min:8'
        ]);



        $remember = ($request['remember']) ? true : false;

        $user = User::where(['phone' => $request->user_id])->orWhere(['email' => $request->user_id])->first();

        if (isset($user) == false) {
            Toastr::error('Credentials do not match or account has been suspended.');
            return back()->withInput();
        }

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }
        if ($email_verification && !$user->is_email_verified) {
            return redirect(route('customer.auth.check', [$user->id]));
        }
        if (isset($user) && $user->is_active && auth('customer')->attempt(['email' => $user->email, 'password' => $request->password_log], $remember)) {
            //            $wish_list = Wishlist::whereHas('wishlistProduct',function($q){
            //                return $q;
            //            })->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray();
            //
            //            session()->put('wish_list', $wish_list);
            // Toastr::info('Welcome to ' . Helpers::get_business_settings('company_name') . '!');
            CartManager::cart_to_db();
            //            return redirect(session('keep_return_url'));
            return  response()->json([
                'success' => true,
                'message' => 'Welcome to ' . Helpers::get_business_settings('company_name') . '!'
            ]);
        }

        Toastr::error('Credentials do not match or account has been suspended.');
        return back()->withInput();
    }


    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');
        Toastr::info('Come back soon, ' . '!');
        return redirect()->route('home');
    }
}
