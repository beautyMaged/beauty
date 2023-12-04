<?php

namespace App\Services;

use App\CPU\Convert;


class CouponService
{
    public $request;
    public function merge($model)
    {
        $model_ids = [];
        for ($i = 0; $i < max(count($this->request->{$model}), count($this->request->{"excluded_{$model}"})); $i++) {
            if (isset($this->request->{$model}[$i]))
                $model_ids[$this->request->{$model}[$i]] = ['state' => 'included'];
            if (isset($this->request->{"excluded_{$model}"}[$i]))
                $model_ids[$this->request->{"excluded_{$model}"}[$i]] = ['state' => 'excluded'];
        }
        return $model_ids;
    }
    public function parse($coupon)
    {
        $coupon->min_purchase = Convert::default($coupon->min_purchase);
        $coupon->discount = $coupon->discount_type == 'amount' ? Convert::default($coupon->discount) : $coupon->discount;
        $coupon->max_discount = Convert::default($coupon->max_discount);
        $coupon->payment_methods = explode(',', $coupon->payment_methods);
        $coupon->categories = $coupon->categories->map(fn ($category) => [$category->id, $category->pivot->state]);
        $coupon->products = $coupon->products->map(fn ($product) => [$product->id, $product->pivot->state]);
        $coupon->brands = $coupon->brands->map(fn ($brand) => [$brand->id, $brand->pivot->state]);
        return $coupon;
    }
}
