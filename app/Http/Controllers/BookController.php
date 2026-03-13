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
        
        if (request('chef')) {
            $query->where('chef', request('chef'));
        }
        
        if (request('page')) {
            return BookResource::collection($query->paginate(10));
        }
        
        return BookResource::collection($query->get());
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
