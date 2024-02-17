<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Jobs\SyncProductsJob;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use App\Model\Cron;
use App\Model\RefundPolicy;
use App\Http\Requests\Seller\SellerRequest;

use function App\CPU\translate;

class RegisterController extends Controller
{
    public function create()
    {
        // $business_mode = Helpers::get_business_settings('business_mode');
        // $seller_registration = Helpers::get_business_settings('seller_registration');
        // if ((isset($business_mode) && $business_mode == 'single') || (isset($seller_registration) && $seller_registration == 0)) {
        //     Toastr::warning(translate('access_denied!!'));
        //     return redirect('/');
        // }
        return view('seller-views.auth.register');
    }

    public function store(SellerRequest $request)
    {
        // $this->validate($request, [
        //     'image' => 'required|mimes:jpg,jpeg,png,gif',
        //     'logo' => 'required|mimes:jpg,jpeg,png,gif',
        //     'banner' => 'required|mimes:jpg,jpeg,png,gif',
        //     'email' => 'required|unique:sellers',
        //     'shop_address' => 'required',
        //     'f_name' => 'required',
        //     'l_name' => 'required',
        //     'shop_name' => 'required',
        //     'phone' => 'required',
        //     'password' => 'required|min:8',
        //     'platform' => 'in:shopify,salla,zid',
        //     'shipping_min'=>'required|integer|min:1',
        //     'shipping_max'=>'required|integer|min:1',
        //     'refund_max'=>'required|integer',
        //     'substitution_max'=>'required|integer',
        //     'productType' => 'required|string',
        //     'country'=>'required|integer',
        // ]);
        if ($request['from_submit'] != 'admin') {
            //recaptcha validation
            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1) {
                try {
                    $request->validate([
                        'g-recaptcha-response' => [
                            function ($attribute, $value, $fail) {
                                $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                                $response = $value;
                                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                                $response = \file_get_contents($url);
                                $response = json_decode($response);
                                if (!$response->success) {
                                    $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                                }
                            },
                        ],
                    ]);
                } catch (\Exception $exception) {
                }
            } else {
                if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
                    Session::forget('default_captcha_code');
                    return back()->withErrors(\App\CPU\translate('Captcha Failed'));
                }
            }
        }
        
        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->FullOwnerName;
            $seller->l_name = $request->l_name;
            $seller->phone = $request->ownerTel;
            $seller->email = $request->ownerEmail;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status = $request->status == 'approved' ? 'approved' : "pending";
            $seller->country_id = $request->country ?? 1;
            // start new data
            $seller->FullManagerName = $request->FullManagerName;
            $seller->ManagerEmail = $request->ManagerEmail;
            $seller->ManagerTel = $request->ManagerTel;
            $seller->agreed = $request->agreed;
            $seller->allCategoriesCount = $request->allCategoriesCount;
            $seller->bestSellingCat = $request->bestSellingCat;
            $seller->bestSellingProduct = $request->bestSellingProduct;
            $seller->brandName = $request->brandName;
            $seller->categoriesCount = $request->categoriesCount;
            $seller->categoriesNames = $request->categoriesNames;
            $seller->compBranches = $request->compBranches;
            $seller->compCustomerServiceEmail = $request->compCustomerServiceEmail;
            $seller->compCustomerServiceNum = $request->compCustomerServiceNum;
            $seller->fieldOfInterest = $request->fieldOfInterest;
            $seller->fillerTel = $request->fillerTel;
            $seller->fullFillerEmail = $request->fullFillerEmail;
            $seller->fullFillerName = $request->fullFillerName;
            $seller->q_data = $request->q_data;
            $seller->productsCount = $request->productsCount;
            $seller->storeLink = $request->storeLink;
            $seller->storeLocation = $request->storeLocation;
            $seller->storeName = $request->storeName;
            $seller->subCategoriesCount = $request->subCategoriesCount;
            $seller->taxNum = $request->taxNum;
            $seller->tradeNumber = $request->tradeNumber;
            $seller->validationNum = $request->validationNum;
            // Upload other image files in the request (e.g., 'iban', 'onlineTradeLicenes', 'taxRecord', 'tradeRecord')
            $imageFields = ['iban', 'onlineTradeLicenes', 'taxRecord', 'tradeRecord'];
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $seller->{$field} = $request->file($field)->store('seller', 'public');
                }
            }
            // end new data
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->platform = $request->platform;
            $shop->name = $request->storeName;
            $shop->address = $request->storeLocation;
            $shop->contact = $request->ownerTel;
            $shop->image = ImageManager::upload('shop/', 'png', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'png', $request->file('banner'));
            $shop->save();

            $seller->refundPolicy()->create([
                'shipping_min' => $request->shipping_min,
                'shipping_max' => $request->shipping_max,
                'refund_max' => $request->refund_max,
                'substitution_max' => $request->substitution_max,
            ]);
        

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller->id,
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->{$request->platform}($request, $seller, $shop);
        });

        Toastr::success('Shop apply successfully!');

        // return $request->status == 'approved' ? back() : redirect()->route('seller.auth.login');
        return response()->json(['message'=>'Registered!'], 200);
        
    }
    private function shopify($request, $seller, $shop)
    {
        // DB::table('shop_rest_api')->insert([
        //     'shop_id' => $shop->id,
        //     'host' => $request->host,
        //     'access_token' => $request->api_access_token,
        //     'api_key' => $request->api_key,
        //     'api_secret' => $request->api_secret
        // ]);

        // SyncProductsJob::dispatch(
        //     $request->host,
        //     $request->api_access_token,
        //     $request->api_key,
        //     $request->api_secret,
        //     $seller->id
        // );
    }

    private function salla($request, $seller, $shop)
    {
        
    }

    private function zid($request, $seller, $shop)
    {
        
    }
}
