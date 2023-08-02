<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
