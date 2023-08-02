<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryShippingCost extends Model
{
    use HasFactory;

    protected $casts = [
        'seller_id' => 'integer',
        'category_id' => 'integer',
        'cost'=>'float',
        'multiply_qty'=>'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
