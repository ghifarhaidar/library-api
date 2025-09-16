<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuggestionResource;
use Illuminate\Http\Request;
use App\Models\Client;

use App\Services\AiService\SuggestionGenerator;

class SuggestionController extends Controller
{

    public function __construct(protected SuggestionGenerator $suggestionGenerator) {}


    public function suggestBook(Request $request)
    {
        $prompt = $request->input('prompt', '');
        $suggestion = $request->has('client_id')
            ? $this->suggestionGenerator->suggestBookForClient($request->input('client_id'), $prompt)
            : $this->suggestionGenerator->suggestBook($prompt);

        return response()->json([
            'book' => new SuggestionResource($suggestion)
        ], 200);    }
}
