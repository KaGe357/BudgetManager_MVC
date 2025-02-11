<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;

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

        require __DIR__ . '/../Views/home.php';
    }
}
