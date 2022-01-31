<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'price', 'compare_price', 'cost',
        'quantity', 'availability', 'status', 'image', 'sku', 'barcode'
    ];

    // protected $with = [
    //     'category'
    // ];

    protected static function booted()
    {
        /*
        creating, created, updating, updated, saving, saved
        deleting, deleted, restoring, resotred, forceDeleting, forceDeleted
        */
        static::forceDeleted(function($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        });

        static::saving(function($product) {
            $product->slug = Str::slug($product->name);
        });
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
}
