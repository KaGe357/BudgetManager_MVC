<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Helpers\SessionHelper;

class SettingsController
{
    private $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $incomeCategories = $this->settingsModel->getIncomeCategories($userId);
        $expenseCategories = $this->settingsModel->getExpenseCategoriesWithLimits($userId);

        require __DIR__ . '/../Views/settings.php';
    }

    public function addIncomeCategory()
    {
        $this->handleCategoryAction('addIncomeCategory', 'income_category_name');
    }

    public function removeIncomeCategory()
    {
        $this->handleCategoryAction('removeIncomeCategory', 'category_id');
    }

    public function addExpenseCategory()
    {
        $this->handleCategoryAction('addExpenseCategory', 'expense_category_name');
    }

    public function removeExpenseCategory()
    {
        $this->handleCategoryAction('removeExpenseCategory', 'category_id');
    }

    public function updateCategoryLimit()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /settings');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $categoryId = $_POST['category_id'] ?? null;
        $limit = $_POST['limit'] ?? null;

        if (!$categoryId) {
            SessionHelper::set('error', 'Nieprawidłowe dane.');
            header('Location: /settings');
            exit();
        }

        $success = $this->settingsModel->updateCategoryLimit($userId, $categoryId, $limit);

        if ($success) {
            SessionHelper::set('success', 'Limit został zaktualizowany.');
        } else {
            SessionHelper::set('error', 'Nie udało się zaktualizować limitu.');
        }

        header('Location: /settings');
        exit();
    }

    private function handleCategoryAction($method, $field)
    {
        SessionHelper::start();
        if (!SessionHelper::has('user') || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST[$field])) {
            header('Location: /settings');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $result = $this->settingsModel->$method($userId, $_POST[$field]);

        // Jeśli dodawanie KATEGORII, zapisz ID do sesji
        if (($method === 'addIncomeCategory' || $method === 'addExpenseCategory') && $result) {
            SessionHelper::set('new_category_id', $result);
        }

        header('Location: /settings');
        exit();
    }

    public function changeUserName()
    {
        SessionHelper::start();
        if (!SessionHelper::has('user') || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['newUserName'])) {
            header('Location: /settings/account');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $newUserName = trim($_POST['newUserName']);

        $this->settingsModel->updateUserName($userId, $newUserName);
        SessionHelper::set('success', 'Nazwa użytkownika została zmieniona.');

        header('Location: /settings/account');
        exit();
    }


    public function changePassword()
    {
        SessionHelper::start();
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        if (
            !isset($_POST['currentPassword'], $_POST['newPassword'], $_POST['confirmPassword']) ||
            empty($_POST['currentPassword']) ||
            empty($_POST['newPassword']) ||
            empty($_POST['confirmPassword'])
        ) {
            SessionHelper::set('error', 'Wszystkie pola są wymagane.');
            header('Location: /settings/account');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword !== $confirmPassword) {
            SessionHelper::set('error', 'Nowe hasło i jego potwierdzenie muszą być takie same.');
            header('Location: /settings/account');
            exit();
        }

        $userPassword = $this->settingsModel->getUserPassword($userId);

        if (!$userPassword || !password_verify($currentPassword, $userPassword)) {
            SessionHelper::set('error', 'Nieprawidłowe obecne hasło!');
            header('Location: /settings/account');
            exit();
        }

        $passwordUpdated = $this->settingsModel->updateUserPassword($userId, $newPassword);

        if ($passwordUpdated) {
            SessionHelper::set('success', 'Hasło zostało zmienione.');
        } else {
            SessionHelper::set('error', 'Nie udało się zmienić hasła.');
        }

        header('Location: /settings/account');
        exit();
    }

    public function accountSettings()
    {
        SessionHelper::start();

        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        require __DIR__ . '/../Views/settings_account.php';
    }

    public function deleteAccount()
    {
        SessionHelper::start();
        if (!SessionHelper::has('user')) {
            header('Location: /login');
            exit();
        }

        $userId = SessionHelper::get('user')['id'];

        // Usuń konto
        $deleted = $this->settingsModel->deleteUser($userId);

        if ($deleted) {
            SessionHelper::destroy(); // Wylogowanie użytkownika
            header('Location: /register'); // Przekierowanie do rejestracji
        } else {
            SessionHelper::set('error', 'Nie udało się usunąć konta.');
            header('Location: /settings/account');
        }
        exit();
    }

    // API endpoint - zwraca limit dla kategorii (używane w ustawieniach)
    public function limit()
    {
        SessionHelper::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!SessionHelper::has('user')) {
            echo json_encode(['error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $userId = SessionHelper::get('user')['id'];
        $categoryName = $_GET['category'] ?? null;

        if (!$categoryName) {
            echo json_encode(['error' => 'Category name required'], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $categoryName = urldecode($categoryName);
        $limit = $this->settingsModel->getLimit($userId, $categoryName);

        echo json_encode(['limit' => (float)$limit], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
