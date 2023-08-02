<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'order_id' => 'integer',
        'payment_for'=>'string',
        'payer_id' => 'integer',
        'payment_receiver_id' => 'integer',
        'paid_by'=>'string',
        'paid_to'=>'string',
        'payment_method'=>'string',
        'payment_status'=>'string',
        'order_details_id' => 'integer',
        'amount' => 'float',
        'transaction_type'=>'string',
        'refund_id'=>'string'

    ];

    public function order_details(){
        return $this->belongsTo(OrderDetail::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
