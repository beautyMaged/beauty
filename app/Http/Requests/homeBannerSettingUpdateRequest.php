<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class homeBannerSettingUpdateRequest extends FormRequest
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
            'title_o' => 'required',
            'title_t' => 'required',
            'description_o' => 'required',
            'description_t' => 'required',
//            'image_o' => 'required',
//            'image_t' => 'required',
        ];
    }
}
