<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BookController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:books.view', only: ['index']),
            new Middleware('permission:books.create', only: ['store']),
            new Middleware('permission:books.view_details', only: ['show']),
            new Middleware('permission:books.update', only: ['update']),
            new Middleware('permission:books.delete', only: ['destroy']),

        ];
    }

    public function __construct(protected BookService $bookService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = $this->bookService->getAllBooks();
        return response()->json(['data' => BookResource::collection($books)], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = $this->bookService->createBook($request->validated());
        return response()->json(['book' => new BookResource($book)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = $this->bookService->getBook($book);
        return response()->json(['book' => new BookResource($book)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $book = $this->bookService->updateBook($book, $request->validated());
        return response()->json(['book' => new BookResource($book)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return response()->json(['message' => 'Book deleted'], 204);
    }
}
