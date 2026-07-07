<?php

class AuthMiddleware {

    public static function requireLogin(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireRole(string|array $roles): void {
        self::requireLogin();

        $allowed     = (array) $roles;
        $currentRole = $_SESSION['user_role'] ?? '';

        if (!in_array($currentRole, $allowed, true)) {
            http_response_code(403);
            require APP_PATH . '/Views/errors/403.php';
            exit;
        }
    }

    public static function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    public static function role(): string {
        return $_SESSION['user_role'] ?? '';
    }

    public static function guestOnly(): void {
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? 'pelanggan';
            header('Location: ' . BASE_URL . ($role === 'admin' ? '/admin/dashboard' : '/pelanggan/dashboard'));
            exit;
        }
    }
}
