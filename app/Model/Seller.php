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
    public $fillable = ['FullManagerName',
    'email',
    'ManagerEmail',
    'ManagerTel',
    'agreed',
    'allCategoriesCount',
    'bestSellingCat',
    'bestSellingProduct',
    'brandName',
    'categoriesCount',
    'categoriesNames',
    'compBranches',
    'compCustomerServiceEmail',
    'compCustomerServiceNum',
    'fieldOfInterest',
    'fillerTel',
    'fullFillerEmail',
    'fullFillerName',
    'q_data',
    'iban',
    'onlineTradeLicenes',
    'productsCount',
    'productsOrigin',
    'taxRecord',
    'tradeRecord',
    'storeLink',
    'storeLocation',
    'storeName',
    'subCategoriesCount',
    'taxNum',
    'tradeNumber',
    'validationNum',
    'password',
    'phone'];

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

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id')->where(['added_by' => 'seller']);
    }

    // replaced with another one
    // public function categories()
    // {
    //     // belongsToMany through hasMany
    //     return Category::whereHas('products', fn ($query) => $query->where('user_id', $this->id))->get();
    // }

    public function brands()
    {
        // belongsToMany through hasMany
        return Brand::whereHas('products', fn ($query) => $query->where('user_id', $this->id))->get();
    }

    public function wallet()
    {
        return $this->hasOne(SellerWallet::class);
    }
    public function refundPolicy(){
        return $this->hasOne(RefundPolicy::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
    
    public function notifications(){
        return $this->hasMany(SellerNotification::class);
    }
    public function categoryCommissions(){
        return $this->hasMany(SellerCategoryCommission::class);
    }

    public function bestProducts(){
        return $this->hasManyThrough(BestProduct::class,Product::class);
    }





    public function categories(){
        return $this->belongsToMany(Category::class);
    }



    public function shippingDurations()
    {
        return $this->hasMany(ShippingDuration::class);
    }
    
    public function fastDeliveries()
    {
        return $this->hasMany(FastDelivery::class);
    }

    public function policies()
    {
        return $this->belongsToMany(Policy::class)->withPivot('note', 'status');
    }


}
