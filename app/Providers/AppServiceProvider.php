<?php

namespace App\Providers;

use App\Services\AiService\AiServiceInterface;
use App\Services\AiService\ClaudeAiService;
use App\Services\AiService\OpenAiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AiServiceInterface::class, function () {
            // return new OpenAiService(config("services.openai.key"));
            return new ClaudeAiService(config("services.cluade.key"));

        });
    }   

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
