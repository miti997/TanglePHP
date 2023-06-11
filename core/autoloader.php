<?php

declare(strict_types=1);

spl_autoload_register(
    function ($className) {
        $classFile = str_replace('\\', DS, $className) . '.php';

        if (file_exists($classFile)) {
            require_once $classFile;
        }
    }
);
