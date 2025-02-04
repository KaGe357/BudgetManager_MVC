<?php

use App\Controllers\AuthController;
use App\Controllers\PageController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


if ($uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new PageController())->index();
} elseif ($uri === '/login' && $method === 'GET') {
    (new AuthController())->showLoginForm();
} elseif ($uri === '/login' && $method === 'POST') {
    (new AuthController())->login();
} elseif ($uri === '/register' && $method === 'GET') {
    (new AuthController())->showRegisterForm();
} elseif ($uri === '/register' && $method === 'POST') {
    (new AuthController())->register();
} else {
    http_response_code(404);
    echo "Nie znaleziono strony";
}
