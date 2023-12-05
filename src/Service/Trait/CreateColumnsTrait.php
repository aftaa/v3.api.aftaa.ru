<?php

namespace App\Service\Trait;

use App\Entity\Block;

trait CreateColumnsTrait
{
    /**
     * @param Block[] $blocks
     * @param bool $skipEmptyBlocks
     * @param bool $skipPrivate
     * @return array
     */
    protected function createColumns(array $blocks, bool $skipEmptyBlocks, bool $skipPrivate = false): array
    {
        $columns = [];
        foreach ($blocks as $block) {
            if ($skipEmptyBlocks && !count($block->getLinks())) {
                continue;
            }
            $links = [];
            foreach ($block->getLinks() as $link) {
                if ($link->isDeleted()) {
                    continue;
                }

                if ($skipPrivate && $link->isPrivate()) {
                    continue;
                }

                $links[$link->getId()] = $link->toArray();
            }
            $this->sortLinks($links);
            $block = $block->toArray();
            $block['links'] = $links;
            $columns[$block['col']][] = $block;
        }
        return $columns;
    }
}
