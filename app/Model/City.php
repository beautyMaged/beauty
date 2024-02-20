<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function sellers(){
        return $this->belongsTo(Seller::class);
    }

    public function shops(){
        return $this->belongsTo(Shop::class);
    }

    public function fastDeliveries()
    {
        return $this->hasMany(FastDelivery::class);
    }

    public function sellerRepositories(){
        return $this->hasMany(SellerRepository::class);
    }
}
