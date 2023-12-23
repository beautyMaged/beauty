<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'min_purchase'  => 'float',
        'max_discount'  => 'float',
        'discount'      => 'float',
        'status'        => 'integer',
        'start_at'      => 'datetime',
        'end_at'        => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    protected $guarded = ['id'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('state');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withPivot('state');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class)->withPivot('state');
    }

    public function scopeActive($query, $user = null)
    {
        return $query
            ->where('status', 1)
            ->where('end_at', '>', DB::raw('NOW()'))
            ->where('start_at', '<', DB::raw('NOW()'))
            ->whereHas('orderDetails', function ($orders) {
                $orders->groupBy('coupon_id')
                    ->havingRaw('COUNT(*) < coupons.limit_all');
            })
            ->when($user, function ($coupon) use ($user) {
                $coupon->whereHas('orderDetails.order', function ($order) use ($user) {
                    $order
                        ->where('customer_id', $user->id)
                        ->groupBy('coupon_id')
                        ->havingRaw('COUNT(*) < coupons.limit_once');
                });
            });
    }
}
