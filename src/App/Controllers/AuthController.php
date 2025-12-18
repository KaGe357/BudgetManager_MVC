<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;
use App\Validators\RegistrationValidator;


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

        // Walidacja tokenu CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($token)) {
            SessionHelper::set('error', 'Nieprawidłowe żądanie. Odśwież stronę i spróbuj ponownie.');
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

        header('Location: /login');
        exit();
    }

    public function showRegisterForm()
    {
        require __DIR__ . '/../Views/register.php';
    }



    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../Views/register.php';
            return;
        }

        // Walidacja tokenu CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($token)) {
            SessionHelper::set('e_email', 'Nieprawidłowe żądanie. Odśwież stronę i spróbuj ponownie.');
            header('Location: /register');
            exit();
        }

        $validator = new RegistrationValidator();

        if (!$validator->validate($_POST)) {
            // Zapisz błędy do sesji
            foreach ($validator->getErrors() as $field => $error) {
                SessionHelper::set("e_{$field}", $error);
            }

            // Zachowaj wprowadzone wartości
            SessionHelper::set('fr_name', $_POST['name'] ?? '');
            SessionHelper::set('fr_email', $_POST['email'] ?? '');

            header('Location: /register');
            exit();
        }

        $userModel = new UserModel();

        if ($userModel->userExists($_POST['email'])) {
            SessionHelper::set('e_email', 'Ten email jest już zarejestrowany.');
            header('Location: /register');
            exit();
        }

        $hashedPassword = password_hash($_POST['haslo1'], PASSWORD_DEFAULT);
        $result = $userModel->createUser($_POST['name'], $_POST['email'], $hashedPassword);

        if ($result === true) {
            SessionHelper::set('udanarejestracja', true);
            header('Location: /');
            exit();
        }

        SessionHelper::set('e_email', 'Błąd podczas tworzenia konta.');
        header('Location: /register');
        exit();
    }
}
