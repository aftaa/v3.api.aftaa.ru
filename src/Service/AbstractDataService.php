<?php

namespace App\Service;

use App\Repository\{BlockRepository, ViewRepository};
use App\Service\Trait\{CreateColumnsTrait, SortLinksTrait};

abstract class AbstractDataService
{
    use SortLinksTrait;
    use CreateColumnsTrait;

    public function __construct(
        protected readonly BlockRepository $blockRepository,
        protected readonly ViewRepository  $viewRepository,
    )
    {
    }

    abstract public function getPrivateData(): array;
}
