<?php

namespace App\Model;
use App\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'URL',
        'type',
        'status',
        'customer_id'
    ];
    public function customer(){
        return $this->belongsTo(User::class);
    }
}
