<?php

namespace App\User;

use App\User\Storage\SessionStorage;

class LoginHandler
{
    public function __construct(private SessionStorage $storage)
    {
    }

    public function login(int $id): void
    {
        $this->storage->add($id);
    }

    public function logout(int $id): void
    {
        $this->storage->remove($id);
    }

    public function hasLoggedIn(int $id): bool
    {
        return $this->storage->has($id);
    }
}
