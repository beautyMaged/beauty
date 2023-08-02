<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use App\CPU\CustomerManager;
use App\CPU\Convert;


class OrderController extends Controller
{
    use CommonTrait;
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $order_ids = OrderDetail::where(['seller_id' => $seller['id']])->pluck('order_id')->toArray();
        $orders = Order::with(['customer','shipping'])->where(['seller_is'=>'seller'])->whereIn('id', $order_ids)->get();
        $orders->map(function ($data) {
            $data['billing_address_data'] = json_decode($data['billing_address_data']);
            return $data;
        });

        return response()->json($orders, 200);
    }

    public function details(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $details = OrderDetail::where(['seller_id' => $seller['id'], 'order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    public function assign_delivery_man(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_man_id' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $order = Order::where(['seller_id' => $seller['id'], 'id' => $request['order_id']])->first();

        $order->delivery_man_id = $request['delivery_man_id'];
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = null;
        $order->third_party_delivery_tracking_id = null;
        $order->save();

        $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token:null;
        $value = Helpers::order_status_update_message('del_assign');
        if($value && !empty($fcm_token)) {
            try {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                if ($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                Helpers::send_push_notif_to_device($fcm_token, $data);
            } catch (\Exception $e) {
            }
        }

        return response()->json(['success' => 1, 'message' => translate('order_deliveryman_assigned_successfully')], 200);
    }

    public function amount_date_update(Request $request){
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $deliveryman_charge = $request->deliveryman_charge;

        $order = Order::find($request->order_id);
        $db_expected_date  = $order->expected_delivery_date;

        $order->deliveryman_charge = $deliveryman_charge;
        $order->expected_delivery_date = $request->expected_delivery_date;

        try {
            DB::beginTransaction();

            if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
                CommonTrait::add_expected_delivery_date_history($request->order_id, $seller['id'], $request->expected_delivery_date, 'seller');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['success' => 0, 'message' => translate('Update fail!')], 403);
        }

        if(!empty($request->expected_delivery_date) && $db_expected_date != $request->expected_delivery_date){
            $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token:null;
            $value = Helpers::order_status_update_message('expected_delivery_date') . " ID: " . $order['id'];
            if($value != null && !empty($fcm_token)) {
                try {
                    $data = [
                        'title' => translate('order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                    ];

                    if ($order->delivery_man_id) {
                        self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                    }
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                } catch (\Exception $e) {
                    return response()->json(['success' => 0, 'message' => translate('Update fail!')], 403);
                }
            }
        }

        return response()->json(['success' => 0, 'message' => translate('Updated successfully!')], 200);
    }

    /**
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'digital_file_after_sell' => 'required|mimes:jpg,jpeg,png,gif,zip,pdf',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $order_details = OrderDetail::find($request->order_id);
        if($order_details){
            $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'));
            $order_details->save();
            return response()->json(['success' => 1, 'message' => translate('File_upload_successfully')], 200);
        }else{
            return response()->json(['success' => 0, 'message' => translate("File_upload_fail!")], 202);
        }
    }

    public function order_detail_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $order = Order::find($request->id);
        if(empty($order->customer))
        {
            return response()->json(['success' => 0, 'message' => translate("Customer account has been deleted. you can't update status!")], 202);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($request->order_status=='delivered' && $order->payment_status !='paid'){

            return response()->json(['success' => 0, 'message' => translate('Before delivered you need to make payment status paid!')],200);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => translate('order is already delivered')], 200);
        }

        try {
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            if ($value) {
                $notif = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $notif);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        try {
            $fcm_token_delivery_man = $order->delivery_man->fcm_token;
            if ($request->order_status == 'canceled' && $value != null && !empty($fcm_token_delivery_man)) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];

                if($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                Helpers::send_push_notif_to_device($fcm_token_delivery_man, $data);
            }
        } catch (\Exception $e) {
        }

        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status'=>'delivered']
            );
        }

        $order->save();

        if ($order->delivery_man_id && $request->order_status == 'delivered') {
            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $order->delivery_man_id)->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->save();
            }

            if($order->deliveryman_charge && $request->order_status == 'delivered'){
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => $seller->id,
                    'user_type' => 'seller',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        if($wallet_status == 1 && $loyalty_point_status == 1)
        {
            if($request->order_status == 'delivered' && $order->payment_status =='paid'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

        CommonTrait::add_order_status_history($order->id, $seller->id, $request->order_status, 'seller');

        return response()->json(['success' => 1, 'message' => translate('order_status_updated_successfully')], 200);
    }

    public function assign_third_party_delivery(Request $request)
    {

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'delivery_service_name' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        return response()->json(['success' => 1, 'message' => translate('third_party_delivery_assigned_successfully')], 200);
    }

    public function update_payment_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more')
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::find($request['order_id']);
        if (isset($order)) {
            if(empty($order->customer))
            {
                return response()->json(['success' => 0, 'message' => translate("Customer account has been deleted. you can't update status!")], 202);
            }

            $order->payment_status = $request['payment_status'];
            $order->save();
            return response()->json(['message' => translate('Payment status updated')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('not found!')]
            ]
        ], 404);
    }
}
