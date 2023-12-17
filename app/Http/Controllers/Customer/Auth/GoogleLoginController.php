<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class GoogleLoginController extends Controller
{
   
    function redirect()
    {
        // update your .env file and delete these three lines
        config(['services.google.client_id' => env('GOOGLE_CLIENT_ID')]);
        config(['services.google.client_secret' => env('GOOGLE_CLIENT_SECRET')]);
        config(['services.google.redirect' => env('GOOGLE_SERVICE_CALLBACK')]);

        return Socialite::driver('google')->redirect();
    }
    // callback
    function callback() {
        // update your .env file and delete these three lines
        config(['services.google.client_id' => env('GOOGLE_CLIENT_ID')]);
        config(['services.google.client_secret' => env('GOOGLE_CLIENT_SECRET')]);
        config(['services.google.redirect' => env('GOOGLE_SERVICE_CALLBACK')]);
        try{$googleUser = Socialite::driver('google')->user();}catch(Exception $e){return $e;};

        $user = User::updateOrCreate([
            'email' => $googleUser->email,],
            ['f_name' => $googleUser->user["given_name"],
            "l_name"=> $googleUser->user["family_name"],
            "image" => $googleUser->avatar,
            "email_verified_at" => now()]);
            Auth::login($user);
            $isFirstTimeLogin = $user->wasRecentlyCreated;
            if($isFirstTimeLogin){
                // if it was the first time, redirect to allow notification page
                return response()->json(["message"=>"welcome ".$googleUser->user["given_name"],
                                        "first_time" => true],200);

            }else{
                //redirect after lgging in  
                return response()->json(["message"=>"welcome ".$googleUser->user["given_name"],
                                        "first_time" => false],200);
            }
        
       
    }
}

