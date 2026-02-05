<?php

namespace App\Core;

class Controller
{
    public function __construct()
    {
        // espaço para auth, middleware, etc
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);

        require dirname(__DIR__) . '/Views/layout/header.php';
        require dirname(__DIR__) . '/Views/' . $view . '.php';
        require dirname(__DIR__) . '/Views/layout/footer.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
