<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SQL
        // INSERT INTO categories (parent_id, name, slug, description, image)
        // VALUES (null, 'Clothes', 'clothes', null, null)

        // Query Builder
        DB::table('categories')->insert([
            'parent_id' => null,
            'name' => 'Clothes',
            'slug' => 'clothes',
            'description' => null,
            'image' => null,
        ]);

        DB::statement("INSERT INTO categories (parent_id, name, slug, description, image)
            VALUES (1, 'kids', 'kids', null, null)");

        
    }
}
