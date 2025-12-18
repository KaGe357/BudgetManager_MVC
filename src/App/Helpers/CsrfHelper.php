<?php

namespace App\Helpers;

class CsrfHelper
{
    public static function generateToken(): string
    {
        SessionHelper::start();

        if (!SessionHelper::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            SessionHelper::set('csrf_token', $token);
        }

        return SessionHelper::get('csrf_token');
    }

    public static function validateToken(string $token): bool
    {
        SessionHelper::start();

        $sessionToken = SessionHelper::get('csrf_token');

        if (!$sessionToken || !$token) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function getTokenField(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
