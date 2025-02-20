<?php

use App\Controllers\BalanceController;
use App\Controllers\ExpenseController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\IncomeController;
use App\Controllers\SettingsController;

// Strony główne
Router::add('GET', '/', [PageController::class, 'index']);
Router::add('GET', '/home', [HomeController::class, 'index']);

// Logowanie i rejestracja
Router::add('GET', '/login', [AuthController::class, 'showLoginForm']);
Router::add('POST', '/login', [AuthController::class, 'login']);
Router::add('GET', '/register', [AuthController::class, 'showRegisterForm']);
Router::add('POST', '/register', [AuthController::class, 'register']);
Router::add('GET', '/logout', [AuthController::class, 'logout']);

// Dochody i wydatki
Router::add('GET', '/income/add', [IncomeController::class, 'add']);
Router::add('POST', '/income/save', [IncomeController::class, 'save']);
Router::add('GET', '/expense/add', [ExpenseController::class, 'add']);
Router::add('POST', '/expense/save', [ExpenseController::class, 'save']);
Router::add('GET', '/balance', [BalanceController::class, 'index']);

// Bilans
Router::add('GET', '/balance', [BalanceController::class, 'index']);
Router::add('POST', '/api/balance', [BalanceController::class, 'getBalanceData']);

// Ustawienia
Router::add('GET', '/settings', [SettingsController::class, 'index']);
Router::add('POST', '/settings/addIncomeCategory', [SettingsController::class, 'addIncomeCategory']);
Router::add('POST', '/settings/removeIncomeCategory', [SettingsController::class, 'removeIncomeCategory']);
Router::add('POST', '/settings/addExpenseCategory', [SettingsController::class, 'addExpenseCategory']);
Router::add('POST', '/settings/removeExpenseCategory', [SettingsController::class, 'removeExpenseCategory']);

Router::add('POST', '/settings/changeUserName', [SettingsController::class, 'changeUserName']);
Router::add('POST', '/settings/changePassword', [SettingsController::class, 'changePassword']);

// Ustawienia konta
Router::add('GET', '/settings/account', [SettingsController::class, 'accountSettings']);
Router::add('POST', '/settings/account/update', [SettingsController::class, 'updateAccount']);

Router::add('POST', '/settings/deleteAccount', [SettingsController::class, 'deleteAccount']);


// Obsługa routingu
Router::dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER['REQUEST_METHOD']);
