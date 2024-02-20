<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $casts = [
        'seller_id ' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        });
    }

    public function token()
    {
        switch ($this->platform) {
            case 'salla':
                return $this->hasOne(SallaOauthToken::class);
            case 'zid':
                return $this->hasOne(ZidOauthToken::class);
            case 'shopify':
                return $this->hasOne(ShopifyOauthToken::class);
        }
    }

    public function agency(){
        return $this->hasOne(Agency::class);
    }

    public function manufacturer(){
        return $this->hasOne(Manufacturer::class);
    }

    public function branches(){
        return $this->hasMany(Branch::class);
    }

    public function connections(){
        return $this->hasMany(Connection::class);
    }

    public function deliveryCompanies()
    {
        return $this->belongsToMany(DeliveryCompany::class);
    }

    public function badges(){
        return $this->hasMany(Badge::class);
    }

    public function shopRepository()
    {
        return $this->hasOne(ShopRepository::class);
    }
}
