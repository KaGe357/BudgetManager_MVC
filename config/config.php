<?php

use Dotenv\Dotenv;

// Załaduj zmienne środowiskowe
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'user' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'dbname' => $_ENV['DB_NAME'] ?? 'budgetmanager',
    'environment' => $_ENV['APP_ENV'] ?? 'production'
];
