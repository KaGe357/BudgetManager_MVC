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
            $result = $expenseModel->saveExpense($user['id'], $amount, $date, $categoryId, $paymentMethodId, $comment);

            if ($result !== false) {
                SessionHelper::set('success', 'Wydatek został dodany.');
            } else {
                SessionHelper::set('error', 'Wystąpił błąd podczas zapisywania wydatku.');
            }
            header('Location: /expense/add');
            exit();
        }
    }

    public function getLimitInfo()
    {
        SessionHelper::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!SessionHelper::has('user')) {
            echo json_encode(['error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $categoryName = $_GET['category'] ?? null;

        if (!$categoryName) {
            echo json_encode(['error' => 'Category name required'], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $expenseModel = new ExpenseModel();
        $data = $expenseModel->getLimitWithSpent($userId, $categoryName);

        echo json_encode([
            'limit' => $data['limit'],
            'spent' => $data['spent']
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
