<?php


class DatabaseConnection
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/config.php';

        try {
            $this->pdo = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'],
                $config['user'],
                $config['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }
}
