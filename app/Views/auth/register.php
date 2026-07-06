<!-- app/Views/auth/register.php -->
<h5 class="fw-700 mb-1" style="color:#1A2B3C;font-weight:700">Buat Akun Baru</h5>
<p class="text-muted mb-4" style="font-size:.85rem">Daftar gratis dan mulai perjalanan impianmu.</p>

<form action="<?= BASE_URL ?>/register" method="POST" novalidate>
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="nama_lengkap" class="form-control"
                   placeholder="Nama sesuai KTP"
                   value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>"
                   required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control"
                   placeholder="nama@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">No. HP / WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" name="no_telp" class="form-control"
                   placeholder="08xxxxxxxxxx"
                   value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password <span class="text-muted">(min. 8 karakter)</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control"
                   placeholder="Buat password kuat" required>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="konfirmasi" class="form-control"
                   placeholder="Ulangi password" required>
        </div>
    </div>
    <button type="submit" class="btn-auth btn">
        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
    </button>
</form>

<div class="divider">atau</div>
<p class="text-center" style="font-size:.85rem;color:#374151">
    Sudah punya akun?
    <a href="<?= BASE_URL ?>/login" style="color:#0A6CFF;font-weight:600;text-decoration:none">Masuk di sini</a>
</p>
