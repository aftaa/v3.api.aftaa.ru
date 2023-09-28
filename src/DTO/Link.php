<?php

namespace App\DTO;

readonly class Link
{
    use ModifyEntityTrait;

    public function __construct(
        public int $block_id,
        public string $name,
        public string $href,
        public string $icon,
        public bool $private,
    )
    {
    }
}
