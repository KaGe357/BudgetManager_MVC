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

        header('Location: /login');
        exit();
    }

    public function showRegisterForm()
    {
        require __DIR__ . '/../Views/register.php';
    }


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wszystko_OK = true;

            // Pobierz dane z formularza
            $name = $_POST['name'];
            $email = $_POST['email'];
            $haslo1 = $_POST['haslo1'];
            $haslo2 = $_POST['haslo2'];

            // Walidacja danych
            if (strlen($name) < 3 || strlen($name) > 20 || !ctype_alnum($name)) {
                $wszystko_OK = false;
                SessionHelper::set('e_name', 'Imię musi posiadać od 3 do 20 znaków i składać się tylko z liter i cyfr (bez polskich znaków)');
            }

            $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($emailB, FILTER_VALIDATE_EMAIL) || $emailB != $email) {
                $wszystko_OK = false;
                SessionHelper::set('e_email', 'Podaj poprawny adres email!');
            }

            if (strlen($haslo1) < 8 || strlen($haslo1) > 20 || $haslo1 !== $haslo2) {
                $wszystko_OK = false;
                SessionHelper::set('e_haslo', 'Hasło musi posiadać od 8 do 20 znaków i oba hasła muszą być identyczne');
            }

            $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

            if (!isset($_POST['regulamin'])) {
                $wszystko_OK = false;
                SessionHelper::set('e_regulamin', 'Potwierdź akceptację regulaminu');
            }

            if ($wszystko_OK) {
                $userModel = new UserModel();
                if ($userModel->userExists($email)) {
                    SessionHelper::set('e_email', 'Istnieje już konto przypisane do tego adresu e-mail!');
                } else {
                    $result = $userModel->createUser($name, $email, $haslo_hash);

                    if ($result === true) {
                        SessionHelper::set('udanarejestracja', true);
                        header('Location: /welcome');
                        exit();
                    } else {
                        SessionHelper::set('e_email', 'Wystąpił błąd podczas tworzenia konta. Spróbuj ponownie później.');
                    }
                }
            }

            // Zachowaj dane wprowadzone w formularzu w sesji
            SessionHelper::set('fr_name', $name);
            SessionHelper::set('fr_email', $email);
            SessionHelper::set('fr_haslo1', $haslo1);
            SessionHelper::set('fr_haslo2', $haslo2);
            if (isset($_POST['regulamin'])) {
                SessionHelper::set('fr_regulamin', true);
            }

            header('Location: /register');
            exit();
        }
    }
}
