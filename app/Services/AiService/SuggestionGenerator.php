<?php

namespace App\Services\AiService;

use App\Models\Book;
use App\Models\Client;

class SuggestionGenerator
{

    public function __construct(protected AiServiceInterface $aiService) {}
    public function suggestBookForClient(int $client, String $prompt)
    {
        return $this->aiService->suggestBookForClient($client, $prompt);
    }

    public function suggestBook(String $prompt)
    {
        return $this->aiService->suggestBook($prompt);
    }
}
