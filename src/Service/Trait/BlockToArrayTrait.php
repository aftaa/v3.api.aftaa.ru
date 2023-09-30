<?php

namespace App\Service\Trait;

use App\Entity\Block;

trait BlockToArrayTrait
{
    /**
     * @param Block $block
     * @return array
     */
    protected function blockToArray(Block $block): array
    {
        return [
            'id' => $block->getId(),
            'name' => $block->getName(),
            'col' => $block->getCol(),
            'sort' => $block->getSort(),
            'deleted' => $block->isDeleted(),
            'private' => $block->isPrivate(),
            'links' => [],
        ];
    }
}
