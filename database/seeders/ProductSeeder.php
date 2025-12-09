<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Read the JSON file
        $json = file_get_contents(database_path('seeders/products.json'));
        $products = json_decode($json, true);

        foreach ($products as $p) {
            $category = Category::where('slug', \Illuminate\Support\Str::slug($p['category']))->first();

            if ($category) {
                Product::updateOrCreate(
                    ['name' => $p['name']],
                    [
                        'description' => $p['description'] ?? '',
                        'price' => $p['price'] ?? 0,
                        'category_id' => $category->id,
                        'image' => $p['image'] ?? null,
                    ]
                );
            }
        }
    }
}
