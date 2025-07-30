<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        // Seed categories
        \App\Models\Category::factory(10)->create();

        // Seed products
        \App\Models\Product::factory(50)->create()->each(function ($product) {
            // Seed 2-3 images per product, one primary
            $images = \App\Models\ProductImage::factory(3)->make();
            $images[0]->is_primary = true;
            foreach ($images as $img) {
                $img->product_id = $product->id;
                $img->save();
            }
        });
    }
}
