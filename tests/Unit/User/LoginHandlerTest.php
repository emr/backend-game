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

        static::assertFalse($loginHandler->hasLoggedIn('id'));
        $loginHandler->login('id');
        static::assertTrue($loginHandler->hasLoggedIn('id'));
        $loginHandler->logout('id');
        static::assertFalse($loginHandler->hasLoggedIn('id'));
    }
}
