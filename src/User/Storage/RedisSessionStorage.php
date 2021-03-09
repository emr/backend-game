<?php

namespace App\User\Storage;

class RedisSessionStorage implements SessionStorage
{
    private const SET_KEY = 'login:';

    public function __construct(private \Redis $client)
    {
    }

    public function add(string $id): void
    {
        $this->client->sAdd(self::SET_KEY, $id);
    }

    public function remove(string $id): void
    {
        $this->client->sRem(self::SET_KEY, $id);
    }

    public function has(string $id): bool
    {
        return $this->client->sIsMember(self::SET_KEY, $id);
    }
}
