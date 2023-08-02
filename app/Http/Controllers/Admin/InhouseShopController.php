<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InhouseShopController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $temporary_close = Helpers::get_business_settings('temporary_close');
        $vacation = Helpers::get_business_settings('vacation_add');

        return view('admin-views.product-settings.inhouse-shop', compact('temporary_close', 'vacation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        //comapy shop banner
        $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first();
        if ($request->has('shop_banner')) {
            $imgBanner = ImageManager::update('shop/', $imgBanner, 'png', $request->file('shop_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
                'value' => $imgBanner
            ]);
        }

        Toastr::success('Updated successfully');
        return back();
    }

    public function temporary_close(Request $request)
    {
        $status = $request->status == 'checked' ? 1 : 0;

        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_close'], [
            'value' => json_encode([
                'status' => $status,
            ]),
        ]);
        return response()->json(['status' => true], 200);
    }

    public function vacation_add(Request $request){
        DB::table('business_settings')->updateOrInsert(['type' => 'vacation_add'], [
            'value' => json_encode([
                'status' => $request->status == 'on' ? 1 : 0,
                'vacation_start_date' => $request->vacation_start_date,
                'vacation_end_date' => $request->vacation_end_date,
                'vacation_note' => $request->vacation_note
            ]),
        ]);

        Toastr::success('Vacation mode updated successfully!');
        return redirect()->back();
    }

}
