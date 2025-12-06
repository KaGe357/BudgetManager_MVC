# BudgetManager MVC

Prosty menedżer budżetu domowego napisany w PHP (architektura MVC) z własnym routerem i inicjalizacją bazy danych. Projekt przeznaczony jest do uruchamiania jako aplikacja (nie biblioteka).

## Funkcje
- Rejestracja i logowanie użytkowników
- Dodawanie przychodów i wydatków z komentarzem i datą
- Dashboard z wykresami przychodów/wydatków (Chart.js)
- Przegląd bilansu z możliwością wyboru zakresu dat
- Historia transakcji z paginacją
- Limity wydatków na kategorie z wizualizacją procentową
- Zarządzanie kategoriami i metodami płatności
- Ustawienia konta (zmiana nazwy, hasła, usuwanie konta)

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

## Główne endpointy
- `/` - strona powitalna
- `/login`, `/register`, `/logout` - autoryzacja
- `/home` - dashboard z wykresami
- `/income/add`, `/expense/add` - dodawanie transakcji
- `/balance` - bilans z filtrami dat
- `/history` - historia wszystkich transakcji
- `/settings` - zarządzanie kategoriami i kontem

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
