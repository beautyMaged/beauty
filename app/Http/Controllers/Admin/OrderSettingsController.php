<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class OrderSettingsController extends Controller
{
    public function order_settings()
    {
        return view('admin-views.business-settings.order-settings.index');
    }

    public function update_order_settings(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'billing_input_by_customer'], [
            'value' => $request['billing_input_by_customer']
        ]);

        Toastr::success('Updated successfully');
        return back();
    }
}
