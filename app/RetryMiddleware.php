<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ConnectException;

class RetryMiddleware
{
    public function retry(int $maxRetry): callable
    {
        return Middleware::retry(
            function (
                int $retries,
                RequestInterface $request,
                ?ResponseInterface $response = null,
                ?\RuntimeException $e = null
            ) use ($maxRetry) {
                if ($retries >= $maxRetry) {
                    return false;
                }

                if ($response && in_array($response->getStatusCode(), [249, 429, 503])) {
                    return true;
                }

                if ($e instanceof ConnectException) {
                    return true;
                }

                return false;
            }
        );
    }
}
