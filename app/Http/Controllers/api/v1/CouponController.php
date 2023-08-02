<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\CPU\translate;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $couponLimit = Order::where(['customer_id'=> $request->user()->id, 'coupon_code'=> $request['code']])
            ->groupBy('order_group_id')->get()->count();

        $coupon_f = Coupon::where(['code' => $request['code']])
            ->where('status',1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))->first();

        if(!$coupon_f){
            return response()->json(translate('invalid_coupon'), 202);
        }
        if($coupon_f && $coupon_f->coupon_type == 'first_order'){
            $coupon = $coupon_f;
        }else{
            $coupon = $coupon_f->limit > $couponLimit ? $coupon_f : null;
        }

        if($coupon && $coupon->coupon_type == 'first_order'){
            $orders = Order::where(['customer_id'=> $request->user()->id])->count();
            if($orders>0){
                return response()->json(translate('sorry_this_coupon_is_not_valid_for_this_user'), 202);
            }
        }

        if ($coupon && (($coupon->coupon_type == 'first_order') || ($coupon->coupon_type == 'discount_on_purchase' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)))) {
            $total = 0;
            foreach (CartManager::get_cart_for_api($request) as $cart) {
                if((is_null($coupon->seller_id) && $cart->seller_is=='admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller')){
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                }
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }

                return response()->json([
                    'coupon_discount' => $discount
                ], 200);
            }
        }elseif($coupon && $coupon->coupon_type == 'free_delivery' && ($coupon->customer_id == '0' || $coupon->customer_id == $request->user()->id)){
            $total = 0;
            $shipping_fee = 0;
            $shippingMethod=Helpers::get_business_settings('shipping_method');
            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

            foreach (CartManager::get_cart_for_api($request) as $cart) {
                if($coupon->seller_id == '0' || (is_null($coupon->seller_id) && $cart->seller_is=='admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller')) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                    if (is_null($coupon->seller_id) || $coupon->seller_id == '0' || $coupon->seller_id == $cart->seller_id) {
                        $shipping_fee += $cart['shipping_cost'];
                    }
                }
                if($shipping_type == 'order_wise' && ($coupon->seller_id=='0' || (is_null($coupon->seller_id) && $cart->seller_is=='admin') || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller'))) {
                    $shipping_fee += CartManager::get_shipping_cost($cart->cart_group_id);
                }
            }

            if ($total >= $coupon['min_purchase']) {
                return response()->json([
                    'coupon_discount' => $shipping_fee
                ], 200);
            }
        }

        return response()->json(translate('invalid_coupon'), 202);
    }
}
