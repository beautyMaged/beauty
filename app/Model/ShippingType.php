<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingType extends Model
{
    use HasFactory;

    protected $casts = [
        'seller_id' => 'integer',
        'shipping_type' => 'string',
    ];
    
}
