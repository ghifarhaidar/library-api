<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'author' => [
                'name' =>  $this->author->name,
            ],
            'genre'  => $this->genre,
            'year'   => $this->year,
        ];
    }
}
