<?php

namespace App\Tests\Unit\User;

use App\User\Storage\InMemoryUserStorage;
use App\User\User;
use PHPUnit\Framework\TestCase;

class InMemoryUserStorageTest extends TestCase
{
    private InMemoryUserStorage $storage;

    protected function setUp(): void
    {
        $this->storage = new InMemoryUserStorage();
    }

    public function testPersist(): void
    {
        $user = new User('test', '123');
        $this->storage->persist($user);

        static::assertEquals('1', $user->id);

        $user2 = new User('test2', '123');
        $this->storage->persist($user2);

        static::assertEquals('2', $user2->id);
        static::assertNotSame($user2->id, $user->id);
    }

    public function testUsernameExists(): void
    {
        static::assertFalse($this->storage->usernameExists('test'));

        $this->storage->persist(new User('test', '123'));

        static::assertTrue($this->storage->usernameExists('test'));
    }

    public function testFindById(): void
    {
        static::assertNull($this->storage->findById('1'));

        $user = new User('test', '123');
        $this->storage->persist($user);

        static::assertNotNull($user->id);
        static::assertSame($user, $this->storage->findById($user->id));
    }

    public function testFindByUsername(): void
    {
        static::assertNull($this->storage->findById('1'));

        $user = new User('test', '123');
        $this->storage->persist($user);

        static::assertSame($user, $this->storage->findByUsername('test'));
    }
}
