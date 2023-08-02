<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Product;
use App\Model\Seller;
use App\Model\WithdrawRequest;
use App\Model\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Review;
use App\Model\OrderTransaction;
use App\Model\DeliveryMan;
use Rap2hpoutre\FastExcel\FastExcel;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        $current_date = date('Y-m-d');

        $sellers = Seller::with(['orders', 'product'])
            ->when($search, function($query) use($search){
                $key = explode(' ', $search);
                foreach ($key as $value) {
                    $query->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        return view('admin-views.seller.index', compact('sellers', 'search', 'current_date'));
    }

    public function view(Request $request, $id, $tab = null)
    {
        $seller = Seller::find($id);
        if(!isset($seller))
        {
            Toastr::error('Seller not found,It may be deleted!');
            return back();
        }
        $current_date = date('Y-m-d');

        if ($tab == 'order') {
            $id = $seller->id;
            $orders = Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$id])->where('order_type','default_type')->latest()->paginate(Helpers::pagination_limit());

            return view('admin-views.seller.view.order', compact('seller', 'orders'));
        } else if ($tab == 'product') {
            $products = Product::where('added_by', 'seller')->where('user_id', $seller->id)->latest()->paginate(Helpers::pagination_limit());
            return view('admin-views.seller.view.product', compact('seller', 'products'));
        } else if ($tab == 'setting') {
            $commission = $request['commission'];
            if ($request->has('commission')) {
                request()->validate([
                    'commission' => 'required | numeric | min:1',
                ]);

                if ($request['commission_status'] == 1 && $request['commission'] == null) {
                    Toastr::error('You did not set commission percentage field.');
                    //return back();
                } else {
                    $seller = Seller::find($id);
                    $seller->sales_commission_percentage = $request['commission_status'] == 1 ? $request['commission'] : null;
                    $seller->save();

                    Toastr::success('Commission percentage for this seller has been updated.');
                }
            }
            $commission = 0;
            if ($request->has('gst')) {
                if ($request['gst_status'] == 1 && $request['gst'] == null) {
                    Toastr::error('You did not set GST number field.');
                    //return back();
                } else {
                    $seller = Seller::find($id);
                    $seller->gst = $request['gst_status'] == 1 ? $request['gst'] : null;
                    $seller->save();

                    Toastr::success('GST number for this seller has been updated.');
                }
            }
            if ($request->has('seller_pos')) {

                    $seller = Seller::find($id);
                    $seller->pos_status = $request->seller_pos;
                    $seller->save();

                    Toastr::success('Seller pos permission updated.');

            }

            //return back();
            return view('admin-views.seller.view.setting', compact('seller'));
        } else if ($tab == 'transaction') {
            $transactions = OrderTransaction::where('seller_is','seller')->where('seller_id',$seller->id);

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search'))
            {
                $key = explode(' ', $request['search']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('order_id', 'like', "%{$value}%")
                            ->orWhere('transaction_id', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }else{
                $transactions = $transactions;
            }
            $status = $request['status'];
            if ($request->has('status') && $status!='all')
            {
                $key = explode(' ', $request['status']);
                $transactions = $transactions->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('status', 'like', "%{$value}%");
                    }
                });
                $query_param = ['status' => $request['status']];
            }
               $transactions = $transactions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.seller.view.transaction', compact('seller', 'transactions','search','status'));

        } else if ($tab == 'review') {
            $sellerId = $seller->id;

            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $product_id = Product::where('added_by','seller')->where('user_id',$sellerId)->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                })->pluck('id')->toArray();

                $reviews = Review::with(['product'])
                    ->whereIn('product_id',$product_id);

                $query_param = ['search' => $request['search']];
            } else {
                $reviews = Review::with(['product'])->whereHas('product', function ($query) use ($sellerId) {
                    $query->where('user_id', $sellerId)->where('added_by', 'seller');
                });
            }
            //dd($reviews->count());
            $reviews = $reviews->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

            return view('admin-views.seller.view.review', compact('seller', 'reviews', 'search'));
        }
        return view('admin-views.seller.view', compact('seller','current_date'));
    }

    public function updateStatus(Request $request)
    {
        $order = Seller::findOrFail($request->id);
        $order->status = $request->status;
        if ($request->status == "approved") {
            Toastr::success('Seller has been approved successfully');
        } else if ($request->status == "rejected") {
            Toastr::info('Seller has been rejected successfully');
        } else if ($request->status == "suspended") {
            $order->auth_token = Str::random(80);
            Toastr::info('Seller has been suspended successfully');
        }
        $order->save();
        return back();
    }

    public function order_list($seller_id)
    {
        $orders = Order::where(['seller_id'=> $seller_id, 'seller_is'=> 'seller'])
                ->latest()
                ->paginate(Helpers::pagination_limit());

        $seller = Seller::findOrFail($seller_id);
        return view('admin-views.seller.order-list', compact('orders', 'seller'));
    }

    public function product_list($seller_id)
    {
        $product = Product::where(['user_id' => $seller_id, 'added_by' => 'seller'])->latest()->paginate(Helpers::pagination_limit());
        $seller = Seller::findOrFail($seller_id);
        return view('admin-views.seller.porduct-list', compact('product', 'seller'));
    }

    public function order_details($order_id, $seller_id)
    {
        $order = Order::with('shipping')->where(['id' => $order_id])->first();
        $shipping_method = Helpers::get_business_settings('shipping_method');
        $delivery_men = DeliveryMan::where('is_active', 1)->when($order->seller_is == 'admin', function ($query) {
            $query->where(['seller_id' => 0]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => $order['seller_id']]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'inhouse_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => 0]);
        })->get();
        return view('admin-views.seller.order-details', compact('order', 'seller_id','delivery_men'));
    }

    public function withdraw()
    {
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['seller'])
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(Helpers::pagination_limit());

        return view('admin-views.seller.withdraw', compact('withdraw_req'));
    }

    public function withdraw_list_export_excel(Request $request){
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_requests = WithdrawRequest::with(['seller', 'withdraw_method'])
            ->whereNull('delivery_man_id')
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')->get();

        $withdraw_requests->map(function ($query) {
            //company info
            $query->shop_name = isset($query->seller) ? $query->seller->shop->name : '';
            $query->shop_phone = isset($query->seller) ? $query->seller->shop->contact : '';
            $query->shop_address = isset($query->seller) ? $query->seller->shop->address : '';
            $query->shop_email = isset($query->seller) ? $query->seller->email : '';

            $query->withdrawal_amount = BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($query->amount));
            $query->status = $query->approved == 0 ? 'Pending' : ($query->approved == 1 ? 'Approved':'Denied');
            $query->note = $query->transaction_note;

            //method info
            $query->withdraw_method_name = isset($query->withdraw_method) ? $query->withdraw_method->method_name : '';
            if(!empty($query->withdrawal_method_fields)){
                foreach (json_decode($query->withdrawal_method_fields) as $key=>$field) {
                    $query[$key] = $field;
                }
            }
        });

        foreach ($withdraw_requests as $key=>$item) {
            unset($item['id']);
            unset($item['seller_id']);
            unset($item['admin_id']);
            unset($item['delivery_man_id']);
            unset($item['request_updated_by']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['amount']);
            unset($item['approved']);
            unset($item['withdrawal_method_fields']);
            unset($item['withdrawal_method_id']);
            unset($item['withdraw_id']);
            unset($item['transaction_note']);
            unset($item['provider']);
            unset($item['withdraw_method']);
        }
        return (new FastExcel($withdraw_requests))->download(time() . '-file.xlsx');
    }

    public function withdraw_view($withdraw_id, $seller_id)
    {
        $withdraw_request = WithdrawRequest::with(['seller'])->where(['id' => $withdraw_id])->first();
        $withdrawal_method = json_decode($withdraw_request->withdrawal_method_fields);

        return view('admin-views.seller.withdraw-view', compact('withdraw_request', 'withdrawal_method'));
    }

    public function withdrawStatus(Request $request, $id)
    {
        $withdraw = WithdrawRequest::find($id);
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request['note'];
        if ($request->approved == 1) {
            SellerWallet::where('seller_id', $withdraw->seller_id)->increment('withdrawn', $withdraw['amount']);
            SellerWallet::where('seller_id', $withdraw->seller_id)->decrement('pending_withdraw', $withdraw['amount']);
            $withdraw->save();
            Toastr::success('Seller Payment has been approved successfully');
            return redirect()->route('admin.sellers.withdraw_list');
        }

        SellerWallet::where('seller_id', $withdraw->seller_id)->increment('total_earning', $withdraw['amount']);
        SellerWallet::where('seller_id', $withdraw->seller_id)->decrement('pending_withdraw', $withdraw['amount']);
        $withdraw->save();
        Toastr::info('Seller Payment request has been Denied successfully');
        return redirect()->route('admin.sellers.withdraw_list');

    }

    public function sales_commission_update(Request $request, $id)
    {
        if ($request['status'] == 1 && $request['commission'] == null) {
            Toastr::error('You did not set commission percentage field.');
            return back();
        }

        $seller = Seller::find($id);
        $seller->sales_commission_percentage = $request['status'] == 1 ? $request['commission'] : null;
        $seller->save();

        Toastr::success('Commission percentage for this seller has been updated.');
        return back();
    }
    public function add_seller()
    {
        return view('admin-views.seller.add-new-seller');
    }
}
