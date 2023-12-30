<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pages(){
        return $this->hasMany(Page::class);
    }
}
