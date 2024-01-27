<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductName extends Model
{
    use HasFactory;
    public function products(){
        return $this->hasMany(Product::class,'name_id','id');
    }
}
