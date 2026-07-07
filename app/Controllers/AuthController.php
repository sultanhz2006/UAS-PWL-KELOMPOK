<?php

require_once APP_PATH . '/Core/BaseController.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Middleware/AuthMiddleware.php';

class AuthController extends BaseController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // ----------------------------------------------------------------
    // GET /login
    // ----------------------------------------------------------------
    public function showLogin(): void {
        AuthMiddleware::guestOnly(); // Redirect jika sudah login
        $this->view('auth/login', ['title' => 'Login — ' . APP_NAME], 'layouts/auth');
    }

    // ----------------------------------------------------------------
    // POST /login
    // ----------------------------------------------------------------
    public function login(): void {
        AuthMiddleware::guestOnly();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validasi dasar
        if (empty($email) || empty($password)) {
            $this->flash('danger', 'Email dan password wajib diisi.');
            $this->redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('danger', 'Format email tidak valid.');
            $this->redirect('/login');
        }

        // Cari user di DB
        $user = $this->userModel->findByEmail($email);

        // Verifikasi password dengan password_verify (BCRYPT)
        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('danger', 'Email atau password salah.');
            $this->redirect('/login');
        }

        // Buat session
        session_regenerate_id(true); // Cegah session fixation
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['nama_lengkap'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        $this->flash('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');

        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/pelanggan/dashboard');
        }
    }

    // ----------------------------------------------------------------
    // GET /register
    // ----------------------------------------------------------------
    public function showRegister(): void {
        AuthMiddleware::guestOnly();
        $this->view('auth/register', ['title' => 'Daftar Akun — ' . APP_NAME], 'layouts/auth');
    }

    // ----------------------------------------------------------------
    // POST /register
    // ----------------------------------------------------------------
    public function register(): void {
        AuthMiddleware::guestOnly();

        $nama     = trim($_POST['nama_lengkap'] ?? '');
        $email    = trim($_POST['email']        ?? '');
        $password = trim($_POST['password']     ?? '');
        $konfirm  = trim($_POST['konfirmasi']   ?? '');
        $noTelp   = trim($_POST['no_telp']      ?? '');

        // --- Validasi input ---
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

        // Cek duplikat email
        if ($this->userModel->emailExists($email)) {
            $this->flash('danger', 'Email sudah terdaftar. Silakan gunakan email lain.');
            $this->redirect('/register');
        }

        // Hash password dengan BCRYPT (cost = 12)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $ok = $this->userModel->create([
            'nama_lengkap' => $nama,
            'email'        => $email,
            'password'     => $hashedPassword,
            'no_telp'      => $noTelp,
            'role'         => 'pelanggan', // Role default selalu pelanggan
        ]);

        if ($ok) {
            $this->flash('success', 'Akun berhasil dibuat! Silakan login.');
            $this->redirect('/login');
        } else {
            $this->flash('danger', 'Gagal membuat akun. Coba lagi.');
            $this->redirect('/register');
        }
    }

    // ----------------------------------------------------------------
    // GET /logout
    // ----------------------------------------------------------------
    public function logout(): void {
        // Hapus semua data session
        $_SESSION = [];

        // Hapus cookie session
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
