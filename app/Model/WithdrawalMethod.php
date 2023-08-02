<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_name',
        'method_fields',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'method_fields' => 'array',
    ];

    protected function scopeOfStatus($query, $status)
    {
        $query->where('is_active', $status);
    }
}
