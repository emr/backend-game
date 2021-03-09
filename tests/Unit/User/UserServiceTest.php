<?php

namespace App\Tests\Unit\User;

use App\User\CreateUser;
use App\User\Exception\DuplicateRecordException;
use App\User\Storage\InMemoryUserStorage;
use App\User\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        $this->userService = new UserService(new InMemoryUserStorage());
    }

    public function testRegister(): void
    {
        $user = $this->userService->register(new CreateUser('test', '123'));

        static::assertNotEmpty($user->id);
        static::assertEquals('test', $user->username);
        static::assertEquals('123', $user->password);
    }

    public function testRegisterDuplicate(): void
    {
        $this->userService->register(new CreateUser('test', '123'));

        static::expectException(DuplicateRecordException::class);
        static::expectExceptionMessage('The given username is already in use');

        $this->userService->register(new CreateUser('test', '123'));
    }

    public function testFindById(): void
    {
        $user1 = $this->userService->register(new CreateUser('user1', '123'));
        $user2 = $this->userService->register(new CreateUser('user2', '123'));
        $user3 = $this->userService->register(new CreateUser('user3', '123'));

        static::assertNotNull($user2->id);
        static::assertSame($user2, $this->userService->findById($user2->id));
        static::assertNull($this->userService->findById('non-existing-id'));
    }

    public function testFindByUsername(): void
    {
        $user1 = $this->userService->register(new CreateUser('user1', '123'));
        $user2 = $this->userService->register(new CreateUser('user2', '123'));
        $user3 = $this->userService->register(new CreateUser('user3', '123'));

        static::assertSame($user2, $this->userService->findByUsername('user2'));
        static::assertNull($this->userService->findByUsername('non_existing_username'));
    }
}
