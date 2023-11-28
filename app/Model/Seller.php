<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'id' => 'integer',
        'orders_count' => 'integer',
        'product_count' => 'integer',
        'poststatus' => 'integer'
    ];

    public function scopeApproved($query)
    {
        return $query->where(['status' => 'approved']);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'seller_id');
    }

    // public function shops()
    // {
    //     return $this->hasMany(Shop::class, 'seller_id');
    // }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id')->where(['added_by' => 'seller']);
    }

    public function categories()
    {
        // belongsToMany through hasMany
        return Category::whereHas('products', fn ($query) => $query->where('user_id', $this->id))->get();
    }

    public function wallet()
    {
        return $this->hasOne(SellerWallet::class);
    }
}
