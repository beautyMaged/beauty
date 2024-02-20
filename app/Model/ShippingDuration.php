<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDuration extends Model
{
    use HasFactory;

    protected $table = 'delivery_company_shop';

    public function shop()
    {
        return $this->belongsTo(Seller::class);
    }

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
}
