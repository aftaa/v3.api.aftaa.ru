<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Repository\ViewRepository;
use App\Service\Trait\BlockToArrayTrait;
use App\Service\Trait\CreateColumnsTrait;
use App\Service\Trait\LinkToArrayTrait;
use App\Service\Trait\SortLinksTrait;
use Doctrine\ORM\Query\QueryException;

final class AdminDataService extends AbstractDataService
{
    /**
     * @return array
     */
    public function getPrivateData(): array
    {
        $blocks = $this->blockRepository->findNotDeletedOrdered();
        $columns = $this->createColumns($blocks, skipEmptyBlocks: false);

        $trash = $this->blockRepository->findTrash();
        $trash = $this->createTrash($trash);

        $views = $this->viewRepository->getTotalViews();
        $views = $this->createViews($views);

        return compact('columns', 'trash', 'views');
    }

    /**
     * @param Block[] $blocks
     * @return array
     */
    private function createTrash(array $blocks): array
    {
        $result = [];
        foreach ($blocks as $block) {
            $resultBlock = $block->toArray();
            foreach ($block->getLinks() as $link) {
                if ($link->isDeleted()) {
                    $resultBlock['links'][$link->getId()] = $link->toArray();
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
    /**
     * @param array $views
     * @return array
     */
    public function createViews(array $views): array
    {
        $arr = [];
        foreach ($views as $view) {
            $arr[$view['id']] = $view['count'];
        }
        return $arr;
    }
}
