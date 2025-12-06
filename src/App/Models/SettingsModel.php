<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;
use Exception;

class SettingsModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    // Pobieranie kategorii dochodów
    public function getIncomeCategories($userId)
    {
        $stmt = $this->db->prepare("SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobieranie kategorii wydatków
    public function getExpenseCategories($userId)
    {
        $stmt = $this->db->prepare("SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Dodawanie kategorii dochodów
    public function addIncomeCategory($userId, $categoryName)
    {
        $stmt = $this->db->prepare("INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES (?, ?)");
        return $stmt->execute([$userId, trim($categoryName)]);
    }

    // Usuwanie kategorii dochodów (przenoszenie do "Inne")
    public function removeIncomeCategory($userId, $categoryId)
    {
        try {
            $this->db->beginTransaction();
            $otherCategoryId = $this->getOrCreateOtherIncomeCategory($userId);

            // Przeniesienie dochodów do kategorii "Inne"
            $stmt = $this->db->prepare("UPDATE incomes SET income_category_assigned_to_user_id = ? WHERE income_category_assigned_to_user_id = ?");
            $stmt->execute([$otherCategoryId, $categoryId]);

            // Usunięcie kategorii
            $stmt = $this->db->prepare("DELETE FROM incomes_category_assigned_to_users WHERE id = ?");
            $stmt->execute([$categoryId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Błąd usuwania kategorii dochodów: " . $e->getMessage());
            return false;
        }
    }

    // Dodawanie kategorii wydatków
    public function addExpenseCategory($userId, $categoryName)
    {
        $stmt = $this->db->prepare("INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (?, ?)");
        return $stmt->execute([$userId, trim($categoryName)]);
    }

    // Usuwanie kategorii wydatków (przenoszenie do Inne")
    public function removeExpenseCategory($userId, $categoryId)
    {
        try {
            $this->db->beginTransaction();
            $otherCategoryId = $this->getOrCreateOtherExpenseCategory($userId);

            // Przeniesienie wydatków do kategorii "Inne"
            $stmt = $this->db->prepare("UPDATE expenses SET expense_category_assigned_to_user_id = ? WHERE expense_category_assigned_to_user_id = ?");
            $stmt->execute([$otherCategoryId, $categoryId]);

            // Usunięcie kategorii
            $stmt = $this->db->prepare("DELETE FROM expenses_category_assigned_to_users WHERE id = ?");
            $stmt->execute([$categoryId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Błąd usuwania kategorii wydatków: " . $e->getMessage());
            return false;
        }
    }

    // Tworzenie lub pobieranie kategorii "Inne" dla dochodów
    private function getOrCreateOtherIncomeCategory($userId)
    {
        return $this->getOrCreateCategory('incomes_category_assigned_to_users', $userId);
    }

    // Tworzenie lub pobieranie kategorii "Inne" dla wydatków
    private function getOrCreateOtherExpenseCategory($userId)
    {
        return $this->getOrCreateCategory('expenses_category_assigned_to_users', $userId);
    }

    // Tworzenie kategorii "Inne", jeśli nie istnieje
    private function getOrCreateCategory($table, $userId)
    {
        $stmt = $this->db->prepare("SELECT id FROM $table WHERE user_id = ? AND name = 'Inne'");
        $stmt->execute([$userId]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            $stmt = $this->db->prepare("INSERT INTO $table (user_id, name) VALUES (?, 'Inne')");
            $stmt->execute([$userId]);
            return $this->db->lastInsertId();
        }

        return $category['id'];
    }

    // Pobieranie limitu dla konkretnej kategorii wydatków
    public function getLimit($userId, $categoryName)
    {
        $stmt = $this->db->prepare("
            SELECT spending_limit 
            FROM expenses_category_assigned_to_users 
            WHERE user_id = ? AND name = ?
        ");
        $stmt->execute([$userId, $categoryName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['spending_limit'] : null;
    }

    // Aktualizacja nazwy użytkownika
    public function updateUserName($userId, $newUserName)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ? WHERE id = ?");
        return $stmt->execute([$newUserName, $userId]);
    }

    // Pobieranie aktualnego hasła użytkownika
    public function getUserPassword($userId)
    {
        $stmt = $this->db->prepare("SELECT pass FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['pass'] : null;
    }

    // Aktualizacja hasła użytkownika
    public function updateUserPassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET pass = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }

    public function deleteUser($userId)
    {
        try {
            $this->db->beginTransaction();

            // Usuń wszystkie transakcje użytkownika (dochody i wydatki)
            $this->db->prepare("DELETE FROM incomes WHERE user_id = ?")->execute([$userId]);
            $this->db->prepare("DELETE FROM expenses WHERE user_id = ?")->execute([$userId]);

            // Usuń kategorie użytkownika
            $this->db->prepare("DELETE FROM incomes_category_assigned_to_users WHERE user_id = ?")->execute([$userId]);
            $this->db->prepare("DELETE FROM expenses_category_assigned_to_users WHERE user_id = ?")->execute([$userId]);

            // Usuń konto użytkownika
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            $this->db->commit();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Błąd usuwania konta: " . $e->getMessage());
            return false;
        }
    }

    // Aktualizacja limitu kategorii wydatków
    public function updateCategoryLimit($userId, $categoryId, $limit)
    {
        $limitValue = ($limit === '' || $limit === null) ? null : floatval($limit);

        $stmt = $this->db->prepare("
            UPDATE expenses_category_assigned_to_users 
            SET spending_limit = ? 
            WHERE id = ? AND user_id = ?
        ");

        return $stmt->execute([$limitValue, $categoryId, $userId]);
    }

    // Pobieranie kategorii wydatków z limitami i sumami wydatków
    public function getExpenseCategoriesWithLimits($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                c.name,
                c.spending_limit,
                COALESCE(SUM(e.amount), 0) as total_spent
            FROM expenses_category_assigned_to_users c
            LEFT JOIN expenses e ON e.expense_category_assigned_to_user_id = c.id 
                AND e.user_id = c.user_id
                AND MONTH(e.date_of_expense) = MONTH(CURRENT_DATE())
                AND YEAR(e.date_of_expense) = YEAR(CURRENT_DATE())
            WHERE c.user_id = ?
            GROUP BY c.id, c.name, c.spending_limit
            ORDER BY c.name
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
