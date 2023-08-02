<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\Helpers;
use App\Model\Category;
use App\Model\Product;
use App\Model\Color;
use Brian2694\Toastr\Facades\Toastr;
use App\Model\Order;
use App\Model\Coupon;
use App\User;
use App\Model\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\CPU\BackEndHelper;
use Rap2hpoutre\FastExcel\FastExcel;

class POSController extends Controller
{
    public function order_list(Request $request)
    {
        $search = $request['search'];
        $from   = $request['from'];
        $to     = $request['to'];

        $key = $request['search'] ? explode(' ', $request['search']) : '';

        $orders = Order::with(['customer'])
                        ->where('order_type','POS')
                        ->where(['seller_is'=>'admin'])
                        ->where('order_status','delivered')
                        ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                            $q->where(function($qq) use ($key){
                                foreach ($key as $value) {
                                    $qq->where('id', 'like', "%{$value}%")
                                        ->orWhere('order_status', 'like', "%{$value}%")
                                        ->orWhere('transaction_ref', 'like', "%{$value}%");
                                }});
                        })
                        ->when(!empty($from) && !empty($to) , function($dateQuery) use($from, $to) {
                            $dateQuery->whereDate('created_at', '>=',$from)
                                ->whereDate('created_at', '<=',$to);
                        })
                        ->latest()
                        ->paginate(Helpers::pagination_limit())
                        ->appends(['search'=>$request['search'],'from'=>$request['from'],'to'=>$request['to']]);

        return view('admin-views.pos.order.list', compact('orders', 'search','from','to'));
    }

    public function bulk_export_data(Request $request)
    {
        $from   = $request['from'];
        $to     = $request['to'];

        $orders = Order::with(['customer'])
            ->where('order_type','POS')
            ->where(['seller_is'=>'admin'])
            ->where('order_status','delivered')
            ->when(!empty($from) && !empty($to) , function($query) use($from, $to) {
                $query->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })->latest()->get();


        $posData = array();
        foreach($orders as $order){
            $posData[] = array(
                'Order ID'      => $order->id,
                'Date'          => date('d M Y',strtotime($order->created_at)),
                'Customer Name' => $order->customer ? $order->customer['f_name'].' '.$order->customer['l_name'] : \App\CPU\translate('invalid_customer_data'),
                'Status'        => $order->payment_status == 'paid' ? 'paid' : 'unpaid',
                'Total'         => \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount)),
                'Order Status'  => $order->order_status ?? '',
            );
        }

        return (new FastExcel($posData))->download('POS_Order_All_data.xlsx');
    }

    public function order_details($id)
    {
        $order = Order::with('details', 'shipping', 'seller')->where(['id' => $id])->first();

        return view('admin-views.pos.order.order-details', compact('order'));
    }

    /*
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $request->validate([
            'digital_file_after_sell'    => 'required|mimes:jpg,jpeg,png,gif,zip,pdf'
        ], [
            'digital_file_after_sell.required' => 'Digital file upload after sell is required',
            'digital_file_after_sell.mimes' => 'Digital file upload after sell upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
        ]);

        $order_details = OrderDetail::find($request->order_id);
        $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'));

        if($order_details->save()){
            Toastr::success('Digital file upload successfully!');
        }else{
            Toastr::error('Digital file upload failed!');
        }
        return back();
    }

    public function index(Request $request)
    {
        $category = $request->query('category_id', 0);
        $keyword = $request->query('search', false);
        $categories = Category::where('position',0)->latest()->get();

        $key = explode(' ', $keyword);
        $products = Product::where('added_by','admin')->where('status',1)
            ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                $query->whereJsonContains('category_ids', [['id' => (string)$request['category_id']]]);
            })
            ->when($keyword, function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate(Helpers::pagination_limit());

        $cart_id = 'wc-'.rand(10,1000);

        if(!session()->has('current_user')){
            session()->put('current_user',$cart_id);
        }

        if(!session()->has('cart_name'))
        {
            if(!in_array($cart_id,session('cart_name')??[]))
            {
                session()->push('cart_name', $cart_id);
            }
        }

        return view('admin-views.pos.index',compact('categories','cart_id','category','keyword','products'));
    }
    public function search_product(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required',
        ]);

        $key = explode(' ', $request['name']);
        $products = Product::where('added_by','admin')->where('status',1)
                                ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                                    $query->whereJsonContains('category_ids', [[['id' => (string)$request['category_id']]]]);
                                })->where(function ($q) use ($key) {
                                    foreach ($key as $value) {
                                        $q->where('name', 'like', "%{$value}%");
                                    }
                                })->paginate(6);

        $count_p = $products->count();$count_p = $products->count();

        if($count_p>0)
        {
            return response()->json([
                'count' => $count_p,
                'id' => $products[0]->id,
                'result' => view('admin-views.pos._search-result', compact('products'))->render(),
            ]);
        }else{
            return response()->json([
                'count' => $count_p,
                'result' => view('admin-views.pos._search-result', compact('products'))->render(),
            ]);
        }

    }
    public function quick_view(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }

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
                    $tax = Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']);
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = Helpers::tax_calculation($product->unit_price, $product['tax'], $product['tax_type']);
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $quantity = $product->current_stock;
        }

        return [
            'price' => \App\CPU\Helpers::currency_converter($price * $request->quantity),
            'discount' => \App\CPU\Helpers::currency_converter($discount),
            'tax' => \App\CPU\Helpers::currency_converter($tax),
            'quantity' => $quantity
        ];

    }
    public function addToCart(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }

        $product = Product::find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $variations = [];
        $price = 0;
        $p_qty = 0;
        $current_qty = 0;

        //check the color enabled or disabled for the product
        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }
        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $data[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        $data['variations'] = $variations;
        $data['variant'] = $str;
        $cart = session($cart_id);
        if (session()->has($cart_id) && count($cart) > 0) {

            foreach ($cart as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id'] && $cartItem['variant'] == $str) {
                    return response()->json([
                        'data' => 1,
                        'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                    ]);
                }
            }

        }

        //Check the string and decreases quantity for the stock
        if ($str != null) {

            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {

                if (json_decode($product->variation)[$i]->type == $str) {
                    $p_qty = json_decode($product->variation)[$i]->qty;
                    $current_qty = $p_qty - $request['quantity'];
                    if($current_qty<0)
                    {
                        return response()->json([
                            'data' => 0,
                            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                        ]);
                    }

                    $price = json_decode($product->variation)[$i]->price;

                }
            }
        } else {
            $p_qty = $product->current_stock;
            $current_qty = $p_qty - $request['quantity'];
            if($product->product_type == 'physical' && $current_qty<0)
            {
                return response()->json([
                    'data' => 0,
                    'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                ]);
            }
            $price = $product->unit_price;
        }

        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['name'] = $product->name;
        $data['discount'] = Helpers::get_product_discount($product, $price);
        $data['image'] = $product->thumbnail;
        $data['tax_model'] = $product->tax_model;


        if (session()->has($cart_id)) {
            $keeper = [];
            foreach (session($cart_id) as $item) {
                array_push($keeper, $item);
            }
            array_push($keeper, $data);
            session()->put($cart_id, $keeper);
        } else {
            session()->put($cart_id, [$data]);
        }

        return response()->json([
            'data' => $data,
            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
        ]);
    }
    public function cart_items()
    {
        return view('admin-views.pos._cart');
    }

    public function emptyCart(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }
        session()->forget($cart_id);
        return response()->json([
            'user_type'=>$user_type,
            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()], 200);
    }
    public function removeFromCart(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';

        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }

        $cart = session($cart_id);
        $cart_keeper = [];

        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $key=>$cartItem) {
                if ($key != $request['key']) {
                    array_push($cart_keeper, $cartItem);
                }
            }
        }
        session()->put($cart_id, $cart_keeper);

        return response()->json(['view' => view('admin-views.pos._cart',compact('cart_id'))->render()], 200);
    }
    public function updateQuantity(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }

        if($request->quantity>0){

            $product = Product::find($request->key);
            $product_qty =0;
            $cart = session($cart_id);
            $keeper=[];

            foreach ($cart as $item){

                if (is_array($item)) {

                    $variant_check = false;
                    if(!empty($item['variant']) && ($item['variant'] == $request->variant) && ($item['id'] == $request->key)){
                        $variant_check = true;
                    }elseif(empty($request->variant) && $item['id'] == $request->key){
                        $variant_check = true;
                    }

                    if ($variant_check) {
                        $str = '';
                        if($item['variations'])
                        {
                            foreach($item['variations'] as $v)
                            {
                                if($str!=null)
                                {
                                    $str .= '-' . str_replace(' ', '', $v);
                                }else{
                                    $str .= str_replace(' ', '', $v);
                                }
                            }
                        }

                        if ($str != null) {

                            $count = count(json_decode($product->variation));
                            for ($i = 0; $i < $count; $i++) {

                                if (json_decode($product->variation)[$i]->type == $str) {

                                    $product_qty = json_decode($product->variation)[$i]->qty;

                                }
                            }
                        } else
                        {
                            $product_qty = $product->current_stock;
                        }

                        $qty = $product_qty - $request->quantity ;

                        if($product->product_type =='physical' && $qty < 0)
                        {
                            return response()->json([
                                'qty' => $qty,
                                'product_type' => $product->product_type,
                                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                                ]);
                        }
                        $item['quantity'] = $request->quantity;
                    }
                    array_push($keeper,$item);
                }
            }
            session()->put($cart_id, $keeper);

            return response()->json([
                'qty_update'=>1,
                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
            ], 200);
        }else{
            return response()->json([
                'upQty'=>'zeroNegative',
                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
            ]);
        }
    }
    public function extra_dis_calculate($cart, $price)
    {

        if ($cart['ext_discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $cart['ext_discount'];
        } else {
            $price_discount = $cart['ext_discount'];
        }

        return $price_discount;
    }

    public function coupon_discount(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }
        if($user_id !=0)
        {
            $coupon_limit = Order::where('customer_id', $user_id)
                ->where('customer_type', 'customer')
                ->where('coupon_code', $request['coupon_code'])->count();

            $coupon = Coupon::where(['code' => $request['coupon_code']])
            ->where('status', '=', 1)
            ->where('added_by', 'admin')
            ->whereDate('start_date', '<=', now())
            ->whereDate('expire_date', '>=', now())->first();

            if(isset($coupon) && $coupon->coupon_type == 'first_order' && $coupon_limit > 0){
                return response()->json([
                    'coupon' => 'Sorry this coupon is not valid for this user',
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            }

            if($coupon && $coupon->limit < $coupon_limit && $coupon->coupon_type != 'first_order') {
                return response()->json([
                    'coupon' => 'coupon_invalid',
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            }
        }else{
            $coupon = Coupon::where(['code' => $request['coupon_code']])
            ->where('status', '=', 1)
            ->where('added_by', 'admin')
            ->whereDate('start_date', '<=', now())
            ->whereDate('expire_date', '>=', now())->first();
        }

        if(!$coupon || $coupon->coupon_type == 'free_delivery' || $coupon->coupon_bearer != 'inhouse' || !in_array($coupon->seller_id, ["0", NULL])) {
            return response()->json([
                'coupon' => 'coupon_invalid',
                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
            ]);
        }

        $carts = session($cart_id);
        $total_product_price = 0;
        $product_discount = 0;
        $product_tax =0;
        $ext_discount = 0;

        if($coupon->customer_id == '0' || $coupon->customer_id == $user_id) {
            if ($carts != null) {
                foreach ($carts as $cart) {
                    if (is_array($cart)) {
                        $product = Product::find($cart['id']);
                        $total_product_price += $cart['price'] * $cart['quantity'];
                        $product_discount += $cart['discount'] * $cart['quantity'];
                        $product_tax += Helpers::tax_calculation($cart['price'], $product['tax'], $product['tax_type']) * $cart['quantity'];
                    }
                }
                if ($total_product_price >= $coupon['min_purchase']) {
                    if ($coupon['discount_type'] == 'percentage') {

                        $discount = (($total_product_price / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total_product_price / 100) * $coupon['discount']);
                    } else {
                        $discount = $coupon['discount'];
                    }
                    if (isset($carts['ext_discount_type'])) {
                        $ext_discount = $this->extra_dis_calculate($carts, $total_product_price);

                    }
                    $total = $total_product_price - $product_discount + $product_tax - $discount - $ext_discount;
                    //return $total;
                    if ($total < 0) {
                        return response()->json([
                            'coupon' => "amount_low",
                            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                        ]);
                    }

                    $cart = session($cart_id, collect([]));
                    $cart['coupon_code'] = $request['coupon_code'];
                    $cart['coupon_discount'] = $discount;
                    $cart['coupon_title'] = $coupon->title;
                    $cart['coupon_bearer'] = $coupon->coupon_bearer;
                    $request->session()->put($cart_id, $cart);

                    return response()->json([
                        'coupon' => 'success',
                        'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                    ]);
                }
            } else {
                return response()->json([
                    'coupon' => 'cart_empty',
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            }
        }

            return response()->json([
                'coupon' =>'coupon_invalid',
                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
            ]);


    }

    public function update_discount(Request $request)
    {
        $cart_id = session('current_user');
        if ($request->type == 'percent' && $request->discount < 0) {
            Toastr::error(\App\CPU\translate('Extra_discount_can_not_be_less_than_0_percent'));
            return response()->json([
                'extra_discount' =>"amount_low",
                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
            ]);
        } elseif ($request->type == 'percent' && $request->discount > 100) {
            Toastr::error(\App\CPU\translate('Extra_discount_can_not_be_more_than_100_percent'));
            return response()->json([
                'extra_discount' =>"amount_low",
                'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
            ]);
        }


        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }

        $cart = session($cart_id, collect([]));
        if($cart!=null)
        {
            $total_product_price = 0;
            $product_discount = 0;
            $product_tax =0;
            $ext_discount = 0;
            $coupon_discount = $cart['coupon_discount']??0;

            foreach($cart as $ct)
            {
                if(is_array($ct))
                {
                    $product = Product::find($ct['id']);
                    $total_product_price += $ct['price'] * $ct['quantity'];
                    $product_discount += $ct['discount'] * $ct['quantity'];
                    $product_tax += Helpers::tax_calculation($ct['price'], $product['tax'], $product['tax_type'])*$ct['quantity'];
                }
            }

            if ($request->type == 'percent') {
                $ext_discount = ($total_product_price / 100) * $request->discount;
            } else {
                $ext_discount = $request->discount;
            }
            $total = $total_product_price - $product_discount + $product_tax - $coupon_discount - $ext_discount;
            if($total < 0)
            {
                return response()->json([
                        'extra_discount' =>"amount_low",
                        'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                    ]);
            }
            else{
                $cart['ext_discount'] = $request->type == 'percent'?$request->discount:BackEndHelper::currency_to_usd($request->discount);
                $cart['ext_discount_type'] = $request->type;
                session()->put($cart_id, $cart);

                return response()->json([
                            'extra_discount' =>"success",
                            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                        ]);
            }
        }else{
            return response()->json([
                            'extra_discount' =>"empty",
                            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()
                        ]);
        }
    }
    public function get_customers(Request $request)
    {
        $key = explode(' ', $request['q']);
        $data = DB::table('users')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            })
            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->limit(8)
            ->get([DB::raw('id,IF(id <> "0", CONCAT(f_name, " ", l_name, " (", phone ,")"),CONCAT(f_name, " ", l_name)) as text')]);

        //$data[] = (object)['id' => false, 'text' => 'walk_in_customer'];

        return response()->json($data);
    }
    public function place_order(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }
        if (session()->has($cart_id)) {
            if (count(session()->get($cart_id)) < 1) {
                Toastr::error(\App\CPU\translate('cart_empty_warning'));
                return back();
            }
        } else {
            Toastr::error(\App\CPU\translate('cart_empty_warning'));
            return back();
        }

        $cart = session($cart_id);
        $total_tax_amount = 0;
        $product_price = 0;
        $order_details = [];

        $order_id = 100000 + Order::all()->count() + 1;
        if (Order::find($order_id)) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }

        $product_subtotal = 0;
        foreach($cart as $c)
        {
            if(is_array($c))
            {
                $discount_on_product = 0;
                $product_subtotal = ($c['price']) * $c['quantity'];
                $discount_on_product += ($c['discount'] * $c['quantity']);

                $product = Product::find($c['id']);
                if($product)
                {
                    $tax = Helpers::tax_calculation($c['price'], $product['tax'], $product['tax_type']);
                    $price = $product['tax_model']=='include' ? $c['price']-$tax : $c['price'];

                    //$product = Helpers::product_data_formatting($product);
                    $or_d = [
                        'order_id' => $order_id,
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'qty' => $c['quantity'],
                        'price' => $price,
                        'seller_id' => $product['user_id'],
                        'tax' => $tax*$c['quantity'],
                        'tax_model' => $product['tax_model'],
                        'discount' => $c['discount']*$c['quantity'],
                        'discount_type' => 'discount_on_product',
                        'delivery_status' => 'delivered',
                        'payment_status' => 'paid',
                        'variant' => $c['variant'],
                        'variation' => json_encode($c['variations']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_tax_amount += $or_d['tax'] * $c['quantity'];
                    $product_price += $product_subtotal - $discount_on_product;
                    $order_details[] = $or_d;

                    if ($c['variant'] != null) {
                        $type = $c['variant'];
                        $var_store = [];

                        foreach (json_decode($product['variation'],true) as $var) {
                            if ($type == $var['type']) {
                                $var['qty'] -= $c['quantity'];
                            }
                            array_push($var_store, $var);
                        }
                        Product::where(['id' => $product['id']])->update([
                            'variation' => json_encode($var_store),
                        ]);
                    }

                    if($product->product_type == 'physical') {
                        Product::where(['id' => $product['id']])->update([
                            'current_stock' => $product['current_stock'] - $c['quantity']
                        ]);
                    }

                    DB::table('order_details')->insert($or_d);
                }
            }
        }

        $total_price = $product_price;
        if (isset($cart['ext_discount'])) {
            $extra_discount = $cart['ext_discount_type'] == 'percent' && $cart['ext_discount'] > 0 ? (($total_price * $cart['ext_discount']) / 100) : $cart['ext_discount'];
            $total_price -= $extra_discount;
        }
        $or = [
            'id' => $order_id,
            'customer_id' => $user_id,
            'customer_type' => 'customer',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'seller_id' => auth('admin')->id(),
            'seller_is' => 'admin',
            'checked' =>1,
            'payment_method' => $request->type,
            'order_type' => 'POS',
            'extra_discount' =>$cart['ext_discount']??0,
            'extra_discount_type' => $cart['ext_discount_type']??null,
            'order_amount' => BackEndHelper::currency_to_usd($request->amount),
            'discount_amount' => $cart['coupon_discount']??0,
            'coupon_code' => $cart['coupon_code']??null,
            'discount_type' => (isset($cart['coupon_code']) && $cart['coupon_code']) ? 'coupon_discount' : null,
            'coupon_discount_bearer' => $cart['coupon_bearer']??'inhouse',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('orders')->insertGetId($or);

        session()->forget($cart_id);
        session(['last_order' => $order_id]);
        Toastr::success(\App\CPU\translate('order_placed_successfully'));
        return back();
    }
    public function store_keys(Request $request)
    {
        session()->put($request['key'], $request['value']);
        return response()->json('', 200);
    }
    public function get_cart_ids(Request $request)
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if(Str::contains(session('current_user'), 'sc'))
        {
            $user_id = explode('-',session('current_user'))[1];
            $user_type = 'sc';
        }
        $cart = session($cart_id);
        $cart_keeper = [];
        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                array_push($cart_keeper, $cartItem);
            }
        }
        session()->put(session('current_user') , $cart_keeper);
        $user_id = explode('-',session('current_user'))[1];
        $current_customer ='';
        if(explode('-',session('current_user'))[0]=='wc')
        {
            $current_customer = 'Walking Customer';
        }else{
            $current =User::where('id',$user_id)->first();
            $current_customer = $current->f_name.' '.$current->l_name. ' (' .$current->phone.')';
        }
        return response()->json([
            'current_user'=>session('current_user'),'cart_nam'=>session('cart_name')??'',
            'current_customer'=>$current_customer,
            'view'=> view('admin-views.pos._cart',compact('cart_id'))->render()]);
    }
    public function clear_cart_ids()
    {
        session()->forget('cart_name');
        session()->forget(session('current_user'));
        session()->forget('current_user');

        return redirect()->route('admin.pos.index');
    }
    public function remove_discount(Request $request)
    {
        $cart_id = ($request->user_id!=0?'sc-'.$request->user_id:'wc-'.rand(10,1000));
        if(!in_array($cart_id,session('cart_name')??[]))
        {
            session()->push('cart_name', $cart_id);
        }

        $cart = session(session('current_user'));

        $cart_keeper = [];
        if (session()->has(session('current_user')) && count($cart) > 0) {
            foreach ($cart as $cartItem) {

                    array_push($cart_keeper, $cartItem);

            }
        }
        if(session('current_user') != $cart_id)
        {
            $temp_cart_name = [];
                foreach(session('cart_name') as $cart_name)
                {
                    if($cart_name != session('current_user'))
                    {
                        array_push($temp_cart_name,$cart_name);
                    }
                }
                session()->put('cart_name',$temp_cart_name);
        }
        session()->put('cart_name',$temp_cart_name);
        session()->forget(session('current_user'));
        session()->put($cart_id , $cart_keeper);
        session()->put('current_user',$cart_id);
        $user_id = explode('-',session('current_user'))[1];
        $current_customer ='';
        if(explode('-',session('current_user'))[0]=='wc')
        {
            $current_customer = 'Walking Customer';
        }else{
            $current =User::where('id',$user_id)->first();
            $current_customer = $current->f_name.' '.$current->l_name. ' (' .$current->phone.')';
        }

        return response()->json([
            'cart_nam'=>session('cart_name'),
            'current_user'=>session('current_user'),
            'current_customer'=>$current_customer,
            'view' => view('admin-views.pos._cart',compact('cart_id'))->render()]);
    }
    public function new_cart_id(Request $request)
    {
        $cart_id = 'wc-'.rand(10,1000);
        session()->put('current_user',$cart_id);
        if(!in_array($cart_id,session('cart_name')??[]))
        {
            session()->push('cart_name', $cart_id);
        }

        return redirect()->route('admin.pos.index');

    }
    public function change_cart(Request $request)
    {

        session()->put('current_user',$request->cart_id);

        return redirect()->route('admin.pos.index');
    }
    public function customer_store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'unique:users',
            'country' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
        ]);
        $user = User::create([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'country' => $request['country'],
            'city' => $request['city'],
            'zip' => $request['zip_code'],
            'street_address' =>$request['address'],
            'is_active' => 1,
            'password' => bcrypt('password')
        ]);
        Toastr::success(\App\CPU\translate('customer added successfully'));
        return back();
    }

}
