<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryManTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_man_id', 'user_id', 'user_type', 'debit', 'transaction_id', 'credit', 'transaction_type'];
}
