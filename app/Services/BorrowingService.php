<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BorrowingService
{

    public function getAllBorrowings(Request $request)
    {
        return Borrowing::with(['client', 'book'])->paginate(10)->appends($request->query());
    }

    public function createBorrowing(array $data): Borrowing
    {
        return DB::transaction(function () use ($data) {

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
        });
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
        return DB::transaction(function () use ($data) {

            $borrowing = Borrowing::where('client_id', $data['client_id'])
                ->where('book_id', $data['book_id'])
                ->whereNull('returned_at')
                ->where('status', 'borrowed')
                ->first();

            if (!$borrowing) {
                throw new \Exception('No active borrowing found for this client and book.');
            }

            $borrowing->update([
                'returned_at' => now(),
                'status'      => 'returned',
            ]);

            // Increment stock back
            $borrowing->book->increment('in_stock');

            return $borrowing;
        });
    }
}
