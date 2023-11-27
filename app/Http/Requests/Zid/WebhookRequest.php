<?php

namespace App\Http\Requests\Zid;

use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
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

    public function rules()
    {
        return [
            // 'event'    => ['required'],
            // 'merchant' => ['required'],
            // 'data'     => ['required'],
        ];
    }
}
