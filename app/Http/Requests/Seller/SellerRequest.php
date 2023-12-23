<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class SellerRequest extends FormRequest
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
        'image' => 'required|mimes:jpg,jpeg,png,gif',
        'logo' => 'required|mimes:jpg,jpeg,png,gif',
        'banner' => 'required|mimes:jpg,jpeg,png,gif',
        'ownerEmail' => 'required|email|unique:sellers,ManagerEmail',
        'FullOwnerName' => 'required|string',
        'l_name' => 'required',
        'ownerTel' => 'required|numeric',
        'password' => 'required|min:8',
        'platform' => 'required|in:shopify,salla,zid',
        'shipping_min' => 'required|integer|min:1',
        'shipping_max' => 'required|integer|min:1',
        'refund_max' => 'required|integer',
        'substitution_max' => 'required|integer',
        'productType' => 'required|string',
        'country' => 'required|integer',
        'FullManagerName' => 'required|string', 
        'ManagerEmail' => 'required|email',
        'ManagerTel' => 'required|string', 
        'agreed' => 'required|boolean',
        'allCategoriesCount' => 'required|integer',
        'bestSellingCat' => 'required|string',
        'bestSellingProduct' => 'required|string',
        'brandName' => 'required|string',
        'categoriesCount' => 'required|integer',
        'categoriesNames' => 'required|string',
        'compBranches' => 'required|string',
        'compCustomerServiceEmail' => 'required|email',
        'compCustomerServiceNum' => 'required|integer',
        'fieldOfInterest' => 'required|string',
        'fillerTel' => 'required|string',
        'fullFillerEmail' => 'required|email',
        'fullFillerName' => 'required|string',
        'q_data' => 'required|json',
        'iban' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'onlineTradeLicenes' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'productsCount' => 'required|integer',
        'taxRecord' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'tradeRecord' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'storeLink' => 'required|url',
        'storeLocation' => 'required|string',
        'storeName' => 'required|string',
        'subCategoriesCount' => 'required|integer',
        'taxNum' => 'required|integer',
        'tradeNumber' => 'required|integer',
        'validationNum' => 'required|integer',
    ];
}
}
