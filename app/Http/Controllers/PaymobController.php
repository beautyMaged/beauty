<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Model\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;

class PaymobController extends Controller
{
    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    protected function GETcURL($url)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    public function credit()
    {
        $currency_code = Currency::where(['code' => 'EGP'])->first();
        if (isset($currency_code) == false) {
            Toastr::error(translate('paymob_supports_EGP_currency'));
            return back();
        }

        $config = Helpers::get_business_settings('paymob_accept');
        try {
            $token = $this->getToken();
            $order = $this->createOrder($token);
            $paymentToken = $this->getPaymentToken($order, $token);
        }catch (\Exception $exception){
            Toastr::error(translate('country_permission_denied_or_misconfiguration'));
            return back();
        }
        return \Redirect::away('https://portal.weaccept.co/api/acceptance/iframes/' . $config['iframe_id'] . '?payment_token=' . $paymentToken);
    }

    public function getToken()
    {
        $config = Helpers::get_business_settings('paymob_accept');
        $response = $this->cURL(
            'https://accept.paymobsolutions.com/api/auth/tokens',
            ['api_key' => $config['api_key']]
        );

        return $response->token;
    }

    public function createOrder($token)
    {
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $value = Convert::usdToegp($value);

        $items = [];
        foreach (CartManager::get_cart() as $detail) {
            array_push($items, [
                'name' => $detail->product['name'],
                'amount_cents' => round(Convert::usdToegp($detail['price']),2) * 100,
                'description' => $detail->product['name'],
                'quantity' => $detail['quantity']
            ]);
        }

        $data = [
            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" => round($value,2) * 100,
            "currency" => "EGP",
            "items" => $items,

        ];
        $response = $this->cURL(
            'https://accept.paymob.com/api/ecommerce/orders',
            $data
        );

        return $response;
    }

    public function getPaymentToken($order, $token)
    {
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $value = Convert::usdToegp($value);
        $user = Helpers::get_customer();

        $config = Helpers::get_business_settings('paymob_accept');
        $billingData = [
            "apartment" => "NA",
            "email" => $user['email'],
            "floor" => "NA",
            "first_name" => $user['f_name'],
            "street" => "NA",
            "building" => "NA",
            "phone_number" => $user['phone'],
            "shipping_method" => "PKG",
            "postal_code" => "NA",
            "city" => "NA",
            "country" => "NA",
            "last_name" => $user['l_name'],
            "state" => "NA",
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => round($value,2) * 100,
            "expiration" => 3600,
            "order_id" => $order->id,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $config['integration_id']
        ];

        $response = $this->cURL(
            'https://accept.paymob.com/api/acceptance/payment_keys',
            $data
        );

        return $response->token;
    }

    public function callback(Request $request)
    {
        $config = Helpers::get_business_settings('paymob_accept');
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = $config['hmac'];
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ($hased == $hmac) {
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'paymob_accept',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => 'tran-' . $unique_id,
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
            return redirect('/account-order');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
