# Migracje bazy danych - Budget Manager

## Automatyczna inicjalizacja

Projekt automatycznie tworzy wszystkie tabele przy pierwszym uruchomieniu poprzez `DatabaseInitializer.php`.

Proces inicjalizacji:
1. Tworzy bazę danych jeśli nie istnieje
2. Tworzy wszystkie tabele (users, incomes, expenses, kategorie, payment_methods, transactions)
3. Dodaje domyślne kategorie i metody płatności

## Ręczna instalacja

Jeśli chcesz ręcznie utworzyć bazę danych:

```bash
mysql -u root -p < migrations.sql
mysql -u root -p budgetmanager < seeds.sql
```

## Struktura bazy danych

### Tabele główne:
- `users` - użytkownicy systemu
- `incomes` - przychody użytkowników
- `expenses` - wydatki użytkowników
- `transactions` - historia transakcji

### Tabele kategorii:
- `incomes_category_default` - domyślne kategorie przychodów (seed data)
- `incomes_category_assigned_to_users` - kategorie przychodów przypisane do użytkowników
- `expenses_category_default` - domyślne kategorie wydatków (seed data)
- `expenses_category_assigned_to_users` - kategorie wydatków przypisane do użytkowników

### Tabele metod płatności:
- `payment_methods_default` - domyślne metody płatności (seed data)
- `payment_methods_assigned_to_users` - metody płatności przypisane do użytkowników

## Domyślne kategorie

### Przychody:
1. Wynagrodzenie
2. Inwestycje
3. Dochód pasywny
4. Inne

### Wydatki:
1. Jedzenie
2. Paliwo
3. Transport publiczny
4. Taxi
5. Rozrywka
6. Zdrowie
7. Ubrania
8. Higiena
9. Dzieci
10. Rekreacja
11. Podróże
12. Oszczędności
13. Na emeryturę
14. Spłata długów
15. Prezenty
16. Inne

### Metody płatności:
1. Debit card
2. Cash
3. Credit card

## Nowe funkcjonalności

### Limity wydatków (spending_limit)
Dodano możliwość ustawiania limitów wydatków na kategorie:
- Kolumna `spending_limit` w tabeli `expenses_category_assigned_to_users`
- Ustawianie limitów przez modal w ustawieniach
- Progress bary w bilansie pokazujące procent wykorzystania limitu
- Alerty na stronie głównej o przekroczonych limitach

```sql
ALTER TABLE expenses_category_assigned_to_users 
ADD COLUMN spending_limit DECIMAL(10,2) DEFAULT NULL;
```

## Backup bazy danych

Eksport struktury (bez danych użytkowników):
```bash
mysqldump -u root --no-data budgetmanager > migrations.sql
```

Eksport pełny (struktura + wszystkie dane):
```bash
mysqldump -u root budgetmanager > backup.sql
```

## Reset bazy danych

**UWAGA: To usunie wszystkie dane!**

```bash
mysql -u root -p -e "DROP DATABASE IF EXISTS budgetmanager; CREATE DATABASE budgetmanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p budgetmanager < migrations.sql
mysql -u root -p budgetmanager < seeds.sql
```

