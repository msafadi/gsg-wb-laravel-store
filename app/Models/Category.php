<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Scopes\MainCategoryScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'description', 'image',
    ];

    // protected $guarded = [];

    protected static function booted()
    {
        //static::addGlobalScope(new MainCategoryScope());

        // static::addGlobalScope('parent-name', function(Builder $builder) {
        //     $builder->leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
        //         ->select([
        //             'categories.*',
        //             'parents.name as parent_name'
        //         ]);
        // });
        static::forceDeleted(function($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        });

        static::saving(function($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function scopeSearch(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('categories.name', 'LIKE', "%{$value}%");
        }
    }

    public function scopeParent(Builder $builder, $parent_id)
    {
        $builder->where('categories.parent_id', '=', $parent_id);
    }

    // Accessors: get{NAME}Attribute
    // $category->image_url
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/blank.png');
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return Storage::disk('public')->url($this->image);
    }

    public function getNameAttribute($value)
    {
        return Str::title($value);
    }

    // Mutators: set{NAME}Atrribute
    // $category->name = 'Value';
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = Str::upper($value); // VALUE
    // }
}
