<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class NewShippingAddressRequest extends FormRequest
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
            'is_billing' => 'sometimes|nullable|boolean',
            'zip' => 'required|nullable|numeric',
            'apartment_number' => 'required|nullable|numeric',
            'head_country' => 'required|string',
            'head_city' => 'required|string',
            'head_address' => 'required|string',
            'head_new_lat' => 'required|numeric',
            'head_new_long' => 'required|numeric',
            'default_address'=>'required|nullable|boolean'
        ];
    }
    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'is_billing.boolean' => 'The is billing field must be a boolean.',
            'zip.required' => 'The zip field is required.',
            'zip.string' => 'The zip field must be a string.',
            'appartment_number.required' => 'The appartment number field is required.',
            'appartment_number.string' => 'The appartment number field must be a string.',
            'country.required' => 'The country field is required.',
            'country.string' => 'The country field must be a string.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city field must be a string.',
            'street_address.required' => 'The street address field is required.',
            'street_address.string' => 'The street address field must be a string.',
            'latitude.required' => 'The latitude field is required.',
            'latitude.string' => 'The latitude field must be a string.',
            'latitude.numeric' => 'The latitude field must be a numeric value.',
            'longitude.required' => 'The longitude field is required.',
            'longitude.string' => 'The longitude field must be a string.',
            'longitude.numeric' => 'The longitude field must be a numeric value.',
            'default_address.boolean' => 'The default address field must be a boolean.',
        ];
    }
}
