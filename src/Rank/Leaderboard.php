<?php

namespace App\Rank;

use App\Rank\Storage\RankStorage;

class Leaderboard
{
    public function __construct(private RankStorage $rankStorage, private int $size)
    {
    }

    public function addScore(PlayerScore $score): void
    {
        $this->rankStorage->increase($score->id, $score->score);
        $this->rankStorage->clearAfter($this->size);
    }

    /**
     * @return array<int, float> id to rank
     */
    public function list(int $start, int $limit): array
    {
        return $this->rankStorage->getSorted($start, $limit);
    }

    public function countList(): int
    {
        return $this->rankStorage->countAll();
    }
}
