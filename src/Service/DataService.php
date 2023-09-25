<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Repository\LinkRepository;
use function Sodium\compare;

class DataService
{
    public function __construct(
        private BlockRepository $blockRepository,
        private LinkRepository  $linkRepository,
    )
    {
    }

    public function getData(bool $top = true)
    {
        $data = [];
        $blocks = $this->blockRepository->findBlocks();

        /** @var Block $block */
        foreach ($blocks as $block) {

            if (!count($block->getLinks())) {
                continue;
            }

            $links = $block->getLinks();
            $dataLinks = [];
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
                $dataLinks[$link['id']] = $link;
            }

            usort($dataLinks, function (array $link1, array $link2): int {
                return strcmp($link1['name'], $link2['name']);
            });

            $block = [
                'id' => $block->getId(),
                'name' => $block->getName(),
                'col' => $block->getCol(),
                'links' => $dataLinks,
            ];
            $data[$block['id']] = $block;
        }
        $data['data'] = $data;

        return $data;
    }
}