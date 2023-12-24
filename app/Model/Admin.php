<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }

    public function categories()
    {
        // belongsToMany through hasMany
        return Category::whereHas('products', fn ($query) => $query->where('user_id', $this->id))->get();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id')->where(['added_by' => 'seller']);
    }
    public function notifications(){
        return $this->hasMany(AdminNotification::class);
    }
}
