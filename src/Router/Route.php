<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Router;

final class Route
{
    public readonly string $regex;

    /** @var string[] */
    public readonly array $paramNames;

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param class-string[] $middleware
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $handler,
        public readonly array $middleware = [],
    ) {
        $names = [];
        $regex = preg_replace_callback(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            function (array $m) use (&$names): string {
                $names[] = $m[1];
                return '(?P<' . $m[1] . '>[^/]+)';
            },
            $path
        );
        $this->regex = '#^' . $regex . '$#';
        $this->paramNames = $names;
    }
}