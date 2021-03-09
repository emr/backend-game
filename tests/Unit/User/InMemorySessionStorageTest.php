<?php

namespace App\Tests\Unit\User;

use App\User\Storage\InMemorySessionStorage;
use PHPUnit\Framework\TestCase;

class InMemorySessionStorageTest extends TestCase
{
    public function testReadAndWrite(): void
    {
        $storage = new InMemorySessionStorage();

        static::assertFalse($storage->has('test'));
        $storage->add('test');
        static::assertTrue($storage->has('test'));
        $storage->remove('test');
        static::assertFalse($storage->has('test'));
    }
}
