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
}
