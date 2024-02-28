<?php

namespace App\Http\Controllers\Web;

use App\User;
use App\Model\Cart;
use App\Model\Shop;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Model\Admin;
use App\Model\Brand;
use App\Model\Color;
use App\Model\Order;
use App\Model\Banner;
use App\Model\Coupon;
use App\Model\Review;
use App\Model\Seller;
use App\Model\Contact;
use App\Model\Product;
use GuzzleHttp\Client;
use App\Model\Category;
use App\Model\Wishlist;
use App\CPU\CartManager;
use App\Helpers\Shopify;
use App\Model\FlashDeal;
use App\Model\HelpTopic;
use App\CPU\BrandManager;
use App\CPU\OrderManager;
use App\Model\StaticPage;
use App\Model\AdminWallet;
use App\Model\OrderDetail;
use App\Model\Transaction;
use App\Model\Translation;
use App\CPU\ProductManager;
use App\Model\CartShipping;
use App\Model\DealOfTheDay;
use App\Model\ShippingType;
use App\Model\Subscription;
use App\Traits\CommonTrait;
use App\CPU\CustomerManager;
use Illuminate\Http\Request;
use App\Model\ShippingMethod;
use App\Model\BusinessSetting;
use App\Model\DeliveryZipCode;
use App\Model\ShippingAddress;
use App\Model\FlashDealProduct;
use function App\CPU\translate;
use function PHPUnit\Framework\isEmpty;
use function React\Promise\all;
use App\Model\DeliveryCountryCode;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\DB;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Mail;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Product\ProductSearchResource;

class WebController extends Controller
{
    use CommonTrait;

    public function maintenance_mode()
    {
        $maintenance_mode = Helpers::get_business_settings('maintenance_mode') ?? 0;
        if ($maintenance_mode) {
            return view('web-views.maintenance-mode');
        }
        return redirect()->route('home');
    }

    // public function confirmLocation(Request $request)
    // {
    //     //        return $request;
    //     if (auth('customer')->check()) {
    //         $user = auth('customer')->user();
    //         // $user->country = $request->head_country;
    //         // $user->city = $request->head_city;
    //         // $user->street_address = $request->head_address;
    //         // $user->lat = $request->head_new_lat;
    //         // $user->long = $request->head_new_long;
    //         // $user->save();
    //         return redirect()->route('shipping-addresses.store');
    //     } else {
    //         session::forget('current_location');
    //         session::forget('current_city');
    //         session::forget('current_country');
    //         session::forget('new_lat');
    //         session::forget('new_long');

    //         session::put('current_location', $request->head_address);
    //         session::put('current_city', $request->head_city);
    //         session::put('current_country', $request->head_country);
    //         session::put('new_lat', $request->head_new_lat);
    //         session::put('new_long', $request->head_new_long);
    //         //                return $request->city . session('current_city');

    //     }
    //     Toastr::success(translate('Location Confirmed'));
    //     return back();
    // }

    // public function confirmLocationAjax(Request $request)
    // {
    //     if (auth('customer')->check()) {
    //         // $user = auth('customer')->user();
    //         // $user->country = $request->country;
    //         // $user->city = $request->city;
    //         // $user->street_address = $request->address;
    //         // $user->lat = $request->new_lat;
    //         // $user->long = $request->new_long;
    //         // $user->save();
    //         // Toastr::success(translate('Location Confirmed'));
    //         // return response()->json([
    //         //     'success' => true,
    //         //     'message' => translate('Location Confirmed')
    //         // ]);
    //         return redirect()->route('shipping-addresses.store');

    //     }
    //     session::forget('current_location');
    //     session::forget('current_city');
    //     session::forget('current_country');
    //     session::forget('new_lat');
    //     session::forget('new_long');

    //     session::put('current_location', $request->address);
    //     session::put('current_city', $request->city);
    //     session::put('current_country', $request->country);
    //     session::put('new_lat', $request->new_lat);
    //     session::put('new_long', $request->new_long);

    //     //                return 'test';
    //     // Toastr::success(translate('Location Confirmed'));

    //     return response()->json([
    //         'success' => true,
    //         'message' => translate('Location Confirmed')
    //     ]);
    // }

