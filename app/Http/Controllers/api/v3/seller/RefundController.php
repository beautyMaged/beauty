<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\Model\Order;
use App\Model\AdminWallet;
use App\Model\SellerWallet;
use App\Model\RefundTransaction;
use App\CPU\Helpers;
use App\Model\DeliveryMan;
use App\Model\OrderDetail;
use Illuminate\Support\Facades\Validator;
Use App\Model\RefundStatus;
use App\User;
use App\CPU\CustomerManager;

class RefundController extends Controller
{
    public function list(Request $request)
    {
        $seller = $request->seller;

        $refund_list = RefundRequest::with('customer','product','order_details')
            ->with(['order'=>function($query){
                $query->select('id','payment_method');
            }])
            ->whereHas('order', function ($query) use($seller) {
                $query->where('seller_is', 'seller')->where('seller_id',$seller['id']);
            })
            ->when($request['search'], function ($query) use($request){
                $key = explode(' ', $request['search']);
                foreach ($key as $value) {
                    $query->where('order_id', 'like', "%{$value}%");
                }
            })->latest()->get();

        $refund_list = $refund_list->map(function($data){
            $data['images'] = json_decode($data['images']);
            return $data;
        });
        return response()->json($refund_list);
    }
    public function refund_details(Request $request)
    {
        $seller = $request->seller;
        $order_details = OrderDetail::find($request->order_details_id);
        $refund_request = RefundRequest::with('refund_status')->where('order_details_id',$request->order_details_id)->get();

            $order = Order::find($order_details->order_id);

            $total_product_price = 0;
            $refund_amount = 0;
            $data = [];
            foreach ($order->details as $key => $or_d) {
                $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
            }

            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

            $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

            $refund_amount = $subtotal - $coupon_discount;

            $data['data'] = $seller;
            $data['product_price'] = $order_details->price;
            $data['quntity'] = $order_details->qty;
            $data['product_total_discount'] = $order_details->discount;
            $data['product_total_tax'] = $order_details->tax;
            $data['subtotal'] = $subtotal;
            $data['coupon_discount'] = $coupon_discount;
            $data['refund_amount'] = $refund_amount;
            $data['refund_request']=$refund_request->map(function($data){
                $data['images']=json_decode($data['images']);
                return $data;
            });
            $data['deliveryman_details']= DeliveryMan::find($order->delivery_man_id);

            return response()->json($data, 200);


    }

    public function refund_status_update(Request $request)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'refund_status' => 'required',
            'refund_request_id' => 'required',
            'note'=>'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $refund = RefundRequest::whereHas('order', function ($query) use($seller) {
                                    $query->where('seller_is', 'seller')->where('seller_id',$seller['id']);
                                })->find($request->refund_request_id);

        $user = User::find($refund->customer_id);

        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($loyalty_point_status == 1)
        {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);

            if($user->loyalty_point < $loyalty_point && $request->refund_status == 'approved')
            {
                return response()->json(['message'=>'Customer has not sufficient loyalty point to take refund for this order'],302);
            }
        }

        if($refund->change_by =='admin'){

            return response()->json(['message'=>'refunded status can not be changed!! Admin already changed the status : '.$refund->status.'!!'],302);
        }
        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);
            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'seller';
            $refund_status->change_by_id = $seller['id'];
            $refund_status->status = $request->refund_status;

            if($request->refund_status == 'pending')
            {
                $order_details->refund_request = 1;
            }
            elseif($request->refund_status == 'approved')
            {
                $order_details->refund_request = 2;
                $refund->approved_note = $request->note;

                $refund_status->message = $request->note;
            }
            elseif($request->refund_status == 'rejected')
            {
                $order_details->refund_request = 3;
                $refund->rejected_note = $request->note;

                $refund_status->message = $request->note;
            }

            $order_details->save();

            $refund->status = $request->refund_status;
            $refund->change_by = 'seller';
            $refund->save();
            $refund_status->save();

            return response()->json(['message'=>'refund status updated successfully!'], 200);

        }else{
            return response()->json(['message'=>'refunded status can not be changed!!'],302);
        }

    }
}
