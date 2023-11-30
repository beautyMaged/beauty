<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;

    protected $casts = [
        'published'  => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
