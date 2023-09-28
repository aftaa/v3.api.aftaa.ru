<?php

namespace App\Service;

use App\Entity\Block;
use App\Entity\Link;
use App\Repository\BlockRepository;
use App\Repository\ViewRepository;

readonly class DataService
{
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
            $row['icon'] = $this->replaceIconHref($row['icon']);
            $top['s' . $row['id']] = $row;
        }

        $columns = $this->processColumns(blocks: $blocks, skipEmptyBlocks: true);
        $data['columns'] = $columns;
        $data['top'] = $top;
        return $data;
    }

    public function getAdminData(): object
    {
        $blocks = $this->blockRepository->findBlocks();
        $trash = $this->blockRepository->findBlocks(true);
        $columns = $this->processColumns($blocks, skipEmptyBlocks: false);
        $trash = $this->processColumns($trash, skipEmptyBlocks: false);
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

                $icon = $link->getIcon();
                $icon = $this->replaceIconHref($icon);

                /** @var Link $link */
                $link = [
                    'id' => $link->getId(),
                    'name' => $link->getName(),
                    'href' => $link->getHref(),
                    'private' => $link->isPrivate(),
                    'icon' => $icon,
                ];
                $data[$link['id']] = $link;
            }

            usort($data, function (array $link1, array $link2): int {
                return strcmp($link1['name'], $link2['name']);
            });

            /** @var Block $block */
            $block = [
                'id' => $block->getId(),
                'name' => $block->getName(),
                'col' => $block->getCol(),
                'private' => $block->isPrivate(),
                'links' => $data,
            ];
            $columns[$block['col']][] = $block;
        }
        return $columns;
    }

    /**
     * @param mixed $icon
     * @return array|mixed|string|string[]
     */
    private function replaceIconHref(mixed $icon): mixed
    {
        return str_replace('https://v2.api.aftaa.ru', 'https://v3.api.aftaa.ru', $icon);
    }
}
