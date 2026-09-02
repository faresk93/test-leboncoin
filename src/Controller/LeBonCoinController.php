<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Controller;

use Fares\TestLeboncoin\Http\JsonResponse;
use Fares\TestLeboncoin\Http\Request;
use Fares\TestLeboncoin\Service\LeBonCoinService;

final class LeBonCoinController
{
    public function __construct(private readonly LeBonCoinService $leBonCoinService)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $int1 = (int)$request->pathParams['int1'] ?? null;
        $int2 = (int)$request->pathParams['int2'] ?? null;
        $limit = (int)$request->pathParams['limit'] ?? null;
        $str1 = $request->pathParams['str1'] ?? null;
        $str2 = $request->pathParams['str2'] ?? null;

        $result = $this->leBonCoinService->fizz($int1, $int2, $limit, $str1, $str2);

        return new JsonResponse($result);
    }
}