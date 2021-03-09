<?php

namespace App\Tests\E2e;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient(server: [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        static::$container->get('Redis')->flushDB();
    }

    protected function signUp(string $username, string $password): void
    {
        $this->client->request(
            'POST',
            '/api/v1/user/signup',
            content: "{\"username\": \"{$username}\", \"password\": \"{$password}\"}"
        );
    }

    protected function signIn(string $username, string $password): void
    {
        $this->client->request(
            'POST',
            '/api/v1/user/signin',
            content: "{\"username\": \"{$username}\", \"password\": \"{$password}\"}"
        );
    }
}
