<?php

namespace Repository;

use Entity\Author;
use Model\RepositoryInterface;
use Traits\Repository\DefaultRepository;

class AuthorRepository extends DatabaseConnection implements
    RepositoryInterface
{
    use DefaultRepository;
    const ENTITY = Author::class;
    const TABLE = 'authors';
    public function getAll(array $params = []): array
    {
        return [];
    }

    public function addAuthor(array $params): Author
    {
        return $this->addDatabaseRecord($params);
    }
}
