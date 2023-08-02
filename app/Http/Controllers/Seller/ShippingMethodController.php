<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\ShippingMethod;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Category;
use App\Model\CategoryShippingCost;
use App\Model\ShippingType;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethod = Helpers::get_business_settings('shipping_method');
        $seller_id = auth('seller')->id();

        if($shippingMethod=='sellerwise_shipping')
        {
            $all_category_ids = Category::where(['position' => 0])->pluck('id')->toArray();
            $category_shipping_cost_ids = CategoryShippingCost::where('seller_id',$seller_id)->pluck('category_id')->toArray();
            foreach($all_category_ids as $id)
            {
                if(!in_array($id,$category_shipping_cost_ids))
                {
                    $new_category_shipping_cost = new CategoryShippingCost;
                    $new_category_shipping_cost->seller_id = $seller_id;
                    $new_category_shipping_cost->category_id = $id;
                    $new_category_shipping_cost->cost = 0;
                    $new_category_shipping_cost->save();
                }
            }
            $all_category_shipping_cost = CategoryShippingCost::where('seller_id',$seller_id)->get();
            $seller_shipping = ShippingType::where('seller_id',$seller_id)->first();
            $shippingType = isset($seller_shipping)==true? $seller_shipping->shipping_type: 'order_wise';
            $shipping_methods = ShippingMethod::where(['creator_id' => $seller_id, 'creator_type' => 'seller'])->latest()->paginate(Helpers::pagination_limit());

            return view('seller-views.shipping-method.add-new', compact('shipping_methods','all_category_shipping_cost','shippingType'));
        }else{
            return back();
        }

    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric'
        ]);

        DB::table('shipping_methods')->insert([
            'creator_id' => auth('seller')->id(),
            'creator_type' => 'seller',
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Toastr::success('Successfully added.');
        return back();
    }

    public function status_update(Request $request)
    {
        ShippingMethod::where(['id' => $request['id']])->update([
            'status' => $request['status']
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function edit($id)
    {
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        if($shippingMethod=='sellerwise_shipping')
        {
            $method = ShippingMethod::where(['id' => $id])->first();
            return view('seller-views.shipping-method.edit', compact('method'));
        }else{
            return redirect('/seller/dashboard');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric'
        ]);

        DB::table('shipping_methods')->where(['id' => $id])->update([
            'creator_id' => auth('seller')->id(),
            'creator_type' => 'seller',
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Toastr::success('Successfully updated.');
        return redirect()->route('seller.business-settings.shipping-method.add');
    }

    public function delete(Request $request)
    {
        $shipping = ShippingMethod::find($request->id);

        $shipping->delete();
        return response()->json();
    }
}
