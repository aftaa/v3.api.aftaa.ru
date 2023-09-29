<?php

namespace App\Service\Helper;

trait SortLinksTrait
{
    /**
     * @param array $links
     * @return void
     */
    public function sortLinks(array &$links): void
    {
        usort($links, function (array $link1, array $link2): int {
            return strcmp($link1['name'], $link2['name']);
        });
    }
}
