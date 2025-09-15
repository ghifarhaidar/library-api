<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Services\BorrowingService;
use App\Http\Resources\BorrowingResource;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\ReturnBookRequest;

class BorrowingController extends Controller
{
    public function __construct(protected BorrowingService $borrowingService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = $this->borrowingService->getAllBorrowings();
        return response()->json(['data' => BorrowingResource::collection($borrowings)], 200);
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
        return response()->json(['data' => new BorrowingResource($borrowing)], 201);
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
