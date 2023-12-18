<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RefundRequestRequest extends FormRequest
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
            'order_details_id' => 'required|numeric',
            'refund_reason' => 'required',
            'refund_request_reason'=>[
                'required',
                Rule::in(["different","expired","dislike","not_ordered","other"])],
            'bill_image' => 'required|image|mimes:jpeg,png,jpg,gif', 

        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'order_details_id.required' => 'The order details ID field is required.',
            'order_details_id.numeric' => 'The order details ID must be a number.',
            'refund_reason.required' => 'The refund reason field is required.',
            'refund_request_reason.required' => 'The refund request reason field is required.',
            'refund_request_reason.numeric' => 'The refund request reason must be a number.',
            'bill_image.required' => 'The bill image field is required.',
            'bill_image.image' => 'The file must be an image.',
            'bill_image.mimes' => 'The image must be of type: jpeg, png, jpg, gif.',
        ];
    }
}
