<?php

namespace App\Tests\Unit\Rank;

use App\Rank\Storage\InMemoryRankStorage;
use PHPUnit\Framework\TestCase;

class InMemoryRankStorageTest extends TestCase
{
    private InMemoryRankStorage $storage;

    protected function setUp(): void
    {
        $this->storage = new InMemoryRankStorage();
    }

    public function testIncrease(): void
    {
        $this->storage->increase(1, 5);
        static::assertEquals([1 => 5], $this->storage->getSorted(0, 1));

        $this->storage->increase(1, 5);
        static::assertEquals([1 => 10], $this->storage->getSorted(0, 1));
    }

    public function testGetSorted(): void
    {
        $this->storage->increase(1, 5);
        $this->storage->increase(2, 10);
        $this->storage->increase(3, 15);

        // with over limit
        static::assertEquals(
            [3 => 15, 2 => 10, 1 => 5],
            $this->storage->getSorted(0, 5)
        );

        $this->storage->increase(1, 50);

        // with under limit
        static::assertEquals(
            [1 => 55, 3 => 15],
            $this->storage->getSorted(0, 2)
        );
    }

    public function testClearAfterAndCountAll(): void
    {
        static::assertEquals(0, $this->storage->countAll());

        $this->storage->increase(1, 5);
        $this->storage->increase(2, 10);
        $this->storage->increase(3, 15);
        $this->storage->increase(4, 20);

        static::assertEquals(4, $this->storage->countAll());

        $this->storage->clearAfter(3);

        static::assertEquals(3, $this->storage->countAll());

        static::assertEquals(
            [4 => 20, 3 => 15, 2 => 10],
            $this->storage->getSorted(0, 5)
        );
    }
}
