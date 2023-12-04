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
        /** @var Seller $seller */
        $seller = auth()->user();
        $query_param = [];
        $search = $request['search'];
        $coupons = $seller->coupons()
            ->when(isset($request['search']) && !empty($request['search']), function ($query) use ($request) {
                $key = explode(' ', $request['search']);
                foreach ($key as $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('discount_type', 'like', "%{$value}%");
                }
            })
            ->withCount('order')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('seller-views.coupon.list', compact('coupons',  'search'));
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
        $coupon = Coupon::findOrFail($id);
        $this->authorize('update', $coupon);
        $coupon = $service->parse($coupon);
        $view = 'edit';
        return view('seller-views.coupon.form', compact('view', 'seller', 'coupon'));
    }

    public function update(UpdateRequest $request, $id, CouponService $service)
    {
        $service->request = $request;
        $coupon = Coupon::find($id);
        $this->authorize('update', $coupon);
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
        $this->authorize('update', $coupon);
        $coupon->status = $request->status;
        return $coupon->save() ? response()->json($request->status) : '"error"';
    }

    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->authorize('delete', $coupon);
        return response()->json($coupon->delete());
    }
}
