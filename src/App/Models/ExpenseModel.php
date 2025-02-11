<?php

namespace App\Models;

use App\Database\DatabaseConnection;

class ExpenseModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function getExpenseCategories($userId)
    {
        $stmt = $this->db->prepare('SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function getPaymentMethods($userId)
    {
        $stmt = $this->db->prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function saveExpense($userId, $amount, $date, $categoryId, $paymentMethodId, $comment)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO expenses (user_id, amount, date_of_expense, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, expense_comment) VALUES (?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$userId, $amount, $date, $categoryId, $paymentMethodId, $comment]);
    }
}
