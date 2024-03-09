<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonQuestionRequest extends FormRequest
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
            'for' => 'required|in:sellers,customers',
            'type' => 'required|in:orders,delivery,payment,refund,products,general',
            'question' => 'required|string',
            'answer' => 'required|string',
        ];
    }
}
