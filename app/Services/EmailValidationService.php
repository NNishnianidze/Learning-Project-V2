<?php

declare(strict_types=1);

namespace App\Services\Emailable;

use App\Contracts\EmailValidationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use App\RetryMiddleware;

class EmailValidationService implements EmailValidationInterface
{
    private string $baseUrl = 'https://api.emailable.com/v1/';

    public function __construct(private string $apiKey, private RetryMiddleware $middleware)
    {
    }

    public function verify(string $email): array
    {
        $stack = HandlerStack::create();

        $maxRetry = 3;

        $stack->push($this->middleware->retry($maxRetry));

        $client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'timeout'  => 5,
                'handler'  => $stack,
            ]
        );

        $params = [
            'api_key' => $this->apiKey,
            'email'   => $email,
        ];

        $response = $client->get('verify', ['query' => $params]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
