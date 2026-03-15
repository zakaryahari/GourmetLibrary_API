<?php

namespace Database\Factories;

use App\Models\Copy;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class CopyFactory extends Factory
{
    protected $model = Copy::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'status' => $this->faker->randomElement(['available', 'borrowed']),
            'is_damaged' => $this->faker->boolean(20),
        ];
    }
}
