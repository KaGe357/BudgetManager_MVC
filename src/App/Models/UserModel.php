<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use Exception;
use PDO;
use PDOException;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function findUserByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function userExists($email)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function createUser($name, $email, $passwordHash)
    {
        try {
            $this->db->beginTransaction();

            // Dodanie użytkownika
            $stmt = $this->db->prepare('INSERT INTO users (name, email, pass) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $passwordHash]);
            $userId = $this->db->lastInsertId();

            // Kopiowanie domyślnych kategorii dochodów
            $stmt = $this->db->prepare('
            INSERT INTO incomes_category_assigned_to_users (user_id, name)
            SELECT ?, name FROM incomes_category_default
        ');
            $stmt->execute([$userId]);

            // Kopiowanie domyślnych kategorii wydatków
            $stmt = $this->db->prepare('
            INSERT INTO expenses_category_assigned_to_users (user_id, name)
            SELECT ?, name FROM expenses_category_default
        ');
            $stmt->execute([$userId]);

            // Kopiowanie domyślnych metod płatności
            $stmt = $this->db->prepare('
            INSERT INTO payment_methods_assigned_to_users (user_id, name)
            SELECT ?, name FROM payment_methods_default
        ');
            $stmt->execute([$userId]);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }



    public function getUserNameById($userId)
    {
        $stmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        return $row ? $row['name'] : "Użytkowniku";
    }
}
