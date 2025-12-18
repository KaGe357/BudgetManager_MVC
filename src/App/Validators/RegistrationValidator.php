<?php

namespace App\Validators;

class RegistrationValidator
{
    private array $errors = [];

    public function validate(array $data): bool
    {
        $this->errors = [];

        $this->validateName($data['name'] ?? '');
        $this->validateEmail($data['email'] ?? '');
        $this->validatePasswords($data['haslo1'] ?? '', $data['haslo2'] ?? '');
        $this->validateTerms($data['regulamin'] ?? false);

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateName(string $name): void
    {
        if (strlen($name) < 3 || strlen($name) > 20) {
            $this->errors['name'] = 'Imię musi mieć od 3 do 20 znaków.';
            return;
        }

        if (!ctype_alnum($name)) {
            $this->errors['name'] = 'Imię może zawierać tylko litery i cyfry.';
        }
    }

    private function validateEmail(string $email): void
    {
        $sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (!filter_var($sanitized, FILTER_VALIDATE_EMAIL) || $sanitized !== $email) {
            $this->errors['email'] = 'Podaj poprawny adres email.';
        }
    }

    private function validatePasswords(string $pass1, string $pass2): void
    {
        if (strlen($pass1) < 8 || strlen($pass1) > 20) {
            $this->errors['haslo'] = 'Hasło musi mieć od 8 do 20 znaków.';
            return;
        }

        if ($pass1 !== $pass2) {
            $this->errors['haslo'] = 'Hasła muszą być identyczne.';
        }
    }

    private function validateTerms(bool $accepted): void
    {
        if (!$accepted) {
            $this->errors['regulamin'] = 'Musisz zaakceptować regulamin.';
        }
    }
}
