<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;


class PageController
{
    public function index()
    {
        if (SessionHelper::get('user')) {
            header('Location: /home');
            exit();
        }

        require __DIR__ . '/../Views/index.php';
    }
}
