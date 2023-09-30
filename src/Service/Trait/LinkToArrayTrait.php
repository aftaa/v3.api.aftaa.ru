<?php

namespace App\Service\Trait;

use App\Entity\Link;

trait LinkToArrayTrait
{
    /**
     * @param Link $link
     * @return array
     */
    protected function linkToArray(Link $link): array
    {
        return [
            'id' => $link->getId(),
            'name' => $link->getName(),
            'href' => $link->getHref(),
            'icon' => $link->getIcon(),
            'private' => $link->isPrivate(),
        ];
    }
}
