<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\EmergencyContact;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;

class EmergencyContactController extends Controller
{
    public function emergency_contact()
    {
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        if($shippingMethod=='inhouse_shipping') {
            Toastr::warning(translate('access_denied!!'));
            return redirect()->route('seller.auth.login');
        }

        $contacts = EmergencyContact::where('user_id', auth('seller')->user()->id)->latest()->paginate(Helpers::pagination_limit());
        return view('seller-views.delivery-man.emergency-contact', compact('contacts'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required'
        ]);
        EmergencyContact::create([
            'user_id' => auth('seller')->user()->id,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'status' => 1
        ]);
        Toastr::success(translate('emergency_contact_added_successfully!'));
        return back();
    }

    public function ajax_status_change(Request $request)
    {
        $status = EmergencyContact::where(['user_id' => auth('seller')->user()->id, 'id' => $request->id])
            ->update(['status' => $request->status]);
        if ($status == true) {
            return [ 'message' => translate('contact_status_changed_successfully!')];
        } else {
            return [ 'message' => translate('contact_status_change_failed!'),
                    'fail' => 1
                ];
        }
    }

    public function destroy(Request $request)
    {
        $delete = EmergencyContact::where(['user_id' => auth('seller')->user()->id, 'id' => $request->id])
            ->delete();
        if ($delete == true) {
            Toastr::success(translate('emergency_contact_deleted_successfully!'));
        } else {
            Toastr::error(translate('emergency_contact_delete_failed!'));
        }
        return back();
    }
}
