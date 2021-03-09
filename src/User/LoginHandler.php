<?php

namespace App\User;

use App\User\Storage\SessionStorage;

class LoginHandler
{
    public function __construct(private SessionStorage $storage)
    {
    }

    public function login(string $id): void
    {
        $this->storage->add($id);
    }

    public function logout(string $id): void
    {
        $this->storage->remove($id);
    }

    public function hasLoggedIn(string $id): bool
    {
        return $this->storage->has($id);
    }
}
