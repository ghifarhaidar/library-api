<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getAllBooks()
    {
        return Book::with('author')->get();
    }

    public function createBook(array $data)
    {
        return Book::create($data);
    }

    public function getBook(Book $book)
    {
        return $book->load('author');
    }

    public function updateBook(Book $book, array $data)
    {
        $book->update($data);
        return $book->load('author');
    }

    public function deleteBook(Book $book)
    {
        $book->delete();
        return true;
    }
}
