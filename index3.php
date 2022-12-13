<?php

$folder = $_SERVER['DOCUMENT_ROOT'] . "/datafiles";
$fileNamesTemplate = "/[a-zA-z0-9]+\.ixt$/";

if (!is_dir($folder)) {
    echo "$folder не является директорией\n";
    die();
}

if ($files = scandir($folder)) {
    foreach ($files as $file) {
        if (!preg_match($fileNamesTemplate, $file)) {
            continue;
        }
        echo $file . "\n";
    }
} else {
    echo "Произошла ошибка при попытке доступа к $folder";
}