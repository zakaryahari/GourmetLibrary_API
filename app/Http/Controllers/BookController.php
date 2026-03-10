<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        return new BookResource($book);
    }
}
