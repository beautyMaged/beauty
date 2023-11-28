<?php

namespace App\CPU;

use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Color;
use App\Model\Currency;
use App\Model\Product;
use App\Model\Shop;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Cassandra\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Model\ShippingType;
use App\Model\CategoryShippingCost;

class CartManager
{
    public static function cart_to_db()
    {
        $user = Helpers::get_customer();
        if (session()->has('offline_cart')) {
            $cart = session('offline_cart');
            $storage = [];
            foreach ($cart as $item) {
                $db_cart = Cart::where(
                    ['customer_id' => $user->id, 'seller_id' => $item['seller_id'], 'seller_is' => $item['seller_is']]
                )->first();
                $storage[] = [
                    'customer_id' => $user->id,
                    'cart_group_id' => isset($db_cart) ? $db_cart['cart_group_id'] : str_replace(
                        'offline',
                        $user->id,
                        $item['cart_group_id']
                    ),
                    'product_id' => $item['product_id'],
                    'color' => $item['color'],
                    'choices' => $item['choices'],
                    'variations' => $item['variations'],
                    'variant' => $item['variant'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax' => $item['tax'],
                    'discount' => $item['discount'],
                    'slug' => $item['slug'],
                    'name' => $item['name'],
                    'thumbnail' => $item['thumbnail'],
                    'seller_id' => ($item['seller_is'] == 'admin') ? 1 : $item['seller_id'],
                    'seller_is' => $item['seller_is'],
                    'shop_info' => $item['shop_info'],
                    'shipping_cost' => $item['shipping_cost'],
                    'shipping_type' => $item['shipping_type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Cart::insert($storage);
            session()->put('offline_cart', collect([]));
        }
    }

    public static function get_cart($group_id = null)
    {
        $user = Helpers::get_customer();
        if (session()->has('offline_cart') && $user == 'offline') {
            //            return $group_id;
            $cart = session('offline_cart')->collect();
            if ($group_id != null)
                return $cart->where('cart_group_id', $group_id)->get();
            else
                return $cart;
        }

        if ($group_id == null)
            $cart = Cart::whereIn('cart_group_id', CartManager::get_cart_group_ids())->get();
        else
            $cart = Cart::where('cart_group_id', $group_id)->get();

        return $cart;
    }

    public static function get_cart_for_api($request, $group_id = null)
    {
        if ($group_id == null) {
            $cart = Cart::whereIn('cart_group_id', CartManager::get_cart_group_ids($request))->get();
        } else {
            $cart = Cart::where('cart_group_id', $group_id)->get();
        }

        return $cart;
    }

    public static function get_cart_group_ids($request = null)
    {
        $user = Helpers::get_customer($request);
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');
            $cart_ids = array_unique($cart->pluck('cart_group_id')->toArray());
        } else {
            $cart_ids = Cart::where(['customer_id' => $user->id])->groupBy('cart_group_id')->pluck(
                'cart_group_id'
            )->toArray();
        }
        return $cart_ids;
    }

    public static function get_shipping_cost($group_id = null)
    {
        $cost = 0;
        if ($group_id == null) {
            $cart_shipping_cost = Cart::where(['product_type' => 'physical'])->whereIn(
                'cart_group_id',
                CartManager::get_cart_group_ids()
            )->sum('shipping_cost');
            $order_wise_shipping_cost = CartShipping::whereHas('cart', function ($query) {
                $query->where(['product_type' => 'physical']);
            })
                ->whereIn('cart_group_id', CartManager::get_cart_group_ids())->sum('shipping_cost');
            $cost = $order_wise_shipping_cost + $cart_shipping_cost;
        } else {
            $data = CartShipping::whereHas('cart', function ($query) {
                $query->where(['product_type' => 'physical']);
            })->where('cart_group_id', $group_id)->first();

            $order_wise_shipping_cost = isset($data) ? $data->shipping_cost : 0;
            $cart_shipping_cost = Cart::where(['cart_group_id' => $group_id, 'product_type' => 'physical'])->sum(
                'shipping_cost'
            );
            $cost = $order_wise_shipping_cost + $cart_shipping_cost;
        }
        return $cost;
    }

    public static function order_wise_shipping_discount()
    {
        if (auth('customer')->check()) {
            $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
            $cart_group_ids = CartManager::get_cart_group_ids();

            $amount = 0;
            if (count($cart_group_ids) > 0) {
                foreach ($cart_group_ids as $cart) {
                    $cart_data = Cart::where('cart_group_id', $cart)->first();
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart_data->seller_is == 'admin') {
                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Model\ShippingType::where(
                                'seller_id',
                                $cart_data->seller_id
                            )->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($shipping_type == 'order_wise' && session('coupon_type') == 'free_delivery' && (session(
                        'coupon_seller_id'
                    ) == '0' || (is_null(
                        session('coupon_seller_id')
                    ) && $cart_data->seller_is == 'admin') || (session(
                        'coupon_seller_id'
                    ) == $cart_data->seller_id && $cart_data->seller_is == 'seller'))) {
                        $amount += CartManager::get_shipping_cost($cart);
                    }
                }
            }

            return $amount;
        }
    }

    public static function cart_total($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = $item['price'] * $item['quantity'];
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_total_applied_discount($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] - $item['discount']) * $item['quantity'];
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_total_with_tax($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] * $item['quantity']) + ($item['tax'] * $item['quantity']);
                $total += $product_subtotal;
            }
        }
        return $total;
    }

    public static function cart_grand_total($cart_group_id = null)
    {
        $cart = CartManager::get_cart($cart_group_id);
        $shipping_cost = CartManager::get_shipping_cost($cart_group_id);
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $tax = $item['tax_model'] == 'include' ? 0 : $item['tax'];
                $product_subtotal = ($item['price'] * $item['quantity'])
                    + ($tax * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $product_subtotal;
            }
            $total += $shipping_cost;
        }
        return $total;
    }

    public static function cart_clean($request = null)
    {
        $cart_ids = CartManager::get_cart_group_ids($request);
        CartShipping::whereIn('cart_group_id', $cart_ids)->delete();
        Cart::whereIn('cart_group_id', $cart_ids)->delete();

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('payment_method');
        session()->forget('shipping_method_id');
        session()->forget('billing_address_id');
        session()->forget('order_id');
        session()->forget('cart_group_id');
        session()->forget('order_note');
    }

    public static function getStock($request)
    {
        $str = '';
        $variations = [];

        $product = Product::find($request->id);

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }
        if ($str != null) {
            $count = count(json_decode($product->variation));
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where(['code' => 'USD'])->first()->exchange_rate;
                session()->put('usd', $usd);
            }
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    if (json_decode($product->variation)[$i]->qty < 1) {
                        return [
                            'status' => 0,
                            'message' => translate('out_of_stock!'),
                            'price' => json_decode($product->variation)[$i]->price
                        ];
                    } else {
                        if (session('direction') == 'rtl') {
                            return [
                                'status' => 1,
                                'message' => 'متبقي ' . json_decode($product->variation)[$i]->qty . ' في المخزون',
                                'price' => \App\CPU\Helpers::currency_converter(
                                    json_decode($product->variation)[$i]->price
                                ) . ' ' . $usd
                            ];
                        } else {
                            return [
                                'status' => 1,
                                'message' => json_decode($product->variation)[$i]->qty . ' peaces in stock',
                                'price' => \App\CPU\Helpers::currency_converter(
                                    json_decode($product->variation)[$i]->price
                                )
                            ];
                        }
                    }
                }
            }
        }
        if (session('direction') == 'rtl') {
            return [
                'status' => 0,
                'message' => 'متبقي ' . $product->current_stock . ' في المخزون'
            ];
        } else {
            return [
                'status' => 0,
                'message' => $product->current_stock . ' peaces in stock',
            ];
        }
    }

    public static function add_to_cart($request, $from_api = false)
    {
        $str = '';
        $variations = [];
        $price = 0;

        $user = Helpers::get_customer($request);
        $product = Product::find($request->id);

        //check the color enabled or disabled for the product
        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }

        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }
        $str = str_replace(str_split('\\/:*?"<>|-_ '), '', $str);
        //        Log::alert($str);
        if ($user == 'offline') {
            if (session()->has('offline_cart')) {
                $cart = session('offline_cart');
                $check = $cart->where('product_id', $request->id)->where('variant', $str)->first();
                if (isset($check) == false) {
                    $cart = collect();
                    $cart['id'] = time();
                } else {
                    return [
                        'status' => 0,
                        'message' => translate('already_added!')
                    ];
                }
            } else {
                $cart = collect();
                session()->put('offline_cart', $cart);
            }
        } else {
            $cart = Cart::where(['product_id' => $request->id, 'customer_id' => $user->id, 'variant' => $str])->first();
            if (isset($cart) == false) {
                $cart = new Cart();
            } else {
                return [
                    'status' => 0,
                    'message' => translate('already_added!')
                ];
            }
        }

        $cart['color'] = $request->has('color') ? $request['color'] : null;
        $cart['product_id'] = $product->id;
        $cart['product_type'] = $product->product_type;
        $cart['choices'] = json_encode($choices);

        //chek if out of stock
        if (($product['product_type'] == 'physical') && ($product['current_stock'] < $request['quantity'])) {
            return [
                'status' => 0,
                'message' => translate('out_of_stock!')
            ];
        }

        $cart['variations'] = json_encode($variations);
        $cart['variant'] = $str;

        //Check the string and decreases quantity for the stock
        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                //                Log::alert(str_replace(str_split('\\/:*?"<>|-_ '), '', json_decode($product->variation)[$i]->type));
                if (str_replace(str_split('\\/:*?"<>|-_ '), '', json_decode($product->variation)[$i]->type) == $str) {
                    $price = json_decode($product->variation)[$i]->price;
                    if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                        return [
                            'status' => 0,
                            'message' => translate('out_of_stock!')
                        ];
                    }
                }
            }
        } else {
            $price = $product->purchase_price;
        }

        $tax = Helpers::tax_calculation($price, $product['tax'], 'percent');

        //generate group id
        if ($user == 'offline') {
            $check = session('offline_cart');
            $cart_check = $check->where('seller_id', ($product->added_by == 'admin') ? 1 : $product->user_id)
                ->where('seller_is', $product->added_by)->first();
        } else {
            $cart_check = Cart::where([
                'customer_id' => $user->id,
                'seller_id' => ($product->added_by == 'admin') ? 1 : $product->user_id,
                'seller_is' => $product->added_by
            ])->first();
        }

        if (isset($cart_check)) {
            $cart['cart_group_id'] = $cart_check['cart_group_id'];
        } else {
            $cart['cart_group_id'] = ($user == 'offline' ? 'offline' : $user->id) . '-' . Str::random(5) . '-' . time();
        }
        //generate group id end

        $cart['customer_id'] = $user->id ?? 0;
        $cart['quantity'] = $request['quantity'];
        /*$data['shipping_method_id'] = $shipping_id;*/
        $cart['price'] = $price;
        $cart['tax'] = $tax;
        $cart['tax_model'] = $product->tax_model;
        $cart['slug'] = $product->slug;
        $cart['name'] = $product->name;
        $cart['discount'] = Helpers::get_product_discount($product, $price);
        /*$data['shipping_cost'] = $shipping_cost;*/
        $cart['thumbnail'] = $product->thumbnail;
        $cart['seller_id'] = ($product->added_by == 'admin') ? 1 : $product->user_id;
        $cart['seller_is'] = $product->added_by;
        $cart['shipping_cost'] = $product->product_type == 'physical' ? CartManager::get_shipping_cost_for_product_category_wise(
            $product,
            $request['quantity']
        ) : 0;
        if ($product->added_by == 'seller') {
            $cart['shop_info'] = Shop::where(['seller_id' => $product->user_id])->first()->name;
        } else {
            $cart['shop_info'] = Helpers::get_business_settings('company_name');
        }

        $shippingMethod = Helpers::get_business_settings('shipping_method');

        if ($shippingMethod == 'inhouse_shipping') {
            $admin_shipping = ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
        } else {
            if ($product->added_by == 'admin') {
                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
            } else {
                $seller_shipping = ShippingType::where('seller_id', $product->user_id)->first();
                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
            }
        }
        $cart['shipping_type'] = $shipping_type;

        if ($user == 'offline') {
            $offline_cart = session('offline_cart');
            $offline_cart->push($cart);
            session()->put('offline_cart', $offline_cart);
        } else {
            $cart->save();
        }
        return [
            'status' => 1,
            'message' => translate('successfully_added!')
        ];
    }

    public static function update_cart_qty($request)
    {
        $user = Helpers::get_customer($request);
        $status = 1;
        $qty = 0;
        if ($user == 'offline') {
            $carts = session('offline_cart')->groupBy('id');
            $cart = $carts[$request->key][0];
            $product = Product::find($cart['product_id']);
            $count = count(json_decode($product->variation));

            if ($count) {
                for ($i = 0; $i < $count; $i++) {
                    if (json_decode($product->variation)[$i]->type == $cart['variant']) {
                        if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                            $status = 0;
                            $qty = $cart['quantity'];
                        }
                    }
                }
            } else {
                if (($product['product_type'] == 'physical') && $product['current_stock'] < $request->quantity) {
                    $status = 0;
                    $qty = $cart['quantity'];
                }
            }
            if ($status) {
                $qty = $request->quantity;
                $cart['quantity'] = $request->quantity;
                $cart['shipping_cost'] = CartManager::get_shipping_cost_for_product_category_wise(
                    $product,
                    $request->quantity
                );
            }
            //            return $status;
            return [
                'status' => $status,
                'qty' => $qty,
                'message' => $status == 1 ? translate('successfully_updated!') : translate('sorry_stock_is_limited')
            ];
        } else {
            $cart = Cart::where(['id' => $request->key, 'customer_id' => $user->id])->first();

            $product = Product::find($cart['product_id']);
            $count = count(json_decode($product->variation));
            if ($count) {
                for ($i = 0; $i < $count; $i++) {
                    if (json_decode($product->variation)[$i]->type == $cart['variant']) {
                        if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                            $status = 0;
                            $qty = $cart['quantity'];
                        }
                    }
                }
            } else {
                if (($product['product_type'] == 'physical') && $product['current_stock'] < $request->quantity) {
                    $status = 0;
                    $qty = $cart['quantity'];
                }
            }

            if ($status) {
                $qty = $request->quantity;
                $cart['quantity'] = $request->quantity;
                $cart['shipping_cost'] = CartManager::get_shipping_cost_for_product_category_wise(
                    $product,
                    $request->quantity
                );
            }

            $cart->save();

            return [
                'status' => $status,
                'qty' => $qty,
                'message' => $status == 1 ? translate('successfully_updated!') : translate('sorry_stock_is_limited')
            ];
        }
    }

    public static function get_shipping_cost_for_product_category_wise($product, $qty)
    {
        $shippingMethod = Helpers::get_business_settings('shipping_method');
        $cost = 0;

        if ($shippingMethod == 'inhouse_shipping') {
            $admin_shipping = ShippingType::where('seller_id', 0)->first();
            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
        } else {
            if ($product->added_by == 'admin') {
                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
            } else {
                $seller_shipping = ShippingType::where('seller_id', $product->user_id)->first();
                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
            }
        }

        if ($shipping_type == 'category_wise') {
            $categoryID = 0;
            foreach ($product->categories as $ct) {
                if ($ct->position == 1) {
                    $categoryID = $ct->id;
                }
            }

            if ($shippingMethod == 'inhouse_shipping') {
                $category_shipping_cost = CategoryShippingCost::where('seller_id', 0)->where(
                    'category_id',
                    $categoryID
                )->first();
            } else {
                if ($product->added_by == 'admin') {
                    $category_shipping_cost = CategoryShippingCost::where('seller_id', 0)->where(
                        'category_id',
                        $categoryID
                    )->first();
                } else {
                    $category_shipping_cost = CategoryShippingCost::where('seller_id', $product->user_id)->where(
                        'category_id',
                        $categoryID
                    )->first();
                }
            }


            if ($category_shipping_cost->multiply_qty == 1) {
                $cost = $qty * $category_shipping_cost->cost;
            } else {
                $cost = $category_shipping_cost->cost;
            }
        } else {
            if ($shipping_type == 'product_wise') {
                if ($product->multiply_qty == 1) {
                    $cost = $qty * $product->shipping_cost;
                } else {
                    $cost = $product->shipping_cost;
                }
            } else {
                $cost = 0;
            }
        }

        return $cost;
    }
}
