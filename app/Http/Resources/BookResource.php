<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'description' => $this->description,
            'isbn'      => $this->isbn,
            'year'      => $this->year,
            'in_stock'  => $this->in_stock,
            'author'    => [
                'id'   => $this->author->id,
                'name' => $this->author->name,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
