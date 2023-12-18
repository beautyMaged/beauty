<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        "price",
        "sale_price",
        "quantity",
        "sku"
    ];
    public function values(){
        return $this->belongsToMany(Value::class,'value_variant');
    }
    public function order_details(){
        return $this->hasMany(Orderdetail::class);
    }
}
