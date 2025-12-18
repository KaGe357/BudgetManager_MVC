# HomeBudget - Menedżer Budżetu Domowego

Aplikacja do zarządzania budżetem domowym napisana w PHP z własną architekturą MVC, routerem i pełnym zabezpieczeniem.

## ✨ Funkcje
- 🔐 **Rejestracja i logowanie** z pełną walidacją
- 💰 **Dodawanie przychodów i wydatków** z komentarzem i datą
- 📊 **Dashboard z wykresami** przychodów/wydatków (Chart.js)
- 📅 **Przegląd bilansu** z możliwością wyboru zakresu dat
- 📜 **Historia transakcji** z paginacją i możliwością usuwania
- 💳 **Limity wydatków** na kategorie z wizualizacją procentową
- ⚙️ **Zarządzanie kategoriami** i metodami płatności
- 👤 **Ustawienia konta** (zmiana nazwy, hasła, usuwanie konta)
- 🔒 **CSRF Protection** we wszystkich formularzach
- 🛡️ **Walidacja danych** po stronie backendu
- 🔍 **Autoryzacja dostępu** do danych użytkownika

## 🚀 Stos technologiczny
- **Backend:** PHP 7.4+ (zalecane 8.0+)
- **Baza danych:** MySQL/MariaDB
- **Frontend:** Bootstrap 5.3.3, Chart.js, Vanilla JavaScript
- **Zarządzanie zależnościami:** Composer (PSR-4 autoload)
- **Zmienne środowiskowe:** phpdotenv

## 📋 Wymagania
- PHP 7.4 lub wyższy
- MySQL/MariaDB
- Composer
- Serwer WWW (Apache/Nginx) lub wbudowany serwer PHP

## 🔧 Instalacja

### 1. Sklonuj repozytorium
```bash
git clone https://github.com/KaGe357/BudgetManager_MVC.git
cd BudgetManager_MVC
```

### 2. Zainstaluj zależności
```bash
composer install
```

### 3. Konfiguracja zmiennych środowiskowych
Skopiuj `.env.example` do `.env` i dostosuj ustawienia:
```bash
cp .env.example .env
```

Edytuj `.env`:
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=budgetmanager
APP_ENV=development
```

### 4. Uruchom aplikację
**Tryb deweloperski** (wbudowany serwer PHP):
```bash
php -S localhost:8080 -t public
```

**Produkcja:** Skonfiguruj document root na `public/`

### 5. Inicjalizacja bazy danych
Aplikacja automatycznie:
- Utworzy bazę danych przy pierwszym uruchomieniu
- Zainicjalizuje wszystkie potrzebne tabele

### 6. Otwórz w przeglądarce
```
http://localhost:8080/
```

## 🔐 Bezpieczeństwo

### Zaimplementowane zabezpieczenia:
- ✅ **CSRF Protection** - tokeny we wszystkich formularzach
- ✅ **Walidacja danych wejściowych** - filter_var, regex, sprawdzanie typów
- ✅ **Autoryzacja** - weryfikacja właściciela danych w każdym zapytaniu
- ✅ **Prepared Statements** - ochrona przed SQL Injection
- ✅ **Password hashing** - bcrypt (password_hash/password_verify)
- ✅ **XSS Protection** - htmlspecialchars() w widokach
- ✅ **Error logging** - try-catch z logowaniem do error_log
- ✅ **Session management** - bezpieczna obsługa sesji

### Testowanie bezpieczeństwa:
```javascript
// Test CSRF (w konsoli przeglądarki)
fetch('/settings/addExpenseCategory', {
    method: 'POST',
    body: 'expense_category_name=Test'
})
.then(r => r.text())
.then(html => console.log(html.includes('Nieprawidłowe żądanie') ? '✅ CSRF działa' : '❌ Luka'));
```

## 🛣️ Endpointy

### Strony publiczne
- `GET /` - Landing page
- `GET /login` - Formularz logowania
- `POST /login` - Autoryzacja użytkownika
- `GET /register` - Formularz rejestracji
- `POST /register` - Tworzenie konta

### Strony wymagające logowania
- `GET /home` - Dashboard z wykresami
- `GET /income/add` - Formularz dodawania przychodu
- `POST /income/save` - Zapis przychodu
- `GET /expense/add` - Formularz dodawania wydatku
- `POST /expense/save` - Zapis wydatku
- `GET /balance` - Bilans z filtrami dat
- `POST /api/balance` - API bilansu (AJAX)
- `GET /history` - Historia transakcji
- `POST /history/expense/delete` - Usuwanie wydatku
- `POST /history/income/delete` - Usuwanie przychodu
- `GET /settings` - Zarządzanie kategoriami
- `POST /settings/addIncomeCategory` - Dodaj kategorię dochodu
- `POST /settings/removeIncomeCategory` - Usuń kategorię dochodu
- `POST /settings/addExpenseCategory` - Dodaj kategorię wydatku
- `POST /settings/removeExpenseCategory` - Usuń kategorię wydatku
- `POST /settings/updateCategoryLimit` - Ustaw limit wydatków
- `GET /settings/account` - Ustawienia konta
- `POST /settings/changeUserName` - Zmiana nazwy
- `POST /settings/changePassword` - Zmiana hasła
- `POST /settings/deleteAccount` - Usuwanie konta
- `GET /logout` - Wylogowanie

### API
- `GET /api/expense/limit?category=...` - Pobierz limit kategorii
- `GET /api/limit?category=...` - Pobierz limit (ustawienia)

## 🎨 Funkcje UI/UX
- 🎨 **Gradient Design** - nowoczesny fioletowy gradient (#667eea → #764ba2)
- 🔔 **Flash Messages** - komunikaty sukcesu/błędu
- ✨ **Highlight nowych kategorii** - zielone tło dla nowo dodanych
- ⚠️ **Ostrzeżenia limitów** - wizualizacja przekroczeń w czasie rzeczywistym
- 📱 **Responsive Design** - Bootstrap 5.3.3
- 🖱️ **Hover Effects** - animacje na przyciskach i kartach
- ✅ **Confirmation Dialogs** - potwierdzenie przed usunięciem

## 🐛 Debugging

### Logi błędów
Backend zapisuje błędy do `error_log` (sprawdź konfigurację PHP):
```php
// W php.ini
error_log = /path/to/php_errors.log
```

### Tryb deweloperski
W `public/index.php` są włączone błędy:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Produkcja:** Wyłącz wyświetlanie błędów, zostaw tylko logowanie.

## 📝 TODO / Możliwe rozszerzenia
- [ ] Eksport danych (CSV/Excel/PDF)
- [ ] Powiadomienia email o przekroczonych limitach
- [ ] Recurring transactions (cykliczne płatności)
- [ ] Wspólne budżety dla rodziny
- [ ] Import transakcji z plików CSV/Excel
- [ ] Aplikacja mobilna (PWA)
- [ ] Testy jednostkowe (PHPUnit)
- [ ] CI/CD pipeline

## 👨‍💻 Autor
**Kamil** - [KaGe357](https://github.com/KaGe357)

**⭐ Jeśli projekt Ci się podoba, zostaw gwiazdkę na GitHubie!**
