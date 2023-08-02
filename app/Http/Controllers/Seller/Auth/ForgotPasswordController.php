<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Model\Seller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\CPU\Helpers;
use App\CPU\SMS_module;
use function App\CPU\translate;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:seller', ['except' => ['logout']]);
    }

    public function forgot_password()
    {
        return view('seller-views.auth.forgot-password');
    }

    public function reset_password_request(Request $request)
    {
        $request->validate([
            'identity' => 'required',
        ]);

        session()->put('forgot_password_identity', $request['identity']);
        $verification_by = Helpers::get_business_settings('forgot_password_verification');


        if($verification_by == 'email')
        {
            $seller = Seller::Where(['email' => $request['identity']])->first();
            if (isset($seller)) {
                $token = Str::random(120);
                DB::table('password_resets')->insert([
                    'identity' => $seller['email'],
                    'token' => $token,
                    'user_type'=>'seller',
                    'created_at' => now(),
                ]);
                $reset_url = url('/') . '/seller/auth/reset-password?token=' . $token;
                Mail::to($seller['email'])->send(new \App\Mail\PasswordResetMail($reset_url));
    
                Toastr::success('Check your email. Password reset url sent.');
                return back();
            }
        }elseif ($verification_by == 'phone') {
            $seller = Seller::Where('phone', 'like', "%{$request['identity']}%")->first();
            if (isset($seller)) {
                $token = rand(1000, 9999);
                DB::table('password_resets')->insert([
                    'identity' => $seller['phone'],
                    'token' => $token,
                    'user_type'=>'seller',
                    'created_at' => now(),
                ]);
                SMS_module::send($seller->phone, $token);
                Toastr::success('Check your phone. Password reset otp sent.');
                return redirect()->route('seller.auth.otp-verification');
            }
        }

        Toastr::error('No such user found!');
        return back();
    }

    public function reset_password_index(Request $request)
    {
        $data = DB::table('password_resets')->where('user_type','seller')->where(['token' => $request['token']])->first();
        if (isset($data)) {
            $token = $request['token'];
            return view('seller-views.auth.reset-password', compact('token'));
        }
        Toastr::error('Invalid URL.');
        return redirect('/seller/auth/login');
    }

    public function otp_verification()
    {
        return view('seller-views.auth.verify-otp');
    }

    public function otp_verification_submit(Request $request)
    {
        $id = session('forgot_password_identity');
        $data = DB::table('password_resets')->where('user_type','seller')->where(['token' => $request['otp']])
            ->where('identity', 'like', "%{$id}%")
            ->first();
        if (isset($data)) {
            $token = $request['otp'];
            return redirect()->route('seller.auth.reset-password', ['token' => $token]);
        }

        Toastr::error(translate('invalid_otp'));
        return back();
    }

    public function reset_password_submit(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
        ]);

        $data = DB::table('password_resets')->where('user_type','seller')->where(['token' => $request['reset_token']])->first();
        if (isset($data)) {
            DB::table('sellers')->where(['email' => $data->identity])
                                ->orWhere(['phone' => $data->identity])->update([
                'password' => bcrypt($request['confirm_password'])
            ]);
            Toastr::success('Password reset successfully.');
            DB::table('password_resets')->where('user_type','seller')->where(['token' => $request['reset_token']])->delete();
            return redirect('/seller/auth/login');
        }
        Toastr::error('Invalid URL.');
        return redirect('/seller/auth/login');
    }
}
