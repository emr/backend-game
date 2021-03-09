<?php

namespace App\User\Storage;

class RedisSessionStorage implements SessionStorage
{
    private const SET_KEY = 'login:';

    public function __construct(private \Redis $client)
    {
    }

    public function add(int $id): void
    {
        $this->client->sAdd(self::SET_KEY, (string) $id);
    }

    public function remove(int $id): void
    {
        $this->client->sRem(self::SET_KEY, (string) $id);
    }

    public function has(int $id): bool
    {
        return $this->client->sIsMember(self::SET_KEY, (string) $id);
    }
}
