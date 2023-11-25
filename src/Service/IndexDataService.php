<?php

namespace App\Service;

use Doctrine\DBAL\Exception;

final class IndexDataService extends AbstractDataService
{
    public function getPublicData(): array
    {
        $publicData = $this->blockRepository->findPublicData();
        $publicData = $this->createColumns($publicData, true, true);
        return $publicData;
    }
    /**
     * @return array
     * @throws Exception
     */
    public function getPrivateData(): array
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
