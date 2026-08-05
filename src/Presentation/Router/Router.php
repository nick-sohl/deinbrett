<?php

namespace DeinBrett\Presentation\Router;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $controller, ?callable $middleware = null): void
    {
        $this->routes[] = $this->makeRoute('GET', $path, $controller, $middleware);
    }

    public function post(string $path, callable $controller, ?callable $middleware = null): void
    {
        $this->routes[] = $this->makeRoute('POST', $path, $controller, $middleware);
    }

    public function dispatch(string $method, string $path): void
    {
        $path = parse_url($path, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (!preg_match($route['pattern'], $path, $matches)) continue;

            $params = [];
            foreach ($matches as $k => $v) {
                if (is_string($k)) $params[$k] = $v;
            }

            if ($route['middleware'] !== null) {
                $shouldContinue = ($route['middleware'])();
                if ($shouldContinue === false) return;
            }

            ($route['controller'])($params);
            return;
        }

        http_response_code(404);
    }

    private function makeRoute(string $method, string $path, callable $controller, ?callable $middleware): array
    {
        return [
            'method'     => $method,
            'path'       => $path,
            'pattern'    => $this->compilePattern($path),
            'controller' => $controller,
            'middleware' => $middleware,
        ];
    }

    private function compilePattern(string $path): string
    {
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function ($m) {
            return '(?P<' . $m[1] . '>[^/]+)';
        }, $path);
        return '#^' . $regex . '$#';
    }
}
