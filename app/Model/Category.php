<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    // use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'parent_id' => 'integer',
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'home_status' => 'integer',
        'priority' => 'integer',

        // 'category_ids' => 'json'
    ];
    protected $fillable = ['category_commission'];

    // public function products()
    // {
    //     //return Product::WhereRaw("json_unquote(json_extract(category_ids, '$[0].id')) = ?", [$this->id])->get();
    //     return $this->hasManyJson(Product::class, "category_ids->ids[]->id");
    // }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->orderBy('priority', 'desc');
    }

    public function childes()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('priority', 'desc');
    }

    public function getNameAttribute($name)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller'))
            return $name;
        return $this->translations[0]->value ?? $name;
    }

    public function scopePriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('top_rated', 'top_rated_globally','best_selling','best_selling_globally');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api'))
                    return $query->where('locale', App::getLocale());
                return $query->select('value', 'translationable_id')->where('locale', Helpers::default_lang());
            }]);
        });
    }
    public function sellersCommissions(){
        return $this->hasMany(SellerCategoryCommission::class);
    }
    public function options(){
        return $this->hasMany(Option::class);
    }

    public function agencies(){
        return $this->hasMany(Agency::class);
    }

    public function manufacturers(){
        return $this->hasMany(Manufacturer::class);
    }

    public function sellers(){
        return $this->belongsToMany(Seller::class);
    }
}
