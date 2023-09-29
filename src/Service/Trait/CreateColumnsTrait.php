<?php

namespace App\Service\Trait;

trait CreateColumnsTrait
{
    /**
     * @param array $blocks
     * @param bool $skipEmptyBlocks
     * @return array
     */
    private function createColumns(array $blocks, bool $skipEmptyBlocks): array
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
