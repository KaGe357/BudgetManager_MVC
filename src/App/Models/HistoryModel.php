<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;
use PDOException;

class HistoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    // Pobieranie wszystkich transakcji (przychody + wydatki) z paginacją
    public function getTransactions($userId, $limit = 10, $offset = 0)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    i.id,
                    'income' as type,
                    i.amount,
                    i.date_of_income as date,
                    i.income_comment as comment,
                    ic.name as category
                FROM incomes i
                JOIN incomes_category_assigned_to_users ic ON i.income_category_assigned_to_user_id = ic.id
                WHERE i.user_id = :userId
                
                UNION ALL
                
                SELECT 
                    e.id,
                    'expense' as type,
                    e.amount,
                    e.date_of_expense as date,
                    e.expense_comment as comment,
                    ec.name as category
                FROM expenses e
                JOIN expenses_category_assigned_to_users ec ON e.expense_category_assigned_to_user_id = ec.id
                WHERE e.user_id = :userId
                
                ORDER BY date DESC
                LIMIT :limit OFFSET :offset
            ");

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getTransactions: " . $e->getMessage());
            return [];
        }
    }

    // Zliczanie wszystkich transakcji użytkownika
    public function getTotalTransactionsCount($userId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM incomes WHERE user_id = ?) +
                    (SELECT COUNT(*) FROM expenses WHERE user_id = ?) as total
            ");

            $stmt->execute([$userId, $userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in getTotalTransactionsCount: " . $e->getMessage());
            return 0;
        }
    }

    public function deleteExpense($userId, $expenseId)
    {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM expenses
                WHERE id = :expenseId AND user_id = :userId
            ');

            $stmt->bindValue(':expenseId', $expenseId, PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error in deleteExpense: " . $e->getMessage());
            return false;
        }
    }

    public function deleteIncome($userId, $incomeId)
    {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM incomes
                WHERE id = :incomeId AND user_id = :userId
            ');

            $stmt->bindValue(':incomeId', $incomeId, PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error in deleteIncome: " . $e->getMessage());
            return false;
        }
    }
}
