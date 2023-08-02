<?php

namespace App\Http\Controllers\api\v3\seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class DeliveryManCashCollectController extends Controller
{

    public function list(Request $request, $id)
    {
        $seller = $request->seller;
        $delivery_man = DeliveryMan::with('wallet')->where('seller_id',$seller->id)->find($id);
        if(!$delivery_man){
            return response()->json(['message' => translate('invalid_deliveryman!')], 403);
        }
        $transactions = $delivery_man->transactions()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $transactions->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['collected_cash'] = $transactions->items();

        return response()->json($data, 200);
    }
    public function cash_receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $id = $request->deliveryman_id;
        $seller = $request->seller;

        $wallet = DeliverymanWallet::where('delivery_man_id', $id)
            ->whereHas('delivery_man', function($query) use($seller){
                $query->where('seller_id',$seller->id);
            })->first();

        if (empty($wallet) || BackEndHelper::currency_to_usd($request->input('amount'))  > $wallet->cash_in_hand) {
            return response()->json(['message' => translate('receive_amount_can_not_be_more_than_cash_in_hand!')], 202);
        }

        $wallet->cash_in_hand -= $request->input('amount');
        DeliveryManTransaction::create([
            'delivery_man_id' => $id,
            'user_id'         => $seller->id,
            'user_type'       => 'seller',
            'credit'           => BackEndHelper::currency_to_usd($request->input('amount')),
            'transaction_type' => 'cash_in_hand'
        ]);

        if ($wallet->save()) {
            return response()->json(['message' => translate('Amount_receive_successfully!')], 200);
        }
        return response()->json(['message' => translate('Amount_receive_failed!')], 403);
    }
}
