<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function city(){
        return $this->belongsTo(City::class);

    }

    public function times(){
        return $this->hasMany(Time::class);

    }

}
