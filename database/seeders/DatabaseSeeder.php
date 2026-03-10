<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::factory(5)->create();

        foreach ($categories as $category) {
            Book::factory(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
