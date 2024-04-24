<?php

spl_autoload_register(function ($class) {
    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    $directories = array(
        'src/',
    );

    foreach ($directories as $directory) {
        $file = $directory . $classFile;

        if (file_exists($file)) {
            require_once $file;
            return;
        }

        $autoloadDirectory = __DIR__;
        $foundFile = findFileRecursively($autoloadDirectory, $classFile);

        if ($foundFile !== null) {
            require_once $foundFile;
            return;
        }
    }

    throw new \Exception("Class $class not found");
});

function findFileRecursively($directory, $classFile) {
    if (!is_dir($directory) || !is_readable($directory)) {
        return null;
    }

    $files = glob($directory . '/*');

    if (empty($files)) {
        return null;
    }

    foreach ($files as $file) {
        if (is_dir($file)) {
            $foundFile = findFileRecursively($file, $classFile);
            if ($foundFile !== null) {
                return $foundFile;
            }
        } elseif (basename($file) === basename($classFile)) {
            return $file;
        }
    }

    return null;
}

