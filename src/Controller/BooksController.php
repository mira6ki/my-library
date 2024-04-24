<?php

namespace Controller;

require_once '../../autoload.php';

use Entity\Book;
use Traits\DataValidationTrait;
use Traits\FileTypeTrait;
use Traits\Repository\RepositoryTrait;
use Traits\XMLServiceTrait;

class BooksController
{
    use RepositoryTrait,
        FileTypeTrait,
        DataValidationTrait,
        XMLServiceTrait;

    public function getAllBooks(): void
    {
        $params = [];
        if (! empty($_GET['search'])) {
            $search = $this->filterInput($_GET['search']);
            $params['search'] = $search;
        }

        $booksRepository = $this->getRepository(Book::class);
        $books = $booksRepository->getAll($params);
        $booksRepository->closeConnection();


        echo json_encode($books);
    }

    public function uploadBookList()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];

            if ($file['error'] === UPLOAD_ERR_OK) {
                $fileType = $this->getFileType($_FILES['file']);

                if (! in_array(
                    $fileType,
                    [
                        'text/xml'
                    ]
                )) {
                    throw new \Exception('Uploaded file must be xmp');
                }

                $tempFilePath = $file['tmp_name'];

                $xml = $this->getXMLService()->processXMLFilePath($tempFilePath);

                if (empty($xml)) {
                    throw new \Exception('Error parsing XML');
                }

                return json_encode(['success' => true]);
            } else {
                throw new \Exception('Error uploading file');
            }
        } else {
            throw new \Exception('Invalid request');
        }
    }

    public function uploadFromDirectory(): void
    {
        $directoryPath = 'C:\xampp\htdocs\public\xmlFiles';

        $this->getXMLService()->processDirectory($directoryPath);
    }
}
