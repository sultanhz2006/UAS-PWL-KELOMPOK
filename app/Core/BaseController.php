<?php
// app/Core/BaseController.php

abstract class BaseController {

    /**
     * Render view dengan layout.
     *
     * @param string $view   Path relatif dari Views/, e.g. 'admin/dashboard'
     * @param array  $data   Data yang di-extract ke scope view
     * @param string $layout Layout yang dipakai, e.g. 'layouts/main'
     */
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void {
        extract($data, EXTR_SKIP);

        $viewFile   = APP_PATH . '/Views/' . $view . '.php';
        $layoutFile = APP_PATH . '/Views/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            die("View tidak ditemukan: $viewFile");
        }

        // Render view ke buffer, lalu sisipkan ke layout via $content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    /**
     * Redirect ke URL relative dari BASE_URL.
     */
    protected function redirect(string $path): never {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Simpan flash message ke session.
     */
    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Kembalikan JSON response (untuk AJAX / API endpoint).
     */
    protected function json(mixed $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
