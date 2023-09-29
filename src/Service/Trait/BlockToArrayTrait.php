<?php

namespace App\Service\Helper;

use App\Entity\Block;

trait BlockToArrayTrait
{
    /**
     * @param Block $block
     * @return array
     */
    public function blockToArray(Block $block): array
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
