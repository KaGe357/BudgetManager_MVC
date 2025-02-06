<?php

namespace App\Models;

use App\Database\DatabaseConnection; // Dodano poprawny namespace

class IncomeModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection(); // Teraz odwołuje się do poprawnego namespace
    }

    public function getIncomeCategoriesForUser($userId)
    {
        $stmt = $this->db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function saveIncome($userId, $amount, $date, $categoryId, $comment)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO incomes (user_id, amount, date_of_income, income_category_assigned_to_user_id, comment) VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$userId, $amount, $date, $categoryId, $comment]);
    }
}
