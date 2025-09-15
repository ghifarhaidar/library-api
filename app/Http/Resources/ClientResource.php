<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'phone'   => $this->phone,
            'address' => $this->address,
            'borrowed_books' => $this->whenLoaded('borrowedBooks', function () {
                return $this->borrowedBooks->map(function ($book) {
                    return [
                        'id'         => $book->id,
                        'title'      => $book->title,
                        'borrowed_at'=> $book->pivot->borrowed_at,
                        'due_at'     => $book->pivot->due_at,
                        'returned_at'=> $book->pivot->returned_at,
                        'status'     => $book->pivot->status,
                    ];
                });
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
