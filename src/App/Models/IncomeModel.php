<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;
use PDOException;

class IncomeModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }

    public function getIncomeCategoriesForUser($userId)
    {
        try {
            $stmt = $this->db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = ?');
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getIncomeCategoriesForUser: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Zapisuje przychód z pełną walidacją
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function saveIncome($userId, $amount, $date, $categoryId, $comment)
    {
        try {
            // Walidacja kwoty
            $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);
            if ($amount === false || $amount <= 0) {
                return ['success' => false, 'error' => 'Kwota musi być liczbą dodatnią.'];
            }

            // Walidacja daty
            if (!$this->isValidDate($date)) {
                return ['success' => false, 'error' => 'Nieprawidłowy format daty.'];
            }

            // Walidacja kategorii - SPRAWDZENIE CZY NALEŻY DO UŻYTKOWNIKA
            $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
            if ($categoryId === false) {
                return ['success' => false, 'error' => 'Nieprawidłowa kategoria.'];
            }

            if (!$this->categoryBelongsToUser($userId, $categoryId)) {
                return ['success' => false, 'error' => 'Kategoria nie należy do tego użytkownika.'];
            }

            // Sanityzacja komentarza
            $comment = trim(htmlspecialchars($comment ?? '', ENT_QUOTES, 'UTF-8'));

            $stmt = $this->db->prepare(
                'INSERT INTO incomes (user_id, amount, date_of_income, income_category_assigned_to_user_id, income_comment) VALUES (?, ?, ?, ?, ?)'
            );
            $result = $stmt->execute([$userId, $amount, $date, $categoryId, $comment]);

            return ['success' => $result, 'error' => $result ? null : 'Błąd zapisu do bazy danych.'];
        } catch (PDOException $e) {
            error_log("Error in saveIncome: " . $e->getMessage());
            return ['success' => false, 'error' => 'Wystąpił błąd podczas zapisywania przychodu.'];
        }
    }

    /**
     * Sprawdza czy kategoria należy do użytkownika
     */
    private function categoryBelongsToUser($userId, $categoryId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM incomes_category_assigned_to_users WHERE id = ? AND user_id = ?');
        $stmt->execute([$categoryId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Waliduje format daty (YYYY-MM-DD)
     */
    private function isValidDate($date): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
