<!-- app/Views/auth/login.php -->
<h5 class="fw-700 mb-1" style="color:#1A2B3C;font-weight:700">Masuk ke Akun</h5>
<p class="text-muted mb-4" style="font-size:.85rem">Selamat datang kembali! Silakan login.</p>

<form action="<?= BASE_URL ?>/login" method="POST" novalidate>
    <div class="mb-3">
        <label class="form-label">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control"
                   placeholder="nama@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required autofocus>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control"
                   placeholder="Masukkan password" required>
        </div>
    </div>
    <button type="submit" class="btn-auth btn">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
    </button>
</form>

<div class="divider">— atau —</div>
<p class="text-center" style="font-size:.85rem;color:#374151">
    Belum punya akun?
    <a href="<?= BASE_URL ?>/register" style="color:#0A6CFF;font-weight:600;text-decoration:none">Daftar sekarang</a>
</p>

<div class="mt-3 p-2 rounded-2" style="background:#F0F9FF;font-size:.78rem;color:#0369A1">
    <i class="bi bi-info-circle me-1"></i>
    Demo admin: <strong>admin@vyantravel.com</strong> / <strong>Admin@1234</strong>
</div>
