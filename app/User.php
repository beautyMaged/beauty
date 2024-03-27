<?php

namespace App;

use App\Model\Order;
use App\Model\ShippingAddress;
use App\Model\Wishlist;
use App\Model\CustomerNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Model\Seller;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f_name', 'l_name', 'name', 'email', 'password', 'phone', 'image', 'login_medium','is_active','social_id','is_phone_verified','temporary_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'integer',
        'is_phone_verified'=>'integer',
        'is_email_verified' => 'integer',
        'wallet_balance'=>'float',
        'loyalty_point'=>'float'
    ];

    public function wish_list()
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class, 'customer_id');
    }
    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function followedSellers(){
        return $this->belongsToMany(Seller::class);
    }

}
