<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastDelivery extends Model
{
    use HasFactory;

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }


}
