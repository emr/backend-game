<?php

namespace App\Rank\Storage;

interface RankStorage
{
    public function increase(int $id, float $score): void;

    public function clearAfter(int $start): void;

    /**
     * @return array<int, float> id to rank
     */
    public function getSorted(int $start, int $limit): array;

    public function countAll(): int;
}
