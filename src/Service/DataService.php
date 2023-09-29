<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use App\Repository\ViewRepository;
use App\Service\Trait\BlockToArrayTrait;
use App\Service\Trait\LinkToArrayTrait;
use App\Service\Trait\SortLinksTrait;
use Doctrine\ORM\Query\QueryException;

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
    public function index(): array
    {
        $blocks = $this->blockRepository->findNotDeletedOrdered();
        $columns = $this->createColumns(blocks: $blocks, skipEmptyBlocks: true);

        $top = $this->viewRepository->findTop(23);
        $top = $this->createTop($top);

        return compact('columns', 'top');
    }

    /**
     * @return array
     * @throws QueryException
     */
    public function admin(): array
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

    /**
     * @param Block[] $blocks
     * @return array
     */
    private function createTrash(array $blocks): array
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

    /**
     * @param array $rows
     * @return array
     */
    public function createTop(array $rows): array
    {
        $top = [];
        foreach ($rows as $row) {
            $top['_' . $row['id']] = $row;
        }
        return $top;
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
