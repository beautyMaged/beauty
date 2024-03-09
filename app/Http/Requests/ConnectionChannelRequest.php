<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnectionChannelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,phone,social_media,url',
            'data' => 'required|string|max:255',
            'logo' => 'required|mimes:png,jpg,jpeg,webp',
            'time' => 'string|max:255',
            'response_after' => 'string|max:255',
        ];
    }
}
