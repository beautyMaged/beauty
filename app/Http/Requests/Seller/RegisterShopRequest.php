<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class RegisterShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trade_name' => 'required|string',

            'e_trade_name' => 'required|string',

            'type' => 'required|in:company,organization,individual,local_seller,other',

            'platform' => 'required|in:shopify,salla,zid,other',

            'image' => 'required|mimes:jpg,jpeg,png,gif',

            'banner' => 'required|mimes:jpg,jpeg,png,gif',

            'commercial_record' => 'numeric|required_without:trade_gov_no',

            'trade_gov_no' => 'numeric|required_without:commercial_record',

            'auth_authority' => 'in:maroof,SBC',

            'AUTH_no' => 'required_with:auth_authority',

            'tax_no' => 'numeric',

            'city_id' => 'required|exists:cities,id',

            'country_id' => 'required|exists:countries,id',
            
            // branches
            'branches.*.name' => 'required|string',
            'branches.*.city_id' => 'required|numeric|exists:cities,id',
            'branches.*.district_id' =>'required|numeric|exists:districts,id',
            'branches.*.description' => 'required|string',
            'branches.*.longitude' =>'required|numeric',
            'branches.*.latitude' =>'required|numeric',
            'branches.*.phone' => 'required|string',
            'branches.*.street' => 'required|string',
            'branches.*.times.*.opening_time' => 'date',
            'branches.*.times.*.closing_time' => 'date',
            'branches.*.times.*.day' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday',

            // connection information
            'connections.*.name' => 'required|string',
            'connections.*.email' => 'required|email',
            'connections.*.phone' => 'required|string',
            'connections.*.role' => 'required|in:management,sales,finantial,customer_service',

            // agencies
            'agency' => 'required_without:manufacturer',
            'agency.name' => 'required_without:manufacturer|string',
            'agency.logo' => 'required_without:manufacturer|mimes:jpg,jpeg,png,gif',
            'agency.country_id' => 'required_without:manufacturer|numeric|exists:countries,id',
            'agency.category_id' => 'required_without:manufacturer|numeric|exists:categories,id',

            // manufacturer
            'manufacturer' => 'required_without:agency',
            'manufacturer.name' => 'required_without:agency|string',
            'manufacturer.logo' => 'required_without:agency|mimes:jpg,jpeg,png,gif',
            'manufacturer.country_id' => 'required_without:agency|numeric|exists:countries,id',
            'manufacturer.category_id' => 'required_without:agency|numeric|exists:categories,id',

            // policies
            'policies' => 'array|required|size:'. $this->nOfPolicies(),
            'policies.*.policy_id' => 'required|numeric|exists:policies,id',
            'policies.*.status' => 'required|boolean',
            'policies.*.note' => 'string',

            // refund policies
            'refund_policy.refund_max' => 'required|numeric|min:0',
            'refund_policy.substitution_max' => 'required|numeric|min:0',
            'refund_policy.days_to_refund_before_reception' => 'required|numeric|min:0',
            'refund_policy.min_days_to_refund' => 'required|numeric|min:0',
            'refund_policy.max_days_to_refund' => 'required|numeric|min:0',

            // fast deliveries
            'fast_deliveries.*.city_id' => 'required|exists:cities,id',
            'fast_deliveries.*.cost' => 'required|numeric',
            'fast_deliveries.*.note' => 'string',

            // new policies
            'new_policies.*.name' => 'required|string',
            'new_policies.*.description' => 'required|',
            'new_policies.*.type' => 'required|in:refund,delivery,common_questions,copyright,product_view,other',
            'new_policies.*.status' => 'required|boolean',
            'new_policies.*.note' => 'string',

            // shop repositories
            'shop_repository' => 'array',
            'shop_repository.country_id' => 'required_with:shop_repository|exists:countries,id',
            'shop_repository.city_id'  => 'required_with:shop_repository|exists:cities,id',
            'shop_repository.opening_time' => 'required_with:shop_repository|date',
            'shop_repository.closing_time' => 'required_with:shop_repository|date',
            'shop_repository.friday_opening_time' => 'required_with:shop_repository|date',
            'shop_repository.friday_closing_time' => 'required_with:shop_repository|date',
            'shop_repository.longitude' => 'required_with:shop_repository|numeric',
            'shop_repository.latitude' => 'required_with:shop_repository|numeric',

            // seller badges
            'badges' => 'array',
            'badges.*.title' => 'required_with:badges|string',
            'badges.*.note' => 'required_with:badges|string',
            'badges.*.icon' => 'required_with:badges|mimes:jpg,jpeg,png,gif',

            // delivery companies
            'delivery_companies' => 'array', 
            'delivery_companies.*.delivery_company_id' => 'required_with:delivery_companies|exists:delivery_companies,id', 
            'delivery_companies.*.main_cities_fees' => 'required_with:delivery_companies|numeric', 
            'delivery_companies.*.towns_fees' => 'required_with:delivery_companies|numeric', 
            'delivery_companies.*.vilages_fees' => 'required_with:delivery_companies|numeric', 
            'delivery_companies.*.link' => 'required_with:delivery_companies|string',

            
            // 48 hour delivery details for each city
            'fast_deliveries' => 'array', 
            'fast_deliveries.*.city_id' => 'required_with:fast_deliveries|exists:cities,id', 
            'fast_deliveries.*.cost' => 'required_with:fast_deliveries|numeric', 
            'fast_deliveries.*.note' => 'required_with:fast_deliveries|string',
            
            // 24 hour delivery details for each city
            'one_day_deliveries' => 'array', 
            'one_day_deliveries.*.city_id' => 'required_with:one_day_deliveries|exists:cities,id', 
            'one_day_deliveries.*.cost' => 'required_with:one_day_deliveries|numeric', 
            'one_day_deliveries.*.note' => 'required_with:one_day_deliveries|string',

            // refund policy
            'refund_policy' => 'array|required',
            'refund_policy.refund_max' => 'required|integer|max:100|min:0',
            'refund_policy.substitution_max' => 'required|integer|max:100|min:0',
            'refund_policy.days_to_refund_before_reception' => 'required|integer|max:100|min:0',
            // refund duration for the whole process
            'refund_policy.min_days_to_refund' => 'required|integer|max:100|min:0',
            'refund_policy.max_days_to_refund'=> 'required|integer|max:100|min:0',






        ];
    }


    private function nOfPolicies(){
        return DB::table('policies')->where('is_approved', '1')->where('is_global', '1')
        ->count();
    }
}
