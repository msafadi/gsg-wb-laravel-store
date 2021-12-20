<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(3, true);
        return [
            'parent_id' => null,
            'name' => $name, // 'Loren Ipsom wat'
            'slug' => Str::slug($name), // 'loren-ipsom-wat'
            'description' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
