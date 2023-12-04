<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        $positions = [];
        foreach (config('services.banner.positions') as $place => $position)
            $positions[$place . '_banner_position'] = Rule::in(array_map(fn ($p) => $p['name'], $position));
        return array_merge($positions, [
            'title' => ['string'],
            'description' => ['string'],
            'resource_type' => ['required', Rule::in(['home', 'category'])],
            'start_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'end_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'target_type' => ['required', 'in:all,products,home'],
            'target' => ['array'],
            'target.*' => ['integer'],
            'image' => ['mimes:png,jpeg,jpg,gif,svg'],
        ]);
    }
}
