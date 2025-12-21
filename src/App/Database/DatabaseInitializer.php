<?php

namespace App\Database;

use PDO;

class DatabaseInitializer
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function initialize()
    {
        $this->createUsersTable();
        $this->createIncomesTables();
        $this->createExpensesTables();
        $this->createPaymentMethodsTables();
        $this->createTransactionsTable();
        $this->createAiAdvicesTable();
        $this->seedDefaultCategories();
    }

    private function createUsersTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                pass VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) 
        ");
    }

    private function createIncomesTables()
    {
        // Tabela kategorii domyślnych przychodów
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS incomes_category_default (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL
            ) 
        ");

        // Tabela kategorii przychodów przypisanych do użytkowników
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS incomes_category_assigned_to_users (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                name VARCHAR(50) NOT NULL
            ) 
        ");

        // Tabela przychodów
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS incomes (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                income_category_assigned_to_user_id INT(11) UNSIGNED NOT NULL,
                amount DECIMAL(8,2) NOT NULL DEFAULT 0.00,
                date_of_income DATE NOT NULL,
                income_comment VARCHAR(100) NOT NULL
            ) 
        ");
    }

    private function createExpensesTables()
    {
        // Tabela kategorii domyślnych wydatków
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS expenses_category_default (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL
            ) 
        ");

        // Tabela kategorii wydatków przypisanych do użytkowników
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS expenses_category_assigned_to_users (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                name VARCHAR(50) NOT NULL
            ) 
        ");

        // Tabela wydatków
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS expenses (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                expense_category_assigned_to_user_id INT(11) UNSIGNED NOT NULL,
                payment_method_assigned_to_user_id INT(11) UNSIGNED NOT NULL,
                amount DECIMAL(8,2) NOT NULL DEFAULT 0.00,
                date_of_expense DATE NOT NULL,
                expense_comment VARCHAR(100) NOT NULL
            ) 
        ");
    }

    private function createPaymentMethodsTables()
    {
        // Tabela domyślnych metod płatności
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS payment_methods_default (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL
            ) 
        ");

        // Tabela metod płatności przypisanych do użytkowników
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS payment_methods_assigned_to_users (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                name VARCHAR(50) NOT NULL
            ) 
        ");
    }

    private function createTransactionsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS transactions (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                category VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) 
        ");
    }

    private function createAiAdvicesTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS ai_advices (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                advice_text LONGTEXT NOT NULL,
                months_analyzed INT(11) NOT NULL,
                balance_snapshot JSON NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user (user_id)
            )
        ");
    }

    private function seedDefaultCategories()
    {
        // Sprawdź czy dane już istnieją
        $check = $this->pdo->query("SELECT COUNT(*) FROM incomes_category_default")->fetchColumn();
        if ($check > 0) {
            return; // Dane już zostały dodane
        }

        // Domyślne kategorie przychodów
        $this->pdo->exec("
            INSERT INTO incomes_category_default (id, name) VALUES
            (1, 'Wynagrodzenie'),
            (2, 'Inwestycje'),
            (3, 'Dochód pasywny'),
            (4, 'Inne')
        ");

        // Domyślne kategorie wydatków
        $this->pdo->exec("
            INSERT INTO expenses_category_default (id, name) VALUES
            (1, 'Jedzenie'),
            (2, 'Paliwo'),
            (3, 'Transport publiczny'),
            (4, 'Taxi'),
            (5, 'Rozrywka'),
            (6, 'Zdrowie'),
            (7, 'Ubrania'),
            (8, 'Higiena'),
            (9, 'Dzieci'),
            (10, 'Rekreacja'),
            (11, 'Podróże'),
            (12, 'Oszczędności'),
            (13, 'Na emeryture'),
            (14, 'Spłata długów'),
            (15, 'Prezenty'),
            (16, 'Inne')
        ");

        // Domyślne metody płatności
        $this->pdo->exec("
            INSERT INTO payment_methods_default (id, name) VALUES
            (1, 'Debit card'),
            (2, 'Cash'),
            (3, 'Credit card')
        ");
    }
}
