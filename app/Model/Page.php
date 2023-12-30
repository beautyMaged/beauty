<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = [];
    public function category(){
        return $this->belongsTo(PageCategory::class);
    }
}
