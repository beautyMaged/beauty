<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    protected $casts = [
        'inhouse_earning' => 'float',
        'commission_earned' => 'float',
        'pending_amount' => 'float',
        'delivery_charge_earned' => 'float',
        'collected_cash' => 'float',
        'total_tax_collected' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
