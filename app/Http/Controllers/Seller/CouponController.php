<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Services\CouponService;
use Carbon\Carbon;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Model\Coupon;
use App\Model\Seller;
use App\CPU\BackEndHelper;
use Illuminate\Http\Request;
use function App\CPU\translate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Coupon\StoreRequest;
use App\Http\Requests\Coupon\UpdateRequest;

class CouponController extends Controller
{
    public function create()
    {
        $seller = auth()->user();
        $view = 'create';
        $coupon = null;
        return view('seller-views.coupon.form', compact('view', 'seller', 'coupon'));
    }
    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        $coupons = Coupon::whereIn('seller_id', [auth()->user()->id, '0'])
            ->when(isset($request['search']) && !empty($request['search']), function ($query) use ($request) {
                $key = explode(' ', $request['search']);
                foreach ($key as $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('discount_type', 'like', "%{$value}%");
                }
            })
            ->withCount('order')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        $customers = User::where('id', '<>', '0')->get();

        return view('seller-views.coupon.list', compact('coupons', 'customers',  'search'));
    }

    public function store(StoreRequest $request, CouponService $service)
    {
        $service->request = $request;
        $coupon = Coupon::create($request->all());
        $coupon->categories()->sync($service->merge('categories'));
        $coupon->products()->sync($service->merge('products'));
        $coupon->brands()->sync($service->merge('brands'));
        Toastr::success(\App\CPU\translate('coupon_added_successfully!'));
        return redirect()->route('seller.coupon.list');
    }

    public function edit($id, CouponService $service)
    {
        $seller = auth()->user();
        $coupon = $service->parse(Coupon::findOrFail($id));
        $view = 'edit';
        return view('seller-views.coupon.form', compact('view', 'seller', 'coupon'));
    }

    public function update(UpdateRequest $request, $id, CouponService $service)
    {
        $service->request = $request;
        $coupon = Coupon::find($id);
        $coupon->update($request->all());
        $coupon->categories()->sync($service->merge('categories'));
        $coupon->products()->sync($service->merge('products'));
        $coupon->brands()->sync($service->merge('brands'));
        Toastr::success(\App\CPU\translate('coupon_updated_successfully'));
        return redirect()->route('seller.coupon.list');
    }

    public function status(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        return $coupon->save() ? response()->json($request->status) : '"error"';
    }

    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon->delete());
    }
}
