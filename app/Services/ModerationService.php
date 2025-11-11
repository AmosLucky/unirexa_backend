<?php

//App\Services\ModerationService;
namespace App\Services;

use OpenAI\Client;

class ModerationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('OPENAI_API_KEY'));
    }

    public function checkText($text)
    {
        $response = $this->client->moderations()->create([
            'input' => $text,
        ]);

        $results = $response->results[0];
        return $results->flagged;
    }
}
