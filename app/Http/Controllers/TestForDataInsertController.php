<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin;
use App\Model\AdminWallet;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\Model\ShippingAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\CPU\Helpers;
use App\User;
use App\CPU\OrderManager;

class TestForDataInsertController extends Controller
{
    public function generate_order()
    {   
        for($j=0; $j<4 ;$j++)
        {
            for($i=0; $i<100;$i++)
            {
                $order_id = 100000 + Order::all()->count() + 1;
                
                $user_id= rand(1, 14);
                $user = User::find($user_id);

                $shipping_address_id = ShippingAddress::where('customer_id',$user->id)->first()->id;
                $billing_address_id = $shipping_address_id;
                $coupon_code = 'xyz';
                $discount = 10;
                $order_note = 'fjkskjfd';

                
                $product_id = rand(1,19);
                    $product = Product::where(['id' => $product_id])->first();
                    $product_price = $product->unit_price *1;
                    $order_details = new OrderDetail;
                    
                    $order_details->order_id = $order_id;
                    $order_details->product_id = $product->id;
                    $order_details->seller_id = $product->user_id;
                    $order_details->product_details = $product;
                    $order_details->qty = 1;
                    $order_details->price = $product->unit_price;
                    $order_details->tax = $product->tax * 1;
                    $order_details->discount = 10 * 1;
                    $order_details->discount_type = 'discount_on_product';
                    $order_details->variant = 0;
                    $order_details->variation = $product->variation;
                    $order_details->delivery_status = 'pending';
                    $order_details->shipping_method_id = null;
                    $order_details->payment_status = 'unpaid';
                    $order_details->created_at = now();
                    $order_details->updated_at = now();
                    $order_details->save();
                
                $order_new = new Order;
               
                    $order_new->id = $order_id;
                    $order_new->verification_code = rand(100000, 999999);
                    $order_new->customer_id = $user->id;
                    $order_new->seller_id = $product->user_id;
                    $order_new->seller_is = $product->added_by;
                    $order_new->customer_type = 'customer';
                    $order_new->payment_status = 'unpaid';
                    $order_new->order_status = 'pending';
                    $order_new->payment_method = 'cash_on_delivery';
                    $order_new->transaction_ref = '';
                    $order_new->order_group_id = rand(1,10000);
                    $order_new->discount_amount = $discount;
                    $order_new->discount_type = 'coupon_discount';
                    $order_new->coupon_code = $coupon_code;
                    $order_new->order_amount =  $product_price - $discount;
                    $order_new->shipping_address = $shipping_address_id;
                    $order_new->shipping_address_data = ShippingAddress::find($shipping_address_id);
                    $order_new->billing_address = $shipping_address_id;
                    $order_new->billing_address_data = ShippingAddress::find($shipping_address_id);
                    $order_new->shipping_cost = 5;
                    $order_new->shipping_method_id = 2;
                    $order_new->created_at = now();
                    $order_new->updated_at = now();
                    $order_new->order_note = $order_note;
                    $order_new->save();

                

                if (1) {
                    $order = Order::find($order_id);
                    $order_summary = OrderManager::order_summary($order);
                    $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount'];
                    $commission = Helpers::sales_commission($order);

                    DB::table('order_transactions')->insert([
                        'transaction_id' => OrderManager::gen_unique_id(),
                        'customer_id' => $order['customer_id'],
                        'seller_id' => $order['seller_id'],
                        'seller_is' => $order['seller_is'],
                        'order_id' => $order_id,
                        'order_amount' => $order_amount,
                        'seller_amount' => $order_amount - $commission,
                        'admin_commission' => $commission,
                        'received_by' => 'admin',
                        'status' => 'hold',
                        'delivery_charge' => $order['shipping_cost'],
                        'tax' => $order_summary['total_tax'],
                        'delivered_by' => 'admin',
                        'payment_method' => $order['payment_method'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (AdminWallet::where('admin_id', 1)->first() == false) {
                        DB::table('admin_wallets')->insert([
                            'admin_id' => 1,
                            'withdrawn' => 0,
                            'commission_earned' => 0,
                            'inhouse_earning' => 0,
                            'delivery_charge_earned' => 0,
                            'pending_amount' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    DB::table('admin_wallets')->where('admin_id', $order['seller_id'])->increment('pending_amount', $order['order_amount']);
                }
            }
        }

        return "done";
    }
}
