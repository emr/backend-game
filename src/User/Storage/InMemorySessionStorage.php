<?php

namespace App\User\Storage;

class InMemorySessionStorage implements SessionStorage
{
    /**
     * @var array<int, true>
     */
    private array $hash = [];

    public function add(int $id): void
    {
        $this->hash[$id] = true;
    }

    public function remove(int $id): void
    {
        unset($this->hash[$id]);
    }

    public function has(int $id): bool
    {
        return \array_key_exists($id, $this->hash);
    }
}
