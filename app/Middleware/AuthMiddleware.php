<?php
// app/Middleware/AuthMiddleware.php

class AuthMiddleware {

    /**
     * Pastikan user sudah login.
     * Jika belum, redirect ke halaman login.
     */
    public static function requireLogin(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Pastikan user sudah login DAN memiliki role tertentu.
     *
     * @param string|array $roles Role yang diizinkan, e.g. 'admin' atau ['admin']
     */
    public static function requireRole(string|array $roles): void {
        self::requireLogin();

        $allowed      = (array) $roles;
        $currentRole  = $_SESSION['user_role'] ?? '';

        if (!in_array($currentRole, $allowed, true)) {
            http_response_code(403);
            require APP_PATH . '/Views/errors/403.php';
            exit;
        }
    }

    /**
     * Cek apakah user sedang login (tanpa redirect).
     */
    public static function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Cek role user saat ini.
     */
    public static function role(): string {
        return $_SESSION['user_role'] ?? '';
    }

    /**
     * Cegah user yang sudah login mengakses halaman guest (login/register).
     */
    public static function guestOnly(): void {
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? 'pelanggan';
            header('Location: ' . BASE_URL . ($role === 'admin' ? '/admin/dashboard' : '/pelanggan/dashboard'));
            exit;
        }
    }
}
