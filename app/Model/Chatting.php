<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Chatting extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'status' => 'integer',
        'seller_id' => 'integer',
        'sent_by_customer' => 'integer',
        'sent_by_seller' => 'integer',
        'seen_by_customer' => 'integer',
        'seen_by_seller' => 'integer',
        'shop_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $guarded=[];

    public function seller_info()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
