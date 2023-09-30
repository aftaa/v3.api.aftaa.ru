<?php

namespace App\Service\Trait;

trait SortLinksTrait
{
    /**
     * @param array $links
     * @return void
     */
    protected function sortLinks(array &$links): void
    {
        usort($links, function (array $link1, array $link2): int {
            return strcmp($link1['name'], $link2['name']);
        });
    }
}
