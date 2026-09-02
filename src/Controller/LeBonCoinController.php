<?php

declare(strict_types=1);

namespace Fares\TestLeboncoin\Controller;

use Fares\TestLeboncoin\DTO\LeBonCoinData;
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
        $data = LeBonCoinData::fromArray($request->pathParams);
        $result = $this->leBonCoinService->list($data);
        // record (for stats)
        $this->leBonCoinService->recordHit($data);

        return new JsonResponse($result);
    }

    public function stats(Request $request): JsonResponse
    {
        $stats = $this->leBonCoinService->stats();
        return new JsonResponse($stats);
    }
}