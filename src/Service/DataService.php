<?php

namespace App\Service;

use App\Entity\Block;
use App\Entity\Link;
use App\Repository\BlockRepository;
use App\Repository\ViewRepository;
use App\Service\Helper\BlockToArrayTrait;
use App\Service\Helper\LinkToArrayTrait;
use App\Service\Helper\SortLinksTrait;

readonly class DataService
{
    use BlockToArrayTrait;
    use LinkToArrayTrait;
    use SortLinksTrait;

    public function __construct(
        private BlockRepository $blockRepository,
        private ViewRepository  $viewRepository,
    )
    {
    }

    /**
     * @return array
     */
    public function getIndexData(): array
    {
        $blocks = $this->blockRepository->findBlocks();
        $rows = $this->viewRepository->findTop(23);
        $top = [];
        foreach ($rows as &$row) {
            $top['_' . $row['id']] = $row;
        }

        $columns = $this->processColumns(blocks: $blocks, skipEmptyBlocks: true);
        $data['columns'] = $columns;
        $data['top'] = $top;
        return $data;
    }

    public function getAdminData(): object
    {
        $blocks = $this->blockRepository->findBlocks();
        $trash = $this->blockRepository->findTrash();
        $trash = $this->processTrash($trash);
        $columns = $this->processColumns($blocks, skipEmptyBlocks: false);
        $views = $this->viewRepository->getViews();
        return (object)[
            'columns' => $columns,
            'trash' => $trash,
            'views' => $views,
        ];
    }

    /**
     * @param array $blocks
     * @param bool $skipEmptyBlocks
     * @return array
     */
    private function processColumns(array $blocks, bool $skipEmptyBlocks): array
    {
        $columns = [];
        foreach ($blocks as $block) {

            if (!count($block->getLinks()) && $skipEmptyBlocks) {
                continue;
            }

            $links = $block->getLinks();
            $data = [];
            foreach ($links as $link) {

                if ($link->isDeleted()) {
                    continue;
                }

                $link = $this->linkToArray($link);
                $data[$link['id']] = $link;
            }

            usort($data, function (array $link1, array $link2): int {
                return strcmp($link1['name'], $link2['name']);
            });

            $block = $this->blockToArray($block);
            $block['links'] = $data;
            $columns[$block['col']][] = $block;
        }
        return $columns;
    }

    /**
     * @param Block[] $blocks
     * @return array
     */
    private function processTrash(array $blocks): array
    {
        $result = [];
        foreach ($blocks as $block) {
            $resultBlock = $this->blockToArray($block);
            foreach ($block->getLinks() as $link) {
                if ($link->isDeleted()) {
                    $resultBlock['links'][$link->getId()] = $this->linkToArray($link);
                }
            }
            $result[$block->getCol()][$block->getId()] = $resultBlock;
        }

        foreach ($result as $col => &$blocks) {
            foreach ($blocks as $blockId => &$block) {
                if ($block['deleted'] || count($block['links']) > 0) {
                    $this->sortLinks($block['links']);
                } else {
                    unset($result[$col][$blockId]);
                }
            }
        }
        return $result;
    }
}
