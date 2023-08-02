<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;
use App\Model\RefundTransaction;
use App\CPU\Helpers;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class RefundTransactionController extends Controller
{
    public function refund_transaction_list(Request $request)
    {
        $search = $request['search'];
        $payment_method = $request['payment_method'];
        $query_param = ['search' => $request['search'], 'payment_method'=>$payment_method];

        $refund_transactions = self::refund_transaction_common_query($request);
        $refund_transactions = $refund_transactions->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.refund-transaction.list',compact('refund_transactions', 'search', 'payment_method'));
    }

    public function refund_transaction_common_query($request){
        $search = $request['search'];
        $payment_method = $request['payment_method'];
        $refund_transaction = RefundTransaction::with(['order.seller.shop', 'order_details.product'])
            ->when($search, function ($query) use($search){
                $key = explode(' ', $search);
                foreach ($key as $value) {
                    $query->orWhere('order_id', 'like', "%{$value}%")
                        ->orWhere('refund_id', 'like', "%{$value}%");
                }
            })->when(!empty($payment_method) && $payment_method != 'all', function ($query) use($payment_method){
                $query->where('payment_method', $payment_method);
            })
            ->latest();

        return $refund_transaction;
    }

    public function refund_transaction_export_excel(Request $request)
    {
        $transactions = self::refund_transaction_common_query($request)->get();

        $tranData = array();
        foreach ($transactions as $transaction) {
            $shop_name = $transaction->order->seller_is == 'seller' ? ($transaction->order->seller ? $transaction->order->seller->shop->name : 'Not Found') : 'inhouse';
            $tranData[] = array(
                'Product Name' => $transaction->order_details->product ? $transaction->order_details->product->name : 'Not Found',
                'Refund ID' => $transaction->refund_id,
                'Order ID' => $transaction->order_id,
                'Shop Name' => $shop_name,
                'Payment Method' => str_replace('_',' ',$transaction->payment_method),
                'Payment Status' => str_replace('_',' ',$transaction->payment_status),
                'Paid By' => str_replace('_',' ',$transaction->paid_by),
                'Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($transaction->amount)),
                'Transaction Type' => str_replace('_',' ',$transaction->transaction_type),
            );
        }

        return (new FastExcel($tranData))->download('Refund_Transaction_details.xlsx');

    }

    public function refund_transaction_summary_pdf(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $transactions = self::refund_transaction_common_query($request)->get();

        $total_amount = 0;
        foreach($transactions as $transaction){
            $total_amount += $transaction->amount;
        }

        $data = array(
            'total_amount' => $total_amount,
            'company_phone' => $company_phone,
            'company_email' => $company_email,
            'company_name' => $company_name,
            'company_web_logo' => $company_web_logo,
        );

        $mpdf_view = View::make('admin-views.refund_transaction_summary_report_pdf', compact('data'));
        Helpers::gen_mpdf($mpdf_view, 'refund_transaction_summary_report_', data('Y'));

    }
}
