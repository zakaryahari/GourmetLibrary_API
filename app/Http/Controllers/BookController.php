<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $query = Book::with('category');
        
        // Filter by category slug
        if (request('category')) {
            $query->whereHas('category', function($q) {
                $q->where('slug', request('category'));
            });
        }
        
        // Filter by chef
        if (request('chef')) {
            $query->where('chef', request('chef'));
        }
        
        // Search by title or chef
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('chef', 'like', '%' . $search . '%');
            });
        }
        
        // Sorting
        if (request('sort')) {
            switch (request('sort')) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('borrow_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Pagination
        $perPage = request('per_page', 10);
        return BookResource::collection($query->paginate($perPage));
    }

    public function show($slug)
    {
        $book = Book::with('category')->where('slug', $slug)->firstOrFail();
        return new BookResource($book);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'chef' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create([
            'title' => $request->title,
            'chef' => $request->chef,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
        ]);

        return new BookResource($book->load('category'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'chef' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update([
            'title' => $request->title,
            'chef' => $request->chef,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
        ]);

        return new BookResource($book->load('category'));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
}
