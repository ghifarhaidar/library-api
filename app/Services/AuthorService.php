<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorService
{
    public function getAllAuthors(Request $request)
    {
        $query = Author::with('books');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        return $query->paginate(10)->appends($request->query());
    }

    public function createAuthor(array $data): Author
    {
        return Author::create($data);
    }

    public function getAuthor(Author $author): Author
    {
        return $author->load('books');
    }

    public function updateAuthor(Author $author, array $data): Author
    {
        $author->update($data);
        return $author;
    }

    public function deleteAuthor(Author $author): bool
    {
        $author->delete();
        return true;
    }
}
