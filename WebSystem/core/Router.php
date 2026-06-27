<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function add(string $action, callable $handler): void {
        $this->routes[$action] = $handler;
    }

    public function dispatch(string $action, ?string $id = null): void {
        if (isset($this->routes[$action])) {
            ($this->routes[$action])($id);
        } elseif (isset($this->routes['default'])) {
            ($this->routes['default'])($id);
        }
    }
}
