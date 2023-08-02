<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $casts = [
        'status'        => 'integer',
        'active_status' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    protected $table = 'social_medias';
}
