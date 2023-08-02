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
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Session;

class SocialAuthController extends Controller
{
    public function redirectToProvider(Request $request, $service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function handleProviderCallback($service)
    {
        $user_data = Socialite::driver($service)->stateless()->user();

        $user = User::where('email', $user_data->getEmail())->first();

        $name = explode(' ', $user_data['name']);
        if (count($name) > 1) {
            $fast_name = implode(" ", array_slice($name, 0, -1));
            $last_name = end($name);
        } else {
            $fast_name = implode(" ", $name);
            $last_name = '';
        }

        if (isset($user) == false) {
            $user = User::create([
                'f_name' => $fast_name,
                'l_name' => $last_name,
                'email' => $user_data->getEmail(),
                'phone' => '',
                'password' => bcrypt($user_data->id),
                'is_active' => 1,
                'login_medium' => $service,
                'social_id' => $user_data->id,
                'is_phone_verified' => 0,
                'is_email_verified' => 1,
                'temporary_token' => Str::random(40)
            ]);
        } else {
            $user->temporary_token = Str::random(40);
            $user->save();
        }

        /*if ($user->phone == '') {
            return redirect()->route('customer.auth.update-phone', $user->id);
        }*/
        //redirect if website user
        $message = self::login_process($user, $user_data->getEmail(), $user_data->id);
        Toastr::info($message);
        return redirect()->route('home');
    }

    public function editPhone($id)
    {
        $user = User::find($id);
        return view('customer-view.auth.update-phone', compact('user'));
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:users|min:11',
        ], [
            'f_name.required' => 'First Name is Required',
            'l_name.required' => 'Last Name is Required',
            'phone.required' => 'Phone Number is Required',
            'unique' => 'Phone Number Must Be Unique!',
            'phone.min' => 'Phone Number Should be Minimum of 11 Character'
        ]);

        $user = User::find($request->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->phone = $request->phone;
        $user->is_active = 1;
        $user->save();

        return redirect(route('customer.auth.check', [$user->id]));
    }

    public static function login_process($user, $email, $password)
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();

        if ($user->is_active && auth('customer')->attempt(['email' => $email, 'password' => $password], true)) {
            $wish_list = Wishlist::whereHas('wishlistProduct',function($q){
                return $q;
            })->where('customer_id', $user->id)->pluck('product_id')->toArray();

            session()->put('wish_list', $wish_list);
            $message = 'Welcome to ' . $company_name->value . '!';
            CartManager::cart_to_db();
        } else {
            $message = 'Credentials are not matched or your account is not active!';
        }

        return $message;
    }
}
