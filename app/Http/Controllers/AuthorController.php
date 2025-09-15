<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Services\AuthorService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:authors.view', only: ['index']),
            new Middleware('permission:authors.create', only: ['store']),
            new Middleware('permission:authors.view_details', only: ['show']),
            new Middleware('permission:authors.update', only: ['update']),
            new Middleware('permission:authors.delete', only: ['destroy']),

        ];
    }

    public function __construct(protected AuthorService $authorService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = $this->authorService->getAllAuthors();
        return response()->json(['data' => AuthorResource::collection($authors)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = $this->authorService->createAuthor($request->validated());
        return response()->json(['author' => new AuthorResource($author)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author = $this->authorService->getAuthor($author);
        return response()->json(['author' => new AuthorResource($author)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $author = $this->authorService->updateAuthor($author, $request->validated());
        return response()->json(['author' => new AuthorResource($author)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $this->authorService->deleteAuthor($author);
        return response()->json(['message' => 'Author deleted'], 204);
    }
}
