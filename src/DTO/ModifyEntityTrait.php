<?php

namespace App\DTO;

trait ModifyEntityTrait
{
    public function modifyEntity(object &$entity, array $skip = []): void
    {
        foreach ($this as $field => $value) {
            if (in_array($field, $skip)) continue;
            $entity->{'set' . ucfirst($field)}($value);
        }
    }
}