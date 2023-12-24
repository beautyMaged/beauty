<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $casts = [
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'title',
        'description',
        'notification_count',
        'image',
        'status',
        'url',
        'customer_id',
        'seller_id'	
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
}
