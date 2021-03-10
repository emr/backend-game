<?php

namespace App\Tests\Unit\Rank;

use App\Rank\Leaderboard;
use App\Rank\PlayerScore;
use App\Rank\Storage\InMemoryRankStorage;
use PHPUnit\Framework\TestCase;

class LeaderboardTest extends TestCase
{
    private Leaderboard $leaderboard;

    protected function setUp(): void
    {
        $this->leaderboard = new Leaderboard(new InMemoryRankStorage(), 10);
    }

    public function testAddScoreAndList(): void
    {
        $this->leaderboard->addScore(new PlayerScore(1, 10));
        $this->leaderboard->addScore(new PlayerScore(2, 20));
        $this->leaderboard->addScore(new PlayerScore(3, 15));
        $this->leaderboard->addScore(new PlayerScore(3, 15));
        $this->leaderboard->addScore(new PlayerScore(4, 5));
        $this->leaderboard->addScore(new PlayerScore(5, 34));

        static::assertEquals(
            [5 => 34, 3 => 30, 2 => 20],
            $this->leaderboard->list(0, 3)
        );
        static::assertEquals(
            [1 => 10, 4 => 5],
            $this->leaderboard->list(3, 3)
        );
        static::assertEquals(5, $this->leaderboard->countList());
    }
}
