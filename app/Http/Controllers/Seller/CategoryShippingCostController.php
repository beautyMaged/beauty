<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CategoryShippingCost;
use Brian2694\Toastr\Facades\Toastr;
use App\CPU\BackEndHelper;

class CategoryShippingCostController extends Controller
{
    public function store(Request $request)
    {
        if(isset($request->ids))
        {
            foreach($request->ids as $key=>$id){
                
                $category_shipping_cost = CategoryShippingCost::find($id);
                $category_shipping_cost->cost = BackEndHelper::currency_to_usd($request->cost[$key]);
                $category_shipping_cost->multiply_qty = isset($request->multiplyQTY)==true? in_array($id,$request->multiplyQTY) ==true?1:0:0;
                $category_shipping_cost->save();

            }
        }

        Toastr::success('Category cost successfully updated.');
        return back();
    }
}
