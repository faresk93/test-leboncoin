<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Controller;

use Fares\TestLeboncoin\Http\JsonResponse;
use Fares\TestLeboncoin\Http\Request;

final class LeBonCoinController
{

    public function index(Request $request): JsonResponse
    {
        return new JsonResponse('TODO');
    }
}