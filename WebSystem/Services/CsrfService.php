<?php

namespace App\Services;

class CsrfService {
    public function generateToken(): string {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public function validateToken(?string $token): bool {
        if (empty($_SESSION['_csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    public function renderHiddenField(): string {
        return '<input type="hidden" name="_csrf_token" value="' . $this->generateToken() . '">';
    }
}
