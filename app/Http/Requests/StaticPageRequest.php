<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaticPageRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'image' => 'required_without:id|mimes:png,jpg,jpeg,webp',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'المحتوي مطلوب',
            'image.required' => 'الصورة مطلوبة',
            'image.mimes' => 'نوع الصورة غير صالح',
        ];
    }
}
