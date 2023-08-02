<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Model\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function order_data()
    {
        $sellerId = auth('seller')->id();

        $new_order = DB::table('orders')->where(['seller_is' => 'seller'])
                                        ->where(['seller_id' => $sellerId])
                                        ->where(['checked' => 0])->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $new_order]
        ]);
    }

}
