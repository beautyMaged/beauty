<?php

namespace App\Http\Controllers\Customer;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Cart;
use App\Model\ShippingType;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_address_id' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        session()->put('customer_id', $request['customer_id']);
        session()->put('order_note', $request['order_note']);
        session()->put('address_id', $request['address_id']);
        session()->put('billing_address_id', $request['billing_address_id']);
        session()->put('coupon_code', $request['coupon_code']);
        session()->put('coupon_discount', $request['coupon_discount']);
        session()->put('payment_mode', 'app');

        $payment_method = $request->payment_method;
        $discount = $request['coupon_discount'] ?? 0;
        if ($discount > 0) {
            session()->put('coupon_code', $request['coupon_code']);
            session()->put('coupon_discount', $discount);
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        $shippingMethod = Helpers::get_business_settings('shipping_method');
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();
        $physical_product = false;
        foreach($carts as $cart)
        {
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }

            if ($shippingMethod == 'inhouse_shipping') {
                $admin_shipping = ShippingType::where('seller_id',0)->first();
                $shipping_type = isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise';
            } else {
                if($cart->seller_is == 'admin'){
                    $admin_shipping = ShippingType::where('seller_id',0)->first();
                    $shipping_type = isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise';
                }else{
                    $seller_shipping = ShippingType::where('seller_id',$cart->seller_id)->first();
                    $shipping_type = isset($seller_shipping)==true?$seller_shipping->shipping_type:'order_wise';
                }
            }

            if($shipping_type == 'order_wise'){
                $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                if (!isset($cart_shipping) && $physical_product) {
                    return response()->json(['errors' => ['code' => 'shipping-method', 'message' => 'Data not found']], 403);
                }
            }
        }

        $customer = User::find($request['customer_id']);

        if (isset($customer)) {
            return view('web-views.mobile-app-view.payment', compact('payment_method'));
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }

    public function success()
    {
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
