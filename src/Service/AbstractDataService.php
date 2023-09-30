<?php

namespace App\Service;

use App\Repository\BlockRepository;
use App\Repository\ViewRepository;
use App\Service\Trait\BlockToArrayTrait;
use App\Service\Trait\CreateColumnsTrait;
use App\Service\Trait\LinkToArrayTrait;
use App\Service\Trait\SortLinksTrait;

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
