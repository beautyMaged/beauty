<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'order_details_id' => 'integer',
        'customer_id' => 'integer',
        'status'=>'string',
        'amount' => 'float',
        'product_id' => 'integer',
        'order_id' => 'integer',
        'refund_reason'=>'string',
        'approved_note'=>'string',
        'rejected_note'=>'string',
        'payment_info'=>'string',
        'change_by'=>'string'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function order_details()
    {
        return $this->belongsTo(OrderDetail::class,'order_details_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function refund_status()
    {
        return $this->hasMany(RefundStatus::class,'refund_request_id');
    }
}
