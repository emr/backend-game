<?php

namespace App\User;

use App\User\Exception\DuplicateRecordException;
use App\User\Storage\UserStorage;

class UserService
{
    public function __construct(private UserStorage $userStorage)
    {
    }

    public function register(CreateUser $data): User
    {
        if ($this->userStorage->usernameExists($data->username)) {
            throw new DuplicateRecordException('The given username is already in use');
        }

        $user = new User($data->username, $data->password);
        $this->userStorage->persist($user);

        return $user;
    }

    public function findById(string $id): ?User
    {
        return $this->userStorage->findById($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->userStorage->findByUsername($username);
    }
}
