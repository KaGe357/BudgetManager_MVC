<?php

namespace App\Controllers;

use App\Models\BalanceModel;
use App\Helpers\SessionHelper;

class BalanceController
{
    public function index()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        $user = SessionHelper::get('user');
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');

        $balanceModel = new BalanceModel();
        $incomeCategories = $balanceModel->getIncomeCategories($user['id'], $startDate, $endDate);
        $expenseCategories = $balanceModel->getExpenseCategories($user['id'], $startDate, $endDate);

        $totalIncomes = array_sum(array_column($incomeCategories, 'total_incomes'));
        $totalExpenses = array_sum(array_column($expenseCategories, 'total_expenses'));
        $balance = $totalIncomes - $totalExpenses;

        require __DIR__ . '/../Views/balance.php';
    }

    public function getBalanceData()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user')) {
            echo json_encode(['success' => false, 'error' => 'User not logged in.']);
            exit();
        }

        $user = SessionHelper::get('user');
        $data = json_decode(file_get_contents('php://input'), true);

        $startDate = $data['start_date'] ?? date('Y-m-01');
        $endDate = $data['end_date'] ?? date('Y-m-t');

        // Walidacja formatu dat
        if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Nieprawidłowy format daty.']);
            exit();
        }

        // Walidacja logiczna dat
        if (strtotime($startDate) > strtotime($endDate)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Data początkowa nie może być późniejsza niż końcowa.']);
            exit();
        }

        try {
            $balanceModel = new BalanceModel();
            $incomes = $balanceModel->getIncomeCategories($user['id'], $startDate, $endDate);
            $expenses = $balanceModel->getExpenseCategories($user['id'], $startDate, $endDate);

            $totalIncome = array_sum(array_column($incomes, 'total_incomes'));
            $totalExpenses = array_sum(array_column($expenses, 'total_expenses'));
            $balance = $totalIncome - $totalExpenses;

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'totalBalance' => $balance,
                'incomes' => $incomes,
                'expenses' => $expenses,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            error_log("Error in getBalanceData: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Wystąpił błąd podczas pobierania danych.']);
        }
        exit();
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
