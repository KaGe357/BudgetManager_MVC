<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BalanceModel;
use App\Helpers\SessionHelper;


class AuthController
{
    public function login()
    {
        if (!isset($_POST['login']) || !isset($_POST['password'])) {
            $_SESSION['error'] = 'Wypełnij wszystkie pola!';
            header('Location: /login');
            exit();
        }

        $email = $_POST['login'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $user = $userModel->findUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            SessionHelper::set('user', [
                'id' => $user['id'],
                'name' => $user['name'],
            ]);

            header('Location: /home');
        } else {
            $_SESSION['error'] = 'Nieprawidłowe dane logowania';
            header('Location: /login');
        }
    }

    public function logout()
    {
        SessionHelper::destroy();
        header('Location: /');
    }
}
