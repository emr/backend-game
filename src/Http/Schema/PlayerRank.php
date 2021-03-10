<?php

namespace App\Http\Schema;

class PlayerRank
{
    public function __construct(public int $id, public string $username, public float $rank)
    {
    }
}
