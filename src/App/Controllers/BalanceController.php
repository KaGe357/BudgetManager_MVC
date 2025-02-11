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

        $balanceModel = new BalanceModel();
        $incomes = $balanceModel->getIncomeCategories($user['id'], $startDate, $endDate);
        $expenses = $balanceModel->getExpenseCategories($user['id'], $startDate, $endDate);

        $totalIncome = array_sum(array_column($incomes, 'total_incomes'));
        $totalExpenses = array_sum(array_column($expenses, 'total_expenses'));
        $balance = $totalIncome - $totalExpenses;

        echo json_encode([
            'success' => true,
            'totalBalance' => $balance,
            'incomes' => $incomes,
            'expenses' => $expenses,
        ]);
    }
}
