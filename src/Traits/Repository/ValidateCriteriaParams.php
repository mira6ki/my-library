<?php

namespace Traits\Repository;

trait ValidateCriteriaParams
{
    public function validateCriteriaParams(string $entity, array $params): array
    {
        $reflectionClass = new \ReflectionClass($entity);

        $properties = $reflectionClass->getProperties();

        $validCriteria = [];
        foreach ($params as $param => $value) {
            foreach ($properties as $property) {
                if ($param === $property->getName()) {
                    $validCriteria[$param] = $value;
                    break;
                }
            }
        }

        return $validCriteria;
    }
}
