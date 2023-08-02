<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\Payer;
use Illuminate\Support\Facades\DB;
use App\Model\Order;
use App\Model\BusinessSetting;
use App\CPU\Helpers;
use App\CPU\CartManager;
use App\CPU\OrderManager;

class MercadoPagoController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = Helpers::get_business_settings('mercadopago');
    }
    public function index(Request $request)
    {
        $data = $this->data;
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $order_amount = CartManager::cart_grand_total() - $discount;
        return view('web-views.payment-view-marcedo-pogo', compact('data', 'order_amount'));
    }
    public function make_payment(Request $request)
    {
        SDK::setAccessToken($this->data['access_token']);
        $payment = new Payment();
        $payment->transaction_amount = (float)$request['transactionAmount'];
        $payment->token = $request['token'];
        $payment->description = $request['description'];
        $payment->installments = (int)$request['installments'];
        $payment->payment_method_id = $request['paymentMethodId'];
        $payment->issuer_id = (int)$request['issuer'];

        $payer = new Payer();
        $payer->email = $request['payer']['email'];
        $payer->identification = array(
            "type" => $request['payer']['identification']['type'],
            "number" => $request['payer']['identification']['number']
        );
        $payment->payer = $payer;

        $payment->save();

        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );

        if($payment->error)
        {
            $response['error'] = $payment->error->message;
            if (auth('customer')->check()) {
                Toastr::error('Payment failed.');
                return redirect('/');
            }
            return response()->json(['message' => 'Payment failed'], 403);
        }
        if($payment->status == 'approved')
        {
            try {
                $unique_id = OrderManager::gen_unique_id();
                $order_ids = [];
                foreach (CartManager::get_cart_group_ids() as $group_id) {
                    $data = [
                        'payment_method' => 'mercadopago',
                        'order_status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_ref' => $request['paymentMethodId'],
                        'order_group_id' => $unique_id,
                        'cart_group_id' => $group_id
                    ];
                    $order_id = OrderManager::generate_order($data);
                    array_push($order_ids, $order_id);
                }
                CartManager::cart_clean();
            } catch (\Exception $e) {
            }
        }
        if (auth('customer')->check()) {
            Toastr::success('Payment success.');
            return view('web-views.checkout-complete');
        }
        return response()->json(['message' => 'Payment succeeded'], 200);
    }
    public function get_test_user(Request $request)
    {
        // curl -X POST \
        // -H "Content-Type: application/json" \
        // -H 'Authorization: Bearer PROD_ACCESS_TOKEN' \
        // "https://api.mercadopago.com/users/test_user" \
        // -d '{"site_id":"MLA"}'

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/users/test_user");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->data['access_token']
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"site_id":"MLA"}');
        $response = curl_exec($curl);
        dd($response);

    }
}
