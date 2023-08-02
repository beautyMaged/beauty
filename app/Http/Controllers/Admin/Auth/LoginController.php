<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use App\Model\Admin;
use Gregwar\Captcha\PhraseBuilder;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
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

        if(Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function login()
    {
        return view('admin-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
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
//            } catch (\Exception $exception) {
//            }
//        } else {
//
//            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
//                Session::forget('default_captcha_code');
//                return back()->withErrors(\App\CPU\translate('Captcha Failed'));
//            }
//
//        }
        $admin = Admin::where('email', $request->email)->first();
        if (isset($admin) && $admin->status != 1) {
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['You are blocked!!, contact with admin.']);
        }else{
            if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                return redirect()->route('admin.dashboard');
            }
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.auth.login');
    }
}
