<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;

class ExpenseModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function getExpenseCategories($userId)
    {
        $stmt = $this->db->prepare('SELECT id, name, spending_limit FROM expenses_category_assigned_to_users WHERE user_id = ?');
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

    public function getLimitWithSpent($userId, $categoryName)
    {
        $stmt = $this->db->prepare("
            SELECT id, spending_limit 
            FROM expenses_category_assigned_to_users 
            WHERE user_id = ? AND name = ?
        ");
        $stmt->execute([$userId, $categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            return ['limit' => null, 'spent' => 0];
        }

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as spent 
            FROM expenses 
            WHERE user_id = ? 
            AND expense_category_assigned_to_user_id = ?
            AND MONTH(date_of_expense) = MONTH(CURDATE())
            AND YEAR(date_of_expense) = YEAR(CURDATE())
        ");
        $stmt->execute([$userId, $category['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'limit' => (float)$category['spending_limit'],
            'spent' => (float)$result['spent']
        ];
    }
}