    public function level1_products()
    {
        $products = [[], []];
        $categories = Category::Select(['id', 'name'])
            ->where('position', 0)
            ->where('home_status', true)
            ->priority()
            ->get();
        foreach ($categories as $category) {
            // colour && brands
            $products[0][$category->id] = Product::Select('colors', 'brand_id')
                ->with(['brand'])
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('id', $category->id);
                })
                ->take(20)
                ->inRandomOrder()
                ->get();
            $products[1][$category->id] = Product::active()
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('id', $category->id);
                })
                ->take(4)
                ->get();
        }
        return $products;
    }
    public function feedData()
    {
        return Category::with(['products' => function ($query) {
            $query
                ->with(['reviews'])
                ->inRandomOrder()
                ->limit(21);
        }])
            ->select(['id', 'name'])
            ->where('position', 0)
            ->where('home_status', true)
            ->priority()
            ->get();
    }

    public function home()
    {
        //        session::forget('current_location');
        //        session::forget('current_city');
        //        session::forget('current_country');
        //        session::forget('new_lat');
        //        session::forget('new_long');

        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $home_categories = Category::where('home_status', true)->priority()->get();
        $home_categories->map(function ($data) {
            $id = '"' . $data['id'] . '"';
            $data['products'] = Product::active()
                ->whereHas('categories', function ($query) use ($id) {
                    $query->where('id', $id);
                })
                /*->whereJsonContains('category_ids', ["id" => (string)$data['id']])*/
                ->inRandomOrder()->take(12)->get();
        });
        //products based on top seller
        $top_sellers = Seller::approved()->with('shop')
            ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(12)->get();
        //end

        //feature products finding based on selling
        $featured_products = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end

        $main_products_banners = Banner::with('product')->where('resource_type', 'product')->where(
            'banner_type',
            'Main Section Banner'
        )->where('published', 1)->get();
        $footer_products_banners = Banner::with('product')->where('resource_type', 'product')->where(
            'banner_type',
            'Footer Banner'
        )->where('published', 1)->get();
        $all_cats = Category::get();
        $latest_products = Product::with(['reviews'])->active()->inRandomOrder()->limit(14)->get()->chunk(7);
        $reviews_of_all_products = Review::with('user')->get();
        $categories = Category::where(['position' => 0])->where('home_status', true)->priority()->take(11)->get();
        $brands = Brand::active()->take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();
        //Top rated
        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();

        if ($bestSellProduct->count() == 0)
            $bestSellProduct = $latest_products;

        if ($topRated->count() == 0)
            $topRated = $bestSellProduct;

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select(
            'deal_of_the_days.*',
            'products.unit_price'
        )->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();

        [$_categories, $_products] = $this->feedData();
        return view(
            'web-views.home',
            compact(
                'reviews_of_all_products',
                'all_cats',
                'main_products_banners',
                'footer_products_banners',
                'featured_products',
                'topRated',
                'bestSellProduct',
                'latest_products',
                'categories',
                'brands',
                'deal_of_the_day',
                'top_sellers',
                'home_categories',
                'brand_setting',
                '_categories',
                '_products',
            )
        );
    }

    public function flash_deals($id)
    {
        $deal = FlashDeal::with([
            'products.product.reviews',
            'products.product' => function ($query) {
                $query->active();
            }
        ])
            ->where(['id' => $id, 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->first();

        $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
            $query->active();
        })->get()->map(function ($data) {
            return [
                'discount' => $data->discount,
                'sellPrice' => $data->product->unit_price,
                'discountedPrice' => $data->product->unit_price - $data->discount,

            ];
        })->toArray();


        // dd($deal->toArray());

        if (isset($deal)) {
            return view('web-views.deals', compact('deal', 'discountPrice'));
        }
        Toastr::warning(translate('not_found'));
        return back();
    }

    public function search_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);
        return view('web-views.sellers', compact('sellers'));
    }

    public function all_categories()
    {
        $categories = Category::get();
        return response()->json($categories);
        return view('web-views.categories', ('categories'));
    }

    public function categories_with_product_count(Request $request)
    {
        $categories = Category::get()
            ->map(
                fn ($category) => [
                    'name' => $category->name,
                    'products' => $category->products()->count()
                ]
            );
        return response()->json($categories);
    }

    public function categories()
    {
        return response()->json(
            Category::Select(['id', 'name'])
                ->With([
                    'childes:id,name,parent_id',
                    'childes.childes:id,name,parent_id',
                    'childes.childes.childes:id,name,parent_id'
                ])
                ->Where('parent_id', 0)
                ->priority()
                ->get()
        );
    }

    public function categories_home()
    {
        return response()->json(
            Category::Select(['id', 'name', 'icon'])
                ->With([
                    'childes:id,name,parent_id',
                    'childes.childes:id,name,parent_id',
                    'childes.childes.childes:id,name,parent_id'
                ])
                ->Where('home_status', true)
                ->Where('parent_id', 0)
                ->priority()
                ->get()
        );
    }

    public function categories_by_category($id)
    {
        $category = Category::with(['childes.childes'])->where('id', $id)->first();
        return response()->json([
            'view' => view('web-views.partials._category-list-ajax', compact('category'))->render(),
        ]);
    }

    public function all_brands(Request $request)
    {
        $brands = Brand::active()->paginate($request['take'] ?? 24);
        // if ($request->wantsJson())
            return response()->json($brands);
        return view('web-views.brands', compact('brands'));
    }

    public function brands_with_product_count()
    {
        return BrandManager::get_active_brands();
    }

    public function all_sellers()
    {
        $business_mode = Helpers::get_business_settings('business_mode');
        if (isset($business_mode) && $business_mode == 'single') {
            Toastr::warning(translate('access_denied!!'));
            return back();
        }
        $sellers = Shop::whereHas('seller', function ($query) {
            return $query->approved();
        })->paginate(24);
        return view('web-views.sellers', compact('sellers'));
    }

    public function seller_profile($id)
    {
        $seller_info = Seller::find($id);
        return view('web-views.seller-profile', compact('seller_info'));
    }

    public function searched_products(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);

        $query = ProductManager::search_products_web($request['name']);
        if ($query->count() == 0)
            $query = ProductManager::translated_product_search_web($request['name']);

        $products = $query->take(10)->get();
        if($products->isEmpty()){
            return response()->json('no produts found');
        }

        // return response()->json([
        //     'result' => view('web-views.partials._search-result', compact('products'))->render(),
        // ]);
        // dd(ProductSearchResource::collection($products));

        return response()->json(ProductSearchResource::collection($products));
    }

    public function checkout_details(Request $request)
    {
        //        $collection = session('offline_cart');
        //        $items = $collection->first();
        //        $shopInfos = collect($items)->pluck('shop_info')->unique();
        //        if ($shopInfos->count() === 1) {
        //            return $this->payToPartner();
        //        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        if (count($cart_group_ids) === 1) {
            $cart = Cart::where('cart_group_id', $cart_group_ids[0])->get();
            $data = [];
            $shop_name = '';
            foreach ($cart as $c) {
                if ($c->product_type == 'physical') {
                    array_push($data, [
                        'name' => $c->name,
                        'price' => $c->price,
                        'quantity' => $c->quantity
                    ]);
                }
                $shop_name = $c->shop_info;
            }
            //            dd($cart[0] , 'hi');
            return $this->payToPartner($data, $shop_name);
        }

        $shippingMethod = Helpers::get_business_settings('shipping_method');

        $physical_product_view = false;
        foreach ($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product_view = true;
                }
            }
        }

        foreach ($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }
            if ($physical_product) {
                foreach ($carts as $cart) {
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart->seller_is == 'admin') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($physical_product && $shipping_type == 'order_wise') {
                        $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                        if (!isset($cart_shipping)) {
                            Toastr::info(translate('select_shipping_method_first'));
                            return redirect('shop-cart');
                        }
                    }
                }
            }
        }

        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        if ($country_restrict_status) {
            $countries = $this->get_delivery_country_array();
        } else {
            $countries = COUNTRIES;
        }

        if ($zip_restrict_status) {
            $zip_codes = DeliveryZipCode::all();
        } else {
            $zip_codes = 0;
        }

        if (count($cart_group_ids) > 0) {
            return view(
                'web-views.checkout-shipping',
                compact(
                    'physical_product_view',
                    'zip_codes',
                    'country_restrict_status',
                    'zip_restrict_status',
                    'countries'
                )
            );
        }

        Toastr::info(translate('no_items_in_basket'));
        return redirect('/');
    }

    public function checkout_payment()
    {
        if (auth('customer')->check()) {
            $cart_group_ids = CartManager::get_cart_group_ids();
            $shippingMethod = Helpers::get_business_settings('shipping_method');

            $physical_products[] = false;
            foreach ($cart_group_ids as $group_id) {
                $carts = Cart::where('cart_group_id', $group_id)->get();
                $physical_product = false;
                foreach ($carts as $cart) {
                    if ($cart->product_type == 'physical') {
                        $physical_product = true;
                    }
                }
                $physical_products[] = $physical_product;
            }
            unset($physical_products[0]);

            $cod_not_show = in_array(false, $physical_products);

            foreach ($cart_group_ids as $group_id) {
                $carts = Cart::where('cart_group_id', $group_id)->get();

                $physical_product = false;
                foreach ($carts as $cart) {
                    if ($cart->product_type == 'physical') {
                        $physical_product = true;
                    }
                }

                if ($physical_product) {
                    foreach ($carts as $cart) {
                        if ($shippingMethod == 'inhouse_shipping') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            if ($cart->seller_is == 'admin') {
                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                            } else {
                                $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                            }
                        }
                        if ($shipping_type == 'order_wise') {
                            $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                            if (!isset($cart_shipping)) {
                                Toastr::info(translate('select_shipping_method_first'));
                                return redirect('shop-cart');
                            }
                        }
                    }
                }
            }

            if (session()->has('address_id') && count($cart_group_ids) > 0) {
                return view('web-views.checkout-payment', compact('cod_not_show'));
            }

            Toastr::error(translate('incomplete_info'));
            return back();
        } else {
            if (session()->has('offline_cart')) {
                if (count(session('offline_cart')) > 0) {
                    $cart = session('offline_cart')->groupBy('cart_group_id');
                    //                $cart_group_ids = CartManager::get_cart_group_ids();
                    $shippingMethod = Helpers::get_business_settings('shipping_method');

                    $physical_products[] = false;
                    foreach ($cart as $group_id => $group) {
                        //                    return $group;
                        //                    $carts = Cart::where('cart_group_id', $group_id)->get();
                        $physical_product = false;
                        foreach ($group as $single_cart) {
                            if ($single_cart['product_type'] == 'physical') {
                                $physical_product = true;
                            }
                        }
                        $physical_products[] = $physical_product;
                    }
                    unset($physical_products[0]);

                    $cod_not_show = in_array(false, $physical_products);

                    foreach ($cart as $group_id => $group) {
                        $physical_product = false;
                        foreach ($group as $single_cart) {
                            if ($single_cart['product_type'] == 'physical') {
                                $physical_product = true;
                            }
                        }
                        if ($physical_product) {
                            foreach ($cart as $group) {
                                if ($shippingMethod == 'inhouse_shipping') {
                                    $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                } else {
                                    if ($group['seller_is'] == 'admin') {
                                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                    } else {
                                        $seller_shipping = ShippingType::where('seller_id', $group['seller_id'])->first();
                                        $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                    }
                                }
                                if ($shipping_type == 'order_wise') {
                                    $cart_shipping = CartShipping::where(
                                        'cart_group_id',
                                        $group['cart_group_id']
                                    )->first();
                                    if (!isset($cart_shipping)) {
                                        Toastr::info(translate('select_shipping_method_first'));
                                        return redirect('shop-cart');
                                    }
                                }
                            }
                        }
                    }

                    //                if (session()->has('address') && count($cart_group_ids) > 0) {
                    //                    return 'success';
                    return view('web-views.checkout-payment', compact('cod_not_show'));
                    //                }
                    //                return 'failed';

                } else {
                    Toastr::info('السلة فارغة');
                    return redirect('/');
                }
            }
        }
    }

    public function checkout_complete(Request $request)
    {
        if (auth('customer')->check()) {
            if ($request->payment_method != 'cash_on_delivery') {
                return back()->with('error', 'Something went wrong!');
            }
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            $cart_group_ids = CartManager::get_cart_group_ids();
            $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }

            if ($physical_product) {
                foreach ($cart_group_ids as $group_id) {
                    $data = [
                        'payment_method' => 'cash_on_delivery',
                        'order_status' => 'pending',
                        'payment_status' => 'unpaid',
                        'transaction_ref' => '',
                        'order_group_id' => $unique_id,
                        'cart_group_id' => $group_id
                    ];
                    $order_id = OrderManager::generate_order($data);
                    array_push($order_ids, $order_id);
                }

                CartManager::cart_clean();
                //            return $order_id;

                return view('web-views.checkout-complete', compact('order_id'));
            }

            return back()->with('error', 'Something went wrong!');
        } else {
            $carts = session('offline_cart');
            if ($request->payment_method != 'cash_on_delivery') {
                return back()->with('error', 'Something went wrong!');
            }
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
                        'payment_method' => 'cash_on_delivery',
                        'order_status' => 'pending',
                        'payment_status' => 'unpaid',
                        'transaction_ref' => '',
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

                        $coupon_process = $coupon ? self::guest_coupon_process($data, $coupon) : $coupon_process;
                    }

                    $order_id = 100000 + Order::all()->count() + 1;
                    if (Order::find($order_id)) {
                        $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
                    }

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
                    if ($coupon && ($coupon->seller_id == null || $coupon->seller_id == '0' || $coupon->seller_id == $cart[0]->seller_id)) {
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
                        if (session()->has('address_id') == false) {
                            $address_id = $req->has('address_id') ? $req['address_id'] : null;
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
                        'payment_by' => isset($data['payment_by']) ? $data['payment_by'] : null,
                        'payment_note' => isset($data['payment_note']) ? $data['payment_note'] : null,
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
                    self::add_order_status_history(
                        $order_id,
                        auth('customer')->id(),
                        $data['payment_status'] == 'paid' ? 'confirmed' : 'pending',
                        'customer'
                    );

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

                    //                    if ($or['payment_method'] != 'cash_on_delivery' && $or['payment_method'] != 'offline_payment') {
                    //                        $order = Order::find($order_id);
                    //                        $order_summary = OrderManager::order_summary($order);
                    //                        $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount'];
                    //
                    //                        DB::table('order_transactions')->insert([
                    //                            'transaction_id' => OrderManager::gen_unique_id(),
                    //                            'customer_id' => $order['customer_id'],
                    //                            'seller_id' => $order['seller_id'],
                    //                            'seller_is' => $order['seller_is'],
                    //                            'order_id' => $order_id,
                    //                            'order_amount' => $order_amount,
                    //                            'seller_amount' => $order_amount - $admin_commission,
                    //                            'admin_commission' => $admin_commission,
                    //                            'received_by' => 'admin',
                    //                            'status' => 'hold',
                    //                            'delivery_charge' => $order['shipping_cost'],
                    //                            'tax' => $order_summary['total_tax'],
                    //                            'delivered_by' => 'admin',
                    //                            'payment_method' => $or['payment_method'],
                    //                            'created_at' => now(),
                    //                            'updated_at' => now(),
                    //                        ]);
                    //
                    //                        if (AdminWallet::where('admin_id', 1)->first() == false) {
                    //                            DB::table('admin_wallets')->insert([
                    //                                'admin_id' => 1,
                    //                                'withdrawn' => 0,
                    //                                'commission_earned' => 0,
                    //                                'inhouse_earning' => 0,
                    //                                'delivery_charge_earned' => 0,
                    //                                'pending_amount' => 0,
                    //                                'created_at' => now(),
                    //                                'updated_at' => now(),
                    //                            ]);
                    //                        }
                    //                        DB::table('admin_wallets')->where('admin_id', $order['seller_id'])->increment('pending_amount', $order['order_amount']);
                    //                    }

                    //                    if ($seller_data->seller_is == 'admin') {
                    //                        $seller = Admin::find($seller_data->seller_id);
                    //                    } else {
                    //                        $seller = Seller::find($seller_data->seller_id);
                    //                    }
                    //
                    //                    try {
                    //                        $fcm_token = $user->cm_firebase_token;
                    //                        $seller_fcm_token = $seller->cm_firebase_token;
                    //                        if ($data['payment_method'] != 'cash_on_delivery' && $or['payment_method'] != 'offline_payment') {
                    //                            $value = Helpers::order_status_update_message('confirmed');
                    //                        } else {
                    //                            $value = Helpers::order_status_update_message('pending');
                    //                        }
                    //
                    //                        if ($value) {
                    //                            $data = [
                    //                                'title' => translate('order'),
                    //                                'description' => $value,
                    //                                'order_id' => $order_id,
                    //                                'image' => '',
                    //                            ];
                    //                            Helpers::send_push_notif_to_device($fcm_token, $data);
                    //                            Helpers::send_push_notif_to_device($seller_fcm_token, $data);
                    //                        }
                    //
                    //                        $emailServices_smtp = Helpers::get_business_settings('mail_config');
                    //                        if ($emailServices_smtp['status'] == 0) {
                    //                            $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
                    //                        }
                    //                        if ($emailServices_smtp['status'] == 1) {
                    //                            Mail::to($user->email)->send(new \App\Mail\OrderPlaced($order_id));
                    //                            Mail::to($seller->email)->send(new \App\Mail\OrderReceivedNotifySeller($order_id));
                    //                        }
                    //                    } catch (\Exception $exception) {
                    //                        //echo $exception;
                    //                    }

                    //                    return $order_id;
                    array_push($order_ids, $order_id);
                }

                Session::forget('offline_cart');
                CartManager::cart_clean();
                //            return $order_id;

                return view('web-views.checkout-complete', compact('order_id'));
            }

            return back()->with('error', 'Something went wrong!');
        }
    }

    public function offline_payment_checkout_complete(Request $request)
    {
        if ($request->payment_method != 'offline_payment') {
            return back()->with('error', 'Something went wrong!');
        }
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $cart_group_ids = CartManager::get_cart_group_ids();

        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'offline_payment',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => $request->transaction_ref,
                'payment_by' => $request->payment_by,
                'payment_note' => $request->payment_note,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean();


        return view('web-views.checkout-complete');
    }

    public function checkout_complete_wallet(Request $request = null)
    {
        $cartTotal = CartManager::cart_grand_total();
        $user = Helpers::get_customer($request);
        if ($cartTotal > $user->wallet_balance) {
            Toastr::warning(translate('inefficient balance in your wallet to pay for this order!!'));
            return back();
        } else {
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'pay_by_wallet',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }

            CustomerManager::create_wallet_transaction(
                $user->id,
                Convert::default($cartTotal),
                'order_place',
                'order payment'
            );
            CartManager::cart_clean();
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            return redirect()->route('payment-success');
        }
        return view('web-views.checkout-complete');
    }

    public function order_placed()
    {
        return view('web-views.checkout-complete');
    }

    public function shop_cart(Request $request)
    {
        //        Session::forget('offline_cart');

        if (auth('customer')->check()) {
            if (auth('customer')->check() && Cart::where(['customer_id' => auth('customer')->id()])->count() > 0) {
                return view('web-views.shop-cart');
            }
            Toastr::info(translate('no_items_in_basket'));
            return redirect('/');
        } else {
            if (session()->has('offline_cart')) {
                $cart = session('offline_cart')->groupBy('cart_group_id');
                if (isset($cart) && $cart->count() > 0) {
                    return view('web-views.shop-cart', compact('cart'));
                } else {
                    Toastr::info(translate('no_items_in_basket'));
                    return redirect('/');
                }
            } else {
                $cart = collect();
                session()->put('offline_cart', $cart);
            }
        }
    }

    //for seller Shop

    public function seller_shop(Request $request, $id)
    {
        $business_mode = Helpers::get_business_settings('business_mode');

        $active_seller = Seller::approved()->find($id);

        if (($id != 0) && empty($active_seller)) {
            Toastr::warning(translate('not_found'));
            return redirect('/');
        }

        if ($id != 0 && $business_mode == 'single') {
            Toastr::error(translate('access_denied!!'));
            return back();
        }
        $product_ids = Product::active()
            ->when($id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($id != 0, function ($query) use ($id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $id);
            })
            ->pluck('id')->toArray();


        $avg_rating = Review::whereIn('product_id', $product_ids)->avg('rating');
        $total_review = Review::whereIn('product_id', $product_ids)->count();
        if ($id == 0) {
            $total_order = Order::where('seller_is', 'admin')->where('order_type', 'default_type')->count();
        } else {
            $seller = Seller::find($id);
            $total_order = $seller->orders->where('seller_is', 'seller')->where('order_type', 'default_type')->count();
        }

        //finding category ids
        $products = Product::whereIn('id', $product_ids)->paginate(12);
        foreach ($products as $product)
            foreach ($product->categories as $category)
                array_push($categories, $category->id);
        $categories = array_unique($categories);
        //end

        //products search
        $products = Product::active()
            ->when($id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($id != 0, function ($query) use ($id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $id);
            })
            ->when(!empty($request->product_name), function ($query) use ($request) {
                $key = explode(' ', $request->product_name);
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhereHas('tags', function ($query) use ($value) {
                            $query->where('tag', 'like', "%{$value}%");
                        });
                }
            })
            ->when(!empty($request->category_id), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            })->paginate(12);

        if ($id == 0) {
            $shop = [
                'id' => 0,
                'name' => Helpers::get_business_settings('company_name'),
            ];
        } else {
            $shop = Shop::where('seller_id', $id)->first();
            if (isset($shop) == false) {
                Toastr::error(translate('shop_does_not_exist'));
                return back();
            }
        }

        $current_date = date('Y-m-d');
        $seller_vacation_start_date = $id != 0 ? date('Y-m-d', strtotime($shop->vacation_start_date)) : null;
        $seller_vacation_end_date = $id != 0 ? date('Y-m-d', strtotime($shop->vacation_end_date)) : null;
        $seller_temporary_close = $id != 0 ? $shop->temporary_close : false;
        $seller_vacation_status = $id != 0 ? $shop->vacation_status : false;

        $temporary_close = Helpers::get_business_settings('temporary_close');
        $inhouse_vacation = Helpers::get_business_settings('vacation_add');
        $inhouse_vacation_start_date = $id == 0 ? $inhouse_vacation['vacation_start_date'] : null;
        $inhouse_vacation_end_date = $id == 0 ? $inhouse_vacation['vacation_end_date'] : null;
        $inhouse_vacation_status = $id == 0 ? $inhouse_vacation['status'] : false;
        $inhouse_temporary_close = $id == 0 ? $temporary_close['status'] : false;

        return view(
            'web-views.shop-page',
            compact(
                'products',
                'shop',
                'categories',
                'current_date',
                'seller_vacation_start_date',
                'seller_vacation_status',
                'seller_vacation_end_date',
                'seller_temporary_close',
                'inhouse_vacation_start_date',
                'inhouse_vacation_end_date',
                'inhouse_vacation_status',
                'inhouse_temporary_close'
            )
        )
            ->with('seller_id', $id)
            ->with('total_review', $total_review)
            ->with('avg_rating', $avg_rating)
            ->with('total_order', $total_order);
    }

    //ajax filter (category based)
    public function seller_shop_product(Request $request, $id)
    {
        $products = Product::active()->with('shop')->where(['added_by' => 'seller'])
            ->where('user_id', $id)
            ->whereHas('categories', function ($query) use ($request) {
                $query->where('id', $request->category_id);
            })
            ->paginate(12);
        $shop = Shop::where('seller_id', $id)->first();
        if ($request['sort_by'] == null) {
            $request['sort_by'] = 'latest';
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render(),
            ], 200);
        }

        return view('web-views.shop-page', compact('products', 'shop'))->with('seller_id', $id);
    }

    public function quick_view(Request $request)
    {
        $product = ProductManager::get_product($request->product_id);
        $totalReviews = Review::where('product_id', $product->id)->count();
        $order_details = OrderDetail::where('product_id', $product->id)->get();
        $wishlists = Wishlist::where('product_id', $product->id)->get();
        $countOrder = count($order_details);
        $countWishlist = count($wishlists);
        $relatedProducts = Product::with(['reviews'])
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->limit(12)->get();
        $current_date = date('Y-m-d');
        $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date(
            'Y-m-d',
            strtotime($product->seller->shop->vacation_start_date)
        ) : null;
        $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date(
            'Y-m-d',
            strtotime($product->seller->shop->vacation_end_date)
        ) : null;
        $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

        $temporary_close = Helpers::get_business_settings('temporary_close');
        $inhouse_vacation = Helpers::get_business_settings('vacation_add');
        $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
        $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
        $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
        $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;

        return response()->json([
            'success' => 1,
            'view' => view(
                'web-views.partials._quick-view-data',
                compact(
                    'product',
                    'totalReviews',
                    'countWishlist',
                    'countOrder',
                    'relatedProducts',
                    'current_date',
                    'seller_vacation_start_date',
                    'seller_vacation_end_date',
                    'seller_temporary_close',
                    'inhouse_vacation_start_date',
                    'inhouse_vacation_end_date',
                    'inhouse_vacation_status',
                    'inhouse_temporary_close'
                )
            )->render(),
        ]);
    }

    public function product(Request $request, $slug)
    {
        $product = Product::active()->with(['reviews', 'seller.shop'])->where('slug', $slug)->first();
        if ($product != null) {

            $product->purchasePrice = Helpers::currency_converter($product->purchase_price);
            // $product->price = Helpers::currency_converter($product->unit_price - Helpers::get_product_discount($product, $product->unit_price));
            $product->priceRange = Helpers::get_price_range($product);

            $categories = $product->categories()->select(['id', 'name'])->get();
            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $relatedProducts = Product::with(['reviews'])->active()->where('user_id', $product->user_id)->where(
                'id',
                '!=',
                $product->id
            )->limit(12)->get();
            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();
            $current_date = date('Y-m-d');
            $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date(
                'Y-m-d',
                strtotime($product->seller->shop->vacation_start_date)
            ) : null;
            $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date(
                'Y-m-d',
                strtotime($product->seller->shop->vacation_end_date)
            ) : null;
            $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

            $temporary_close = Helpers::get_business_settings('temporary_close');
            $inhouse_vacation = Helpers::get_business_settings('vacation_add');
            $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
            $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
            $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
            $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => compact(
                        'product',
                        'categories',
                        'countWishlist',
                        'countOrder',
                        'relatedProducts',
                        'deal_of_the_day',
                        'current_date',
                        'seller_vacation_start_date',
                        'seller_vacation_end_date',
                        'seller_temporary_close',
                        'inhouse_vacation_start_date',
                        'inhouse_vacation_end_date',
                        'inhouse_vacation_status',
                        'inhouse_temporary_close'
                    )
                ], 200);
            }
            return view(
                'web-views.products.details',
                compact(
                    'product',
                    'countWishlist',
                    'countOrder',
                    'relatedProducts',
                    'deal_of_the_day',
                    'current_date',
                    'seller_vacation_start_date',
                    'seller_vacation_end_date',
                    'seller_temporary_close',
                    'inhouse_vacation_start_date',
                    'inhouse_vacation_end_date',
                    'inhouse_vacation_status',
                    'inhouse_temporary_close'
                )
            );
        }
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => translate('not_found'),
            ], 404);
        }
        Toastr::error(translate('not_found'));
        return back();
    }

    public function tester()
    {
        $porduct_data = Product::active();
        $products = $porduct_data->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach ($product->categories as $category) {
                if ($category['id'] == 'Cat id') {
                    array_push($product_ids, $product['id']);
                }
            }
        }
        $products = $porduct_data->whereIn('id', $product_ids)->get();

        $colors = Color::pluck('code')->toArray();
        $selected_colors = [];
        foreach ($products as $product) {
            if (count(json_decode($product->colors)) > 0) {
                foreach (json_decode($product->colors) as $color) {
                    if (in_array($color, $colors)) {
                        array_push($selected_colors, $color);
                    }
                }
            }
        }
        $colors = Color::whereIn('code', $selected_colors)->get();
    }

    public function products(Request $request)
    {
        // $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];
        $request['sort_by'] ??= 'latest';
        $porduct_data = Product::active()->with(['reviews', 'brand']);

        /* data_from */
        switch ($request['data_from']) {
            case 'latest':
                $query = $porduct_data;
                $title = 'recent_pro';
                // ->Select('id', 'name', 'images', 'brand_id', 'colors', 'slug', 'variation');
                // if ($request['id'] != null && !empty($request['id']))
                //     // $query = $query->whereRaw("JSON_CONTAINS(JSON_EXTRACT(category_ids,'$[*].id'),?,'$')", [$request['id']]);
                //     $query = $query->WhereJsonContains("category_ids", ['id' => strval($request['id'])]);
                break;
            case 'category':
                $query = $porduct_data->whereHas('categories', function ($query) use ($request) {
                    $query->where('id', $request['id']);
                });
                break;
            case 'brand':
                $query = $porduct_data->where('brand_id', $request['id']);
                break;
            case 'top-rated':
                $title = "top_rate_pro";
                $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                    ->groupBy('product_id')
                    ->orderBy("count", 'desc')->get();
                $product_ids = [];
                foreach ($reviews as $review)
                    array_push($product_ids, $review['product_id']);
                $query = $porduct_data->whereIn('id', $product_ids);
                if ($request['id'] != null && !empty($request['id']))
                    $query = $query->whereHas('categories', function ($query) use ($request) {
                        $query->where('id', $request['id']);
                    });
                break;
            case 'best-selling':
                $title = "top_sell_pro";

                $details = OrderDetail::with('product')
                    ->select('product_id', DB::raw('COUNT(product_id) as count'))
                    ->groupBy('product_id')
                    ->orderBy("count", 'desc')
                    ->get();
                $product_ids = [];
                foreach ($details as $detail) {
                    array_push($product_ids, $detail['product_id']);
                }
                $query = $porduct_data->whereIn('id', $product_ids);

                if ($request['id'] != null && !empty($request['id']))
                    $query = $query->whereHas('categories', function ($query) use ($request) {
                        $query->where('id', $request['id']);
                    });
                break;
            case 'most-favorite':
                $details = Wishlist::with('product')
                    ->select('product_id', DB::raw('COUNT(product_id) as count'))
                    ->groupBy('product_id')
                    ->orderBy("count", 'desc')
                    ->get();
                $product_ids = [];
                foreach ($details as $detail)
                    array_push($product_ids, $detail['product_id']);

                $query = $porduct_data->whereIn('id', $product_ids);

                if ($request['id'] != null && !empty($request['id']))
                    $query = $query->whereHas('categories', function ($query) use ($request) {
                        $query->where('id', $request['id']);
                    });

                break;
            case 'featured':
                $query = Product::with(['reviews'])->active()->where('featured', 1);
                break;
            case 'featured_deal':
                $title = "special_offers";

                $featured_deal_id = FlashDeal::where(['status' => 1])->where(['deal_type' => 'feature_deal'])->pluck(
                    'id'
                )->first();
                $featured_deal_product_ids = FlashDealProduct::where('flash_deal_id', $featured_deal_id)->pluck(
                    'product_id'
                )->toArray();
                $query = Product::with(['reviews'])->active()->whereIn('id', $featured_deal_product_ids);

                if ($request['id'] != null && !empty($request['id']))
                    $query = $query->whereHas('categories', function ($query) use ($request) {
                        $query->where('id', $request['id']);
                    });
                break;
            case 'search':
                $query = ProductManager::search_products_web($request['name']);
                if ($query->count() == 0)
                    $query = ProductManager::translated_product_search_web($request['name']);
                break;
            case 'discounted':
                $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
                break;
            case 'banner':
                $banner = Banner::findOrFail($request['id']);
                if ($banner->published == 1) {
                    if ($banner->target == 'products')
                        $query = $banner->products()->with(['reviews'])->active();
                    if ($banner->target == 'all')
                        $query = $banner->seller->products()->with(['reviews'])->active();
                } else
                    $query = new Collection();
                break;
        }

        /* sort_by */
        $query = ProductManager::search_products_web($request['name']);

        switch ($request['sort_by']) {
            case 'latest':
                $fetched = $query->latest();
                break;
            case 'low-high':
                $fetched = $query->orderBy('unit_price', 'ASC');
                break;
            case 'high-low':
                $fetched = $query->orderBy('unit_price', 'DESC');
                break;
            case 'a-z':
                $fetched = $query->orderBy('name', 'ASC');
                break;
            case 'z-a':
                $fetched = $query->orderBy('name', 'DESC');
                break;
            default:
                $fetched = $query->latest();
        }

        /* colour */
        if ($request->has('color') && $request->color != null)
            $query = $query->WhereJsonContains("colors", ['#' . $request['color']]);
        if ($request['min_price'] != null || $request['max_price'] != null) {
            //            return $request['min_price'];
            //            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
            $fetched = $fetched->whereBetween('purchase_price', [$request['min_price'], $request['max_price']]);
            //            return \response()->json(['data' => $fetched->get()]);

        }

        //        $products = $fetched->paginate(20)->appends($data);
        $products = $fetched->simplePaginate($request['take'] ?? 12);

        // ->appends($data);
        // $colors = Color::pluck('code')->toArray();
        $selected_colors = [];
        // $brands_ids = [];
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $products->map(function ($product) use ($selected_colors /*, $brands_ids */) {
            $product->pre_price = Helpers::currency_converter($product->unit_price);
            $product->price = Helpers::currency_converter($product->unit_price - Helpers::get_product_discount($product, $product->unit_price));
            if ($product->discount > 0) {
                if ($product->discount_type == 'percent')
                    $product->discountValue = round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings : 0)) . "%";
                else
                if ($product->discount_type == 'flat')
                    $product->discountValue = Helpers::currency_converter($product->discount);
                $product->purchasePrice = Helpers::currency_converter($product->purchase_price);
                $product->priceAfterDiscount = Helpers::currency_converter($product->purchase_price - Helpers::get_product_discount($product, $product->purchase_price));
            }
            // array_push($brands_ids, $product->brand_id);
            if (count(json_decode($product->colors)) > 0)
                foreach (json_decode($product->colors) as $color)
                    array_push($selected_colors, $color);
            return $product;
        });
        $colors = Color::Select('name', 'code')->whereIn('code', $selected_colors)->get();
        // $brands_ids = $query->pluck('brand_id')->toArray();
        // $brands = Brand::Select('id', 'name', 'image')->WhereIn('id', $brands_ids)->get();

        // foreach ($products as $product)
        //     if (count(json_decode($product->colors)) > 0)
        //         foreach (json_decode($product->colors) as $color)
        //             // if (in_array($color, $colors))
        //             array_push($selected_colors, $color);



        // if ($request->ajax()) {
        //     return response()->json([
        //         'total_product' => $products->total(),
        //         'view' => view('web-views.products._ajax-products', compact('products'))->render()
        //     ], 200);
        // }
        $title = '';
        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];
        if ($request['data_from'] == 'brand') {
            $brand_data = Brand::active()->find((int)$request['id']);
            if ($brand_data) {
                $title = $brand_data->name;
                $data['brand_name'] = $brand_data->name;
            } else {
                // Toastr::warning(translate('not_found'));
                return response()->json([
                    'success' => false,
                    'message' => translate('not_found')
                ], 404);
            }
        } else {
            if (!isset($title) && isset($request['id'])) {
                $title = Category::find((int)$request['id'])->name;
                $data['brand_name'] = $title;
            }
        }
        //        return $products;
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'products' => $products,
                // 'data' => $data,
                'colors' => $colors,
                'title' => $title
                // 'brands' => $brands,
            ], 200);
        }
        return response()->json(['products'=>$products]);
        return view('web-views.products.view', compact('products', 'data', 'colors'/*, 'brands' */), $data);
    }

    public function discounted_products(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews']);

        if ($request['data_from'] == 'category') {
            $query = $porduct_data->whereHas('categories', function ($query) use ($request) {
                $query->where('id', $request['id']);
            });
        }

        if ($request['data_from'] == 'brand')
            $query = $porduct_data->where('brand_id', $request['id']);

        if ($request['data_from'] == 'latest')
            $query = $porduct_data->orderBy('id', 'DESC');

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'featured')
            $query = Product::with(['reviews'])->active()->where('featured', 1);

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $query = $porduct_data->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        if ($request['data_from'] == 'discounted_products')
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);

        if ($request['sort_by'] == 'latest')
            $fetched = $query->latest();
        elseif ($request['sort_by'] == 'high-low')
            $fetched = $query->orderBy('unit_price', 'DESC');
        elseif ($request['sort_by'] == 'a-z')
            $fetched = $query->orderBy('name', 'ASC');
        elseif ($request['sort_by'] == 'z-a')
            $fetched = $query->orderBy('name', 'DESC');
        elseif ($request['sort_by'] == 'low-high') {
            return "low";
            $fetched = $query->orderBy('unit_price', 'ASC');
        } else
            $fetched = $query;

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween(
                'unit_price',
                [
                    Helpers::convert_currency_to_usd($request['min_price']),
                    Helpers::convert_currency_to_usd($request['max_price'])
                ]
            );
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(5)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render()
            ], 200);
        }
        if ($request['data_from'] == 'category')
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        if ($request['data_from'] == 'brand')
            $data['brand_name'] = Brand::active()->find((int)$request['id'])->name;

        return view('web-views.products.view', compact('products', 'data'), $data);
    }

    public function viewWishlist()
    {
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $digital_product_setting = BusinessSetting::where('type', 'digital_product')->first()->value;

        $wishlists = Wishlist::whereHas('wishlistProduct', function ($q) {
            return $q;
        })->where('customer_id', auth('customer')->id())->get();
        return view('web-views.users-profile.account-wishlist', compact('wishlists', 'brand_setting'));
    }

    public function storeWishlist(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $wishlist = Wishlist::where('customer_id', auth('customer')->id())->where(
                    'product_id',
                    $request->product_id
                )->first();
                if (empty($wishlist)) {
                    $wishlist = new Wishlist;
                    $wishlist->customer_id = auth('customer')->id();
                    $wishlist->product_id = $request->product_id;
                    $wishlist->save();

                    $countWishlist = Wishlist::whereHas('wishlistProduct', function ($q) {
                        return $q;
                    })->where('customer_id', auth('customer')->id())->get();

                    $data = \App\CPU\translate("Product has been added to wishlist");

                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put(
                        'wish_list',
                        Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray()
                    );
                    return response()->json(
                        [
                            'success' => $data,
                            'value' => 1,
                            'count' => count($countWishlist),
                            'id' => $request->product_id,
                            'product_count' => $product_count
                        ]
                    );
                } else {
                    $data = \App\CPU\translate("Product already added to wishlist");
                    return response()->json(['error' => $data, 'value' => 2]);
                }
            } else {
                $data = translate('login_first');
                return response()->json(['error' => $data, 'value' => 0]);
            }
        }
    }

    public function deleteWishlist(Request $request)
    {
        Wishlist::where(['product_id' => $request['id'], 'customer_id' => auth('customer')->id()])->delete();
        $data = "Product has been remove from wishlist!";
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
        session()->put(
            'wish_list',
            Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray()
        );
        return response()->json([
            'success' => $data,
            'count' => count($wishlists),
            'id' => $request->id,
            'wishlist' => view('web-views.partials._wish-list-data', compact('wishlists'))->render(),
        ]);
    }

    //for HelpTopic
    public function helpTopic()
    {
        $helps = HelpTopic::Status()->latest()->get();
        return response()->json([
            'success' => true,
            'helps' => $helps
        ]);
    }

    //for Contact US Page
    public function contacts()
    {
        return view('web-views.contacts');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('web-views.about-us', [
            'about_us' => $about_us,
        ]);
    }

    public function termsandCondition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('web-views.terms', compact('terms_condition'));
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('web-views.privacy-policy', compact('privacy_policy'));
    }

    public function refund_policy()
    {
        $refund_policy = json_decode(BusinessSetting::where('type', 'refund-policy')->first()->value);
        if (!$refund_policy->status) {
            return back();
        }
        $refund_policy = $refund_policy->content;
        return view('web-views.refund-policy', compact('refund_policy'));
    }

    public function return_policy()
    {
        $return_policy = json_decode(BusinessSetting::where('type', 'return-policy')->first()->value);
        if (!$return_policy->status) {
            return back();
        }
        $return_policy = $return_policy->content;
        return view('web-views.return-policy', compact('return_policy'));
    }

    public function cancellation_policy()
    {
        $cancellation_policy = json_decode(BusinessSetting::where('type', 'cancellation-policy')->first()->value);
        if (!$cancellation_policy->status) {
            return back();
        }
        $cancellation_policy = $cancellation_policy->content;
        return view('web-views.cancellation-policy', compact('cancellation_policy'));
    }

    //order Details

    public function orderdetails()
    {
        return view('web-views.orderdetails');
    }

    public function chat_for_product(Request $request)
    {
        return $request->all();
    }

    public function supportChat()
    {
        return view('web-views.users-profile.profile.supportTicketChat');
    }

    public function error()
    {
        return view('web-views.404-error-page');
    }

    public function contact_store(Request $request)
    {
        //recaptcha validation
        $recaptcha = Helpers::get_business_settings('recaptcha');
        //        if (isset($recaptcha) && $recaptcha['status'] == 1) {
        //
        //            try {
        //                $request->validate([
        //                    'g-recaptcha-response' => [
        //                        function ($attribute, $value, $fail) {
        //                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
        //                            $response = $value;
        //                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
        //                            $response = \file_get_contents($url);
        //                            $response = json_decode($response);
        //                            if (!$response->success) {
        //                                $fail(\App\CPU\translate('ReCAPTCHA Failed'));
        //                            }
        //                        },
        //                    ],
        //                ]);
        //
        //            } catch (\Exception $exception) {
        //                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
        //            }
        //        } else {
        //            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
        //                Session::forget('default_captcha_code');
        //                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
        //            }
        //        }

        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',

        ]);
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        return response()->json([
            'success' => true,
            'message' => translate('Your Message Send Successfully'),
        ]);
    }

    public function captcha($tmp)
    {
        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if (Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function order_note(Request $request)
    {
        if ($request->has('order_note')) {
            session::put('order_note', $request->order_note);
        }
        return response()->json();
    }

    public function complete_order_as_guest(Request $request)
    {
        Log::alert($request);
        session::forget('order_note');
        session::forget('person_name');
        session::forget('person_phone');
        session::forget('person_area');
        session::forget('person_city');
        session::forget('person_zip');
        session::forget('person_address');
        session::forget('payment_scenario');

        //        return $request;
        if ($request->has('order_note')) {
            session::put('order_note', $request->order_note);
        }
        if ($request->has('contact_person_name')) {
            session::put('person_name', $request->contact_person_name);
        }
        if ($request->has('order_phone')) {
            session::put('person_phone', $request->order_phone);
        }
        if ($request->has('area')) {
            session::put('person_area', $request->area);
        }

        if ($request->has('city')) {
            session::put('person_city', $request->city);
        }

        if ($request->has('zip')) {
            session::put('person_zip', $request->zip);
        }

        if ($request->has('address')) {
            session::put('person_address', $request->address);
        }
        if ($request->has('email')) {
            session::put('person_email', $request->email);
        }
        if ($request->has('payment_scenario')) {
            session::put('payment_scenario', $request->payment_scenario);
        }
        if ($request->has('shop_name')) {
            session::put('shop_name', $request->shop_name);
        }
        //        return 'yeste';

        return response()->json([
            'data' => [
                'order_note' => session('order_note'),
                'person_name' => session('person_name'),
                'person_phone' => session('person_phone'),
                'person_area' => session('person_area'),
                'person_city' => session('person_city'),
                'person_zip' => session('person_zip'),
                'person_address' => session('person_address'),
                'payment_scenario' => session('payment_scenario'),
                'shop_name' => session('shop_name'),
            ]
        ]);
    }

    public function digital_product_download($id)
    {
        $order_data = OrderDetail::with('order.customer')->find($id);
        $customer_id = auth('customer')->id();
        if ($order_data->order->customer->id != $customer_id) {
            Toastr::info(translate('Invalid customer'));
            return redirect('/');
        }

        if ($order_data->product->digital_product_type == 'ready_product' && $order_data->product->digital_file_ready) {
            $file_path = storage_path('app/public/product/digital-product/' . $order_data->product->digital_file_ready);
        } else {
            $file_path = storage_path('app/public/product/digital-product/' . $order_data->digital_file_after_sell);
        }

        return \response()->download($file_path);
    }

    public function subscription(Request $request)
    {
        $subscription_email = Subscription::where('email', $request->subscription_email)->first();
        if (isset($subscription_email)) {
            // Toastr::info(translate('You already subcribed this site!!'));
            return response()->json([
                'success' => true,
                'message' => translate('You already subcribed this site!!')
            ]);
        } else {
            $new_subcription = new Subscription;
            $new_subcription->email = $request->subscription_email;
            $new_subcription->save();

            // Toastr::success(translate('Your subscription successfully done!!'));
            return response()->json([
                'success' => true,
                'message' => translate('Your subscription successfully done!!')
            ]);
        }
    }

    public function review_list_product(Request $request)
    {
        $productReviews = Review::where('product_id', $request->product_id)->latest()->paginate(
            2,
            ['*'],
            'page',
            $request->offset
        );


        return response()->json([
            'productReview' => view('web-views.partials.product-reviews', compact('productReviews'))->render(),
            'not_empty' => $productReviews->count()
        ]);
    }

    public function staticPage($id)
    {
        $page = StaticPage::find($id);
        if ($page) {
            return view('web-views.static-page', compact('page'));
        }
        Toastr::error('الصفحة غير موجودة');
        return redirect('/');
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
        $group_id_wise_cart = CartManager::get_cart($data['cart_group_id']);
        $total_amount = 0;
        foreach ($carts as $cart) {
            if (($coupon->seller_id == null && $cart->seller_is == 'admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')) {
                $total_amount += ($cart['price'] * $cart['quantity']);
            }
        }

        if (($group_id_wise_cart[0]->seller_is == 'admin' && $coupon->seller_id == null) || $coupon->seller_id == '0' || ($coupon->seller_id == $group_id_wise_cart[0]->seller_id && $group_id_wise_cart[0]->seller_is == 'seller')) {
            $cart_group_ids = CartManager::get_cart_group_ids($req ?? null);
            $discount = 0;

            if ($coupon->coupon_type == 'discount_on_purchase' || $coupon->coupon_type == 'first_order') {
                $group_id_percent = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api(
                        $req,
                        $cart_group_id
                    ) : CartManager::get_cart($cart_group_id);
                    $cart_group_amount = 0;
                    if ($coupon->seller_id == null || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id) {
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
                    $cart_group_data = $req ? CartManager::get_cart_for_api(
                        $req,
                        $cart_group_id
                    ) : CartManager::get_cart($cart_group_id);

                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart_group_data[0]->seller_is == 'admin') {
                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Model\ShippingType::where(
                                'seller_id',
                                $cart_group_data[0]->seller_id
                            )->first();
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

    public static function guest_coupon_process($data, $coupon)
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
            if (($coupon->seller_id == null && $cart['seller_is'] == 'admin') || $coupon->seller_id == '0' || ($coupon->seller_id == $cart['seller_id'] && $cart['seller_is'] == 'seller')) {
                $total_amount += ($cart['price'] * $cart['quantity']);
            }
        }

        if (($group_id_wise_cart[0]['seller_is'] == 'admin' && $coupon->seller_id == null) || $coupon->seller_id == '0' || ($coupon->seller_id == $group_id_wise_cart[0]['seller_id'] && $group_id_wise_cart[0]['seller_is'] == 'seller')) {
            $cart_group_ids = CartManager::get_cart_group_ids($req ?? null);
            $discount = 0;

            if ($coupon->coupon_type == 'discount_on_purchase' || $coupon->coupon_type == 'first_order') {
                $group_id_percent = array();
                foreach ($cart_group_ids as $cart_group_id) {
                    $cart_group_data = $req ? CartManager::get_cart_for_api($req, $cart_group_id) : session(
                        'offline_cart'
                    )->groupBy('cart_group_id')[$cart_group_id];
                    $cart_group_amount = 0;
                    if ($coupon->seller_id == null || $coupon->seller_id == '0' || $coupon->seller_id == $cart_group_data[0]->seller_id) {
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
                    $cart_group_data = $req ? CartManager::get_cart_for_api(
                        $req,
                        $cart_group_id
                    ) : CartManager::get_cart($cart_group_id);

                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart_group_data[0]->seller_is == 'admin') {
                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = \App\Model\ShippingType::where(
                                'seller_id',
                                $cart_group_data[0]->seller_id
                            )->first();
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

    public function payToPartner($data = null, $shop_name = null)
    {
        if (!auth('customer')->check()) {
            $shop_name = \session()->get('shop_name');
            $data = session('offline_cart');
        }
        $shop = Shop::where('name', $shop_name)->first();
        $commission = $shop->seller->sales_commission_percentage;
        $api = DB::table('shop_rest_api')->where('shop_id', $shop->id)->get();
        $api = $api->first();
        $shopifyStore = new Shopify(
            $api->host,
            $api->access_token,
            $api->api_key,
            $api->api_secret,
        );

        $web_url = $shopifyStore->makeDraftOrder($data, $shop_name, $commission);
        //        $shopifyStore->deleteDraftOrder('1147557937399');
        //        $shopifyStore->completeDraftOrder('1148106703095');
        //        dd($web_url);
        return redirect($web_url);
    }
    // function that filter products with their origin country
    private function product_origin_filter($country_code, $products){
        
    try{    
        if($country_code == 'imported'){
            return $products->filter(function($product){
                return $product->seller->country->code != 'SU'; 
            });
        }else if($country_code){
            return $products->filter(function($product) use ($country_code){
                return $product->seller->country->code == $country_code; 
            });
        }else{
            return $products;
        }
    }catch(Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
    }

    }

    // function that return minimum and maimum prices of a product
    private function min_and_max($products) {
        try {
            if(empty($products)){
                return [];
            }
            $prices = $products->pluck('unit_price'); 
    
            // Sort the prices array
            sort($prices);
    
            // Calculate the minimum, maximum, and step
            $min = reset($prices);
            $max = end($prices);
            $step = ($max - $min) / 5;
    
            $min = round($min);
            $max = round($max);
    
            // Calculate the price categories
            $price_categories = [];
            for ($i = 1; $i < 6; $i++) {
                $categ = round($min + ($step * $i));
                array_push($price_categories, $categ);
            }
    
            return array_unique($price_categories);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // products of customized prices
    private function products_between_prices($products, $min = 0, $max = 0){
        if(empty($products) || $min == $max){
            return [];
        }
        try{
            $choosed_products = $products->
                filter(function($product) use ($min, $max){
                        if($product->unit_price >= $min && $product->unit_price <= $max){//change the price later
                            return $product;
                        }
                });
            return $choosed_products;
        }catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // get similar products `by name`
    public function getSimilarProducts($id){
        $ProductName = Product::find($id)->name()->getResults();
        return $ProductName->products;
    }
    // // get similar products `by name`
    // public function getSimilarProducts($id){
    //     $product = Product::find($id);
    //     if(!$product){
    //         return response()->json(['error'=>'product not found'],404);

    //     }else{
    //         $sameProducts = Product::where('name',$product->name)->where('id', '!=', $id)->get();
    //         if(empty($sameProducts)){
    //             return response()->json(['message'=>'no similar products found']);
    //         }else{
    //             return response()->json(['data'=>$sameProducts],200);
    //         }
    //     }
    // }

    // sort products by recent products first
    private function sortByCreatedAt($products)
    {
        if ($products instanceof \Illuminate\Database\Eloquent\Collection) {
            return $products->sortByDesc('created_at');
        }else{
            throw new \Exception('Invalid parameter. parameter must be an instance of product collection');

        }

    }

    // sort products by ratings
    private function sortByRatings($products)
    {
        if ($products instanceof \Illuminate\Database\Eloquent\Collection) {
            return $products->sortByDesc(function ($product) {
                return $product->reviews->avg('rating');
            });
        }else{
            throw new \Exception('Invalid parameter. parameter must be an instance of product collection');

        }
    }




    // static function tindex(){
    //     $products = Product::all(); // Get all products
    //     return \App\Http\Resources\Product\ProductResource::collection($products)->resolve(); // Return products as a resource collection
    // }
    
}
