<?php

namespace DeinBrett\Presentation\Router;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $controller): void
    {
        $this->routes[] = ['method' => 'GET', 'path' => $path, 'controller' => $controller];
    }

    public function post(string $path, callable $controller): void
    {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'controller' => $controller];
    }

    public function dispatch(string $method, string $path): void
    {
        $path = parse_url($path, PHP_URL_PATH);
        // Loop thorough our registered Routes
        foreach ($this->routes as $route) {
            // If the Request from the User matches with one of our routes
            if ($route['method'] === $method && $route['path'] === $path) {
                ($route['controller'])();
                return;
            }
        }
        // No route matched the request
        http_response_code(404);
    }
}
