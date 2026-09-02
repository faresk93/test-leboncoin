<?php

namespace Fares\TestLeboncoin\Service;

class LeBonCoinService
{

    /**
     * Return the list of fizzbuzz numbers (adapted)
     *
     * @param int $int1
     * @param int $int2
     * @param int $limit
     * @param string $str1
     * @param string $str2
     * @return array<string>
     */
    public function fizz(int $int1, int $int2, int $limit, string $str1, string $str2): array
    {
        $result = [];

        for ($i = 1; $i <= $limit; $i++) {
            $matchesInt1 = $i % $int1 === 0;
            $matchesInt2 = $i % $int2 === 0;

            $result[] = match (true) {
                $matchesInt1 && $matchesInt2 => $str1 . $str2,
                $matchesInt1 => $str1,
                $matchesInt2 => $str2,
                default => (string)$i,
            };
        }

        return $result;
    }
}