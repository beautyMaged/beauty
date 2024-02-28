<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sellers()
    {
        return $this->belongsToMany(Seller::class);
    }
    
    public function shippingDurations()
    {
        return $this->hasMany(ShippingDuration::class);
    }
}
