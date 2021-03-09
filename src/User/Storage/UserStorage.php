<?php

namespace App\User\Storage;

use App\User\User;

interface UserStorage
{
    public function persist(User $user): void;

    public function usernameExists(string $username): bool;

    public function findById(string $id): ?User;

    public function findByUsername(string $username): ?User;
}
