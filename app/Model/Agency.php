<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
