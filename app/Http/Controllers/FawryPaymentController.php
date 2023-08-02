<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class Fawry
{
    public $merchantCode;

    public $securityKey;

    public function __construct()
    {
        $config = Helpers::get_business_settings('fawry_pay');
        $this->merchantCode = $config['merchant_code'];
        $this->securityKey = $config['security_key'];
    }

    public function endpoint($uri)
    {
        return config('fawry.debug') ?
            'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/' . $uri :
            'https://www.atfawry.com/ECommerceWeb/Fawry/' . $uri;
    }

    public function createCardToken($cardNumber, $expiryYear, $expiryMonth, $cvv, $user)
    {
        $result = $this->post(
            $this->endpoint("cards/cardToken"), [
                "merchantCode" => $this->merchantCode,
                "customerProfileId" => md5($user->id),
                "customerMobile" => $user->phone,
                "customerEmail" => $user->email,
                "cardNumber" => $cardNumber,
                "expiryYear" => $expiryYear,
                "expiryMonth" => $expiryMonth,
                "cvv" => $cvv
            ]
        );

        /*if ($result->statusCode == 200) {
            $user->update([
                'payment_card_last_four' => $result->card->lastFourDigits,
                'payment_card_brand' => str_replace(' ', '', $result->card->brand),
                'payment_card_fawry_token' => $result->card->token,
            ]);
        }*/

        return $result;
    }

    public function listCustomerTokens($user)
    {
        return $this->get(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'customerProfileId' => md5($user->id),
                'signature' => hash('sha256', $this->merchantCode . md5($user->id) . $this->securityKey),
            ]
        );
    }

    public function deleteCardToken($user)
    {
        $result = $this->delete(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'customerProfileId' => md5($user->id),
                'signature' => hash(
                    'sha256',
                    $this->merchantCode .
                    md5($user->id) .
                    $user->payment_card_fawry_token .
                    $this->securityKey
                )
            ]
        );

        if ($result->statusCode == 200) {
            $user->update([
                'payment_card_last_four' => null,
                'payment_card_brand' => null,
                'payment_card_fawry_token' => null,
            ]);
        }

        return $result;
    }

    public function chargeViaCard($merchantRefNum, $user, $amount, $chargeItems = [], $description = null, $cardToken)
    {
        return $this->post(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'paymentMethod' => 'CARD',
                'cardToken' => $cardToken,
                'customerProfileId' => md5($user->id),
                'customerMobile' => $user->mobile,
                'customerEmail' => $user->email,
                'amount' => $amount,
                'currencyCode' => 'EGP',
                'chargeItems' => $chargeItems,
                'description' => $description,
                'signature' => hash(
                    'sha256',
                    $this->merchantCode .
                    $merchantRefNum .
                    md5($user->id) .
                    'CARD' .
                    (float)$amount .
                    $cardToken .
                    $this->securityKey
                )
            ]
        );
    }

    public function chargeViaFawry($merchantRefNum, $user, $paymentExpiry, $amount, $chargeItems = [], $description = null)
    {
        return $this->post(
            $this->endpoint("payments/charge"), [
                [
                    'merchantCode' => $this->merchantCode,
                    'merchantRefNum' => $merchantRefNum,
                    'paymentMethod' => 'PAYATFAWRY',
                    'paymentExpiry' => $paymentExpiry,
                    'customerProfileId' => md5($user->id),
                    'customerMobile' => $user->phone,
                    'customerEmail' => $user->email,
                    'amount' => $amount,
                    'currencyCode' => 'EGP',
                    'chargeItems' => $chargeItems,
                    'description' => $description,
                    'signature' => hash(
                        'sha256',
                        $this->merchantCode .
                        $merchantRefNum .
                        md5($user->id) .
                        'PAYATFAWRY' .
                        (float)$amount .
                        $this->securityKey
                    )
                ]
            ]
        );
    }

    public function refund($fawryRefNumber, $refundAmount, $reason = null)
    {
        return $this->post(
            $this->endpoint("payments/refund"), [
                'merchantCode' => $this->merchantCode,
                'referenceNumber' => $fawryRefNumber,
                'refundAmount' => $refundAmount,
                'reason' => $reason,
                'signature' => hash(
                    'sha256',
                    $this->merchantCode .
                    $fawryRefNumber .
                    number_format((float)$refundAmount, 2) .
                    $this->securityKey
                )
            ]
        );
    }

    public function get($url, $data)
    {
        $params = '';
        foreach ($data as $key => $value)
            $params .= $key . '=' . $value . '&';

        $params = trim($params, '&');

        $ch = curl_init($url . "?" . $params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }

    public function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)))
        );

        return json_decode(curl_exec($ch));
    }

    public function delete($url, $data)
    {
        $params = '';
        foreach ($data as $key => $value)
            $params .= $key . '=' . $value . '&';

        $params = trim($params, '&');

        $ch = curl_init($url . "?" . $params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }
}

class FawryPaymentController extends Controller
{

    public function index()
    {
        return view('web-views.payment-view-fawry');
    }

    public function payment(Request $request)
    {
        $obj = new Fawry();
        $user = Helpers::get_customer();
        $is_success = false;
        $result = $obj->createCardToken($request['card_number'], $request['year'], $request['month'], $request['cvv'], $user);
        if ($result->statusCode == 200) {
            $reference_number = OrderManager::gen_unique_id();
            $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
            $value = CartManager::cart_grand_total() - $discount;
            $payment = $obj->chargeViaCard($reference_number, $user, $value, [], 'product purchase', $result->card->token);
            if ($payment->statusCode == 200) {
                $is_success = true;
                $unique_id = OrderManager::gen_unique_id();
                $order_ids = [];
                foreach (CartManager::get_cart_group_ids() as $group_id) {
                    $data = [
                        'payment_method' => 'fawry_pay',
                        'order_status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_ref' => $payment->referenceNumber,
                        'order_group_id' => $unique_id,
                        'cart_group_id' => $group_id
                    ];
                    $order_id = OrderManager::generate_order($data);
                    array_push($order_ids, $order_id);
                }
            }
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            if ($is_success == true) {
                CartManager::cart_clean();
                return redirect()->route('payment-success');
            } else {
                return redirect()->route('payment-fail');
            }
        } else {
            if ($is_success == true) {
                CartManager::cart_clean();
                return view('web-views.checkout-complete');
            } else {
                Toastr::error('Payment failed!');
                return back();
            }
        }
    }

}
