<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ShippingType;

class ShippingTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shippingType'    => 'required',
        ]);

        //$user = auth('admin')->user();
        $shipping_type = ShippingType::where('seller_id',0)->first();
        if(isset($shipping_type))
        {
            $shipping_type->shipping_type = $request->shippingType;
            $shipping_type->save();
        }else{
            $new_shipping_type = new ShippingType;
            $new_shipping_type->seller_id = 0;
            $new_shipping_type->shipping_type = $request->shippingType;
            $new_shipping_type->save();
        }
        return response()->json();
    }
}
