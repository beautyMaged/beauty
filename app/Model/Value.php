<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = ["option_id", "value"];

    public function option(){
        return $this->belongsTo(Option::class, 'option_id');
    }

    public function variants(){
        return $this->belongsToMany(Variant::class, 'value_variant');
    }
    
}
