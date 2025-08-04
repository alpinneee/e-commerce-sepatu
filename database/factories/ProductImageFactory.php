<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'image_path' => 'products/' . $this->faker->image('public/storage/products', 640, 640, null, false),
            'is_primary' => $this->faker->boolean(80),
        ];
    }
} 