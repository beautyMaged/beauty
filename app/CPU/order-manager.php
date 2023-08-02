<?php

namespace App\CPU;

use App\Model\Admin;
use App\Model\AdminWallet;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Coupon;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\Model\ShippingType;
use App\Model\ShippingAddress;
use App\Model\Transaction;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class OrderManager
{
    use CommonTrait;
    public static function track_order($order_id)
    {
        $order = Order::where(['id' => $order_id])->first();
        $order['billing_address_data'] = json_decode($order['billing_address_data']);
        $order['shipping_address_data'] = json_decode($order['shipping_address_data']);
        return $order;
    }

    public static function gen_unique_id()
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }

    public static function order_summary($order)
    {
        $sub_total = 0;
        $total_tax = 0;
        $total_discount_on_product = 0;
        foreach ($order->details as $key => $detail) {
            $sub_total += $detail->price * $detail->qty;
            $total_tax += $detail->tax;
            $total_discount_on_product += $detail->discount;
        }
        $total_shipping_cost = $order['shipping_cost'];
        return [
            'subtotal' => $sub_total,
            'total_tax' => $total_tax,
            'total_discount_on_product' => $total_discount_on_product,
            'total_shipping_cost' => $total_shipping_cost,
        ];
    }

    public static function order_summary_before_place_order($cart, $coupon_discount)
    {
        $coupon_code = session()->has('coupon_code') ? session('coupon_code') : 0;
        $coupon = Coupon::where(['code' => $coupon_code])
            ->where('status',1)
            ->first();

        $sub_total = 0;
        $total_discount_on_product = 0;

        if($coupon && ($coupon->seller_id == NULL || $coupon->seller_id == '0' || $coupon->seller_id == $cart[0]->seller_id)){
            $coupon_discount = $coupon->coupon_type == 'free_delivery' ? 0 : $coupon_discount;
        }else{
            $coupon_discount = 0;
        }

        foreach ($cart as $item) {
            $sub_total += $item->price * $item->quantity;
            $total_discount_on_product += $item->discount * $item->quantity;
        }

        $order_total = $sub_total-$total_discount_on_product-$coupon_discount;
        return [
            'order_total' => $order_total
        ];
    }

    public static function stock_update_on_order_status_change($order, $status)
    {
        if ($status == 'returned' || $status == 'failed' || $status == 'canceled') {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 1) {
                    $product = Product::find($detail['product_id']);
                    $type = $detail['variant'];
                    $var_store = [];
                    foreach (json_decode($product['variation'], true) as $var) {
                        if ($type == $var['type']) {
                            $var['qty'] += $detail['qty'];
                        }
                        array_push($var_store, $var);
                    }
                    Product::where(['id' => $product['id']])->update([
                        'variation' => json_encode($var_store),
                        'current_stock' => $product['current_stock'] + $detail['qty'],
                    ]);
                    OrderDetail::where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 0,
                        'delivery_status' => $status
                    ]);
                }
            }
        } else {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = Product::find($detail['product_id']);

                    $type = $detail['variant'];
                    $var_store = [];
                    foreach (json_decode($product['variation'], true) as $var) {
                        if ($type == $var['type']) {
                            $var['qty'] -= $detail['qty'];
                        }
                        array_push($var_store, $var);
                    }
                    Product::where(['id' => $product['id']])->update([
                        'variation' => json_encode($var_store),
                        'current_stock' => $product['current_stock'] - $detail['qty'],
                    ]);
                    OrderDetail::where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 1,
                        'delivery_status' => $status
                    ]);
                }
            }
        }
    }

    public static function wallet_manage_on_order_status_change($order, $received_by)
    {
        $order = Order::find($order['id']);
        $order_summary = OrderManager::order_summary($order);
        $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount_amount'];
        $commission = $order['admin_commission'];
        $shipping_model = Helpers::get_business_settings('shipping_method');

        if (AdminWallet::where('admin_id', 1)->first() == false) {
            DB::table('admin_wallets')->insert([
                'admin_id' => 1,
                'withdrawn' => 0,
                'commission_earned' => 0,
                'inhouse_earning' => 0,
                'delivery_charge_earned' => 0,
                'pending_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (SellerWallet::where('seller_id', $order['seller_id'])->first() == false) {
            DB::table('seller_wallets')->insert([
                'seller_id' => $order['seller_id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if($order->coupon_code && $order->coupon_code != '0' && $order->seller_is == 'seller' && $order->discount_type == 'coupon_discount'){
            if($order->coupon_discount_bearer == 'inhouse'){
                $seller_wallet = SellerWallet::where('seller_id',$order->seller_id)->first();
                $seller_wallet->total_earning += $order->discount_amount;
                $seller_wallet->save();

                $paid_by = 'admin';
                $payer_id = 1;
                $payment_receiver_id = $order->seller_id;
                $paid_to = 'seller';

            }elseif($order->coupon_discount_bearer == 'seller'){
                $paid_by = 'seller';
                $payer_id = $order->seller_id;
                $payment_receiver_id = $order->seller_id;
                $paid_to = 'admin';
            }

            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->payment_for = 'coupon_discount';
            $transaction->payer_id = $payer_id;
            $transaction->payment_receiver_id = $payment_receiver_id;
            $transaction->paid_by = $paid_by;
            $transaction->paid_to = $paid_to;
            $transaction->payment_status = 'disburse';
            $transaction->amount = $order->discount_amount;
            $transaction->transaction_type = 'expense';
            $transaction->save();
        }

        if ($order['payment_method'] == 'cash_on_delivery' || $order['payment_method'] == 'offline_payment') {
            DB::table('order_transactions')->insert([
                'transaction_id' => OrderManager::gen_unique_id(),
                'customer_id' => $order['customer_id'],
                'seller_id' => $order['seller_id'],
                'seller_is' => $order['seller_is'],
                'order_id' => $order['id'],
                'order_amount' => $order_amount,
                'seller_amount' => $order_amount - $commission,
                'admin_commission' => $commission,
                'received_by' => $received_by,
                'status' => 'disburse',
                'delivery_charge' => $order['shipping_cost'],
                'tax' => $order_summary['total_tax'],
                'delivered_by' => $received_by,
                'payment_method' => $order['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $wallet = AdminWallet::where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            if ($shipping_model == 'inhouse_shipping') {
                $wallet->delivery_charge_earned += $order['shipping_cost'];
            }
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = AdminWallet::where('admin_id', 1)->first();
                $wallet->inhouse_earning += $order_amount;
                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                }
                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            } else {
                $wallet = SellerWallet::where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;
                $wallet->total_tax_collected += $order_summary['total_tax'];

                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                    $wallet->collected_cash += $order['order_amount']; //total order amount
                } else {
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'];
                }

                $wallet->save();
            }
        } else {
            $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
            $transaction->status = 'disburse';
            $transaction->save();

            $wallet = AdminWallet::where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            $wallet->pending_amount -= $order['order_amount'];
            if ($shipping_model == 'inhouse_shipping') {
                $wallet->delivery_charge_earned += $order['shipping_cost'];
            }
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = AdminWallet::where('admin_id', 1)->first();
                $wallet->inhouse_earning += $order_amount;
                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                }
                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            } else {
                $wallet = SellerWallet::where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;

                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'] + $order['shipping_cost'];
                } else {
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'];
                }

                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            }
        }
    }

    public static function coupon_process($data, $coupon){
        $req = array_key_exists('request', $data) ? $data['request'] : null;
        $coupon_discount = 0;
        if(session()->has('coupon_discount')){
            $coupon_discount = session('coupon_discount');
        }elseif($req['coupon_discount']){
            $coupon_discount = $req['coupon_discount'];
        }

        $carts = $req ? CartManager::get_cart_for_api($req) : CartManager::get_cart();
        $group_id_wise_cart = CartManager::get_cart($data['cart_group_id']);
        $total_amount = 0;
        foreach($carts as $cart) {
            if (($coupon->seller_id == NULL && $cart->seller_is=='admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart->seller_id && $cart->seller_is=='seller')) {
                $total_amount += ($cart['price'] * $cart['quantity']);

            }
        }

        if(($group_id_wise_cart[0]->seller_is=='admin' && $coupon->seller_id == NULL) || $coupon->seller_id == '0' || ($coupon->seller_id == $group_id_wise_cart[0]->seller_id && $group_id_wise_cart[0]->seller_is=='seller')){
            $cart_group_ids = CartManager::get_cart_group_ids($req ?? null);
            $discount = 0;

            if ($coupon->coupon_type == 'discount_on_purchase' || $coupon->coupon_type == 'first_order') {
                $group_id_percent = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api($req, $cart_group_id) : CartManager::get_cart($cart_group_id);
                    $cart_group_amount = 0;
                    if ($coupon->seller_id == NULL || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id) {
                        $cart_group_amount = $cart_group_data->sum(function ($item) {
                            return ($item['price'] * $item['quantity']);
                        });
                    }
                    $percent = number_format(($cart_group_amount / $total_amount) * 100, 2);
                    $group_id_percent[$cart_group_id] = $percent;
                }
                $discount = ($group_id_percent[$data['cart_group_id']] * $coupon_discount) / 100;

            } elseif ($coupon->coupon_type == 'free_delivery') {
                $shippingMethod=Helpers::get_business_settings('shipping_method');

                $free_shipping_by_group_id = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api($req, $cart_group_id) : CartManager::get_cart($cart_group_id);

                    if( $shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    }else {

                        if ($cart_group_data[0]->seller_is == 'admin') {
                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Model\ShippingType::where('seller_id', $cart_group_data[0]->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if($shipping_type == 'order_wise' && (($coupon->seller_id == null && $cart_group_data[0]->seller_is == 'admin') || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id)) {
                        $free_shipping_by_group_id[$cart_group_id] = $cart_group_data[0]->cart_shipping->shipping_cost ?? 0;
                    }else{
                        if(($coupon->seller_id == null && $cart_group_data[0]->seller_is == 'admin') || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id) {
                            $shipping_cost = CartManager::get_shipping_cost($data['cart_group_id']);
                            $free_shipping_by_group_id[$cart_group_id] = $shipping_cost;
                        }
                    }
                }
                $discount = (isset($free_shipping_by_group_id[$data['cart_group_id']]) && $free_shipping_by_group_id[$data['cart_group_id']]) ? $free_shipping_by_group_id[$data['cart_group_id']] : 0;
            }
            $calculate_data = array(
                'discount' => $discount,
                'coupon_bearer' => $coupon->coupon_bearer,
                'coupon_code' => $coupon->code,
            );
            return $calculate_data;
        }

        $calculate_data = array(
            'discount' => 0,
            'coupon_bearer' => 'inhouse',
            'coupon_code' => 0,
        );

        return $calculate_data;
    }

    public static function generate_order($data)
    {
        $req = array_key_exists('request', $data) ? $data['request'] : null;
        $coupon_process = array(
            'discount' => 0,
            'coupon_bearer' => 'inhouse',
            'coupon_code' => 0,
        );
        if((isset($req['coupon_code']) && $req['coupon_code']) || session()->has('coupon_code')){
            $coupon_code = $req['coupon_code'] ?? session('coupon_code');
            $coupon = Coupon::where(['code' => $coupon_code])
                ->where('status',1)
                ->first();

            $coupon_process = $coupon ? self::coupon_process($data, $coupon) : $coupon_process;
        }

        $order_id = 100000 + Order::all()->count() + 1;
        if (Order::find($order_id)) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }
        $address_id = session('address_id') ? session('address_id') : null;
        $billing_address_id = session('billing_address_id') ? session('billing_address_id') : null;
        $coupon_code = $coupon_process['coupon_code'];
        $coupon_bearer = $coupon_process['coupon_bearer'];
        $discount = $coupon_process['discount'];
        $order_note = session()->has('order_note') ? session('order_note') : null;

        $cart_group_id = $data['cart_group_id'];
        $admin_commission = Helpers::sales_commission_before_order($cart_group_id, $discount);

        if ($req != null) {
            if (session()->has('address_id') == false) {
                $address_id = $req->has('address_id') ? $req['address_id'] : null;
            }
        }
        $user = Helpers::get_customer($req);

        $seller_data = Cart::where(['cart_group_id' => $cart_group_id])->first();
        $shipping_method = CartShipping::where(['cart_group_id' => $cart_group_id])->first();
        if (isset($shipping_method)) {
            $shipping_method_id = $shipping_method->shipping_method_id;
        } else {
            $shipping_method_id = 0;
        }

        $shipping_model = Helpers::get_business_settings('shipping_method');
        if ($shipping_model == 'inhouse_shipping') {
            $admin_shipping = ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
        } else {
            if ($seller_data->seller_is == 'admin') {
                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
            } else {
                $seller_shipping = ShippingType::where('seller_id', $seller_data->seller_id)->first();
                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
            }
        }

        $or = [
            'id' => $order_id,
            'verification_code' => rand(100000, 999999),
            'customer_id' => $user->id,
            'seller_id' => $seller_data->seller_id,
            'seller_is' => $seller_data->seller_is,
            'customer_type' => 'customer',
            'payment_status' => $data['payment_status'],
            'order_status' => $data['order_status'],
            'payment_method' => $data['payment_method'],
            'transaction_ref' => $data['transaction_ref'],
            'payment_by' => isset($data['payment_by']) ? $data['payment_by'] : NULL,
            'payment_note' => isset($data['payment_note']) ? $data['payment_note'] : NULL,
            'order_group_id' => $data['order_group_id'],
            'discount_amount' => $discount,
            'discount_type' => $discount == 0 ? null : 'coupon_discount',
            'coupon_code' => $coupon_code,
            'coupon_discount_bearer' => $coupon_bearer,
            'order_amount' => CartManager::cart_grand_total($cart_group_id) - $discount,
            'admin_commission' => $admin_commission,
            'shipping_address' => $address_id,
            'shipping_address_data' => ShippingAddress::find($address_id),
            'billing_address' => $billing_address_id,
            'billing_address_data' => ShippingAddress::find($billing_address_id),
            'shipping_cost' => CartManager::get_shipping_cost($data['cart_group_id']),
            'shipping_method_id' => $shipping_method_id,
            'shipping_type' => $shipping_type,
            'created_at' => now(),
            'updated_at' => now(),
            'order_note' => $order_note
        ];

//        confirmed
        DB::table('orders')->insertGetId($or);
        self::add_order_status_history($order_id, auth('customer')->id(), $data['payment_status']=='paid'?'confirmed':'pending', 'customer');

        foreach (CartManager::get_cart($data['cart_group_id']) as $c) {
            $product = Product::where(['id' => $c['product_id']])->first();
            $price = $c['tax_model']=='include' ? $c['price']-$c['tax'] : $c['price'];
            $or_d = [
                'order_id' => $order_id,
                'product_id' => $c['product_id'],
                'seller_id' => $c['seller_id'],
                'product_details' => $product,
                'qty' => $c['quantity'],
                'price' => $price,
                'tax' => $c['tax'] * $c['quantity'],
                'tax_model' => $c['tax_model'],
                'discount' => $c['discount'] * $c['quantity'],
                'discount_type' => 'discount_on_product',
                'variant' => $c['variant'],
                'variation' => $c['variations'],
                'delivery_status' => 'pending',
                'shipping_method_id' => null,
                'payment_status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now()
            ];

            if ($c['variant'] != null) {
                $type = $c['variant'];
                $var_store = [];
                foreach (json_decode($product['variation'], true) as $var) {
                    if ($type == $var['type']) {
                        $var['qty'] -= $c['quantity'];
                    }
                    array_push($var_store, $var);
                }
                Product::where(['id' => $product['id']])->update([
                    'variation' => json_encode($var_store),
                ]);
            }

            Product::where(['id' => $product['id']])->update([
                'current_stock' => $product['current_stock'] - $c['quantity']
            ]);

            DB::table('order_details')->insert($or_d);

        }

        if ($or['payment_method'] != 'cash_on_delivery' && $or['payment_method'] != 'offline_payment') {
            $order = Order::find($order_id);
            $order_summary = OrderManager::order_summary($order);
            $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount'];

            DB::table('order_transactions')->insert([
                'transaction_id' => OrderManager::gen_unique_id(),
                'customer_id' => $order['customer_id'],
                'seller_id' => $order['seller_id'],
                'seller_is' => $order['seller_is'],
                'order_id' => $order_id,
                'order_amount' => $order_amount,
                'seller_amount' => $order_amount - $admin_commission,
                'admin_commission' => $admin_commission,
                'received_by' => 'admin',
                'status' => 'hold',
                'delivery_charge' => $order['shipping_cost'],
                'tax' => $order_summary['total_tax'],
                'delivered_by' => 'admin',
                'payment_method' => $or['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (AdminWallet::where('admin_id', 1)->first() == false) {
                DB::table('admin_wallets')->insert([
                    'admin_id' => 1,
                    'withdrawn' => 0,
                    'commission_earned' => 0,
                    'inhouse_earning' => 0,
                    'delivery_charge_earned' => 0,
                    'pending_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('admin_wallets')->where('admin_id', $order['seller_id'])->increment('pending_amount', $order['order_amount']);
        }

        if ($seller_data->seller_is == 'admin') {
            $seller = Admin::find($seller_data->seller_id);
        } else {
            $seller = Seller::find($seller_data->seller_id);
        }

        try {
            $fcm_token = $user->cm_firebase_token;
            $seller_fcm_token = $seller->cm_firebase_token;
            if ($data['payment_method'] != 'cash_on_delivery' && $or['payment_method'] != 'offline_payment') {
                $value = Helpers::order_status_update_message('confirmed');
            } else {
                $value = Helpers::order_status_update_message('pending');
            }

            if ($value) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order_id,
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                Helpers::send_push_notif_to_device($seller_fcm_token, $data);
            }

            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                Mail::to($user->email)->send(new \App\Mail\OrderPlaced($order_id));
                Mail::to($seller->email)->send(new \App\Mail\OrderReceivedNotifySeller($order_id));
            }
        } catch (\Exception $exception) {
            //echo $exception;
        }

        return $order_id;
    }
}
