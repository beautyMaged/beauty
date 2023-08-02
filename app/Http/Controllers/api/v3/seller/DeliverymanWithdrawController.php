<?php

namespace App\Http\Controllers\api\v3\seller;

use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\DeliverymanWallet;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;

class DeliverymanWithdrawController extends Controller
{
    public function list(Request $request)
    {
        $seller = $request->seller;
        $status = null;
        if($request->status == 'approved'){
            $status = 1;
        }elseif($request->status == 'denied'){
            $status = 2;
        }elseif($request->status == 'pending'){
            $status = '0';
        }

        $withdraws = WithdrawRequest::with(['delivery_men'])
            ->where('seller_id', $seller->id)
            ->whereNotNull('delivery_man_id')
            ->when($request->status == 'all', function ($query) {
                return $query;
            })
            ->when($status!=null, function ($query) use($status){
                return $query->where('approved', $status);
            })
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $withdraws->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['withdraws'] = $withdraws->items();
        return response()->json($data, 200);
    }

    public function details(Request $request, $id){
        $seller = $request->seller;
        $details = WithdrawRequest::with(['delivery_men'])
            ->where('delivery_man_id', '<>', null)
            ->where(['seller_id' => $seller->id])
            ->find($id);

        return response()->json(['details'=>$details], 200);
    }

    public function status_update(Request $request)
    {
        $id = $request->id;
        $seller = $request->seller;
        $withdraw = WithdrawRequest::where(['seller_id' => $seller->id])->find($id);
        if(!$withdraw){
            return response()->json(['message' => translate('Invalid_withdraw!')], 403);
        }
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request->note;

        $wallet = DeliverymanWallet::where('delivery_man_id', $withdraw->delivery_man_id)->first();
        if ($request->approved == 1) {
            $wallet->total_withdraw   += Convert::usd($withdraw['amount']);
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->current_balance  -= Convert::usd($withdraw['amount']);
            $wallet->save();
            $withdraw->save();

            return response()->json(['message' => translate('Delivery_man_payment_has_been_approved_successfully!')], 200);
        }else{
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->save();
            $withdraw->save();

            return response()->json(['message' => translate('Delivery_man_payment_request_has_been_Denied_successfully!')], 200);
        }
    }
}
