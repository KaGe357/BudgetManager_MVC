<?php

namespace App\Controllers;

use App\Models\IncomeModel;
use App\Helpers\SessionHelper;

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
        $userId = SessionHelper::get('user')['id'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $categoryId = $_POST['category_id'];
        $comment = $_POST['comment'] ?? '';

        $incomeModel = new IncomeModel();
        $result = $incomeModel->saveIncome($userId, $amount, $date, $categoryId, $comment);

        if ($result) {
            header('Location: /home');
        } else {
            echo "Wystąpił błąd podczas zapisywania dochodu.";
        }
    }
}
