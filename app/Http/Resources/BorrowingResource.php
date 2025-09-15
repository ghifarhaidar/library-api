<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'borrowed_at' => $this->borrowed_at,
            'due_at'      => $this->due_at,
            'returned_at' => $this->returned_at,

            'client' => [
                'id'    => $this->client->id,
                'name'  => $this->client->name,
                'phone' => $this->client->phone,
            ],

            'book' => [
                'id'    => $this->book->id,
                'title' => $this->book->title,
                'isbn'  => $this->book->isbn,
            ],
        ];
    }
}
