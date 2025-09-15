<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'nationality' => $this->nationality,
            'books'       => $this->whenLoaded('books', function () {
                return $this->books->pluck('title'); // or BookResource if you want full details
            }),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
