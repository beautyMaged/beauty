<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Model\BusinessSetting;
use App\Model\Currency;
use Illuminate\Http\Request;
use Modules\PaymentModule\Traits\Payment;

class PaymentController extends Controller
{
    use Payment;

    public function make_payment(Request $request)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $currency_code = 'BDT';
        } else {
            $default = BusinessSetting::where(['type' => 'system_default_currency'])->first()->value;
            $currency_code = Currency::find($default)->code;
        }

        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $user = Helpers::get_customer();

        $this->create_payment([
            'unit_id' => '',
            'unit_name' => '',
            'customer_id' => $user['id'],
            'payment_amount' => $value,
            'callback' => '',
            'hook' => '',
            'currency_code' => $currency_code,
            'business_name' => '',
            'business_logo_url' => '',
        ]);
    }
}
