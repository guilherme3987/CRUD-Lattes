<?php

namespace App\Services;

class AuthService {
    public function isAuthenticated(): bool {
        return !empty($_SESSION['logged_in']);
    }

    public function getLoggedInId(): ?string {
        return $_SESSION['id_lattes'] ?? null;
    }

    public function getLoggedInName(): ?string {
        return $_SESSION['nome'] ?? null;
    }

    public function login(string $idLattes, string $nome): void {
        $_SESSION['logged_in'] = true;
        $_SESSION['id_lattes'] = $idLattes;
        $_SESSION['nome'] = $nome;
        session_regenerate_id(true);
    }

    public function logout(): void {
        session_destroy();
    }

    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password, string $hash): bool {
        if (password_verify($password, $hash)) {
            return true;
        }
        // Transition from legacy md5 hashes
        if ($hash === md5($password)) {
            return true;
        }
        return false;
    }

    public function isLegacyHash(string $hash): bool {
        return strlen($hash) === 32 && ctype_xdigit($hash);
    }

    public function requireAuth(): void {
        if (!$this->isAuthenticated()) {
            header('Location: /?action=login');
            exit;
        }
    }
}
