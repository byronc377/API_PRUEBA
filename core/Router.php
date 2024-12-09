<?php

class Router {
    public array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    // Método para manejar rutas GET
    public function get(string $path, callable|string $action): void {
        $this->routes['GET'][$path] = $action;
    }

    // Método para manejar rutas POST
    public function post(string $path, callable|string $action): void {
        $this->routes['POST'][$path] = $action;
    }

    // Método para resolver la ruta actual
    public function resolve(): void {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();
        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
            return;
        }

        if (is_callable($action)) {
            call_user_func($action);
        } else if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            require_once dirname(__DIR__) . "/app/Controllers/" . $controller . ".php";
            $controllerInstance = new $controller();
            call_user_func([$controllerInstance, $method]);
        }
    }
}
