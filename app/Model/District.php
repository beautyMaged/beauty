<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $guarded = [];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function branches(){
        return $this->hasMany(Branch::class);
    }
}
