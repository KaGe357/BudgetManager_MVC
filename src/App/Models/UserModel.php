<?php

namespace App\Models;

use DatabaseConnection;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }

    public function findUserByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function userExists($email)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function createUser($name, $email, $passwordHash)
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, pass) VALUES (?, ?, ?)');
        return $stmt->execute([$name, $email, $passwordHash]);
    }
}
