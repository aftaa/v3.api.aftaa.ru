<?php

namespace App\DTO;

readonly class Block
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
