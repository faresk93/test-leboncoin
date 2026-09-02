<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Exception;

class HttpException extends \RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message,
    ) {
        parent::__construct($message);
    }
}