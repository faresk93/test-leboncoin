<?php

declare(strict_types=1);

use Fares\TestLeboncoin\Container\Container;
use Fares\TestLeboncoin\Controller\LeBonCoinController;
use Fares\TestLeboncoin\Utils\ErrorHandler;

return static function (Container $c): void {
    $c->set(LeBonCoinController::class, static fn (Container $c) => new LeBonCoinController());

    $c->set(ErrorHandler::class, static fn () => new ErrorHandler(
        debug: ($_ENV['APP_ENV'] ?? 'local') !== 'production',
    ));
};
