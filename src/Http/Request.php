<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Http;

final class Request
{
    /**
     * @param array<string, string> $headers
     * @param array<string, string> $query
     * @param array<string, string> $pathParams
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $headers,
        public readonly array $query,
        public readonly ?array $jsonBody,
        public array $pathParams = [],
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';
        $path   = parse_url($uri, PHP_URL_PATH) ?: '/';

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = (string)$value;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = (string)$_SERVER['CONTENT_TYPE'];
        }

        $body = file_get_contents('php://input') ?: '';
        $json = null;
        if ($body !== '' && str_contains($headers['content-type'] ?? '', 'application/json')) {
            $decoded = json_decode($body, true);
            $json = is_array($decoded) ? $decoded : null;
        }

        return new self(
            method: $method,
            path: $path,
            headers: $headers,
            query: array_map('strval', $_GET),
            jsonBody: $json,
        );
    }

    public function header(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function queryInt(string $name, int $default): int
    {
        return isset($this->query[$name]) && ctype_digit($this->query[$name])
            ? (int)$this->query[$name]
            : $default;
    }

}