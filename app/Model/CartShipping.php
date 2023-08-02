<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartShipping extends Model
{
    public function cart(){
        return $this->belongsTo(Cart::class,'cart_group_id','cart_group_id');
    }
}
