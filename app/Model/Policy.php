<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    public function sellers(){
        return $this->belongsToMany(Seller::class)->withPivot('note', 'status');
    }
}
