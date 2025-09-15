<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'author_id'   => 'sometimes|exists:authors,id',
            'isbn'        => 'sometimes|string|max:13|unique:books,isbn,' . $this->book->id,
            'year'        => 'nullable|integer|min:1000|max:' . now()->year,
            'in_stock'    => 'sometimes|integer|min:0',
        ];
    }
}
