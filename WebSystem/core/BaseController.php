<?php

namespace App\Core;

use App\Services\AuthService;
use App\Services\CsrfService;

class BaseController {
    protected AuthService $auth;
    protected CsrfService $csrf;

    public function __construct() {
        $this->auth = new AuthService();
        $this->csrf = new CsrfService();
    }

    protected function render(string $view, array $data = []): void {
        $data['auth'] = $this->auth;
        $data['csrf'] = $this->csrf;
        $data['currentAction'] = $_GET['action'] ?? 'index';
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . "/../views/$view.php";
        require __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}
