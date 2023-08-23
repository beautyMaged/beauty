<?php

namespace App\Http\Controllers\Web;


use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Color;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function App\CPU\translate;

class CartController extends Controller
{
    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']):0;
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = $product->tax_model=='exclude' ? Helpers::tax_calculation($product->purchase_price, $product['tax'], $product['tax_type']) : 0;
            $discount = Helpers::get_product_discount($product, $product->purchase_price);
            $price = $product->purchase_price - $discount + $tax;
            $quantity = $product->current_stock;
        }

        return [
            'price' => \App\CPU\Helpers::currency_converter($price * $request->quantity),
            'discount' => \App\CPU\Helpers::currency_converter($discount),
//            'tax' => $product->tax_model=='exclude' ? \App\CPU\Helpers::currency_converter($tax) : 'incl.',
            'tax' => $product->tax_model=='exclude' ? translate('not_inc') : translate('incl'),
            'quantity' => $quantity
        ];
    }

    public function addToCart(Request $request)
    {
        $cart = CartManager::add_to_cart($request);
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
        return response()->json($cart);
    }

    public function getStock(Request $request) {
        $stock = CartManager::getStock($request);
        return response()->json($stock);
    }


    public function updateNavCart()
    {
        return response()->json(['data' => view('layouts.front-end.partials.cart')->render()]);
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        $user = Helpers::get_customer();

        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
//            return $request->key;
             $cart = session('offline_cart');

            $new_collection = collect([]);
            foreach ($cart as $item) {
                if ($item['id'] !=  $request->key) {
                    $new_collection->push($item);
                }
            }

            session()->put('offline_cart', $new_collection);
//            return response()->json(['data' => view('web-views.partials._order-summary')->render()]);

        } else {
            Cart::where(['id' => $request->key, 'customer_id' => auth('customer')->id()])->delete();
        }

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');
        session()->forget('shipping_method_id');
        session()->forget('order_note');

        return response()->json(['data' => view('layouts.front-end.partials.cart_details')->render()]);
    }

    // updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $response = CartManager::update_cart_qty($request);

        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        if ($response['status'] == 0) {
            return response()->json($response);
        }

        return response()->json(view('layouts.front-end.partials.cart_details')->render());
    }
}
