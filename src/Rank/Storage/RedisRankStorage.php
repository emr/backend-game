<?php

namespace App\Rank\Storage;

class RedisRankStorage implements RankStorage
{
    private const ZSET_KEY = 'rank:';

    public function __construct(private \Redis $redisClient)
    {
    }

    public function increase(int $id, float $score): void
    {
        $this->redisClient->zIncrBy(self::ZSET_KEY, $score, (string) $id);
    }

    public function clearAfter(int $start): void
    {
        $this->redisClient->zRemRangeByRank(self::ZSET_KEY, 0, -1 * $start);
    }

    /**
     * @return array<int, float> id to rank
     */
    public function getSorted(int $start, int $limit): array
    {
        return $this->redisClient->zRevRange(self::ZSET_KEY, $start, $start + $limit - 1, true);
    }

    public function countAll(): int
    {
        return $this->redisClient->zCount(self::ZSET_KEY, '-inf', '+inf');
    }
}
