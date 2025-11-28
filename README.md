# BudgetManager MVC

Prosty menedżer budżetu domowego napisany w PHP (architektura MVC) z własnym routerem i inicjalizacją bazy danych. Projekt przeznaczony jest do uruchamiania jako aplikacja (nie biblioteka).

## Funkcje
- Rejestracja i logowanie użytkowników
- Dodawanie przychodów i wydatków
- Przegląd bilansu oraz ostatnich transakcji
- Podstawowe ustawienia konta i kategorii (przychody/wydatki)

## Stos technologiczny
- PHP 7.4+ (zalecane 8.0+)
- MySQL/MariaDB
- Composer (autoload PSR-4: `App\\` → `src/App/`)

## Wymagania
- Zainstalowane: PHP, Composer, serwer MySQL
- Dostęp do konsoli (można użyć wbudowanego serwera PHP)

## Szybki start
1) Sklonuj repozytorium i zainstaluj zależności:
```bash
composer install
```

2) Skonfiguruj połączenie z bazą danych w `config/config.php`:
```php
return [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'dbname' => 'budgetmanager',
];
```

3) Uruchom w trybie deweloperskim (wskazując katalog `public/` jako document root):
```bash
php -S localhost:8000 -t public
```
Aplikacja przy pierwszym uruchomieniu:
- utworzy bazę danych (`dbname` z konfiguracji), jeśli nie istnieje,
- zainicjalizuje podstawowe tabele (np. `users`).

4) Wejdź w przeglądarce na:
```
http://localhost:8000/
```
Rejestracja: `/register`, logowanie: `/login`.

## Routing (skrót)
Zdefiniowany w `routes/web.php`:
- GET `/` → `PageController@index`
- GET `/home` → `HomeController@index`
- GET `/login` → `AuthController@showLoginForm`
- POST `/login` → `AuthController@login`
- GET `/register` → `AuthController@showRegisterForm`
- POST `/register` → `AuthController@register`
- GET `/logout` → `AuthController@logout`
- GET `/income/add` → `IncomeController@add`
- POST `/income/save` → `IncomeController@save`
- GET `/expense/add` → `ExpenseController@add`
- POST `/expense/save` → `ExpenseController@save`
- GET `/balance` → `BalanceController@index`
- POST `/api/balance` → `BalanceController@getBalanceData`
- GET `/settings` → `SettingsController@index`
- ...oraz akcje ustawień konta i kategorii

## Struktura projektu (wybrane)
- `public/index.php` – front controller dla serwera www
- `routes/web.php` – definicje tras
- `src/App/Core/Router.php` – prosty router
- `src/App/Controllers/*` – kontrolery
- `src/App/Models/*` – modele
- `src/App/Views/*` – widoki
- `src/App/Database/DatabaseConnection.php` – połączenie i automatyczne tworzenie bazy
- `src/App/Database/DatabaseInitializer.php` – inicjalizacja tabel
- `config/config.php` – konfiguracja DB
