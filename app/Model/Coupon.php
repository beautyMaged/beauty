<?php

namespace App\Model;

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

    public function order()
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
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
}
