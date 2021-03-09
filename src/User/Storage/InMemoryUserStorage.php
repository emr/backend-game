<?php

namespace App\User\Storage;

use App\User\User;

class InMemoryUserStorage implements UserStorage
{
    private int $idIncrementer = 1;

    /**
     * @var array<string, string>
     */
    private array $usernameToId = [];

    /**
     * @var array<string, User>
     */
    private array $users = [];

    public function persist(User $user): void
    {
        $user->id = (string) $this->idIncrementer++;
        $this->usernameToId[$user->username] = $user->id;
        $this->users[$user->id] = $user;
    }

    public function usernameExists(string $username): bool
    {
        return \array_key_exists($username, $this->usernameToId);
    }

    public function findById(string $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findByUsername(string $username): ?User
    {
        $id = $this->usernameToId[$username] ?? null;
        if (null === $id) {
            return null;
        }

        return $this->findById($id);
    }
}
