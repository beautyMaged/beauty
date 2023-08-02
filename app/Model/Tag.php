<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];

    public function items()
    {
        return $this->belongsToMany(Product::class)->using(ProductTag::class);
    }
}
