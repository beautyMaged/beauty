<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $guarded = [];
    protected $casts = [
        'customer_id',
        'is_billing',
        'country',
        'city',
        'zip',
        'street_address',
        'appartment_number',
        'latitude',
        'longitude',
        'default_address'


    ];

    public function user(){
        return $this->belongsTo(User::class, 'customer_id');
    }
}
