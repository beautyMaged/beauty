<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'f_name' => 'required|min:2',
            'l_name' => 'required|min:2',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 


        ];
    }

    // /**
    //  * Get the error messages for the defined validation rules.
    //  *
    //  * @return array
    // //  */
    public function messages()
    {
        
        return [
            // email
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            // first name
            'f_name.required' => 'The name field is required.',
            'f_name.min' => 'The name must be at least :min characters.',
            // last name
            'l_name.required' => 'The name field is required.',
            'l_name.min' => 'The name must be at least :min characters.',
            // phone
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'phone.min' => 'The phone number must be at least :min digits.',
            // image
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be of type: :mimes.',
            'image.max' => 'The image size must not exceed :max kilobytes.',
        ];
    }
}
