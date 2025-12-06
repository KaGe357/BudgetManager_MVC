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
}
