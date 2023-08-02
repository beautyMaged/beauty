<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use App\Model\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function vacation_add(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->vacation_status = $request->vacation_status;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        return response()->json(['status' => true], 200);
    }

    public function temporary_close(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->temporary_close = $request->status;
        $shop->save();

        return response()->json(['status' => true], 200);
    }
}
