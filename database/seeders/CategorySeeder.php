<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Read the JSON file
        $json = file_get_contents(database_path('seeders/products.json'));
        $products = json_decode($json, true);

        // Extract unique categories
        $categories = collect($products)->pluck('category')->unique();

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)], 
                ['name' => $name, 'slug' => Str::slug($name)]
            );
        }
    }
}
