<?php

namespace App\Rank;

class PlayerScore
{
    public function __construct(public int $id, public float $score)
    {
    }
}
