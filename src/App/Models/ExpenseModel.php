<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use DateTime;
use Exception;
use PDO;
use PDOException;

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
        try {
            // Sprawdź czy kategoria należy do użytkownika
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM expenses_category_assigned_to_users 
             WHERE id = :categoryId AND user_id = :userId'
            );
            $stmt->execute([':categoryId' => $categoryId, ':userId' => $userId]);

            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Nieprawidłowa kategoria wydatku.');
            }

            // Sprawdź czy metoda płatności należy do użytkownika
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM payment_methods_assigned_to_users 
             WHERE id = :methodId AND user_id = :userId'
            );
            $stmt->execute([':methodId' => $paymentMethodId, ':userId' => $userId]);

            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Nieprawidłowa metoda płatności.');
            }

            // Walidacja kwoty
            if (!is_numeric($amount) || $amount <= 0) {
                throw new Exception('Kwota musi być liczbą dodatnią.');
            }

            // Walidacja daty
            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
                throw new Exception('Nieprawidłowy format daty.');
            }

            // Zapis wydatku
            $stmt = $this->db->prepare(
                'INSERT INTO expenses (user_id, amount, date_of_expense, 
             expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, 
             expense_comment) 
             VALUES (:userId, :amount, :date, :categoryId, :methodId, :comment)'
            );

            return $stmt->execute([
                ':userId' => $userId,
                ':amount' => $amount,
                ':date' => $date,
                ':categoryId' => $categoryId,
                ':methodId' => $paymentMethodId,
                ':comment' => $comment
            ]);
        } catch (PDOException $e) {
            error_log("Database error in saveExpense: " . $e->getMessage());
            throw new Exception("Nie udało się zapisać wydatku.");
        }
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
