<?php

use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Controllers\BalanceController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new PageController())->index();
} elseif ($uri === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->login();
} elseif ($uri === '/logout') {
    (new AuthController())->logout();
} elseif ($uri === '/balance') {
    (new BalanceController())->index();
} else {
    http_response_code(404);
    echo 'Nie znaleziono strony';
}
