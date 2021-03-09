<?php

namespace App\User\Storage;

class InMemorySessionStorage implements SessionStorage
{
    /**
     * @var array<string, true>
     */
    private array $hash = [];

    public function add(string $id): void
    {
        $this->hash[$id] = true;
    }

    public function remove(string $id): void
    {
        unset($this->hash[$id]);
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->hash);
    }
}
