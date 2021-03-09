<?php

namespace App\Http\Schema\Response;

use Symfony\Component\Serializer\Annotation\Groups;

class SuccessResponse
{
    #[Groups(['response'])]
    public string $status = 'success';

    #[Groups(['response'])]
    public \DateTimeInterface $timestamp;

    #[Groups(['response'])]
    public mixed $result;

    public function __construct(mixed $result)
    {
        $this->timestamp = new \DateTime();
        $this->result = $result;
    }
}
