<?php

namespace App\Service;

use Doctrine\DBAL\Exception;

final class IndexDataService extends AbstractDataService
{
    /**
     * @return array
     * @throws Exception
     */
    public function getData(): array
    {
        $blocks = $this->blockRepository->findNotDeletedOrdered();
        $columns = $this->createColumns(blocks: $blocks, skipEmptyBlocks: true);

        $top = $this->viewRepository->findTop(17);
        $top = $this->createTop($top);

        $last = $this->viewRepository->findLast(7);

        return compact('columns', 'top', 'last');
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
