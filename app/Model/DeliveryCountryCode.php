<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCountryCode extends Model
{
    use HasFactory;
    protected $fillable = ['country_code'];

}
