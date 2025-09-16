<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;

class BookService
{
    public function getAllBooks(Request $request)
    {
        $query = Book::with('author');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(
                function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%")
                        ->orWhere('isbn', 'like', "%$search%");
                }
            );
        }
        if ($request->has('year')) {
            $year = $request->year;

            if (isset($year['gt'])) {
                $query->where('year', '>', $year['gt']);
            }
            if (isset($year['lt'])) {
                $query->where('year', '<', $year['lt']);
            }
            if (isset($year['gte'])) {
                $query->where('year', '>=', $year['gte']);
            }
            if (isset($year['lte'])) {
                $query->where('year', '<=', $year['lte']);
            }
            if (isset($year['eq'])) {
                $query->where('year', $year['eq']);
            }
        }
        return $query->paginate(10)->appends($request->query());
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
