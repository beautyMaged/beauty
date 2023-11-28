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
        'resource_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'resource_id')->where('resource_type', 'product');;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
