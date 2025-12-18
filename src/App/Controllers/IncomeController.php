<?php

namespace App\Controllers;

use App\Models\IncomeModel;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;

class IncomeController
{
    public function add()
    {
        $userId = SessionHelper::get('user')['id'];

        $incomeModel = new IncomeModel();
        $categories = $incomeModel->getIncomeCategoriesForUser($userId);

        require __DIR__ . '/../Views/income/add.php';
    }

    public function save()
    {
        // Walidacja tokenu CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($token)) {
            SessionHelper::set('error', 'Nieprawidłowe żądanie. Odśwież stronę i spróbuj ponownie.');
            header('Location: /income/add');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $categoryId = $_POST['category_id'];
        $comment = $_POST['comment'] ?? '';

        $incomeModel = new IncomeModel();
        $result = $incomeModel->saveIncome($userId, $amount, $date, $categoryId, $comment);

        if ($result['success']) {
            SessionHelper::set('success', 'Przychód został dodany.');
        } else {
            SessionHelper::set('error', $result['error'] ?? 'Wystąpił błąd podczas zapisywania przychodu.');
        }
        header('Location: /income/add');
        exit();
    }
}
