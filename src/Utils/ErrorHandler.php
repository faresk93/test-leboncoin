<?php

namespace Fares\TestLeboncoin\Utils;

use Fares\TestLeboncoin\Exception\HttpException;
use Fares\TestLeboncoin\Http\JsonResponse;
use Fares\TestLeboncoin\Http\Response;
use Throwable;

class ErrorHandler
{
    public function __construct(private readonly bool $debug = false)
    {
    }

    public function toResponse(Throwable $e): Response
    {
        if ($e instanceof HttpException) {
            return new JsonResponse([
                'error' => true,
                'message' => $e->getMessage(),
            ], $e->statusCode);
        }

        $payload = [
            'error' => true,
            'message' => 'Internal server error',
        ];
        if ($this->debug) {
            $payload['exception'] = $e::class;
            $payload['detail'] = $e->getMessage();
            $payload['trace'] = explode("\n", $e->getTraceAsString());
        }

        return new JsonResponse($payload, 500);
    }

}