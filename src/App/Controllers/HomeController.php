<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Models\HomeModel;

class HomeController
{
    public function index()
    {
        // Sprawdź, czy użytkownik jest zalogowany
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        $user = SessionHelper::get('user');
        $userName = $user['name'];
        $userId = $user['id'];

        // Pobierz dane do dashboardu
        $homeModel = new HomeModel();
        $totalIncomes = $homeModel->getTotalIncomesThisMonth($userId);
        $totalExpenses = $homeModel->getTotalExpensesThisMonth($userId);
        $balance = $totalIncomes - $totalExpenses;

        $incomeCategories = $homeModel->getIncomeCategoriesForChart($userId);
        $expenseCategories = $homeModel->getExpenseCategoriesForChart($userId);
        $exceededLimits = $homeModel->getExceededLimits($userId);

        require __DIR__ . '/../Views/home.php';
    }
}
