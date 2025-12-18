<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use Exception;
use PDO;
use PDOException;

class BalanceModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function getIncomeCategories($userId, $startDate, $endDate)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT icatu.name AS income_category_name, 
                   COALESCE(SUM(i.amount), 0) AS total_incomes
            FROM incomes_category_assigned_to_users icatu
            LEFT JOIN incomes i 
                   ON icatu.id = i.income_category_assigned_to_user_id
                   AND i.user_id = :userId
                   AND i.date_of_income BETWEEN :startDate AND :endDate
            WHERE icatu.user_id = :userId
            GROUP BY icatu.id, icatu.name
            ORDER BY icatu.name
        ");

            $stmt->execute([
                ':userId' => $userId,
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getIncomeCategories: " . $e->getMessage());
            throw new Exception("Nie udało się pobrać kategorii przychodów. Spróbuj ponownie później.");
        }
    }

    public function getExpenseCategories($userId, $startDate, $endDate)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT ecatu.name AS expense_category_name, 
                       COALESCE(SUM(e.amount), 0) AS total_expenses,
                       ecatu.spending_limit
                FROM expenses_category_assigned_to_users ecatu
                LEFT JOIN expenses e 
                       ON ecatu.id = e.expense_category_assigned_to_user_id
                       AND e.user_id = ?
                       AND e.date_of_expense BETWEEN ? AND ?
                WHERE ecatu.user_id = ?
                GROUP BY ecatu.id, ecatu.name, ecatu.spending_limit
                ORDER BY ecatu.name;
            ");
            $stmt->execute([$userId, $startDate, $endDate, $userId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getExpenseCategories: " . $e->getMessage());
            throw new Exception("Nie udało się pobrać kategorii wydatków. Spróbuj ponownie później.");
        }
    }
}
