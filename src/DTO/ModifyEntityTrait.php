<?php

namespace App\DTO;

trait ModifyEntityTrait
{
    public function modifyEntity(object &$entity): void
    {
        foreach ($this as $field => $value) {
            $entity->{'set' . ucfirst($field)}($value);
        }
    }
}