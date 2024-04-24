<?php

namespace Traits;

trait FileTypeTrait
{
    private function getFileType(array $file): string
    {
        $tmpName = $file['tmp_name'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileType = finfo_file($fileInfo, $tmpName);
        finfo_close($fileInfo);

        return $fileType;
    }
}
