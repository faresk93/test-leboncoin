<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Router;

use Fares\TestLeboncoin\Container\Container;
use Fares\TestLeboncoin\Exception\NotFoundException;
use Fares\TestLeboncoin\Http\Request;
use Fares\TestLeboncoin\Http\Response;

final class Router
{
    /** @var Route[] */
    private array $routes = [];

    /** @var class-string[] */
    private array $globalMiddleware = [];

    public function __construct(private readonly Container $container)
    {
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param class-string[] $middleware
     */
    public function add(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = new Route(strtoupper($method), $path, $handler, $middleware);
    }

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    /** @param class-string[] $middleware */
    public function useGlobal(array $middleware): void
    {
        $this->globalMiddleware = array_merge($this->globalMiddleware, $middleware);
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route->method !== $request->method) {
                continue;
            }
            if (!preg_match($route->regex, $request->path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route->paramNames as $name) {
                $params[$name] = $matches[$name];
            }
            $request->pathParams = $params;

            return $this->run($request, $route);
        }

        throw new NotFoundException("Route not found: {$request->method} {$request->path}");
    }

    private function run(Request $request, Route $route): Response
    {
        $chain = array_merge($this->globalMiddleware, $route->middleware);

        $next = function (Request $req) use ($route): Response {
            [$class, $method] = $route->handler;
            $controller = $this->container->get($class);
            return $controller->{$method}($req);
        };

        foreach (array_reverse($chain) as $middlewareClass) {
            $middleware = $this->container->get($middlewareClass);
            $current = $next;
            $next = static fn (Request $req): Response => $middleware->handle($req, $current);
        }

        return $next($request);
    }
}