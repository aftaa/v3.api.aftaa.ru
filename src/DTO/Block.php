<?php

namespace App\DTO;

class BlockDTO
{
    use ModifyEntityTrait;

    public function __construct(
        public string $name,
        public int $col,
        public int $sort,
        public bool $private,
    )
    {
    }
}
