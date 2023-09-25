<?php

namespace App\Service;

use App\Repository\BlockRepository;
use App\Repository\LinkRepository;

class DataService
{
    public function __construct(
        private BlockRepository $blockRepository,
        private LinkRepository $linkRepository,
    )
    {
    }

    public function getData(bool $top = true)
    {
        $blocks = $this->blockRepository->findBlocks();
        dump($blocks);
        return $blocks;
    }
}