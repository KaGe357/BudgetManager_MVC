<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;

class BalanceModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function getIncomeCategories($userId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare('
            SELECT 
                icatu.name AS income_category_name, 
                COALESCE(SUM(i.amount), 0) AS total_incomes
            FROM incomes_category_assigned_to_users icatu
            LEFT JOIN incomes i 
                ON i.income_category_assigned_to_user_id = icatu.id 
                AND i.user_id = ? 
                AND i.date_of_income BETWEEN ? AND ?
            WHERE icatu.user_id = ?
            GROUP BY icatu.name
            ORDER BY icatu.name
        ');
        $stmt->execute([$userId, $startDate, $endDate, $userId]);

        return $stmt->fetchAll();
    }

    public function getExpenseCategories($userId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT 
                ecatu.name AS expense_category_name, 
                COALESCE(SUM(e.amount), 0) AS total_expenses
            FROM expenses_category_assigned_to_users ecatu
            INNER JOIN expenses e 
                ON ecatu.id = e.expense_category_assigned_to_user_id
                AND e.date_of_expense BETWEEN ? AND ?
                AND e.user_id = ?
            WHERE ecatu.user_id = ?
            GROUP BY ecatu.name
            ORDER BY ecatu.name;
        ");
        $stmt->execute([$startDate, $endDate, $userId, $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
