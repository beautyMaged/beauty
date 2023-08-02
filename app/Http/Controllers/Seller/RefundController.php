<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\User;
use App\CPU\CustomerManager;

class RefundController extends Controller
{
    public function list(Request $request, $status)
    {
        $refund_list = RefundRequest::whereHas('order', function ($query) {
            $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
        });
        $search = $request->search;
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
        
        return view('seller-views.refund.list',compact('refund_list','search'));
    }
    public function details($id)
    {
        $refund = RefundRequest::whereHas('order', function ($query) {
            $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
        })->find($id);

        return view('seller-views.refund.details',compact('refund'));
    }
    public function refund_status_update(Request $request)
    {
        $refund = RefundRequest::whereHas('order', function ($query) {
            $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                })->find($request->id);

        $user = User::find($refund->customer_id);
        
        if(!isset($user))
        {
            Toastr::warning(translate('This account has been deleted, you can not modify the status!!'));
            return back();
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        
        if($loyalty_point_status == 1)
        {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);
    
            if($user->loyalty_point < $loyalty_point && $request->refund_status == 'approved')
            {
                Toastr::warning(translate('Customer has not sufficient loyalty point to take refund for this order!!'));
                return back();
            }
        }

        if($refund->change_by =='admin'){
            Toastr::warning(translate('refunded status can not be changed!! Admin already changed the status : '.$refund->status.'!!'));
            return back();
        }



        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);
            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'seller';
            $refund_status->change_by_id = auth('seller')->id();
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
            }
            $order_details->save();
            
            $refund->status = $request->refund_status;
            $refund->change_by = 'seller';
            $refund->save();
            $refund_status->save();
            Toastr::success(translate('refund_status_updated!!'));
            return back();

        }else{
            Toastr::warning(translate('refunded status can not be changed!!'));
            return back();
        }   
        
    }
}
