<?php

namespace App\Models;

use App\Database\DatabaseConnection; // Import klasy DatabaseConnection

class BalanceModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection(); // Pobranie połączenia PDO
    }

    public function getBalanceForUser($userId)
    {
        $stmt = $this->db->prepare('SELECT SUM(amount) as total_balance FROM transactions WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetch(); // Zwraca wynik zapytania
    }
}
