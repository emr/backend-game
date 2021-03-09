<?php

namespace App\User\Storage;

interface SessionStorage
{
    public function add(string $id): void;

    public function remove(string $id): void;

    public function has(string $id): bool;
}
