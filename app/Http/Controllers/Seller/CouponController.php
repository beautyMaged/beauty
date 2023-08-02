<?php

namespace App\Http\Controllers\Seller;

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
        $coupons = Coupon::whereIn('seller_id', [auth('seller')->user()->id, '0'])
                ->when(isset($request['search']) && !empty($request['search']), function($query) use($request){
                    $key = explode(' ', $request['search']);
                    foreach ($key as $value) {
                        $query->where('title', 'like', "%{$value}%")
                            ->orWhere('code', 'like', "%{$value}%")
                            ->orWhere('discount_type', 'like', "%{$value}%");
                    }
                })
                ->withCount('order')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        $customers = User::where('id', '<>', '0')->get();

        return view('seller-views.coupon.add-new', compact('coupons', 'customers',  'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_type' => 'required',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase',
            'discount' => 'required_if:coupon_type,discount_on_purchase',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons',
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ], [
            'coupon_bearer.required_if' => translate('coupon_bearer_is_required!'),
            'seller_id.required_if' => translate('seller_is_required!'),
            'customer_id.required_if' => translate('customer_is_required!'),
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
        $coupon->added_by = 'seller';
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->status = 1;
        $coupon->coupon_bearer = 'seller';
        $coupon->seller_id = auth('seller')->user()->id;
        $coupon->customer_id = $request->customer_id;
        $coupon->limit = $request->limit;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase') {
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        }

        $coupon->save();

        Toastr::success(\App\CPU\translate('coupon_added_successfully!'));
        return back();
    }

    public function edit($id)
    {
        $coupon = Coupon::where('coupon_bearer','seller')->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($id);

        if(!$coupon){
            Toastr::error('Invalid Coupon!');
            return redirect()->route('seller.coupon.add-new');
        }
        $customers = User::where('id', '<>', '0')->get();
        return view('seller-views.coupon.edit', compact('coupon', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'coupon_type' => 'required',
            'customer_id' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'limit' => 'required_if:coupon_type,discount_on_purchase,free_delivery',
            'discount_type' => 'required_if:coupon_type,discount_on_purchase',
            'discount' => 'required_if:coupon_type,discount_on_purchase',
            'min_purchase' => 'required',
            'code' => 'required|unique:coupons,code,' . $id,
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ], [
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

        $coupon = Coupon::where('coupon_bearer','seller')->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($id);
        if(!$coupon){
            Toastr::warning(\App\CPU\translate('coupon_not_found'));
        }
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->limit = $request->limit;
        $coupon->min_purchase = Convert::usd($request->min_purchase);

        if ($request->coupon_type == 'discount_on_purchase') {
            $coupon->customer_id = $request->customer_id;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount = $request->discount_type == 'amount' ? Convert::usd($request->discount) : $request['discount'];
            $coupon->max_discount = Convert::usd($request->max_discount != null ? $request->max_discount : $request->discount);
        } elseif ($request->coupon_type == 'free_delivery') {
            $coupon->customer_id = $request->customer_id;
            $coupon->discount_type = 'percentage';
            $coupon->discount = 0;
            $coupon->max_discount = 0;
        }
        $coupon->save();

        Toastr::success(\App\CPU\translate('coupon_updated_successfully'));
        return back();
    }

    public function status_update(Request $request)
    {
        $coupon = Coupon::where('coupon_bearer','seller')->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($request->id);
        if(!$coupon){
            Toastr::warning(\App\CPU\translate('coupon_not_found'));
        }
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success(\App\CPU\translate('coupon_status_updated'));
        return back();
    }

    public function quick_view_details(Request $request)
    {
        $coupon = Coupon::whereIn('seller_id',[auth('seller')->user()->id, '0'])->find($request->id);

        return response()->json([
            'view' => view('seller-views.coupon.details-quick-view', compact('coupon'))->render(),
        ]);
    }

    public function delete($id)
    {
        $coupon = Coupon::where(['added_by'=>'seller', 'coupon_bearer'=>'seller'])
        ->whereIn('seller_id', [auth('seller')->user()->id, '0'])->find($id);
        if(!$coupon){
            Toastr::warning(\App\CPU\translate('coupon_not_found'));
        }
        $coupon->delete();
        Toastr::success(\App\CPU\translate('coupon_deleted_successfully'));
        return back();
    }
}
