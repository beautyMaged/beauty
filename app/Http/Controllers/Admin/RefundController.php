<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\Model\Order;
use App\Model\AdminWallet;
use App\Model\SellerWallet;
use App\Model\RefundTransaction;
use App\CPU\Helpers;
use App\Model\OrderDetail;
Use App\Model\RefundStatus;
use App\CPU\CustomerManager;
use App\User;
use App\CPU\Convert;

class RefundController extends Controller
{
    public function list(Request $request, $status)
    {
        $search = $request->search;
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $refund_list = RefundRequest::whereHas('order', function ($query) {
                $query->where('seller_is', 'admin');
            });

        }else{
            $refund_list = new RefundRequest;
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $refund_list = $refund_list->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%")
                        ->orWhere('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $refund_list = $refund_list->where('status',$status)->latest()->paginate(Helpers::pagination_limit());

        return view('admin-views.refund.list',compact('refund_list','search', 'status'));
    }
    public function details($id)
    {
        $refund = RefundRequest::find($id);

        return view('admin-views.refund.details',compact('refund'));
    }
    public function refund_status_update(Request $request)
    {
        $refund = RefundRequest::find($request->id);
        $user = User::find($refund->customer_id);

        if(!isset($user))
        {
            Toastr::warning(translate('This account has been deleted, you can not modify the status!!'));
            return back();
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);

        if( $loyalty_point_status == 1)
        {

            if($user->loyalty_point < $loyalty_point && ($request->refund_status == 'refunded' || $request->refund_status == 'approved'))
            {
                Toastr::warning(translate('Customer has not sufficient loyalty point to take refund for this order!!'));
                return back();
            }
        }

        if($request->refund_status == 'refunded' && $refund->status != 'refunded')
        {
            $order = Order::find($refund->order_id);
            if($order->seller_is == 'admin')
            {
                $admin_wallet = AdminWallet::where('admin_id',$order->seller_id)->first();
                $admin_wallet->inhouse_earning = $admin_wallet->inhouse_earning - $refund->amount;
                $admin_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();

            }else{
                $seller_wallet = SellerWallet::where('seller_id',$order->seller_id)->first();
                $seller_wallet->total_earning = $seller_wallet->total_earning - $refund->amount;
                $seller_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();
            }


        }
        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);

            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'admin';
            $refund_status->change_by_id = auth('admin')->id();
            $refund_status->status = $request->refund_status;

            if($request->refund_status == 'pending')
            {
                $order_details->refund_request = 1;
            }
            elseif($request->refund_status == 'approved')
            {
                $order_details->refund_request = 2;
                $refund->approved_note = $request->approved_note;

                $refund_status->message = $request->approved_note;

            }
            elseif($request->refund_status == 'rejected')
            {
                $order_details->refund_request = 3;
                $refund->rejected_note = $request->rejected_note;

                $refund_status->message = $request->rejected_note;
            }
            elseif($request->refund_status == 'refunded')
            {
                $order_details->refund_request = 4;
                $refund->payment_info = $request->payment_info;
                $refund_status->message = $request->payment_info;

                if($loyalty_point > 0 && $loyalty_point_status == 1)
                {
                    CustomerManager::create_loyalty_point_transaction($refund->customer_id, $refund->order_id, $loyalty_point, 'refund_order');
                }

                $wallet_add_refund = Helpers::get_business_settings('wallet_add_refund');

                if($wallet_add_refund==1 && $request->payment_method == 'customer_wallet')
                {
                    CustomerManager::create_wallet_transaction($refund->customer_id, Convert::default($refund->amount), 'order_refund','order_refund');
                }
            }
            $order_details->save();

            $refund->status = $request->refund_status;
            $refund->change_by = 'admin';
            $refund->save();
            $refund_status->save();


            Toastr::success(translate('refund_status_updated!!'));
            return back();

        }else{
            Toastr::warning(translate('refunded status can not be changed!!'));
            return back();
        }



    }
    public function index()
    {
        return view('admin-views.refund.index');
    }
    public function update(Request $request)
    {
        $request->validate([
            'refund_day_limit' => 'required',
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'refund_day_limit'], [
            'value' => $request['refund_day_limit']
        ]);
        Toastr::success(translate('refund_day_limit_updated!!'));
        return back();
    }
    public function inhouse_order_filter()
    {
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            session()->put('show_inhouse_orders', 0);
        } else {
            session()->put('show_inhouse_orders', 1);
        }
        return back();
    }
}
