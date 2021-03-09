<?php

namespace App\User\Storage;

use App\User\User;

class RedisUserStorage implements UserStorage
{
    public const INCREMENTER_KEY = 'user:';
    public const USER_HASH_NAMESPACE = 'user:';
    public const USERNAME_TO_ID_HASH_KEY = 'username:';

    public function __construct(private \Redis $client)
    {
    }

    public function persist(User $user): void
    {
        $id = $this->client->incr(self::INCREMENTER_KEY);
        $user->id = (string) $id;

        $this->client->hMSet(self::USER_HASH_NAMESPACE.$id, [
            'username' => $user->username,
            'password' => $user->password,
        ]);
        $this->client->hSet(self::USERNAME_TO_ID_HASH_KEY, $user->username, $user->id);
    }

    public function usernameExists(string $username): bool
    {
        return false !== $this->client->hGet(self::USERNAME_TO_ID_HASH_KEY, $username);
    }

    public function findById(string $id): ?User
    {
        $data = $this->client->hGetAll(self::USER_HASH_NAMESPACE.$id);

        if ([] === $data) {
            return null;
        }

        $user = new User($data['username'], $data['password']);
        $user->id = $id;

        return $user;
    }

    public function findByUsername(string $username): ?User
    {
        $id = $this->client->hGet(self::USERNAME_TO_ID_HASH_KEY, $username);

        if (false === $id) {
            return null;
        }

        return $this->findById($id);
    }
}
