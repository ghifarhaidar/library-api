<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Borrowing;
use App\Models\Book;


class BorrowingService
{

    public function getAllBorrowings()
    {
        return Borrowing::with(['client', 'book'])->get();
    }

    public function createBorrowing(array $data): Borrowing
    {
        $book = Book::findOrFail($data['book_id']);

        // Check stock
        if ($book->in_stock <= 0) {
            throw new \Exception("This book is currently out of stock.");
        }

        // Set borrowed_at and due_at
        $data['borrowed_at'] = $data['borrowed_at'] ?? Carbon::now()->toDateString();
        $data['due_at'] = $data['due_at'] ?? Carbon::parse($data['borrowed_at'])->addDays(7)->toDateString();

        // Create borrowing
        $borrowing = Borrowing::create($data);

        // Decrement book stock
        $book->decrement('in_stock');

        return $borrowing->load(['client', 'book']);
    }

    public function getBorrowing(Borrowing $borrowing)
    {

        return $borrowing;
    }

    public function updateBorrowing(Borrowing $borrowing, array $data): Borrowing
    {
        $borrowing->update($data);
        return $borrowing;
    }

    public function returnBook(array $data)
    {
        $borrowing = Borrowing::where('client_id', $data['client_id'])
            ->where('book_id', $data['book_id'])
            ->where('status', 'borrowed')
            ->first();

        if (!$borrowing) {
            return null;
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);

        $borrowing->book->increment('in_stock');
        return $borrowing->load(['client', 'book']);
    }
}
