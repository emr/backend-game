<?php

namespace App\Tests\Unit\User;

use App\User\Storage\InMemorySessionStorage;
use PHPUnit\Framework\TestCase;

class InMemorySessionStorageTest extends TestCase
{
    public function testReadAndWrite(): void
    {
        $storage = new InMemorySessionStorage();

        static::assertFalse($storage->has(9));
        $storage->add(9);
        static::assertTrue($storage->has(9));
        $storage->remove(9);
        static::assertFalse($storage->has(9));
    }
}
