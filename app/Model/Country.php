<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable=['name', 'code'];

    public function sellers(){
        return $this->hasMany(Seller::class);
    }

    public function agencies(){
        return $this->hasMany(Agency::class);
    }

    public function manufacturers(){
        return $this->hasMany(Manufacturer::class);
    }

    public function cities(){
        return $this->hasMany(City::class);
    }

    public function sellerRepositories(){
        return $this->hasMany(SellerRepository::class);
    }
}
