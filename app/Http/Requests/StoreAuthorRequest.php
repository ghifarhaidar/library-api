<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust if you add role checks later
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
        ];
    }
}
