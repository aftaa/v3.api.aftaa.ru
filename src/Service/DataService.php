<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Repository\LinkRepository;

readonly class DataService
{
    public function __construct(
        private BlockRepository $blockRepository,
    )
    {
    }

    /**
     * @param bool $top
     * @return array
     */
    public function getData(bool $top = true): array
    {
        $columns = [];
        $blocks = $this->blockRepository->findBlocks();

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

                $link = [
                    'id' => $link->getId(),
                    'name' => $link->getName(),
                    'href' => $link->getHref(),
                    'icon' => $link->getIcon(),
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
        $columns['data'] = $columns;
        return $columns;
    }
}