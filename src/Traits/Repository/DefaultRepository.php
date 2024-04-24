<?php

namespace Traits\Repository;

use Exception\UniqueViolationException;
use PDO;
use Traits\DataValidationTrait;

trait DefaultRepository
{
    use ValidateCriteriaParams,
        HydrateEntityTrait,
        DataValidationTrait;

    public function findOneByCriteria(array $params): ?object
    {
        $validCriteria = $this->validateCriteriaParams(self::ENTITY, $params);

        if (empty($validCriteria)) {
            return null;
        }

        $sql = sprintf("SELECT * FROM %s WHERE ", self::TABLE);
        $queryParams = [];
        foreach ($validCriteria as $param => $value) {
            $queryParams[] = "$param = :$param";
        }
        $sql .= implode(" AND ", $queryParams);

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($validCriteria);
        $entities = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (! empty($entities) && count($entities) > 1) {
            throw new UniqueViolationException(
                sprintf(
                    '%s records found by the provided criteria.',
                    count($entities)
                )
            );
        } elseif (empty($entities)) {
            return null;
        }

        $entity = $entities[0];

        return $this->hydrate(self::ENTITY, $entity);
    }

    public function addDatabaseRecord(array $fields): object
    {
        $reflectionClass = new \ReflectionClass(self::ENTITY);
        $properties = $reflectionClass->getProperties();

        $columns = [];
        $placeholders = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($propertyName !== 'id') {
                $columns[] = $propertyName;
                $placeholders[] = ':' . $propertyName;
            }
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            self::TABLE,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $result = $this->getConnection()->prepare($sql);

        $params = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($property->getName() == 'id') {
                continue;
            }

            $paramValue = $fields[$propertyName] ?? null;
            $paramValue = $this->filterInput($paramValue);
            if (empty($paramValue)) {
                $paramValue = null;
            }

            $params[$propertyName] = $paramValue;
        }

        $result->execute($params);
        ;
        $entityId = $this->getConnection()->lastInsertId();
        $entityClass = self::ENTITY;
        $entity = new $entityClass();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if ($propertyName == 'id') {
                $entity->setId($entityId);
            } else {
                $methodName = 'set' . ucfirst($propertyName);

                if (method_exists($entity, $methodName)) {
                    $paramValue = $params[$propertyName] ?? null;
                    $entity->$methodName($paramValue);
                }
            }
        }

        return $entity;
    }

    public function updateDatabaseRecord(array $fields, object $entity): object
    {
        $reflectionClass = new \ReflectionClass(self::ENTITY);
        $properties = $reflectionClass->getProperties();

        $updateColumns = [];
        $params = ['id' => $entity->getId()];
        $shouldUpdate = false;

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($propertyName !== 'id' && isset($fields[$propertyName])) {
                $fieldValue = $fields[$propertyName];
                $getterMethod = 'get' . ucfirst($propertyName);
                $propertyValue = $entity->$getterMethod();

                if ($fieldValue !== $propertyValue) {
                    $updateColumns[] = "$propertyName = :$propertyName";
                    $params[$propertyName] = $this->filterInput($fieldValue);
                    $shouldUpdate = true;

                    $setterMethod = 'set' . ucfirst($propertyName);
                    if (method_exists($entity, $setterMethod)) {
                        $entity->$setterMethod($fieldValue);
                    }
                }
            }
        }

        if ($shouldUpdate) {
            $sql = sprintf(
                'UPDATE %s SET %s WHERE id = :id',
                self::TABLE,
                implode(', ', $updateColumns)
            );

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
        }

        return $entity;
    }
}
