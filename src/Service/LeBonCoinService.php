<?php

namespace Fares\TestLeboncoin\Service;

use Fares\TestLeboncoin\DTO\LeBonCoinData;
use Redis;

class LeBonCoinService
{
    private const SORTED_SET_KEY = 'leboncoin:request_stats';

    public function __construct(private readonly Redis $redis)
    {
        $this->redis->connect(
            getenv('REDIS_HOST') ?: '127.0.0.1',
            (int) (getenv('REDIS_PORT') ?: 6379)
        );
    }

    /**
     * Return the list of fizzbuzz numbers (adapted)
     *
     * @param LeBonCoinData $data
     * @return array<string>
     */
    public function list(LeBonCoinData $data): array
    {
        $result = [];

        for ($i = 1; $i <= $data->getLimit(); $i++) {
            $matchesInt1 = $i % $data->getInt1() === 0;
            $matchesInt2 = $i % $data->getInt2() === 0;

            $str1 = $data->getStr1();
            $str2 = $data->getStr2();
            $result[] = match (true) {
                $matchesInt1 && $matchesInt2 => $str1 . $str2,
                $matchesInt1 => $str1,
                $matchesInt2 => $str2,
                default => (string)$i,
            };
        }

        return $result;
    }

    public function recordHit(LeBonCoinData $data)
    {
        $this->redis->zIncrBy(self::SORTED_SET_KEY, 1, json_encode($data->toArray()));
    }

    public function stats()
    {
        $top = $this->redis->zRevRange(self::SORTED_SET_KEY, 0, 0, true);

        if (empty($top)) {
            return null;
        }

        $member = array_key_first($top);
        $hits = (int) $top[$member];

        $params = $this->decodeMember((string) $member);

        return new LeBonCoinData(
            int1: $params['int1'],
            int2: $params['int2'],
            limit: $params['limit'],
            str1: $params['str1'],
            str2: $params['str2'],
            hits: $hits,
        );
    }

    /**
     * @param string $member
     * @return array
     */
    private function decodeMember(string $member): array
    {
        try {
            /** @var array{int1: int, int2: int, limit: int, str1: string, str2: string} $decoded */
            $decoded = json_decode($member, true, flags: JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to decode stored stats member: ' . $member, previous: $e);
        }
    }
}