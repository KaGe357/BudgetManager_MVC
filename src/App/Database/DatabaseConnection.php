<?php

namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../../config/config.php';

        try {
            $pdo = new PDO(
                "mysql:host=" . $config['host'],
                $config['user'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $dbName = $config['dbname'];
            $checkDb = $pdo->prepare("SHOW DATABASES LIKE :dbname");
            $checkDb->execute([':dbname' => $dbName]);
            $databaseExists = $checkDb->fetch();

            if (!$databaseExists) {
                $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                // Baza danych została utworzona
            }

            $this->pdo = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $dbName,
                $config['user'],
                $config['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function prepare(string $query): \PDOStatement
    {
        return $this->pdo->prepare($query);
    }
}
