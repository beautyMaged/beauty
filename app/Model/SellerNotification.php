<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'URL',
        'type',
        'status',
        'seller_id'
    ];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
