<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPolicy extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'shipping_min',
        'shipping_max',
        'refund_max',
        'substitution_max',
    ];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
