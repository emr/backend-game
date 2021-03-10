<?php

namespace App\Rank;

use App\Http\Validator\HasLoggedIn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Since this object is used as an input in `EndGameRequest`, zero values
 * are provided as defaults to allow serializer to instantiate this class.
 */
class PlayerScore
{
    #[
        Assert\GreaterThan(0, message: 'Invalid player id "{{ value }}"'),
        HasLoggedIn(message: 'Player with id "{{ id }}" has not logged in')
    ]
    public int $id;

    public float $score;

    public function __construct(int $id = 0, float $score = 0)
    {
        $this->id = $id;
        $this->score = $score;
    }
}
