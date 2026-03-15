<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Copy;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1 admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@gourmet.com',
        ]);

        // Create 20 regular users (gourmands)
        $users = User::factory(20)->create();

        // Create 5 categories
        $categories = Category::factory(5)->create();

        // Create 50 books (10 per category)
        $books = collect();
        foreach ($categories as $category) {
            $categoryBooks = Book::factory(10)->create([
                'category_id' => $category->id,
                'borrow_count' => rand(0, 50),
            ]);
            $books = $books->merge($categoryBooks);
        }

        // Create 3-5 copies for each book
        foreach ($books as $book) {
            Copy::factory(rand(3, 5))->create([
                'book_id' => $book->id,
            ]);
        }

        // Create some borrows (active and returned)
        $copies = Copy::all();
        foreach ($users->random(15) as $user) {
            // Each user borrows 1-3 books
            $userCopies = $copies->random(rand(1, 3));
            
            foreach ($userCopies as $copy) {
                // 70% returned, 30% still borrowed
                $isReturned = rand(1, 100) <= 70;
                
                Borrow::create([
                    'user_id' => $user->id,
                    'copy_id' => $copy->id,
                    'borrowed_at' => now()->subDays(rand(1, 30)),
                    'returned_at' => $isReturned ? now()->subDays(rand(0, 15)) : null,
                ]);
                
                // Update copy status
                if (!$isReturned) {
                    $copy->update(['status' => 'borrowed']);
                }
            }
        }
    }
}
