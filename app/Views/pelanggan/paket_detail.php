<!-- app/Views/pelanggan/paket_detail.php -->
<div class="mb-4">
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="text-decoration-none text-muted" style="font-size:.85rem">
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
                 style="width:100%;height:300px;object-fit:cover">
            <?php else: ?>
            <div style="width:100%;height:300px;background:linear-gradient(135deg,#0A6CFF,#06B6D4);
                        display:flex;align-items:center;justify-content:center;color:#fff;font-size:4rem">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <h4 style="font-weight:700;color:#1A2B3C"><?= htmlspecialchars($paket['nama_paket']) ?></h4>
                    <span class="badge bg-primary rounded-pill px-3 py-2"><?= $paket['durasi_hari'] ?> Hari</span>
                </div>
                <div class="d-flex gap-3 flex-wrap mb-3" style="font-size:.85rem;color:#64748B">
                    <span><i class="bi bi-geo-alt-fill text-primary me-1"></i><?= htmlspecialchars($paket['destinasi']) ?></span>
                    <span><i class="bi bi-people-fill text-primary me-1"></i>Kuota: <?= $paket['kuota'] ?> orang</span>
                </div>
                <p style="color:#475569;line-height:1.7">
                    <?= nl2br(htmlspecialchars($paket['deskripsi'] ?? 'Deskripsi tidak tersedia.')) ?>
                </p>
            </div>
        </div>

        <!-- Info Penerbangan (API Aviationstack) -->
        <?php if (!empty($penerbangan['penerbangan'])): ?>
        <div class="stat-card mb-4">
            <h6 style="font-weight:600;color:#1A2B3C;margin-bottom:12px">
                <i class="bi bi-airplane-fill text-primary me-2"></i>
                Penerbangan ke <?= htmlspecialchars($penerbangan['bandara_nama']) ?>
                (<?= htmlspecialchars($penerbangan['bandara_kode']) ?>)
            </h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" style="font-size:.82rem">
                    <thead style="background:#F8FAFC">
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
            <div class="mt-2" style="font-size:.75rem;color:#94A3B8">
                Data penerbangan dari Aviationstack API. Dapat berubah sewaktu-waktu.
            </div>
        </div>
        <?php elseif ($penerbangan === null || empty($penerbangan['penerbangan'])): ?>
        <div class="alert alert-info py-2" style="font-size:.82rem">
            <i class="bi bi-info-circle me-1"></i>
            Info penerbangan destinasi belum tersedia atau gagal dimuat dari Aviationstack API.
        </div>
        <?php endif; ?>
    </div>

    <!-- Kolom Kanan: Form Booking -->
    <div class="col-lg-4">
        <div class="stat-card" style="position:sticky;top:80px">
            <div class="mb-3">
                <div class="text-muted" style="font-size:.8rem">Harga per orang</div>
                <div style="font-size:1.8rem;font-weight:800;color:#0A6CFF">
                    Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
                </div>
            </div>
            <hr>
            <form action="<?= BASE_URL ?>/pelanggan/booking/store" method="POST">
                <input type="hidden" name="paket_id" value="<?= $paket['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-500" style="font-size:.85rem">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_berangkat" class="form-control"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500" style="font-size:.85rem">Jumlah Peserta</label>
                    <input type="number" name="jumlah_peserta" class="form-control"
                           min="1" max="<?= $paket['kuota'] ?>" value="1" required
                           onchange="hitungTotal(this.value)">
                    <div class="form-text">Maks. <?= $paket['kuota'] ?> orang</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500" style="font-size:.85rem">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2"
                              placeholder="Permintaan khusus, dll."></textarea>
                </div>

                <!-- Kalkulasi Harga -->
                <div class="p-3 rounded-3 mb-3" style="background:#F0F9FF">
                    <div class="d-flex justify-content-between" style="font-size:.82rem;color:#64748B">
                        <span>Harga × Peserta</span>
                        <span id="totalDisplay">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-700" style="color:#0A6CFF">
                        <span>Total Bayar</span>
                        <span id="totalFinal">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2" style="font-weight:600">
                    <i class="bi bi-calendar-plus me-2"></i>Pesan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const hargaSatuan = <?= $paket['harga'] ?>;
function hitungTotal(jumlah) {
    const total = hargaSatuan * parseInt(jumlah || 1);
    const fmt   = new Intl.NumberFormat('id-ID').format(total);
    document.getElementById('totalDisplay').textContent = 'Rp ' + fmt;
    document.getElementById('totalFinal').textContent   = 'Rp ' + fmt;
}
</script>
