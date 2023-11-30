<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'title' => ['string'],
            'description' => ['string'],
            'resource_type' => ['required', 'in:home,category'],
            'banner_type' => ['required', Rule::in([
                'Main Banner',
                'Footer Banner',
                'Popup Banner'
            ])],
            'start_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'end_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'target_type' => ['required', 'in:all,products,home'],
            'target' => ['array'],
            'target.*' => ['integer'],
            'image' => ['mimes:png,jpeg,jpg,gif,svg'],
        ];
    }
}
