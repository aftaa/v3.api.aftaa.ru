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
            if (!count($block->getLinks()) && $skipEmptyBlocks) {
                continue;
            }
            $links = $block->getLinks();
            $arr = [];
            foreach ($links as $link) {
                if ($link->isDeleted()) {
                    continue;
                }

                if ($skipPrivate && $link->isPrivate()) {
                    continue;
                }

                $arr[$link->getId()] = $link->toArray();
            }
            $this->sortLinks($arr);
            $block = $block->toArray();
            $block['links'] = $arr;
            $columns[$block['col']][] = $block;
        }
        return $columns;
    }
}
