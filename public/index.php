<?php
/**
 * VyanTravel — Front Controller
 * Semua request masuk ke sini via .htaccess
 */

declare(strict_types=1);

// ---- Bootstrap ----
define('ROOT_PATH', dirname(__DIR__));

// Bootstrap — app.php akan skip define yang sudah ada
require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/app/Core/Router.php';
require_once ROOT_PATH . '/app/Core/BaseController.php';
require_once ROOT_PATH . '/app/Middleware/AuthMiddleware.php';


// ---- Session ----
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,   // Set true di production (HTTPS)
    'httponly' => true,    // Mencegah XSS via JavaScript
    'samesite' => 'Lax',
]);
session_start();

// ---- Routing ----
$router = new Router();

// --- Guest routes ---
$router->get('/',          ['AuthController', 'showLogin']);
$router->get('/login',     ['AuthController', 'showLogin']);
$router->post('/login',    ['AuthController', 'login']);
$router->get('/register',  ['AuthController', 'showRegister']);
$router->post('/register', ['AuthController', 'register']);
$router->get('/logout',    ['AuthController', 'logout']);

// --- Admin routes ---
$router->get('/admin/dashboard',                ['AdminController', 'dashboard']);
$router->get('/admin/paket',                    ['AdminController', 'paketIndex']);
$router->get('/admin/paket/create',             ['AdminController', 'paketCreate']);
$router->post('/admin/paket/store',             ['AdminController', 'paketStore']);
$router->get('/admin/paket/:id/edit',           ['AdminController', 'paketEdit']);
$router->post('/admin/paket/:id/update',        ['AdminController', 'paketUpdate']);
$router->post('/admin/paket/:id/delete',        ['AdminController', 'paketDelete']);
$router->get('/admin/booking',                  ['AdminController', 'bookingIndex']);
$router->post('/admin/booking/:id/status',      ['AdminController', 'bookingUpdateStatus']);

// --- Pelanggan routes ---
$router->get('/pelanggan/dashboard',            ['PelangganController', 'dashboard']);
$router->get('/pelanggan/paket',                ['PelangganController', 'paketList']);
$router->get('/pelanggan/paket/:id',            ['PelangganController', 'paketDetail']);
$router->post('/pelanggan/booking/store',       ['PelangganController', 'bookingStore']);
$router->get('/pelanggan/booking',              ['PelangganController', 'bookingList']);
$router->get('/pelanggan/booking/:id/download', ['PelangganController', 'downloadTiket']);

// ---- Dispatch ----
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);
