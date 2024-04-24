<?php

namespace Log;

class LogErrorService
{
    const logFilePath = "../../logError.log";

    public function logException(\Exception $exception): void
    {
        $logEntry = sprintf(
            "[%s] Exception: %s\nFile: %s\nLine: %d\nTrace: %s\n\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        file_put_contents(self::logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
