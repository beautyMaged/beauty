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
        'email' => 'required|email|unique:sellers,email',
        'f_name' => 'required|string',
        'l_name' => 'required',
        'phone' => 'required|numeric',
        'password' => 'required|min:8',
        'country_id' => 'required|exists:countries,id',
    ];
}
}
