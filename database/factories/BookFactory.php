<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);
        
        return [
            'title' => $title,
            'chef' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'slug' => Str::slug($title),
            'category_id' => Category::factory(),
        ];
    }
}
