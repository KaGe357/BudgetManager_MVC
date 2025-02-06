<?php

require_once './vendor/autoload.php';

use App\Database\DatabaseConnection;
use App\Database\DatabaseInitializer;

// Ustawienia dla wyświetlania błędów
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Połączenie z bazą danych
    $db = new DatabaseConnection();
    $pdo = $db->getConnection();

    // Inicjalizacja bazy danych (tworzenie tabel, jeśli nie istnieją)
    $initializer = new DatabaseInitializer($pdo);
    $initializer->initialize();
} catch (Exception $e) {
    die("Błąd podczas inicjalizacji aplikacji: " . $e->getMessage());
}

// Wczytanie routingu
require_once './routes/web.php';
