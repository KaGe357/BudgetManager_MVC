<?php

namespace App\Controllers;

use App\Models\ExpenseModel;
use App\Helpers\SessionHelper;

class ExpenseController
{
    public function add()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        $user = SessionHelper::get('user');
        $expenseModel = new ExpenseModel();

        $categories = $expenseModel->getExpenseCategories($user['id']);
        $paymentMethods = $expenseModel->getPaymentMethods($user['id']);

        require __DIR__ . '/../Views/expense/add.php';
    }

    public function save()
    {
        SessionHelper::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = SessionHelper::get('user');

            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $categoryId = $_POST['category_id'];
            $paymentMethodId = $_POST['payment_method_id'];
            $comment = $_POST['comment'] ?? '';

            $expenseModel = new ExpenseModel();
            $expenseModel->saveExpense($user['id'], $amount, $date, $categoryId, $paymentMethodId, $comment);

            header('Location: /home');
            exit();
        }
    }
}
