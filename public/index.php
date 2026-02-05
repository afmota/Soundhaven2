<?php

declare(strict_types=1);

// Inicia sessão (preparado para login futuro)
session_start();

// Autoload simples
spl_autoload_register(function (string $class) {
    $baseDir = dirname(__DIR__) . '/app/';

    $file = $baseDir . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Carrega .env manualmente
$envPath = dirname(__DIR__) . '/.env';

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}
