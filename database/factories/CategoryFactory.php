<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(2, true); // dua kata
        $slug = Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 9999));
        return [
            'name' => ucfirst($name),
            'slug' => $slug,
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
} 