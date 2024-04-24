<?php

namespace Traits\Repository;

trait HydrateEntityTrait
{
    public function hydrate(string $class, $data)
    {
        $reflectionClass = new \ReflectionClass($class);
        $entity = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($data as $columnName => $columnValue) {
            $columnName = str_replace('_id', '', $columnName);
            $methodName = 'set' . ucfirst($columnName);

            if (method_exists($entity, $methodName)) {
                $entity->$methodName($columnValue);
            }
        }

        return $entity;
    }
}
