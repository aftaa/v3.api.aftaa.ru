<?php

namespace App\DTO;

trait ModifyEntityTrait
{
    public function modifyEntity(object &$entity): void
    {
        foreach ($this as $field => $value) {
            if ('boolean' === gettype($value) && null === $value) {
                $value = false;
            }
            $entity->{'set' . ucfirst($field)}($value);
        }
    }
}