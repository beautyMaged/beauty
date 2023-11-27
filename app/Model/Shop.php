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
}
