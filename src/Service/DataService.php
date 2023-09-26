<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Repository\ViewRepository;

readonly class DataService
{
    public function __construct(
        private BlockRepository $blockRepository,
        private ViewRepository $viewRepository,
    )
    {
    }

    /**
     * @param bool $top
     * @return array
     */
    public function getData(bool $displayTop = true): array
    {
        $columns = [];
        $blocks = $this->blockRepository->findBlocks();
        if ($displayTop) {
            $top = $this->viewRepository->findTop(23);
            foreach ($top as &$row) {
                $row['icon'] = str_replace('https://v2.api.aftaa.ru', 'https://v3.api.aftaa.ru', $row['icon']);
            }
        }

        /** @var Block $block */
        foreach ($blocks as $block) {

            if (!count($block->getLinks())) {
                continue;
            }

            $links = $block->getLinks();
            $data = [];
            foreach ($links as $link) {

                if ($link->isDeleted()) {
                    continue;
                }

                $icon = $link->getIcon();
                $icon = str_replace('https://v2.api.aftaa.ru', 'http://v3.api.aftaa', $icon);

                $link = [
                    'id' => $link->getId(),
                    'name' => $link->getName(),
                    'href' => $link->getHref(),
                    'icon' => $icon,
                ];
                $data[$link['id']] = $link;
            }

            usort($data, function (array $link1, array $link2): int {
                return strcmp($link1['name'], $link2['name']);
            });

            $block = [
                'id' => $block->getId(),
                'name' => $block->getName(),
                'col' => $block->getCol(),
                'links' => $data,
            ];
            $columns[$block['col']][$block['id']] = $block;
        }
        $columns['columns'] = $columns;
        if ($displayTop) {
            $columns['top'] = $top;
        }
        return $columns;
    }
}
