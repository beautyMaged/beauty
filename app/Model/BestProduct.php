<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestProduct extends Model
{
    use HasFactory;
    protected $guarded = []; 

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function seller(){
        return $this->hasOneThrough(Seller::class, Product::class);
    }
}
