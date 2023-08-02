<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function App\CPU\translate;

class Paytabs
{
    function send_api_request($request_url, $data, $request_method = null)
    {
        $config = Helpers::get_business_settings('paytabs');

        $data['profile_id'] = $config['profile_id'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $config['base_url'] .'/'. $request_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CUSTOMREQUEST => isset($request_method) ? $request_method : 'POST',
            CURLOPT_POSTFIELDS => json_encode($data, true),
            CURLOPT_HTTPHEADER => array(
                'authorization:' . $config['server_key'],
                'Content-Type:application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $response;
    }

    function is_valid_redirect($post_values)
    {
        $config = Helpers::get_business_settings('paytabs');

        $serverKey = $config['server_key'];

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);

        $signature = hash_hmac('sha256', $query, $serverKey);
        if (hash_equals($signature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }
}

class PaytabsController extends Controller
{
    public function payment()
    {
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $tran = OrderManager::gen_unique_id();
        $user = Helpers::get_customer();

        $plugin = new Paytabs();
        $request_url = 'payment/request';
        $data = [
            "tran_type" => "sale",
            "tran_class" => "ecom",
            "cart_id" => $tran,
            "cart_currency" => "EGP",
            "cart_amount" => round($value,2),
            "cart_description" => "products",
            "paypage_lang" => "en",
            "callback" => url('/') . "/paytabs-response", // Nullable - Must be HTTPS, otherwise no post data from paytabs
            "return" => url('/') . "/paytabs-response", // Must be HTTPS, otherwise no post data from paytabs , must be relative to your site URL
            "customer_details" => [
                "name" => $user->f_name,
                "email" => $user->email,
                "phone" => "000000",
                "street1" => "address",
                "city" => "not given",
                "state" => "not given",
                "country" => "not given",
                "zip" => "00000"
            ],
            "shipping_details" => [
                "name" => "not given",
                "email" => "not given",
                "phone" => "not given",
                "street1" => "not given",
                "city" => "not given",
                "state" => "not given",
                "country" => "not given",
                "zip" => "0000"
            ],
            "user_defined" => [
                "udf9" => "UDF9",
                "udf3" => "UDF3"
            ]
        ];
        $page = $plugin->send_api_request($request_url, $data);
        header('Location:' . $page['redirect_url']); /* Redirect browser */
        exit();
    }

    public function callback_response(Request $request)
    {
        $plugin = new Paytabs();

        $response_data = $_POST;

        $transRef = filter_input(INPUT_POST, 'tranRef');

        if (!$transRef) {
            Toastr::error(translate('Transaction reference is not set. return url must be HTTPs with POST method to can retrieve data'));
            return back();
        }

        $is_valid = $plugin->is_valid_redirect($response_data);
        if (!$is_valid) {
            Toastr::error(translate('Not a valid PayTabs response'));
            return back();
        }

        $request_url = 'payment/query';
        $data = [
            "tran_ref" => $transRef
        ];
        $verify_result = $plugin->send_api_request($request_url, $data);
        $is_success = $verify_result['payment_result']['response_status'] === 'A';
        if ($is_success) {
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'sslcommerz',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => $transRef,
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            if ($is_success == TRUE) {
                CartManager::cart_clean();
                return redirect()->route('payment-success');
            } else {
                return redirect()->route('payment-fail');
            }
        } else {
            CartManager::cart_clean();
            return view('web-views.checkout-complete');
        }
    }
}
