<?php

declare(strict_types=1);

use Fares\TestLeboncoin\Container\Container;
use Fares\TestLeboncoin\Controller\LeBonCoinController;
use Fares\TestLeboncoin\Service\LeBonCoinService;
use Fares\TestLeboncoin\Utils\ErrorHandler;

return static function (Container $c): void {
    // service
    $c->set(LeBonCoinService::class, static fn (Container $c) => new LeBonCoinService(new Redis()));

    // controller
    $c->set(LeBonCoinController::class, static fn (Container $c) => new LeBonCoinController(
        $c->get(LeBonCoinService::class),
    ));

    $c->set(ErrorHandler::class, static fn () => new ErrorHandler(
        debug: ($_ENV['APP_ENV'] ?? 'local') !== 'production',
    ));
};
