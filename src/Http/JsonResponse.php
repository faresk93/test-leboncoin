<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Http;

final class JsonResponse extends Response
{
    public function __construct(mixed $payload, int $status = 200, array $headers = [])
    {
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($body === false) {
            $body = '{"error":true,"message":"Failed to encode response"}';
            $status = 500;
        }
        parent::__construct(
            status: $status,
            body: $body,
            headers: ['Content-Type' => 'application/json; charset=utf-8'] + $headers,
        );
    }
}