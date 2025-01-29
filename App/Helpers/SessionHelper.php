<?php

namespace App\Helpers;

class SessionHelper
{
    // Rozpoczyna sesję, jeśli jeszcze nie jest rozpoczęta
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Pobiera wartość z sesji na podstawie klucza
    public static function get($key)
    {
        self::start(); // Upewnij się, że sesja jest rozpoczęta
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Ustawia wartość w sesji dla danego klucza
    public static function set($key, $value)
    {
        self::start(); // Upewnij się, że sesja jest rozpoczęta
        $_SESSION[$key] = $value;
    }

    // Usuwa wartość z sesji
    public static function remove($key)
    {
        self::start(); // Upewnij się, że sesja jest rozpoczęta
        unset($_SESSION[$key]);
    }

    // Sprawdza, czy dany klucz istnieje w sesji
    public static function has($key)
    {
        self::start(); // Upewnij się, że sesja jest rozpoczęta
        return isset($_SESSION[$key]);
    }

    // Zamyka sesję i usuwa wszystkie dane
    public static function destroy()
    {
        self::start(); // Upewnij się, że sesja jest rozpoczęta
        session_unset(); // Usuwa wszystkie zmienne sesji
        session_destroy(); // Zniszczy sesję
    }
}
