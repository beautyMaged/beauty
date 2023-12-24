<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'URL',
        'type',
        'status',
        'admin_id'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
