<?php

namespace App\Service;

use App\Repository\{BlockRepository, ViewRepository};
use App\Service\Trait\{BlockToArrayTrait, CreateColumnsTrait, LinkToArrayTrait, SortLinksTrait};

abstract class AbstractDataService
{
    use BlockToArrayTrait;
    use LinkToArrayTrait;
    use SortLinksTrait;
    use CreateColumnsTrait;

    public function __construct(
        protected readonly BlockRepository $blockRepository,
        protected readonly ViewRepository  $viewRepository,
    )
    {
    }

    abstract public function getData(): array;
}
