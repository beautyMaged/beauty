<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'discount'     => 'float',
        'status'       => 'integer',
        'start_date'   => 'date',
        'expire_date'  => 'date',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function order(){
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }
}
