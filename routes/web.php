<?php

use App\Controllers\BalanceController;
use App\Controllers\ExpenseController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\IncomeController;
use App\Controllers\SettingsController;
use App\Controllers\HistoryController;
use App\Controllers\AiAdvisorController;

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

// Historia transakcji
Router::add('GET', '/history', [HistoryController::class, 'index']);
Router::add('POST', '/history/expense/delete', [HistoryController::class, 'deleteExpense']);
Router::add('POST', '/history/income/delete', [HistoryController::class, 'deleteIncome']);

// API endpoints
Router::add('GET', '/api/expense/limit', [ExpenseController::class, 'getLimitInfo']);
Router::add('GET', '/api/limit', [SettingsController::class, 'limit']);

// Ustawienia
Router::add('GET', '/settings', [SettingsController::class, 'index']);
Router::add('POST', '/settings/addIncomeCategory', [SettingsController::class, 'addIncomeCategory']);
Router::add('POST', '/settings/removeIncomeCategory', [SettingsController::class, 'removeIncomeCategory']);
Router::add('POST', '/settings/addExpenseCategory', [SettingsController::class, 'addExpenseCategory']);
Router::add('POST', '/settings/removeExpenseCategory', [SettingsController::class, 'removeExpenseCategory']);
Router::add('POST', '/settings/updateCategoryLimit', [SettingsController::class, 'updateCategoryLimit']);

Router::add('POST', '/settings/changeUserName', [SettingsController::class, 'changeUserName']);
Router::add('POST', '/settings/changePassword', [SettingsController::class, 'changePassword']);

// Ustawienia konta
Router::add('GET', '/settings/account', [SettingsController::class, 'accountSettings']);
Router::add('POST', '/settings/account/update', [SettingsController::class, 'updateAccount']);

Router::add('POST', '/settings/deleteAccount', [SettingsController::class, 'deleteAccount']);

// Doradca AI 
Router::add('POST', '/api/ai/advice', [AiAdvisorController::class, 'generateAdvice']);
Router::add('GET', '/api/ai/advice/latest', [AiAdvisorController::class, 'getLatestAdvice']);


// Obsługa dynamicznych parametrów URL przed dispatch
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/api/expense/limit/(.+)$#', $uri, $matches)) {
    $_GET['category'] = urldecode($matches[1]);
    $uri = '/api/expense/limit';
}

if (preg_match('#^/api/limit/(.+)$#', $uri, $matches)) {
    $_GET['category'] = urldecode($matches[1]);
    $uri = '/api/limit';
}

// Obsługa routingu
Router::dispatch($uri, $_SERVER['REQUEST_METHOD']);
