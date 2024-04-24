<?php

namespace Service;

use Entity\Author;
use Entity\Book;
use Traits\DataValidationTrait;
use Traits\FileTypeTrait;
use Traits\LogErrorServiceTrait;
use Traits\Repository\RepositoryTrait;

class XmlService
{
    use RepositoryTrait,
        FileTypeTrait,
        DataValidationTrait,
        LogErrorServiceTrait;

    public function processXMLData(\SimpleXMLElement $xml): void
    {
        foreach ($xml->book as $book) {
            if (empty($book->author) || empty($book->name)) {
                continue;
            }

            $authorName = (string)$book->author;
            $bookDate = (string) $book->date;

            if (! empty($bookDate)) {
                $bookDate = new \DateTime($bookDate);
                $bookDate = $bookDate->format('Y-m-d');
            } else {
                $bookDate = null;
            }

            $authorsRepository = $this->getRepository(Author::class);

            $author = $authorsRepository->findOneByCriteria(['name' => $authorName]);

            if (!$author) {
                $author = $authorsRepository->addAuthor(
                    [
                        'name' => $authorName
                    ]
                );
            }

            $bookName = (string)$book->name;
            $booksRepository = $this->getRepository(Book::class);

            $existingBook = $booksRepository->findOneByCriteria(
                [
                    'name' => $bookName,
                    'author' => $author->getId()
                ]
            );

            if (!$existingBook) {
                $booksRepository->addBook(
                    [
                        'author' => $author->getId(),
                        'name' => $bookName,
                        'date' => $bookDate
                    ]
                );
            } else {
                $booksRepository->updateDatabaseRecord(
                    [
                        'date' => $bookDate
                    ],
                    $existingBook
                );
            }
        }
    }

    public function processXMLFilePath(string $filePath): ? \SimpleXMLElement
    {
        try {
            $xml = simplexml_load_file($filePath);

            if ($xml !== false) {
                $this->processXMLData($xml);
            }
        } catch (\Exception $e) {
            $this->getLogErrorsService()->logException($e);

            echo $e->getMessage();
        }

        return $xml;
    }

    public function processDirectory(string $directoryPath): void
    {
        $directoryIterator = new \RecursiveDirectoryIterator($directoryPath, \FilesystemIterator::SKIP_DOTS);

        $files = new \RecursiveIteratorIterator(
            $directoryIterator,
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $filePath = $file->getPathname();

                if ($this->isXMLFile($filePath)) {
                    $this->processXMLFilePath($filePath);
                }
            }
        }
    }

    public function isXMLFile(string $filePath): bool
    {
        $fileType = mime_content_type($filePath);

        return $fileType === 'application/xml' || $fileType === 'text/xml';
    }
}
