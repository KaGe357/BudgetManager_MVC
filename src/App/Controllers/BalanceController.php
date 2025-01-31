<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BalanceModel;
use App\Helpers\SessionHelper;

class BalanceController
{
    public function index()
    {
        $userId = SessionHelper::get('user')['id'];

        $balanceModel = new \BalanceModel();
        $data = $balanceModel->getBalanceForUser($userId);

        require __DIR__ . '/../Views/balance.php';
    }
}
