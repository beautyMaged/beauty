<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable=['name', 'symbol'];

    public function sellers(){
        return $this->hasMany(Seller::class);
    }
}
