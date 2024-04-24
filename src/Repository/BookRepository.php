<?php

namespace Repository;

use Entity\Book;
use Model\RepositoryInterface;
use Traits\Repository\DefaultRepository;

class BookRepository extends DatabaseConnection implements
    RepositoryInterface
{
    use DefaultRepository;

    const ENTITY = Book::class;
    const TABLE = 'books';

    public function getAll(array $params = []): array
    {
        $columns = [
            'books.id as booksId',
            'books.name as bookName',
            'books.date as date',
            'authors.name as author'
        ];

        $joins = [
            'JOIN authors ON books.author = authors.id'
        ];
        $where = [];
        $paramsToBind = [];

        if (!empty($params['search'])) {
            $searchTerm = $params['search'];

            $where[] = "to_tsvector('simple', books.name) @@ plainto_tsquery('simple', ?)
                    OR to_tsvector('simple', authors.name) @@ plainto_tsquery('simple', ?)";
            $paramsToBind[] = $searchTerm;
            $paramsToBind[] = $searchTerm;
        }

        $sql = sprintf(
            'SELECT %s FROM books %s %s',
            join(',', $columns),
            join(' ', $joins),
            !empty($where) ? 'WHERE ' . join(' AND ', $where) : ''
        );

        $result = $this->getConnection()->prepare($sql);

        foreach ($paramsToBind as $index => $param) {
            $result->bindValue($index + 1, $param);
        }

        $result->execute();
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addBook(array $params): Book
    {
        return $this->addDatabaseRecord($params);
    }
}
