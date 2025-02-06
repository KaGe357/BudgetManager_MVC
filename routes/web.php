<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\IncomeController;

// Strony główne
Router::add('GET', '/', [PageController::class, 'index']);
Router::add('GET', '/home', [HomeController::class, 'index']);

// Logowanie i rejestracja
Router::add('GET', '/login', [AuthController::class, 'showLoginForm']);
Router::add('POST', '/login', [AuthController::class, 'login']);
Router::add('GET', '/register', [AuthController::class, 'showRegisterForm']);
Router::add('POST', '/register', [AuthController::class, 'register']);
Router::add('GET', '/logout', [AuthController::class, 'logout']);

// Dochody
Router::add('GET', '/income/add', [IncomeController::class, 'add']);
Router::add('POST', '/income/save', [IncomeController::class, 'save']);

// Obsługa routingu
Router::dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER['REQUEST_METHOD']);
