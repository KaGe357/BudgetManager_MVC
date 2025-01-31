<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Helpers\SessionHelper;

class AuthController
{
    public function showLoginForm()
    {
        require __DIR__ . '/../Views/login.php';
    }

    public function login()
    {
        if (!isset($_POST['login']) || !isset($_POST['haslo'])) {
            SessionHelper::set('error', 'Wypełnij wszystkie pola!');
            header('Location: /login');
            exit();
        }

        $email = $_POST['login'];
        $password = $_POST['haslo'];

        $userModel = new UserModel();
        $user = $userModel->findUserByEmail($email);

        if ($user && password_verify($password, $user['pass'])) {
            SessionHelper::set('user', [
                'id' => $user['id'],
                'name' => $user['name'],
            ]);
            header('Location: /home');
            exit();
        } else {
            SessionHelper::set('error', 'Nieprawidłowy login lub hasło!');
            header('Location: /login');
            exit();
        }
    }

    public function logout()
    {
        SessionHelper::destroy();
        header('Location: /');
        exit();
    }
}
