<?php

namespace App\Service\Trait;

use App\Entity\Link;

trait CreateColumnsTrait
{
    /**
     * @param array $blocks
     * @param bool $skipEmptyBlocks
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
                /** @var $link Link */
                if ($link->isDeleted()) {
                    continue;
                }
                if ($link->isPrivate() && $skipPrivate) {
                    continue;
                }
                $link = $this->linkToArray($link);
                $arr[$link['id']] = $link;
            }
            $this->sortLinks($arr);
            $block = $this->blockToArray($block);
            $block['links'] = $arr;
            $columns[$block['col']][] = $block;
        }
        return $columns;
    }
}
