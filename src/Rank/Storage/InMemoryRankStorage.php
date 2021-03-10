<?php

namespace App\Rank\Storage;

class InMemoryRankStorage implements RankStorage
{
    /**
     * @var array<int, float>
     */
    private array $storage = [];

    public function increase(int $id, float $score): void
    {
        $this->storage[$id] = ($this->storage[$id] ?? 0) + $score;
        arsort($this->storage);
    }

    public function clearAfter(int $start): void
    {
        $this->storage = $this->getSorted(0, $start);
    }

    /**
     * @return array<int, float> id to rank
     */
    public function getSorted(int $start, int $limit): array
    {
        return \array_slice($this->storage, $start, $limit, true);
    }

    public function countAll(): int
    {
        return \count($this->storage);
    }
}
