<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\CartManager;
use App\Model\BusinessSetting;
use App\Model\Banner;
use App\Model\SocialMedia;
use App\Model\Wishlist;
use App\Model\Currency;
use App\Model\HomeBannerSetting;
use Illuminate\Http\Request;

class StaticDataController extends Controller
{
    public function csrfToken()
    {
        return response()->json(csrf_token());
    }
    public function translate($locale)
    {
        return include(base_path('resources/lang/' . ($locale ?? Helpers::default_lang()) . '/messages.php'));
    }

    public function settings()
    {
        return BusinessSetting::all()->pluck('value', 'type');
    }

    public function session()
    {
        return response()->json(Session()->all());
    }

    public function token(Request $request)
    {
        return response()->json($request->session()->token());
    }

    public function banner($type)
    {
        $banner = [];
        switch ($type) {
            case 'popUp':
                $banner = Banner::where(['banner_type' => 'Popup Banner', 'published' => 1])->inRandomOrder()->first();
            case 'main':
                $banner = Banner::where(['banner_type' => 'Main Banner', 'published' => 1])->orderBy('id', 'desc')->get();
            case 'setting':
                $banner = HomeBannerSetting::first();
            case 'footerProducts':
                $banner = Banner::with('product')
                    ->where('resource_type', 'product')
                    ->where(
                        'banner_type',
                        'Footer Banner'
                    )
                    ->where('published', 1)
                    ->get()
                    ->map(function ($banner) {
                        $out = $banner->toArray();
                        $out['product'] = Helpers::currency_converter($banner->product->unit_price);
                        return $out;
                    });
        }
        return response()->json($banner);
    }

    public function socialMedia()
    {
        return SocialMedia::where('status', 1)->pluck('link', 'name');
    }

    public function getCart()
    {
        $cart = CartManager::get_cart();
        $total = 0;
        if (!empty($cart)) {
            $total = Helpers::currency_converter(CartManager::cart_total_applied_discount($cart));
            $cart = $cart->map(function ($item) {
                $item['finalPrics'] = Helpers::currency_converter(($item['price'] - $item['discount']) * $item['quantity']);
                $item['price'] = Helpers::currency_converter($item['price']);
                $item['discount'] = Helpers::currency_converter($item['discount']);
                return $item;
            });
        }
        return response()->json([
            "total" => $total,
            "cart" => $cart,
        ]);
    }

    public function getCustomer()
    {
        $bool = auth('customer')->check();
        return response()->json($bool ? auth('customer')->user() : false);
    }

    public function isMultyCurrency()
    {
        $bool = Helpers::get_business_settings('currency_model') == 'multi_currency';
        return response()->json([$bool, $bool ? Currency::where('status', 1)->get() : false]);
    }

    public function wishlists()
    {
        return response()->json(Wishlist::where('customer_id', auth('customer')->id()));
    }

    // public function test2() {
    //     return response()->json(
    //         session::put('current_city', 'a7a2')
    //     );
    // }
}
