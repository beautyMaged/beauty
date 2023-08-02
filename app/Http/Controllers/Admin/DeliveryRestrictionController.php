<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\DeliveryCountryCode;
use App\Model\DeliveryZipCode;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;

class DeliveryRestrictionController extends Controller
{

    public function index(Request $request)
    {
        $stored_countries = DeliveryCountryCode::latest()
                            ->paginate(Helpers::pagination_limit(), ['*'], 'country_page');
        $country_restriction_status = BusinessSetting::where('type', 'delivery_country_restriction')->first('value');
        $zip_code_area_restriction_status = BusinessSetting::where('type', 'delivery_zip_code_area_restriction')->first('value');

        $countries = COUNTRIES;
        $stored_country_code = $stored_countries->pluck('country_code')->toArray();

        $stored_zip = DeliveryZipCode::latest()
                    ->paginate(Helpers::pagination_limit(), ['*'], 'zipcode_page');

        return view('admin-views.business-settings.delivery-restriction', compact('countries','stored_countries', 'stored_country_code', 'stored_zip', 'country_restriction_status', 'zip_code_area_restriction_status'));
    }

    public function addDeliveryCountry(Request $request)
    {
        $request->validate([
            'country_code' => 'required'
        ]);

        $data = array();
        foreach ($request->input('country_code') as $code)
        {
            $data[] = array(
                'country_code' => $code
            );
        }

        DeliveryCountryCode::insert($data);

        Toastr::success('Delivery country added successfully!');
        return back();
    }

    public function deliveryCountryDelete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $country = DeliveryCountryCode::find($request->id);

        if($country && $country->delete()){
            Toastr::success('Delivery country deleted successfully!');
        }else{
            Toastr::error('Fail to delete delivery country!');
        }

        return back();
    }

    public function addZipCode(Request $request)
    {
        $request->validate([
            'zipcode' => 'required'
        ]);

        $zip_codes = explode(',' ,$request->zipcode);
        $existing_zip_codes = DeliveryZipCode::pluck('zipcode')->toArray();
        $zip_codes = array_diff($zip_codes, $existing_zip_codes);

        if (!$zip_codes) {
            Toastr::warning(translate('Delivery_zip_code_already_exists!'));
            return back();
        }

        $data = array();
        foreach ($zip_codes as $code)
        {
            $data[] = array(
                'zipcode' => $code
            );
        }

        DeliveryZipCode::insert($data);

        Toastr::success('Delivery zip code added successfully!');
        return back();
    }

    public function zipCodeDelete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $zip = DeliveryZipCode::find($request->id);

        if($zip && $zip->delete()){
            Toastr::success('Delivery zip code deleted successfully!');
        }else{
            Toastr::error('Fail to delete delivery zip code!');
        }
        return back();
    }
}
