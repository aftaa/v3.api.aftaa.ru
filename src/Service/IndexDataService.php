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

readonly class IndexDataService
{
    use BlockToArrayTrait;
    use LinkToArrayTrait;
    use SortLinksTrait;
    use CreateColumnsTrait;

    public function __construct(
        private BlockRepository $blockRepository,
        private ViewRepository  $viewRepository,
    )
    {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $blocks = $this->blockRepository->findNotDeletedOrdered();
        $columns = $this->createColumns(blocks: $blocks, skipEmptyBlocks: true);

        $top = $this->viewRepository->findTop(23);
        $top = $this->createTop($top);

        return compact('columns', 'top');
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
}
