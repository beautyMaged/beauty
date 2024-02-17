<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPolicy extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'refund_max',
        'substitution_max',
    ];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
