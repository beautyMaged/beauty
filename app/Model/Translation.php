<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;
    protected $table = 'translations';

    protected $fillable = [
        'translationable_type',
        'translationable_id',
        'locale',
        'key',
        'value',
    ];

    public function translationable()
    {
        return $this->morphTo();
    }
}
