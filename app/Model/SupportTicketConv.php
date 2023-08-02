<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SupportTicketConv extends Model
{
    protected $casts = [
        'support_ticket_id' => 'integer',
        'admin_id'          => 'integer',
        'position'          => 'integer',

        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
