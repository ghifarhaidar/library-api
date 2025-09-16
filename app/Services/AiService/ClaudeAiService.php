<?php

namespace App\Services\AiService;

use App\Models\Book;
use App\Models\Client;
use Illuminate\Support\Str;

class ClaudeAiService implements AiServiceInterface
{
    public function __construct(private string $apiKey) {}


    public function suggestBookForClient(int $client, String $prompt)
    {
        $suggestion = Str::length($prompt) + $client + Str::length($this->apiKey) ;
        if ($suggestion < 1) $suggestion = 1;
        if ($suggestion > Book::count()) $suggestion = Book::count();
        return Book::find($suggestion);
    }

    public function suggestBook(String $prompt)
    {
        $suggestion = Str::length($prompt)+ Str::length($this->apiKey) ;;
        if ($suggestion < 1) $suggestion = 1;
        if ($suggestion > Book::count()) $suggestion = Book::count();
        return Book::find($suggestion);
    }
}
