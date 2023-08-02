<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Seller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function App\CPU\translate;

class CouponController extends Controller
{
    public function add_new(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        $cou = Coupon::where(['added_by' => 'admin'])
            ->when(isset($request['search']) && !empty($request['search']), function ($query) use ($search) {
                    $key = explode(' ', $search);
                    foreach ($key as $value) {
                        $query->where('title', 'like', "%{$value}%")
                            ->orWhere('code', 'like', "%{$value}%")
                            ->orWhere('discount_type', 'like', "%{$value}%");
                    }
            })
            ->withCount('order')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        $sellers = Seller::with('shop')->approved()->get();
        $customers = User::where('id', '<>', '0')->get();

        return view('admin-views.coupon.add-new', compact('cou', 'customers', 'sellers', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_type' => 'required',
            'coupon_bearer' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'seller_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase,first_order',
            'discount' => 'required_if:coupon_type,discount_on_purchase,first_order',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons',
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ], [
            'coupon_bearer.required_if' => translate('coupon_bearer_is_required!'),
            'seller_id.required_if' => translate('select_seller_is_required!'),
            'customer_id.required_if' => translate('select_customer_is_required!'),
            'limit.required_if' => translate('limit_for_same_user_is_required!'),
            'discount_type.required_if' => translate('discount_type_is_required!'),
            'discount.required_if' => translate('discount_amount_is_required!'),
            'min_purchase.required' => translate('minimum_purchase_is_required!'),

        ]);

        if($request->discount_type == 'amount' && $request->discount > $request->min_purchase){
            Toastr::error('The minimum purchase amount must be greater than discount amount');
            return redirect()->back();
        }

        $coupon = new Coupon();
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->status = 1;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase' || $request->coupon_type == 'free_delivery') {
            $coupon->coupon_bearer = $request->coupon_bearer;
            $coupon->seller_id = $request->seller_id == 'inhouse' ? NULL : $request->seller_id;
            $coupon->customer_id = $request->customer_id;
            $coupon->limit = $request->limit;
        }

        if ($request->coupon_type == 'discount_on_purchase' || $request->coupon_type == 'first_order') {
            $coupon->discount_type = $request->discount_type;
            $coupon->customer_id = 0;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        }

        $coupon->save();

        Toastr::success('Coupon added successfully!');
        return back();
    }

    public function edit($id)
    {
        $sellers = Seller::with('shop')->approved()->get();
        $customers = User::where('id', '<>', '0')->get();
        $c = Coupon::where(['added_by' => 'admin'])->find($id);
        if(!$c){
            Toastr::error('Invalid Coupon!');
            return redirect()->route('admin.coupon.add-new');
        }
        return view('admin-views.coupon.edit', compact('c', 'customers', 'sellers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'coupon_type' => 'required',
            'coupon_bearer' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'seller_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase,first_order',
            'discount' => 'required_if:coupon_type,discount_on_purchase,first_order',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons,code,' . $id,
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ], [
            'coupon_bearer.required_if' => translate('coupon_bearer_is_required!'),
            'seller_id.required_if' => translate('select_seller_is_required!'),
            'customer_id.required_if' => translate('select_customer_is_required!'),
            'limit.required_if' => translate('limit_for_same_user_is_required!'),
            'discount_type.required_if' => translate('discount_type_is_required!'),
            'discount.required_if' => translate('discount_amount_is_required!'),
            'min_purchase.required' => translate('minimum_purchase_is_required!'),

        ]);

        if($request->discount_type == 'amount' && $request->discount > $request->min_purchase){
            Toastr::error('The minimum purchase amount must be greater than discount amount');
            return redirect()->back();
        }

        $coupon = Coupon::where(['added_by' => 'admin'])->find($id);
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase') {
            $coupon->coupon_bearer = $request->coupon_bearer;
            $coupon->seller_id = $request->seller_id == 'inhouse' ? NULL : $request->seller_id;
            $coupon->customer_id = $request->customer_id;
            $coupon->limit = $request->limit;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        } elseif ($request->coupon_type == 'free_delivery') {
            $coupon->coupon_bearer = $request->coupon_bearer;
            $coupon->seller_id = $request->seller_id == 'inhouse' ? NULL : $request->seller_id;
            $coupon->customer_id = $request->customer_id;
            $coupon->limit = $request->limit;

            $coupon->discount_type = 'percentage';
            $coupon->discount = 0;
            $coupon->max_discount = 0;
        } elseif ($request->coupon_type == 'first_order') {
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);

            $coupon->coupon_bearer = 'inhouse';
            $coupon->seller_id = NULL;
            $coupon->customer_id = 0;
            $coupon->limit = 0;
        }
        $coupon->save();

        Toastr::success('Coupon updated successfully!');
        return back();
    }

    public function status(Request $request)
    {
        $coupon = Coupon::where(['added_by' => 'admin'])->find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success('Coupon status updated!');
        return back();
    }

    public function quick_view_details(Request $request)
    {
        $coupon = Coupon::where(['added_by' => 'admin'])->find($request->id);

        return response()->json([
            'view' => view('admin-views.coupon.details-quick-view', compact('coupon'))->render(),
        ]);
    }

    public function delete($id)
    {
        $coupon = Coupon::where(['added_by' => 'admin'])->find($id);
        $coupon->delete();
        Toastr::success('Coupon deleted successfully!');
        return back();
    }

    public function ajax_get_seller(Request $request)
    {
        $sellers = Seller::with('shop')->approved()->get();
        $output='<option value="" disabled selected>Select Seller</option>';
        $output.='<option value="0">All Seller</option>';
        if($request->coupon_bearer == 'inhouse') {
            $output .= '<option value="inhouse">Inhouse</option>';
        }
        foreach($sellers as $seller)
        {
            $output .= '<option value="'.$seller->id.'">'.$seller->shop->name.'</option>';
        }
        echo $output;
    }
}
