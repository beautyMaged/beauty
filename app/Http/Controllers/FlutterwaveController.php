<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use Illuminate\Support\Facades\DB;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Http;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class FlutterwaveController extends Controller
{
    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $order_amount = CartManager::cart_grand_total() - $discount;

        $user_data = Helpers::get_customer();
        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $order_amount,
            'email' => $user_data['email'],
            'tx_ref' => $reference,
            'currency' => Helpers::currency_code(),
            'redirect_url' => route('flutterwave_callback'),
            'customer' => [
                'email' => $user_data['email'],
                "phone_number" => $user_data['phone'],
                "name" => $user_data['name']
            ],

            "customizations" => [
                "title" => Helpers::get_business_settings('company_name'),
                "description" => 1,
            ]
        ];

        try {
            $payment = Flutterwave::initializePayment($data);
            return redirect($payment['data']['link']);
        }catch (\Exception $exception){
            Toastr::error(translate('configuration_invalid'));
            return back();
        }
    }

    public function callback()
    {
        $status = request()->status;

        //if payment is successful
        if ($status == 'successful') {
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'stripe',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => session('transaction_ref'),
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }
            CartManager::cart_clean();
            if (auth('customer')->check()) {
                Toastr::success('Payment success.');
                return view('web-views.checkout-complete');
            }
            return response()->json(['message' => 'Payment succeeded'], 200);
        }

        if (auth('customer')->check()) {
            Toastr::error('Payment failed.');
            return redirect('/');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
