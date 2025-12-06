<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;

class HomeModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    // Pobieranie sumy przychodów dla bieżącego miesiąca
    public function getTotalIncomesThisMonth($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM incomes
            WHERE user_id = ?
            AND MONTH(date_of_income) = MONTH(CURRENT_DATE())
            AND YEAR(date_of_income) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    // Pobieranie sumy wydatków dla bieżącego miesiąca
    public function getTotalExpensesThisMonth($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM expenses
            WHERE user_id = ?
            AND MONTH(date_of_expense) = MONTH(CURRENT_DATE())
            AND YEAR(date_of_expense) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    // Pobieranie kategorii przychodów z sumami dla wykresu
    public function getIncomeCategoriesForChart($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                icatu.name as category_name,
                COALESCE(SUM(i.amount), 0) as total
            FROM incomes_category_assigned_to_users icatu
            LEFT JOIN incomes i ON icatu.id = i.income_category_assigned_to_user_id
                AND i.user_id = ?
                AND MONTH(i.date_of_income) = MONTH(CURRENT_DATE())
                AND YEAR(i.date_of_income) = YEAR(CURRENT_DATE())
            WHERE icatu.user_id = ?
            GROUP BY icatu.id, icatu.name
            HAVING total > 0
            ORDER BY total DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobieranie kategorii wydatków z sumami dla wykresu
    public function getExpenseCategoriesForChart($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                ecatu.name as category_name,
                COALESCE(SUM(e.amount), 0) as total
            FROM expenses_category_assigned_to_users ecatu
            LEFT JOIN expenses e ON ecatu.id = e.expense_category_assigned_to_user_id
                AND e.user_id = ?
                AND MONTH(e.date_of_expense) = MONTH(CURRENT_DATE())
                AND YEAR(e.date_of_expense) = YEAR(CURRENT_DATE())
            WHERE ecatu.user_id = ?
            GROUP BY ecatu.id, ecatu.name
            HAVING total > 0
            ORDER BY total DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sprawdzanie przekroczonych limitów
    public function getExceededLimits($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.name,
                c.spending_limit,
                COALESCE(SUM(e.amount), 0) as total_spent
            FROM expenses_category_assigned_to_users c
            LEFT JOIN expenses e ON e.expense_category_assigned_to_user_id = c.id 
                AND e.user_id = c.user_id
                AND MONTH(e.date_of_expense) = MONTH(CURRENT_DATE())
                AND YEAR(e.date_of_expense) = YEAR(CURRENT_DATE())
            WHERE c.user_id = ?
            AND c.spending_limit IS NOT NULL
            AND c.spending_limit > 0
            GROUP BY c.id, c.name, c.spending_limit
            HAVING total_spent >= c.spending_limit
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
