<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Validator;
use App\CPU\CustomerManager;
use Brian2694\Toastr\Facades\Toastr;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Mail;
use App\Model\WalletTransaction;

class CustomerWalletController extends Controller
{

    public function add_fund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'=>'exists:users,id',
            'amount'=>'numeric|min:.01|max:10000000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $wallet_transaction = CustomerManager::create_wallet_transaction($request->customer_id, $request->amount, 'add_fund_by_admin',$request->referance);


        if($wallet_transaction)
        {

            try{
                Mail::to($wallet_transaction->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
            }catch(\Exception $ex)
            {
                info($ex);
            }

            return response()->json([], 200);
        }

        return response()->json(['errors'=>[
            'message'=>\App\CPU\translate('failed_to_create_transaction')
        ]], 200);
    }

    public function report(Request $request)
    {
        $customer_status = BusinessSetting::where('type','wallet_status')->first()->value; //customer disable check

        $data = WalletTransaction::selectRaw('sum(credit) as total_credit, sum(debit) as total_debit')
        ->when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->get();

        $transactions = WalletTransaction::
        when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->latest()
        ->paginate(Helpers::pagination_limit());

        return view('admin-views.customer.wallet.report', compact('data','transactions', 'customer_status'));
    }

}
