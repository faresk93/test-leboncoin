<?php

namespace Fares\TestLeboncoin\DTO;

use InvalidArgumentException;

final class LeBonCoinData
{

    public function __construct(
        public readonly int $int1,
        public readonly int $int2,
        public readonly int $limit,
        public readonly string $str1,
        public readonly string $str2,
        public readonly ?int $hits = 0,
    ) {
        if ($int1 <= 0 || $int2 <= 0) {
            throw new InvalidArgumentException('int1 and int2 must be strictly positive integers.');
        }

        if ($limit <= 0) {
            throw new InvalidArgumentException('limit must be a strictly positive integer.');
        }

        if ($limit > 1_000_000) {
            // Sane upper bound so nobody can DoS the server with limit=PHP_INT_MAX.
            throw new InvalidArgumentException('limit is too large (max 1,000,000).');
        }

        if ($str1 === '' || $str2 === '') {
            throw new InvalidArgumentException('str1 and str2 must not be empty.');
        }
    }

    /**
     * @param array $params
     * @return self
     */
    public static function fromArray(array $params): self
    {
        foreach (['int1', 'int2', 'limit', 'str1', 'str2'] as $key) {
            if (!array_key_exists($key, $params) || $params[$key] === '' || $params[$key] === null) {
                throw new InvalidArgumentException(sprintf('Missing required parameter "%s".', $key));
            }
        }

        foreach (['int1', 'int2', 'limit'] as $key) {
            if (filter_var($params[$key], FILTER_VALIDATE_INT) === false) {
                throw new InvalidArgumentException(sprintf('Parameter "%s" must be an integer.', $key));
            }
        }

        return new self(
            int1: (int) $params['int1'],
            int2: (int) $params['int2'],
            limit: (int) $params['limit'],
            str1: (string) $params['str1'],
            str2: (string) $params['str2'],
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'int1' => $this->int1,
            'int2' => $this->int2,
            'limit' => $this->limit,
            'str1' => $this->str1,
            'str2' => $this->str2,
        ];
    }

    public function getInt1(): int
    {
        return $this->int1;
    }

    public function getInt2(): int
    {
        return $this->int2;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getStr1(): string
    {
        return $this->str1;
    }

    public function getStr2(): string
    {
        return $this->str2;
    }
}