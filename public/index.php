<?php

declare(strict_types=1);

use Fares\TestLeboncoin\Container\Container;
use Fares\TestLeboncoin\Http\Request;
use Fares\TestLeboncoin\Router\Router;
use Fares\TestLeboncoin\Utils\ErrorHandler;

require __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);

$dotenv = Dotenv\Dotenv::createImmutable($root);
$dotenv->safeLoad();

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Typey');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    return;
}

$container = new Container();
(require $root . '/config/services.php')($container);

$router = new Router($container);
(require $root . '/config/routes.php')($router);

$handler = $container->get(ErrorHandler::class);

try {
    $request  = Request::fromGlobals();
    $response = $router->dispatch($request);
} catch (Throwable $e) {
    $response = $handler->toResponse($e);
}

$response->send();
