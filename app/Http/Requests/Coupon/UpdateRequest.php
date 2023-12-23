<?php

namespace App\Http\Requests\Coupon;

use Carbon\Carbon;
use App\CPU\Convert;
use function App\CPU\translate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
    public function rules()
    {
        return [
            'id' => ['required', Rule::exists('coupons')],
            'title' => ['required', 'max:191'],
            'code' => ['required', Rule::unique('coupons')->ignore($this->id)],
            'discount_type' => ['required', Rule::in(["amount", "percentage"])],
            'discount' => ['required', 'numeric'],
            'max_discount' => ['required', 'numeric'],
            'free_delivery' => ['required', Rule::in(["true", "false"])],
            'exclude_discounted' => ['required', Rule::in(["true", "false"])],
            'limit_once' => ['required', 'integer'],
            'limit_all' => ['required', 'integer'],
            'min_purchase' => ['required', 'numeric'],
            'start_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'end_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'payment_methods' => ['required', 'array'],
            'payment_methods.*' => [
                'required',
                Rule::in([
                    "cash_on_delivery",
                    "credit_card",
                    "paypal",
                    "mada",
                    "bank_transfer",
                    "apple_pay",
                    "bank_transfer",
                ])
            ],
            'categories' => ['array'],
            'categories.*' => ['integer', Rule::exists('categories', 'id')],
            'products' => ['array'],
            'products.*' => ['integer', Rule::exists('products', 'id')],
            'brands' => ['array'],
            'brands.*' => ['integer', Rule::exists('brands', 'id')],
            'excluded_brands' => ['array'],
            'excluded_brands.*' => ['integer', Rule::exists('brands', 'id')],
            'excluded_categories' => ['array'],
            'excluded_categories.*' => ['integer', Rule::exists('categories', 'id')],
            'excluded_products' => ['array'],
            'excluded_products.*' => ['integer', Rule::exists('products', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => translate('coupon_not_found'),
            'limit_once.required' => translate('limit_for_same_user_is_required!'),
            'discount_type.required' => translate('discount_type_is_required!'),
            'discount.required' => translate('discount_amount_is_required!'),
            'min_purchase.required' => translate('minimum_purchase_is_required!'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->discount_type == 'amount' && $this->discount > $this->min_purchase) {
                    $validator->errors()->add(
                        'min_purchase',
                        'The minimum purchase amount must be greater than discount amount!'
                    );
                }
            }
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'seller_id' => auth()->user()->id,
            'start_at' => Carbon::parse($this->start_at)->format('Y-m-d\TH:i'),
            'end_at' => Carbon::parse($this->end_at)->format('Y-m-d\TH:i'),
            'min_purchase' => Convert::usd($this->min_purchase),
            'discount' => $this->discount_type == 'amount' ? Convert::usd($this->discount) : $this->discount,
            'max_discount' => Convert::usd($this->max_discount),
            'payment_methods' => join(',', $this->payment_methods),
        ]);
        $this->mergeIfMissing([
            'products' => [],
            'categories' => [],
            'brands' => [],
            'excluded_products' => [],
            'excluded_categories' => [],
            'excluded_brands' => [],
        ]);
        $this->replace($this->only([
            'seller_id',
            'title',
            'code',
            'discount_type',
            'discount',
            'max_discount',
            'free_delivery',
            'exclude_discounted',
            'limit_once',
            'limit_all',
            'min_purchase',
            'start_at',
            'end_at',
            'payment_methods',
            'categories',
            'products',
            'brands',
            'excluded_brands',
            'excluded_categories',
            'excluded_products',
        ]));
    }
}
