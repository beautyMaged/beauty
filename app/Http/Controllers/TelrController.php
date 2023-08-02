<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Model\BusinessSetting;
use App\Model\CartShipping;
use App\Model\Coupon;
use App\Model\Currency;
use App\Model\Order;
use App\Model\Product;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Model\ShippingType;
use App\Traits\CommonTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function App\CPU\translate;

class TelrController extends Controller
{
    use CommonTrait;

//    public function __construct() {
//
//    }

    public function view()
    {
        return view('web-views.test_map');
    }

    public function store(Request $request)
    {
//        return 'test';

        $currency_code = 'SAR';


        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;

        $user = [];
        $f_name = '';
        $l_name = '';
        $street_address = '';
        $city = '';
        $country = '';
        $zip = '';
        $email = '';
        $phone = '';
        if (Helpers::get_customer() == 'offline') {

            $f_name = 'Offline_';
            $l_name = session('person_name');
            $phone = session('person_phone');
            $country = session('person_area');
            $city = session('person_city');
            $zip = session('person_zip');
            $street_address = session('person_address');
            $email = session('person_email');

        } else {
            $user = Helpers::get_customer();
            $f_name = $user->f_name;
            $l_name = $user->l_name;
            $phone = $user->phone;
            $country = $user->country;
            $city = $user->city;
            $zip = '11011';
            $street_address = $user->street_address;
            $email = $user->email;
        }
//        return $l_name;
        $order_id = rand(0, 9999);
        $amount = $value;
        $billingParams = [
            'first_name' => $f_name,
            'sur_name' => $l_name,
            'address_1' => $street_address,
            'address_2' => $street_address,
            'city' => $city,
            'region' => $city,
            'zip' => $zip,
            'country' => $country,
            'email' => $email,
            'bill_phone' => $phone,
        ];

        $telrManager = new \TelrGateway\TelrManager();


        return $telrManager->pay($order_id, $amount, 'Telr Testing Payment', $billingParams)->redirect();

    }

    public function success(Request $request)
    {
        $telrManager = new \TelrGateway\TelrManager();
        $transaction = $telrManager->handleTransactionResponse($request);
        $tran_id = $transaction['response']['order']['transaction']['ref'];

//        return $request;
        if (Helpers::get_customer() == 'offline') {

            $carts = session('offline_cart');

            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart['product_type'] == 'physical') {
                    $physical_product = true;
                }
            }
            if ($physical_product) {
                foreach ($carts as $cart) {
                    $data = [
                        'payment_method' => 'telr',
                        'order_status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_ref' => $tran_id,
                        'order_group_id' => $unique_id,
                        'cart_group_id' => $cart['cart_group_id']
                    ];

                    $req = array_key_exists('request', $data) ? $data['request'] : null;

                    $coupon_process = array(
                        'discount' => 0,
                        'coupon_bearer' => 'inhouse',
                        'coupon_code' => 0,
                    );

                    if ((isset($req['coupon_code']) && $req['coupon_code']) || session()->has('coupon_code')) {
                        $coupon_code = $req['coupon_code'] ?? session('coupon_code');
                        $coupon = Coupon::where(['code' => $coupon_code])
                            ->where('status', 1)
                            ->first();

//                        return $coupon;
                        $coupon_process = $coupon ? self::coupon_process($data, $coupon) : $coupon_process;
                    }

                    $order_id = 100000 + Order::all()->count() + 1;
                    if (Order::find($order_id)) {
                        $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
                    }

                    $f_name = 'Offline_';
                    $l_name = session('person_name');
                    $phone = session('person_phone');
                    $country = session('person_area');
                    $city = session('person_city');
                    $zip = session('person_zip');
                    $street_address = session('person_address');
                    $email = session('person_email');

                    $address_id = new \stdClass();
                    $address_id->contact_person_name = session('person_name');
                    $address_id->phone = session('person_phone');
                    $address_id->city = session('person_city');
                    $address_id->email = session('person_email');
                    $address_id->address = session('person_address');

                    $billing_address_id = new \stdClass();
                    $billing_address_id->contact_person_name = session('person_name');
                    $billing_address_id->phone = session('person_phone');
                    $billing_address_id->city = session('person_city');
                    $billing_address_id->email = session('person_email');
                    $billing_address_id->address = session('person_address');




                    $coupon_code = $coupon_process['coupon_code'];
                    $coupon_bearer = $coupon_process['coupon_bearer'];
                    $discount = $coupon_process['discount'];
                    $order_note = session()->has('order_note') ? session('order_note') : null;

                    $cart_group_id = $data['cart_group_id'];

                    $this_carts = $carts;


                    $coupon_code = session()->has('coupon_code') ? session('coupon_code') : 0;
                    $coupon = Coupon::where(['code' => $coupon_code])
                        ->where('status', 1)
                        ->first();

                    $sub_total = 0;
                    $total_discount_on_product = 0;
                    $coupon_discount = 0;
                    if ($coupon && ($coupon->seller_id == NULL || $coupon->seller_id == '0' || $coupon->seller_id == $cart[0]->seller_id)) {
                        $coupon_discount = $coupon->coupon_type == 'free_delivery' ? 0 : $coupon_discount;
                    } else {
                        $coupon_discount = 0;
                    }

                    foreach ($this_carts as $item) {
                        $sub_total += $item['price'] * $item['quantity'];
                        $total_discount_on_product += $item['discount'] * $item['quantity'];
                    }

                    $order_total = $sub_total - $total_discount_on_product - $coupon_discount;
                    $cart_summery = [
                        'order_total' => $order_total
                    ];


                    $commission_amount = 0;
                    if ($carts[0]['seller_is'] == 'seller') {
                        $seller = Seller::find($carts[0]['seller_id']);
                        if (isset($seller) && $seller['sales_commission_percentage'] !== null) {
                            $commission = $seller['sales_commission_percentage'];
                        } else {
                            $commission = Helpers::get_business_settings('sales_commission');
                        }
                        $commission_amount = number_format(($cart_summery['order_total'] / 100) * $commission, 2);
                    }

                    if ($req != null) {
                        if (session()->has('person_address') == false) {
                            $address_id = $req->has('person_address') ? $req['person_address'] : null;
                        }
                    }

                    $user = Helpers::get_customer($req);

                    $seller_data = $carts[0];
                    $shipping_method = CartShipping::where(['cart_group_id' => $carts[0]['cart_group_id']])->first();
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
                        'customer_id' => 'guest_' . rand(100000, 999999),
                        'seller_id' => $seller_data['seller_id'],
                        'seller_is' => $seller_data['seller_is'],
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
                        'order_amount' => $cart_summery['order_total'] - $discount,
                        'admin_commission' => $commission_amount,
                        'shipping_address' => null,
                        'shipping_address_data' => json_encode($address_id),
                        'billing_address' => null,
                        'billing_address_data' => json_encode($billing_address_id),
                        'shipping_cost' => CartManager::get_shipping_cost($data['cart_group_id']),
                        'shipping_method_id' => $shipping_method_id,
                        'shipping_type' => $shipping_type,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'order_note' => $order_note
                    ];
//        confirmed
                    DB::table('orders')->insertGetId($or);
                    self::add_order_status_history($order_id, auth('customer')->id(), $data['payment_status'] == 'paid' ? 'confirmed' : 'pending', 'customer');

                    foreach ($this_carts as $c) {
                        $product = Product::where(['id' => $c['product_id']])->first();
                        $price = $c['tax_model'] == 'include' ? $c['price'] - $c['tax'] : $c['price'];
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
                            'payment_status' => 'paid',
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


//                    return $order_id;
                    array_push($order_ids, $order_id);
                }

                Session::forget('offline_cart');
                CartManager::cart_clean();
//            return $order_id;

                return view('web-views.checkout-complete', compact('order_id'));
            }

