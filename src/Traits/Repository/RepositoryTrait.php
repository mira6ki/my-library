<?php

namespace Traits\Repository;

use Entity\Author;
use Entity\Book;
use Model\RepositoryInterface;
use Repository\AuthorRepository;
use Repository\BookRepository;

trait RepositoryTrait
{
    public function getRepository(string $entityType): RepositoryInterface
    {
        return match ($entityType) {
            Book::class => new BookRepository(),
            Author::class => new AuthorRepository(),
            default => throw new \InvalidArgumentException('Invalid entity type'),
        };
    }
}
