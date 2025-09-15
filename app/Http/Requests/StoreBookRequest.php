<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'author_id'   => 'required|exists:authors,id',
            'isbn'        => 'required|string|max:13|unique:books,isbn',
            'year'        => 'nullable|integer|min:1000|max:' . now()->year,
            'in_stock'    => 'required|integer|min:0',
        ];
    }
}
