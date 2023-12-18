<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['product_id', 'type', 'name'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function values(){
        return $this->hasMany(Value::class);
    }
}
