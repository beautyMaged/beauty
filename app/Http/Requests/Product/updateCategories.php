<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class updateCategories extends FormRequest
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
            'category_id' => 'required|integer',
            'sub_category_id' => 'integer|nullable',
            'sub_sub_category_id' => 'integer|nullable',
        ];
    }
}
