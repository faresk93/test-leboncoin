<?php

declare(strict_types=1);

use Fares\TestLeboncoin\Controller\LeBonCoinController;
use Fares\TestLeboncoin\Router\Router;

return static function (Router $router): void {
    $router->get('/api', [LeBonCoinController::class, 'index']);
};
