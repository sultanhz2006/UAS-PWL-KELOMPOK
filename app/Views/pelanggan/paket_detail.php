<!-- app/Views/pelanggan/paket_detail.php -->
<?php if (!isset($paket) || !is_array($paket)): ?>
<div class="alert alert-danger py-3 fs-92">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>Paket tidak ditemukan atau data paket tidak tersedia.
</div>
<?php return; ?>
<?php endif; ?>
<?php $penerbangan = isset($penerbangan) && is_array($penerbangan) ? $penerbangan : []; ?>
<div class="mb-4">
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="text-decoration-none text-muted fs-85">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke daftar paket
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Detail Paket -->
    <div class="col-lg-8">
        <div class="stat-card p-0 overflow-hidden mb-4">
            <?php if ($paket['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($paket['foto']) ?>"
                 alt="<?= htmlspecialchars($paket['nama_paket']) ?>"
                 class="img-cover-300">
            <?php else: ?>
            <div class="img-cover-300 bg-gradient-primary-alt d-flex align-items-center justify-content-center text-white fs-4rem">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <h4 class="fw-700 mb-0"><?= htmlspecialchars($paket['nama_paket']) ?></h4>
                    <span class="badge bg-primary rounded-pill px-3 py-2"><?= $paket['durasi_hari'] ?> Hari</span>
                </div>
                <div class="d-flex gap-3 flex-wrap mb-3 fs-85 text-secondary-soft">
                    <span><i class="bi bi-geo-alt-fill text-primary me-1"></i><?= htmlspecialchars($paket['destinasi']) ?></span>
                    <span><i class="bi bi-people-fill text-primary me-1"></i>Kuota: <?= $paket['kuota'] ?> orang</span>
                </div>
                <p class="text-secondary-soft lh-17">
                    <?= nl2br(htmlspecialchars($paket['deskripsi'] ?? 'Deskripsi tidak tersedia.')) ?>
                </p>
            </div>
        </div>

        <!-- Info Penerbangan (API Aviationstack) -->
        <?php if (!empty($penerbangan['penerbangan'] ?? null)): ?>
        <div class="stat-card mb-4">
            <h6 class="fw-600 mb-3">
                <i class="bi bi-airplane-fill text-primary me-2"></i>
                Penerbangan ke <?= htmlspecialchars($penerbangan['bandara_nama'] ?? '') ?>
                (<?= htmlspecialchars($penerbangan['bandara_kode'] ?? '') ?>)
            </h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 fs-82">
                    <thead class="bg-light">
                        <tr>
                            <th>Maskapai</th>
                            <th>Flight</th>
                            <th>Dari</th>
                            <th>Berangkat</th>
                            <th>Tiba</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penerbangan['penerbangan'] as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['maskapai']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($f['nomor']) ?></span></td>
                            <td><?= htmlspecialchars($f['dari']) ?></td>
                            <td><?= htmlspecialchars(substr($f['waktu_berangkat'], 11, 5) ?: $f['waktu_berangkat']) ?></td>
                            <td><?= htmlspecialchars(substr($f['waktu_tiba'], 11, 5) ?: $f['waktu_tiba']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($f['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-2 fs-75 text-muted-soft">
                Data penerbangan dari Aviationstack API. Dapat berubah sewaktu-waktu.
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info py-2 fs-82">
            <i class="bi bi-info-circle me-1"></i>
            Info penerbangan destinasi belum tersedia atau gagal dimuat dari Aviationstack API.
        </div>
        <?php endif; ?>
    </div>

    <!-- Kolom Kanan: Form Booking -->
    <div class="col-lg-4">
        <div class="stat-card sticky-top-80">
            <div class="mb-3">
                <div class="text-muted fs-8">Harga per orang</div>
                <div class="fs-18 fw-800 text-primary">
                    Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
                </div>
            </div>
            <hr>
            <form action="<?= BASE_URL ?>/pelanggan/booking/store" method="POST">
                <input type="hidden" name="paket_id" value="<?= $paket['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-500 fs-85">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_berangkat" class="form-control"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 fs-85">Jumlah Peserta</label>
                    <input type="number" name="jumlah_peserta" id="jumlahPeserta" class="form-control"
                           min="1" max="<?= $paket['kuota'] ?>" value="1" required
                           oninput="hitungTotal(this.value)">
                    <div class="form-text">Maks. <?= $paket['kuota'] ?> orang</div>
                    <div id="pesertaError" class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 fs-85">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2"
                              placeholder="Permintaan khusus, dll."></textarea>
                </div>

                <!-- Kalkulasi Harga -->
                <div class="p-3 rounded-3 mb-3 bg-soft">
                    <div class="d-flex justify-content-between fs-82 text-secondary-soft">
                        <span>Harga x Peserta</span>
                        <span id="totalDisplay">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-700 text-primary">
                        <span>Total Bayar</span>
                        <span id="totalFinal">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <button type="submit" id="btnPesan" class="btn btn-primary w-100 rounded-3 py-2 fw-600">
                    <i class="bi bi-calendar-plus me-2"></i>Pesan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const hargaSatuan = <?= (int) $paket['harga'] ?>;
const kuotaMaksimal = <?= (int) $paket['kuota'] ?>;
const jumlahPesertaInput = document.getElementById('jumlahPeserta');
const pesertaError = document.getElementById('pesertaError');
const btnPesan = document.getElementById('btnPesan');
const totalDisplay = document.getElementById('totalDisplay');
const totalFinal = document.getElementById('totalFinal');

function hitungTotal(jumlah) {
    const peserta = parseInt(jumlah, 10);
    let pesanError = '';

    if (!peserta || peserta < 1) {
        pesanError = 'Jumlah peserta minimal 1 orang.';
    } else if (peserta > kuotaMaksimal) {
        pesanError = 'Jumlah peserta tidak boleh melebihi kuota.';
    }

    if (pesanError) {
        jumlahPesertaInput.classList.add('is-invalid');
        pesertaError.textContent = pesanError;
        btnPesan.disabled = true;
        totalDisplay.textContent = '-';
        totalFinal.textContent = '-';
        return;
    }

    jumlahPesertaInput.classList.remove('is-invalid');
    pesertaError.textContent = '';
    btnPesan.disabled = false;

    const total = hargaSatuan * peserta;
    const fmt = new Intl.NumberFormat('id-ID').format(total);
    totalDisplay.textContent = 'Rp ' + fmt;
    totalFinal.textContent = 'Rp ' + fmt;
}

hitungTotal(jumlahPesertaInput.value);
</script>
