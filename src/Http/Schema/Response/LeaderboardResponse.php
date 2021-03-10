<?php

namespace App\Http\Schema\Response;

use App\Http\Schema\PaginationMeta;
use App\Http\Schema\PlayerRank;

class LeaderboardResponse extends SuccessResponse
{
    /**
     * @param iterable<PlayerRank> $ranks
     */
    public function __construct(iterable $ranks, PaginationMeta $paginationMeta)
    {
        parent::__construct([
            'list' => $ranks,
            'meta' => $paginationMeta,
        ]);
    }
}
