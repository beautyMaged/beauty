<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $casts = [
        'customer_id' => 'integer',
        'status' => 'string',

        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function conversations()
    {
        return $this->hasMany(SupportTicketConv::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
