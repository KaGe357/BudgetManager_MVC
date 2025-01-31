<?php

class BalanceModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }

    public function getBalanceForUser($userId)
    {
        $stmt = $this->db->prepare('SELECT ... FROM ... WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }
}
