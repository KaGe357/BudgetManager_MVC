<?php

namespace App\Controllers;

use App\Models\HistoryModel;
use App\Helpers\SessionHelper;

class HistoryController
{
    private $historyModel;

    public function __construct()
    {
        SessionHelper::start();
        $this->historyModel = new HistoryModel();
    }

    public function index()
    {
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit;
        }

        $user = SessionHelper::get('user');
        $userId = $user['id'];
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $itemsPerPage = 10;
        $offset = ($page - 1) * $itemsPerPage;

        // Pobierz transakcje i liczbę całkowitą
        $transactions = $this->historyModel->getTransactions($userId, $itemsPerPage, $offset);
        $totalTransactions = $this->historyModel->getTotalTransactionsCount($userId);
        $totalPages = ceil($totalTransactions / $itemsPerPage);

        // Załaduj widok
        require_once __DIR__ . '/../Views/history.php';
    }

    public function deleteExpense()
    {
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit;
        }

        $user = SessionHelper::get('user');
        $userId = $user['id'];
        $expenseId = isset($_POST['expense_id']) ? (int)$_POST['expense_id'] : 0;
        $page = isset($_POST['page']) ? max(1, (int)$_POST['page']) : 1;

        if ($expenseId <= 0) {
            SessionHelper::set('error', 'Nieprawidłowy identyfikator wydatku.');
            header('Location: /history?page=' . $page);
            exit;
        }

        $deleted = $this->historyModel->deleteExpense($userId, $expenseId);

        if ($deleted) {
            SessionHelper::set('success', 'Wydatek został usunięty.');
        } else {
            SessionHelper::set('error', 'Nie udało się usunąć wydatku.');
        }

        header('Location: /history?page=' . $page);
        exit;
    }

    public function deleteIncome()
    {
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit;
        }

        $user = SessionHelper::get('user');
        $userId = $user['id'];
        $incomeId = isset($_POST['income_id']) ? (int)$_POST['income_id'] : 0;
        $page = isset($_POST['page']) ? max(1, (int)$_POST['page']) : 1;

        if ($incomeId <= 0) {
            SessionHelper::set('error', 'Nieprawidłowy identyfikator przychodu.');
            header('Location: /history?page=' . $page);
            exit;
        }

        $deleted = $this->historyModel->deleteIncome($userId, $incomeId);

        if ($deleted) {
            SessionHelper::set('success', 'Przychód został usunięty.');
        } else {
            SessionHelper::set('error', 'Nie udało się usunąć przychodu.');
        }

        header('Location: /history?page=' . $page);
        exit;
    }
}
