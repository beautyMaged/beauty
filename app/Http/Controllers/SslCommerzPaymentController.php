<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Library\sslcommerz\SslCommerzNotification;
use App\Model\BusinessSetting;
use App\Model\Cart;
use App\Model\Currency;
use App\Model\Order;
use App\Model\Product;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function App\CPU\convert_price;

class SslCommerzPaymentController extends Controller
{

    public function index(Request $request)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $currency_code = 'BDT';
        } else {
            $default = BusinessSetting::where(['type' => 'system_default_currency'])->first()->value;
            $currency_code = Currency::find($default)->code;
        }



        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $user = Helpers::get_customer();

        $config = Helpers::get_business_settings('ssl_commerz_payment');

        $post_data = array();
        $post_data['store_id'] = $config['store_id'];
        $post_data['store_passwd'] = $config['store_password'];
        $post_data['total_amount'] = Convert::usdTobdt($value);
        $post_data['currency'] = $currency_code;
        $post_data['tran_id'] = OrderManager::gen_unique_id(); // tran_id must be unique

        $post_data['success_url'] = route('ssl-success');
        $post_data['fail_url'] = route('ssl-fail');
        $post_data['cancel_url'] = route('ssl-cancel');

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->f_name . ' ' . $user->l_name;
        $post_data['cus_email'] = $user->email;
        $post_data['cus_add1'] = $user->street_address == null ? 'address' : $user->user()->street_address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "";
        $post_data['cus_phone'] = $user->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Shipping";
        $post_data['ship_add1'] = "address 1";
        $post_data['ship_add2'] = "address 2";
        $post_data['ship_city'] = "City";
        $post_data['ship_state'] = "State";
        $post_data['ship_postcode'] = "ZIP";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Country";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        # REQUEST SEND TO SSLCOMMERZ
        $mode = env('APP_MODE');
        if ($mode == 'live') {
            $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
            $host = false;
        } else {
            $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
            $host = true;
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $host); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            Toastr::error('Misconfiguration or data is missing!');
            return back();
        }

        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
            # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
            # header("Location: ". $sslcz['GatewayPageURL']);
            exit;
        } else {
            Toastr::error('Misconfiguration or data is missing!');
            return back();
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids() as $group_id) {
            $data = [
                'payment_method' => 'sslcommerz',
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'transaction_ref' => $tran_id,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            if ($request['status'] == 'VALID') {
                CartManager::cart_clean();
                return redirect()->route('payment-success');
            } else {
                return redirect()->route('payment-fail');
            }
        } else {
            if ($request['status'] == 'VALID') {
                CartManager::cart_clean();
                return view('web-views.checkout-complete');
            } else {
                DB::table('orders')
                    ->whereIn('id', $order_ids)
                    ->update(['order_status' => 'failed']);
                Toastr::error('Payment failed!');
                return back();
            }
        }
    }

    public function fail(Request $request)
    {
        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            return redirect()->route('payment-fail');
        }
        Toastr::error('Payment process failed!');
        return back();
    }

    public function cancel(Request $request)
    {
        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            return redirect()->route('payment-fail');
        }
        Toastr::error('Payment process canceled!');
        return back();
    }
}
