<?php

require_once APP_PATH . '/Core/BaseController.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Middleware/AuthMiddleware.php';

class AuthController extends BaseController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin(): void {
        AuthMiddleware::guestOnly();
        $this->view('auth/login', ['title' => 'Login — ' . APP_NAME], 'layouts/auth');
    }

    public function login(): void {
        AuthMiddleware::guestOnly();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->flash('danger', 'Email dan password wajib diisi.');
            $this->redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('danger', 'Format email tidak valid.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('danger', 'Email atau password salah.');
            $this->redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['nama_lengkap'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        $this->flash('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');

        if ($user['role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/pelanggan/dashboard');
        }
    }

    public function showRegister(): void {
        AuthMiddleware::guestOnly();
        $this->view('auth/register', ['title' => 'Daftar Akun — ' . APP_NAME], 'layouts/auth');
    }

    public function register(): void {
        AuthMiddleware::guestOnly();

        $nama     = trim($_POST['nama_lengkap'] ?? '');
        $email    = trim($_POST['email']        ?? '');
        $password = trim($_POST['password']     ?? '');
        $konfirm  = trim($_POST['konfirmasi']   ?? '');
        $noTelp   = trim($_POST['no_telp']      ?? '');

        $errors = [];
        if (empty($nama))     $errors[] = 'Nama lengkap wajib diisi.';
        if (empty($email))    $errors[] = 'Email wajib diisi.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
        if (strlen($password) < 8)  $errors[] = 'Password minimal 8 karakter.';
        if ($password !== $konfirm) $errors[] = 'Konfirmasi password tidak cocok.';

        if (!empty($errors)) {
            $this->flash('danger', implode('<br>', $errors));
            $this->redirect('/register');
        }

        if ($this->userModel->emailExists($email)) {
            $this->flash('danger', 'Email sudah terdaftar. Silakan gunakan email lain.');
            $this->redirect('/register');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $ok = $this->userModel->create([
            'nama_lengkap' => $nama,
            'email'        => $email,
            'password'     => $hashedPassword,
            'no_telp'      => $noTelp,
            'role'         => 'pelanggan',
        ]);

        if ($ok) {
            $this->flash('success', 'Akun berhasil dibuat! Silakan login.');
            $this->redirect('/login');
        } else {
            $this->flash('danger', 'Gagal membuat akun. Coba lagi.');
            $this->redirect('/register');
        }
    }

    public function logout(): void {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        $this->flash('success', 'Anda berhasil logout.');
        $this->redirect('/login');
    }
}
