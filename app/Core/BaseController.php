<?php

abstract class BaseController {

    protected function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void {
        extract($data, EXTR_SKIP);

        $viewFile   = APP_PATH . '/Views/' . $view . '.php';
        $layoutFile = APP_PATH . '/Views/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            die("View tidak ditemukan: $viewFile");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    protected function redirect(string $path): never {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function json(mixed $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
