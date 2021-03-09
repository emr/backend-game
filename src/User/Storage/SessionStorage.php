<?php

namespace App\User\Storage;

interface SessionStorage
{
    public function add(int $id): void;

    public function remove(int $id): void;

    public function has(int $id): bool;
}
