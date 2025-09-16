<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\SuggestionController;

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/borrowings/return', [BorrowingController::class, 'returnBook']);

    Route::post('suggestionsForClient', [SuggestionController::class, 'suggestBookForClient']);
    Route::post('suggestions', [SuggestionController::class, 'suggestBook']);

    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('borrowings', BorrowingController::class);
    Route::apiResource('admins', UserController::class)
        ->parameters(['admins' => 'user']);
});

Route::get('/hello', function () {
    return "sjshjshjsjh";
});
