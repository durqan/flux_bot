<?php

namespace App\Services\Flux;

use GuzzleHttp\Client;

class FluxKontextPro
{
    protected Client $client;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function sendRequest($prompt, $image = null)
    {
        return $this->client->post('https://api.bfl.ai/v1/flux-kontext-pro', [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-key' => env('FLUX_KONTENT_KEY')
            ],
            'json' => [
                'prompt' => $prompt,
                'input_image' => $image
            ]
        ]);
    }
}
