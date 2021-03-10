<?php

namespace App\Http\Schema;

class PaginationMeta
{
    public function __construct(
        public int $itemCount,
        public int $totalItems,
        public int $totalPage,
        public int $currentPage,
    ) {
    }
}
