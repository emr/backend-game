<?php

namespace App\Tests\Unit\User;

use App\User\LoginHandler;
use App\User\Storage\InMemorySessionStorage;
use PHPUnit\Framework\TestCase;

class LoginHandlerTest extends TestCase
{
    public function testSession(): void
    {
        $loginHandler = new LoginHandler(new InMemorySessionStorage());

        static::assertFalse($loginHandler->hasLoggedIn(8));
        $loginHandler->login(8);
        static::assertTrue($loginHandler->hasLoggedIn(8));
        $loginHandler->logout(8);
        static::assertFalse($loginHandler->hasLoggedIn(8));
    }
}
