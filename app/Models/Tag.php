<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Many-to-Many: Tag has many products
    public function products()
    {
        return $this->belongsToMany(Product::class);
        return $this->belongsToMany(
            Product::class, // Related model
            'product_tag',  // Pivot table name
            'tag_id',       // Current model FK in pivot table
            'product_id',   // Related model FK in pivot table
            'id',           // Local (PK) current model
            'id'            // Local (PK) related model
        );
    }
}
