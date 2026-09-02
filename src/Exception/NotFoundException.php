<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Exception;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct(404, $message);
    }
}