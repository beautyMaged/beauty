<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::prefix('app')->group(function () {
    /*Auth::routes();*/
    Route::get('authentication-failed', function () {
        $errors = [];
        array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
        return response()->json([
            'errors' => $errors
        ], 401);
    })->name('authentication-failed');

    Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customer.'], function () {

        Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
            Route::get('login', 'LoginController@login')->name('login');
            Route::post('login', 'LoginController@submit');
            Route::post('loginFromModal', 'LoginController@submitFromModal')->name('loginFromModal');
            Route::get('logout', 'LoginController@logout')->name('logout');

            Route::get('sign-up', 'RegisterController@register')->name('sign-up');
            Route::post('sign-up', 'RegisterController@submit');

            Route::get('check/{id}', 'RegisterController@check')->name('check');

            Route::post('verify', 'RegisterController@verify')->name('verify');

            Route::get('update-phone/{id}', 'SocialAuthController@editPhone')->name('update-phone');
            Route::post('update-phone/{id}', 'SocialAuthController@updatePhone');

            Route::get('login/{service}', 'SocialAuthController@redirectToProvider')->name('service-login');
            Route::get('login/{service}/callback', 'SocialAuthController@handleProviderCallback')->name('service-callback');

            Route::get('recover-password', 'ForgotPasswordController@reset_password')->name('recover-password');
            Route::post('forgot-password', 'ForgotPasswordController@reset_password_request')->name('forgot-password');
            Route::get('otp-verification', 'ForgotPasswordController@otp_verification')->name('otp-verification');
            Route::post('otp-verification', 'ForgotPasswordController@otp_verification_submit');
            Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
            Route::post('reset-password', 'ForgotPasswordController@reset_password_submit');
        });

        Route::group(['prefix' => 'payment-mobile'], function () {
            Route::get('/', 'PaymentController@payment')->name('payment-mobile');
        });

        Route::group([], function () {
            Route::get('set-payment-method/{name}', 'SystemController@set_payment_method')->name('set-payment-method');
            Route::get('set-shipping-method', 'SystemController@set_shipping_method')->name('set-shipping-method');
            Route::post('choose-shipping-address', 'SystemController@choose_shipping_address')->name('choose-shipping-address');
            Route::post('choose-billing-address', 'SystemController@choose_billing_address')->name('choose-billing-address');

            Route::group(['prefix' => 'reward-points', 'as' => 'reward-points.', 'middleware' => ['auth:customer']], function () {
                Route::get('convert', 'RewardPointController@convert')->name('convert');
            });
        });
    });
});
