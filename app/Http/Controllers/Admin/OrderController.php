<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Seller;
use App\Traits\CommonTrait;
use App\Model\ShippingAddress;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{
    use CommonTrait;
    public function list(Request $request, $status)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $delivery_man_id = $request['delivery_man_id'];

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'seller.shop'])
            ->when($status != 'all', function ($q) use($status){
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->when($filter,function($q) use($filter){
                $q->when($filter == 'all', function($q){
                    return $q;
                })
                    ->when($filter == 'POS', function ($q){
                        $q->whereHas('details', function ($q){
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                        $q->whereHas('details', function ($query) use ($filter){
                            $query->whereHas('product', function ($query) use ($filter){
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }});
            })->when(!empty($from) && !empty($to), function($dateQuery) use($from, $to) {
                $dateQuery->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })->when($delivery_man_id, function ($q) use($delivery_man_id){
                $q->where(['delivery_man_id'=> $delivery_man_id]);
            })
            ->latest('id')
            ->paginate(Helpers::pagination_limit())
            ->appends(['search'=>$request['search'],'filter'=>$request['filter'],'from'=>$request['from'],'to'=>$request['to'],'delivery_man_id'=>$request['delivery_man_id']]);

            $pending_query = Order::where(['order_status' => 'pending']);
            $pending_count = $this->common_query_status_count($pending_query, $status, $request);

            $confirmed_query = Order::where(['order_status' => 'confirmed']);
            $confirmed_count = $this->common_query_status_count($confirmed_query, $status, $request);

            $processing_query = Order::where(['order_status' => 'processing']);
            $processing_count = $this->common_query_status_count($processing_query, $status, $request);

            $out_for_delivery_query = Order::where(['order_status' => 'out_for_delivery']);
            $out_for_delivery_count = $this->common_query_status_count($out_for_delivery_query, $status, $request);

            $delivered_query = Order::where(['order_status' => 'delivered']);
            $delivered_count = $this->common_query_status_count($delivered_query, $status, $request);

            $canceled_query = Order::where(['order_status' => 'canceled']);
            $canceled_count = $this->common_query_status_count($canceled_query, $status, $request);

            $returned_query = Order::where(['order_status' => 'returned']);
            $returned_count = $this->common_query_status_count($returned_query, $status, $request);

            $failed_query = Order::where(['order_status' => 'failed']);
            $failed_count = $this->common_query_status_count($failed_query, $status, $request);

        return view(
                'admin-views.order.list',
                compact(
                    'orders',
                    'search',
                    'from', 'to', 'status',
                    'filter',
                    'pending_count',
                    'confirmed_count',
                    'processing_count',
                    'out_for_delivery_count',
                    'delivered_count',
                    'returned_count',
                    'failed_count',
                    'canceled_count'
                )
            );
    }

    public function common_query_status_count($query, $status, $request){
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';

            return $query->when($status != 'all', function ($q) use($status){
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->when($filter,function($q) use($filter) {
                $q->when($filter == 'all', function ($q) {
                    return $q;
                })
                ->when($filter == 'POS', function ($q){
                    $q->whereHas('details', function ($q){
                        $q->where('order_type', 'POS');
                    });
                })
                ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                    $q->whereHas('details', function ($query) use ($filter){
                        $query->whereHas('product', function ($query) use ($filter){
                            $query->where('added_by', $filter);
                        });
                    });
                });
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }});
            })->when(!empty($from) && !empty($to), function($dateQuery) use($from, $to) {
                $dateQuery->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })->count();
    }

    public function details($id)
    {
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = Order::with('details.product_all_status', 'shipping', 'seller.shop')->where(['id' => $id])->first();

        $physical_product = false;
        foreach($order->details as $product){
            if(isset($product->product) && $product->product->product_type == 'physical'){
                $physical_product = true;
            }
        }

        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
            ->whereNotIn('order_group_id', ['def-order-group'])
            ->whereNotIn('id', [$order['id']])
            ->get();

        $total_delivered = Order::where(['seller_id' => $order->seller_id, 'order_status' => 'delivered', 'order_type' => 'default_type'])->count();

        $shipping_method = Helpers::get_business_settings('shipping_method');
        $delivery_men = DeliveryMan::where('is_active', 1)->when($order->seller_is == 'admin', function ($query) {
            $query->where(['seller_id' => 0]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => $order['seller_id']]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'inhouse_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => 0]);
        })->get();

        $shipping_address = ShippingAddress::find($order->shipping_address);
        if($order->order_type == 'default_type')
        {
            return view('admin-views.order.order-details', compact('shipping_address','order', 'linked_orders', 'delivery_men', 'total_delivered', 'company_name', 'company_web_logo', 'physical_product'));
        }else{
            return view('admin-views.pos.order.order-details', compact('order', 'company_name', 'company_web_logo'));
        }

    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }
        $order = Order::find($order_id);
        $order->delivery_man_id = $delivery_man_id;
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = null;
        $order->third_party_delivery_tracking_id = null;
        $order->save();

        $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token : null;
        $value = Helpers::order_status_update_message('del_assign') . " ID: " . $order['id'];
        if(!empty($fcm_token)) {
            try {
                if ($value != null) {
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
                }
            } catch (\Exception $e) {
                Toastr::warning(\App\CPU\translate('Push notification failed for DeliveryMan!'));
            }
        }

        return response()->json(['status' => true], 200);
    }

    public function status(Request $request)
    {
        $user_id = auth('admin')->id();

        $order = Order::find($request->id);

        if(!isset($order->customer))
        {
            return response()->json(['customer_status'=>0],200);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($request->order_status=='delivered' && $order->payment_status !='paid'){

            return response()->json(['payment_status'=>0],200);
        }
        $fcm_token = isset($order->customer) ? $order->customer->cm_firebase_token : null;
        $value = Helpers::order_status_update_message($request->order_status);
        if(!empty($fcm_token)) {
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
            }
        }

        try {
            $fcm_token_delivery_man = $order->delivery_man->fcm_token;
            if ($request->order_status == 'canceled' && $value != null) {
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
        $order->save();

        if($loyalty_point_status == 1)
        {
            if($request->order_status == 'delivered' && $order->payment_status =='paid'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

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
                    'user_id' => 0,
                    'user_type' => 'admin',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        self::add_order_status_history($request->id, 0, $request->order_status, 'admin');

        $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        if (isset($transaction) && $transaction['status'] == 'disburse') {
            return response()->json($request->order_status);
        }

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'admin');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status'=>'delivered']
            );
        }

        return response()->json($request->order_status);
    }

    public function amount_date_update(Request $request){
        $field_name = $request->field_name;
        $field_val = $request->field_val;
        $user_id = 0;

        $order = Order::find($request->order_id);
        $order->$field_name = $field_val;

        try {
            DB::beginTransaction();

            if($field_name == 'expected_delivery_date'){
                self::add_expected_delivery_date_history($request->order_id, $user_id, $field_val, 'admin');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status' => false], 403);
        }

        if($field_name == 'expected_delivery_date') {
            $fcm_token = isset($order->delivery_man) ? $order->delivery_man->fcm_token:null;
            $value = Helpers::order_status_update_message($field_name) . " ID: " . $order['id'];
            if(!empty($fcm_token)) {
                try {
                    if ($value != null) {
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
                    }
                } catch (\Exception $e) {
                    Toastr::warning(\App\CPU\translate('Push notification failed for DeliveryMan!'));
                }
            }
        }

        return response()->json(['status' => true], 200);
    }

    public function payment_status(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::find($request->id);

            if(!isset($order->customer))
            {
                return response()->json(['customer_status'=>0],200);
            }

            $order = Order::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            $data = $request->payment_status;
            return response()->json($data);
        }
    }

    public function generate_invoice($id)
    {
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $seller = Seller::find($order->details->first()->seller_id);
        $user = User::find($order->customer_id);
        if (isset($user) && $user->id != 0) {
            $shipping_address_json = [];
            $data["client_name"] =  $order->customer["f_name"] . ' ' . $order->customer["l_name"];
            $data["email"] = $order->customer !=null?$order->customer["email"]:\App\CPU\translate('email_not_found');

            $data["phone"] =  $order->customer !=null?$order->customer["phone"]:\App\CPU\translate('phone_not_found');

            $data["address"] =  $order->shippingAddress ? $order->shippingAddress['address'] : '';

        } else {

            $shipping_address_json=json_decode($order['shipping_address_data']);
            $data["client_name"] =  isset($shipping_address_json->contact_person_name) && $shipping_address_json->contact_person_name != null ? $shipping_address_json->contact_person_name : '';
            $data["email"] =  isset($shipping_address_json->email) && $shipping_address_json->email != null ? $shipping_address_json->email : '';
            $data["phone"] =  isset($shipping_address_json->phone) && $shipping_address_json->phone != null ? $shipping_address_json->phone : '';
            $data["address"] =  isset($shipping_address_json->address) && $shipping_address_json->address != null ? $shipping_address_json->address : '';
        }

        $data["order"] = $order;
        $mpdf_view = View::make('admin-views.order.invoice',
            compact('order', 'seller', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'shipping_address_json', 'data')
        );
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    /*
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $request->validate([
            'digital_file_after_sell'    => 'required|mimes:jpg,jpeg,png,gif,zip,pdf'
        ], [
            'digital_file_after_sell.required' => 'Digital file upload after sell is required',
            'digital_file_after_sell.mimes' => 'Digital file upload after sell upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
        ]);

        $order_details = OrderDetail::find($request->order_id);
        $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'));

        if($order_details->save()){
            Toastr::success('Digital file upload successfully!');
        }else{
            Toastr::error('Digital file upload failed!');
        }
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
    public function update_deliver_info(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        Toastr::success(\App\CPU\translate('updated_successfully!'));
        return back();
    }

    public function bulk_export_data(Request $request, $status)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];

        if ($status != 'all') {
            $orders = Order::when($filter,function($q) use($filter){
                $q->when($filter == 'all', function($q){
                    return $q;
                })
                    ->when($filter == 'POS', function ($q){
                        $q->whereHas('details', function ($q){
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                        $q->whereHas('details', function ($query) use ($filter){
                            $query->whereHas('product', function ($query) use ($filter){
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
                ->with(['customer'])->where(function($query) use ($status){
                    $query->orWhere('order_status',$status)
                        ->orWhere('payment_status',$status);
                });
        } else {
            $orders = Order::with(['customer'])
                ->when($filter,function($q) use($filter){
                    $q->when($filter == 'all', function($q){
                        return $q;
                    })
                        ->when($filter == 'POS', function ($q){
                            $q->whereHas('details', function ($q){
                                $q->where('order_type', 'POS');
                            });
                        })
                        ->when(($filter == 'admin' || $filter == 'seller'), function($q) use($filter){
                            $q->whereHas('details', function ($query) use ($filter){
                                $query->whereHas('product', function ($query) use ($filter){
                                    $query->where('added_by', $filter);
                                });
                            });
                        });
                });
        }

        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $orders = $orders->when($request->has('search') && $search!=null,function ($q) use ($key) {
            $q->where(function($qq) use ($key){
                foreach ($key as $value) {
                    $qq->where('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_ref', 'like', "%{$value}%");
                }});
        })->when(!empty($from) && !empty($to), function($dateQuery) use($from, $to) {
            $dateQuery->whereDate('created_at', '>=',$from)
                ->whereDate('created_at', '<=',$to);
        })->orderBy('id', 'DESC')->get();

        if ($orders->count()==0) {
            Toastr::warning(\App\CPU\translate('Data is Not available!!!'));
            return back();
        }

        $storage = array();

        foreach ($orders as $item) {

            $order_amount = $item->order_amount;
            $discount_amount = $item->discount_amount;
            $shipping_cost = $item->shipping_cost;
            $extra_discount = $item->extra_discount;

            $storage[] = [
                'order_id'=>$item->id,
                'Customer Id' => $item->customer_id,
                'Customer Name'=> isset($item->customer) ? $item->customer->f_name. ' '.$item->customer->l_name:'not found',
                'Order Group Id' => $item->order_group_id,
                'Order Status' => $item->order_status,
                'Order Amount' => Helpers::currency_converter($order_amount),
                'Order Type' => $item->order_type,
                'Coupon Code' => $item->coupon_code,
                'Discount Amount' => Helpers::currency_converter($discount_amount),
                'Discount Type' => $item->discount_type,
                'Extra Discount' => Helpers::currency_converter($extra_discount),
                'Extra Discount Type' => $item->extra_discount_type,
                'Payment Status' => $item->payment_status,
                'Payment Method' => $item->payment_method,
                'Transaction_ref' => $item->transaction_ref,
                'Verification Code' => $item->verification_code,
                'Billing Address' => isset($item->billingAddress)? $item->billingAddress->address:'not found',
                'Billing Address Data' => $item->billing_address_data,
                'Shipping Type' => $item->shipping_type,
                'Shipping Address' => isset($item->shippingAddress)? $item->shippingAddress->address:'not found',
                'Shipping Method Id' => $item->shipping_method_id,
                'Shipping Method Name' => isset($item->shipping)? $item->shipping->title:'not found',
                'Shipping Cost' => Helpers::currency_converter($shipping_cost),
                'Seller Id' => $item->seller_id,
                'Seller Name' => isset($item->seller)? $item->seller->f_name. ' '.$item->seller->l_name:'not found',
                'Seller Email'  => isset($item->seller)? $item->seller->email:'not found',
                'Seller Phone'  => isset($item->seller)? $item->seller->phone:'not found',
                'Seller Is' => $item->seller_is,
                'Shipping Address Data' => $item->shipping_address_data,
                'Delivery Type' => $item->delivery_type,
                'Delivery Man Id' => $item->delivery_man_id,
                'Delivery Service Name' => $item->delivery_service_name,
                'Third Party Delivery Tracking Id' => $item->third_party_delivery_tracking_id,
                'Checked' => $item->checked,

            ];
        }

        return (new FastExcel($storage))->download('Order_All_details.xlsx');
    }
}
