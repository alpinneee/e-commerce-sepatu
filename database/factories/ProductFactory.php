<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-#####')),
            'name' => $this->faker->words(3, true),
            'slug' => fn(array $attributes) => Str::slug($attributes['name']),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100000, 1000000),
            'discount_price' => $this->faker->optional(0.5)->numberBetween(80000, 900000),
            'stock' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
} 