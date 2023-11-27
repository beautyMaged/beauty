<?php

namespace App\Model;

use App\Model\Shop;
use Illuminate\Database\Eloquent\Model;

class ShopifyOauthToken extends Model
{
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function hasExpired()
    {
        return now()->timestamp > $this->expires_in;
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
