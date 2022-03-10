<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'price', 'compare_price', 'cost',
        'quantity', 'availability', 'status', 'image', 'sku', 'barcode'
    ];

    // protected $with = [
    //     'category'
    // ];

    protected static function booted()
    {
        static::observe(ProductObserver::class);
    }

    // Inverse One-to-Many: Product Belongs To Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
        return $this->belongsTo(Category::class);
    }

    // Many-to-Many: Product has many Tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
        return $this->belongsToMany(
            Tag::class,     // Related model
            'product_tag',  // Pivot table name
            'product_id',   // Current model FK in pivot table
            'tag_id',       // Related model FK in pivot table
            'id',           // Local (PK) current model
            'id'            // Local (PK) related model
        );
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function cartUsers()
    {
        return $this->belongsToMany(
            User::class,
            'carts',
            'product_id',
            'user_id',
            'id',
            'id'
        );
    }

    public static function statusOptions()
    {
        return [
            'active' => 'Active',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ];
    }

    public static function availabilities()
    {
        return [
            'in-stock' => 'In Stock',
            'out-of-stock' => 'Out of Stock',
            'back-order' => 'Back-Order'
        ];
    }

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

    public function getDiscountPercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return number_format(($this->compare_price - $this->price) / $this->compare_price * 100, 1);
    }

    public function getUrlAttribute()
    {
        return route('products.show', [$this->category->slug, $this->slug]);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(150)
              ->height(150);
    }
}
