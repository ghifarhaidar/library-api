<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Services\BorrowingService;
use App\Http\Resources\BorrowingResource;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\ReturnBookRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BorrowingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:borrowings.view', only: ['index']),
            new Middleware('permission:borrowings.create', only: ['store']),
            new Middleware('permission:borrowings.view_details', only: ['show']),
            new Middleware('permission:borrowings.update', only: ['returnBook']),
            new Middleware('permission:borrowings.delete', only: ['destroy']),

        ];
    }

    public function __construct(protected BorrowingService $borrowingService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $borrowings = $this->borrowingService->getAllBorrowings($request);
        return response()->json([
            'data' => BorrowingResource::collection($borrowings),
            'meta' => [
                'current_page' => $borrowings->currentPage(),
                'last_page'    => $borrowings->lastPage(),
                'per_page'     => $borrowings->perPage(),
                'total'        => $borrowings->total(),
            ],
            'links' => [
                'first' => $borrowings->url(1),
                'last'  => $borrowings->url($borrowings->lastPage()),
                'prev'  => $borrowings->previousPageUrl(),
                'next'  => $borrowings->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        $borrowing = $this->borrowingService->createBorrowing($request->validated());
        return response()->json(['data' => new BorrowingResource($borrowing)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing = $this->borrowingService->getBorrowing($borrowing);
        return response()->json([
            'data' => new BorrowingResource($borrowing),
            'meta' => [
                'current_page' => $borrowing->currentPage(),
                'last_page'    => $borrowing->lastPage(),
                'per_page'     => $borrowing->perPage(),
                'total'        => $borrowing->total(),
            ],
            'links' => [
                'first' => $borrowing->url(1),
                'last'  => $borrowing->url($borrowing->lastPage()),
                'prev'  => $borrowing->previousPageUrl(),
                'next'  => $borrowing->nextPageUrl(),
            ],
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    // public function update(returnBookRequest $request, Borrowing $borrowing)
    // {
    //     $borrowing = $this->borrowingService->updateBorrowing($borrowing, $request->validate());
    //     return response()->json(['data' => new BorrowingResource($borrowing)], 201);

    // }

    public function returnBook(ReturnBookRequest $request)
    {
        $data = $request->validated();
        $borrowing = $this->borrowingService->returnBook($data);

        return response()->json([
            'message' => 'Borrowing record updated successfully',
            'data'    => $borrowing
        ], 200);
    }
}
