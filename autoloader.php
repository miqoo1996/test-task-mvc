<?php

spl_autoload_register(function($class) {
    $baseDir = ROOT_DIR . '/';

    // Convert the class namespace to a file path
    $filePath = str_replace('\\', '/', $class) . '.php';

    // Build the full path to the file
    $fullPath = $baseDir . $filePath;

    // Check if the file exists
    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
});