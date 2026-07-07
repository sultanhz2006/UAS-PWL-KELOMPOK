<?php

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        // Bersihkan query string
        $uri = strtok($uri, '?');
        $uri = urldecode($uri);
        // Hapus base path agar routing bekerja di subfolder
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $uri  = '/' . ltrim(substr($uri, strlen($base)), '/');
        $uri  = ($uri === '') ? '/' : $uri;

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $handler) {
            // Konversi :param ke regex
            $pattern = preg_replace('#:([a-zA-Z0-9_]+)#', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                [$controllerClass, $methodName] = $handler;

                // Auto-load controller
                $file = APP_PATH . '/Controllers/' . $controllerClass . '.php';
                if (!file_exists($file)) {
                    $this->abort(500, "Controller $controllerClass tidak ditemukan.");
                }
                require_once $file;

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $ctrl   = new $controllerClass();
                $ctrl->$methodName(...array_values($params));
                return;
            }
        }

        $this->abort(404, "Halaman tidak ditemukan.");
    }

    private function abort(int $code, string $msg): void {
        http_response_code($code);
        $viewFile = APP_PATH . "/Views/errors/{$code}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<h1>Error {$code}</h1><p>{$msg}</p>";
        }
        exit;
    }
}
