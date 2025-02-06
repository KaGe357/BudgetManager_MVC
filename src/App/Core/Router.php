<?php

namespace App\Core;

class Router
{
    private static $routes = [];

    public static function add($method, $path, $callback)
    {
        self::$routes[] = compact('method', 'path', 'callback');
    }

    public static function dispatch($uri, $method)
    {
        foreach (self::$routes as $route) {
            if ($route['path'] === $uri && $route['method'] === $method) {
                if (is_array($route['callback'])) {
                    [$controllerClass, $actionMethod] = $route['callback'];
                    $controller = new $controllerClass(); // Utwórz instancję kontrolera
                    call_user_func([$controller, $actionMethod]);
                } else {
                    call_user_func($route['callback']);
                }
                return;
            }
        }

        http_response_code(404);
        echo "Nie znaleziono strony";
    }
}
