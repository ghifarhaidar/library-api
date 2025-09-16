<?php

namespace App\Services\AiService;

use App\Models\Book;
use App\Models\Client;
use Illuminate\Support\Str;

interface AiServiceInterface
{


    public function suggestBookForClient(int $client, String $prompt);


    public function suggestBook(String $prompt);

}
