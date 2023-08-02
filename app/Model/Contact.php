<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $casts = [
        'seen'       => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'subject',
        'message',
    ];
}
