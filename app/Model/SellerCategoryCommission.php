<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCategoryCommission extends Model
{
    use HasFactory;
    protected $table = 'sellers_categories_commissions';
    protected $guarded = [];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