            return redirect(url('/'))->with('error', 'Something went wrong!');
        } else {

            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'telr',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => $tran_id,
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }
            CartManager::cart_clean();
            $order_id = $order_ids[0];

            return view('web-views.checkout-complete', compact('order_id'));
        }
//        return view('web-views.telr.payment_success');
    }

    public function cancel(Request $request)
    {
//        $telrManager = new \TelrGateway\TelrManager();
//        return $transaction = $telrManager->handleTransactionResponse($request);
        Toastr::error(translate('Your Order Has Been Canceled'));
        return redirect(url('/'));
//        return view('web-views.telr.payment_canceled');
    }

    public function declined(Request $request)
    {
        Toastr::error(translate('Your Order Has Been Declined Because of Some issue with The Bank'));
        return redirect(url('/'));
//        return view('web-views.telr.payment_declined');
    }

    public static function coupon_process($data, $coupon)
    {
        $req = array_key_exists('request', $data) ? $data['request'] : null;
        $coupon_discount = 0;
        if (session()->has('coupon_discount')) {
            $coupon_discount = session('coupon_discount');
        } elseif ($req['coupon_discount']) {
            $coupon_discount = $req['coupon_discount'];
        }

        $carts = $req ? CartManager::get_cart_for_api($req) : CartManager::get_cart();

        $group_id_wise_cart = session('offline_cart')->collect();
//        return $coupon;
//        return $group_id_wise_cart = CartManager::get_cart($data['cart_group_id']);
        $total_amount = 0;
        foreach ($carts as $cart) {
//            return $cart['seller_is'];
            if (($coupon->seller_id == NULL && $cart['seller_is'] == 'admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart['seller_id'] && $cart['seller_is'] == 'seller')) {
                $total_amount += ($cart['price'] * $cart['quantity']);

            }

        }

        if (($group_id_wise_cart[0]['seller_is'] == 'admin' && $coupon->seller_id == NULL) || $coupon->seller_id == '0' || ($coupon->seller_id == $group_id_wise_cart[0]['seller_id'] && $group_id_wise_cart[0]['seller_is'] == 'seller')) {
            $cart_group_ids = CartManager::get_cart_group_ids($req ?? null);
            $discount = 0;

            if ($coupon->coupon_type == 'discount_on_purchase' || $coupon->coupon_type == 'first_order') {
                $group_id_percent = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api($req, $cart_group_id) : session('offline_cart')->groupBy('cart_group_id')[$cart_group_id];
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
                $shippingMethod = Helpers::get_business_settings('shipping_method');

                $free_shipping_by_group_id = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api($req, $cart_group_id) : CartManager::get_cart($cart_group_id);

                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {

                        if ($cart_group_data[0]->seller_is == 'admin') {
                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Model\ShippingType::where('seller_id', $cart_group_data[0]->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($shipping_type == 'order_wise' && (($coupon->seller_id == null && $cart_group_data[0]->seller_is == 'admin') || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id)) {
                        $free_shipping_by_group_id[$cart_group_id] = $cart_group_data[0]->cart_shipping->shipping_cost ?? 0;
                    } else {
                        if (($coupon->seller_id == null && $cart_group_data[0]->seller_is == 'admin') || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id) {
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

}
