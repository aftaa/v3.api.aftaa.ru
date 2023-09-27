<?php

namespace App\DTO;

readonly class BlockDTO
{
    public function __construct(
        public string $name,
        public int $col,
        public int $sort,
        public bool $public,
    )
    {
    }
}
