<?php

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function getAllAuthors()
    {
        return Author::with('books')->get();
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
