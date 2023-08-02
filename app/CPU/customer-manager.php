<?php

namespace App\CPU;

use App\Model\CustomerWalletHistory;
use App\Model\SupportTicket;
use App\Model\Transaction;
use App\Model\BusinessSetting;
use App\Model\WalletTransaction;
use App\Model\LoyaltyPointTransaction;
use App\User;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use App\Model\OrderDetail;

class CustomerManager
{
    public static function create_support_ticket($data)
    {
        $support = new SupportTicket();
        $support->customer_id = $data['customer_id'];
        $support->subject = $data['subject'];
        $support->type = $data['type'];
        $support->priority = $data['priority'];
        $support->description = $data['description'];
        $support->status = $data['status'];
        $support->save();

        return $support;
    }

    public static function user_transactions($customer_id, $customer_type)
    {
        return Transaction::where(['payer_id' => $customer_id])->orWhere(['payment_receiver_id' => $customer_type])->get();
    }

    public static function user_wallet_histories($user_id)
    {
        return CustomerWalletHistory::where(['customer_id' => $user_id])->get();
    }

    public static function create_wallet_transaction($user_id, float $amount, $transaction_type, $referance)
    {
        if(BusinessSetting::where('type','wallet_status')->first()->value != 1) return false;
        $user = User::find($user_id);
        $current_balance = $user->wallet_balance;

        $wallet_transaction = new WalletTransaction();
        $wallet_transaction->user_id = $user->id;
        $wallet_transaction->transaction_id = \Str::uuid();
        $wallet_transaction->reference = $referance;
        $wallet_transaction->transaction_type = $transaction_type;

        $debit = 0.0;
        $credit = 0.0;

        if(in_array($transaction_type, ['add_fund_by_admin','add_fund','order_refund','loyalty_point']))
        {
            $credit = $amount;
            if($transaction_type == 'add_fund')
            {
                $wallet_transaction->admin_bonus = Convert::usd($amount*BusinessSetting::where('type','wallet_add_fund_bonus')->first()->value/100);
            }
            else if($transaction_type == 'loyalty_point')
            {
                $credit = (($amount / BusinessSetting::where('type','loyalty_point_exchange_rate')->first()->value)*Convert::default(1));
                // $credit= $credit*Convert::default(1);
            }
        }
        else if($transaction_type == 'order_place')
        {
            $debit = $amount;
        }

        $wallet_transaction->credit = Convert::usd($credit);
        $wallet_transaction->debit = Convert::usd($debit);
        $wallet_transaction->balance = $current_balance + Convert::usd($credit) - Convert::usd($debit);
        $wallet_transaction->created_at = now();
        $wallet_transaction->updated_at = now();
        $user->wallet_balance = $current_balance + Convert::usd($credit) - Convert::usd($debit);

        try{
            DB::beginTransaction();
            $user->save();
            $wallet_transaction->save();
            DB::commit();
            if(in_array($transaction_type, ['loyalty_point','order_place','add_fund_by_admin'])) return $wallet_transaction;
            return true;
        }catch(\Exception $ex)
        {
            info($ex);
            DB::rollback();

            return false;
        }
        return false;
    }

    public static function create_loyalty_point_transaction($user_id, $referance, $amount, $transaction_type)
    {
        $settings = array_column(BusinessSetting::whereIn('type',['loyalty_point_status','loyalty_point_exchange_rate','loyalty_point_item_purchase_point'])->get()->toArray(), 'value','type');
        if($settings['loyalty_point_status'] != 1)
        {
            return true;
        }

        $credit = 0;
        $debit = 0;
        $user = User::find($user_id);
        
        $loyalty_point_transaction = new LoyaltyPointTransaction();
        $loyalty_point_transaction->user_id = $user->id;
        $loyalty_point_transaction->transaction_id = \Str::uuid();
        $loyalty_point_transaction->reference = $referance;
        $loyalty_point_transaction->transaction_type = $transaction_type;
        
        if($transaction_type=='order_place')
        {
            $credit = (int)($amount * $settings['loyalty_point_item_purchase_point']/100);
        }
        else if($transaction_type=='point_to_wallet')
        {
            $debit = $amount;
        }else if($transaction_type=='refund_order')
        {
            $debit = $amount;
        }
        
        $current_balance = $user->loyalty_point + $credit - $debit;
        $loyalty_point_transaction->balance = $current_balance;
        $loyalty_point_transaction->credit = $credit;
        $loyalty_point_transaction->debit = $debit;
        $loyalty_point_transaction->created_at = now();
        $loyalty_point_transaction->updated_at = now();
        $user->loyalty_point = $current_balance;
        
        try{
            DB::beginTransaction();
            $user->save();
            $loyalty_point_transaction->save();
            DB::commit();
            return true;
        }catch(\Exception $ex)
        {
            info($ex);
            DB::rollback();

            return false;
        }
        return false;
    }

    public static function count_loyalty_point_for_amount($id)
    {
        $order_details = OrderDetail::find($id);
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        $loyalty_point = 0;
        if($loyalty_point_status == 1)
        {
            $loyalty_point_item_purchase_point = Helpers::get_business_settings('loyalty_point_item_purchase_point');
            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;
            
            $loyalty_point = (int)(Convert::default($subtotal) * $loyalty_point_item_purchase_point /100);
            
            return $loyalty_point;
        }
        return $loyalty_point;
    }
}
