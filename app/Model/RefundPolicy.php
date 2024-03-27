<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPolicy extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
