<?php

require_once '../../autoload.php';

use Controller\BooksController;
use Log\LogErrorService;

try {
    $crontabErrors = new LogErrorService();
    $booksController = new BooksController();

    $booksController->uploadFromDirectory();

    $logFilePath = 'crontab.log';

    $logMessage = date('Y-m-d H:i:s') . " - crontab.php executed\n";

    file_put_contents($logFilePath, $logMessage, FILE_APPEND);
} catch (Exception $e) {
    $crontabErrors = new LogErrorService();
    $crontabErrors->logException($e);

    echo "Error executing uploadFromDirectory action. Check the log for details.";
}
