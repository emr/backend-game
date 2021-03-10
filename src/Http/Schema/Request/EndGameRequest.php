<?php

namespace App\Http\Schema\Request;

use App\Http\Validator\UniquePlayers;
use App\Rank\PlayerScore;
use Symfony\Component\Validator\Constraints as Assert;

class EndGameRequest
{
    /** @var PlayerScore[] */
    #[
        Assert\Valid,
        UniquePlayers(message: 'Player with id "{{ id }}" has multiple scores'),
    ]
    public array $scores = [];

    public function addScore(PlayerScore $score): void
    {
        $this->scores[] = $score;
    }
}
