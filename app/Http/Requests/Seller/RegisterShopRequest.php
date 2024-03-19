<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

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
            'policies.*.policy_id' => 'required|numeric|exists:countries,id',
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
            'shop_repository.country_id' => 'required|in:countries.id',
            'shop_repository.city_id'  => 'required|in:cities.id',
            'shop_repository.opening_time' => 'required|date',
            'shop_repository.closing_time' => 'required|date',
            'shop_repository.friday_opening_time' => 'required|date',
            'shop_repository.friday_closing_time' => 'required|date',
            'shop_repository.longitude' => 'required|numeric',
            'shop_repository.latitude' => 'required|numeric',

            // seller badges
            'badges.*.title' => 'required|string',
            'badges.*.note' => 'required|string',
            'badges.*.icon' => 'required|mimes:jpg,jpeg,png,gif'



        ];
    }
}
