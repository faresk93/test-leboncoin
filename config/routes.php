<?php

declare(strict_types=1);

use Fares\TestLeboncoin\Controller\LeBonCoinController;
use Fares\TestLeboncoin\Router\Router;

return static function (Router $router): void {
    $router->get('/api/list/{int1}/{int2}/{limit}/{str1}/{str2}', [LeBonCoinController::class, 'index']);
    $router->get('/api/stats', [LeBonCoinController::class, 'stats']);
};
