<!-- app/Views/pelanggan/paket.php -->
<?php
$keyword    = $keyword ?? '';
$destinasi  = $destinasi ?? '';
$sort       = $sort ?? '';
$destinasis = $destinasis ?? [];
$pakets     = $pakets ?? [];
$hasFilter  = $keyword !== '' || $destinasi !== '' || $sort !== '';
$sortOptions = [
    'terbaru'      => 'Terbaru',
    'harga_asc'    => 'Harga terendah',
    'harga_desc'   => 'Harga tertinggi',
    'durasi_asc'   => 'Durasi tersingkat',
    'durasi_desc'  => 'Durasi terlama',
];
?>
<div class="mb-4">
    <h5 class="fw-700 mb-0">Paket Wisata</h5>
    <!-- Search & Filter -->
    <form method="GET" action="<?= BASE_URL ?>/pelanggan/paket" class="stat-card p-3 mt-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fs-8 text-secondary-soft">Cari Paket</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0 ps-0"
                           placeholder="Nama paket atau destinasi..."
                           value="<?= htmlspecialchars($keyword) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-8 text-secondary-soft">Destinasi</label>
                <select name="destinasi" class="form-select">
                    <option value="">Semua destinasi</option>
                    <?php foreach ($destinasis as $d): ?>
                    <option value="<?= htmlspecialchars($d) ?>" <?= $destinasi === $d ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-8 text-secondary-soft">Urutkan</label>
                <select name="sort" class="form-select">
                    <?php foreach ($sortOptions as $value => $label): ?>
                    <option value="<?= $value ?>" <?= ($sort ?: 'terbaru') === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary rounded-3">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </div>
        <?php if ($hasFilter): ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
            <div class="text-muted fs-82">
                Menampilkan <?= count($pakets) ?> paket sesuai filter.
            </div>
            <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-sm btn-outline-secondary rounded-3">
                Reset Filter
            </a>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($pakets)): ?>
<div class="text-center py-5">
    <i class="bi bi-emoji-frown fs-3rem text-muted"></i>
    <p class="text-muted mt-2">Tidak ada paket wisata yang cocok dengan filter.</p>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-outline-primary rounded-3 mt-1">Reset Filter</a>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($pakets as $p): ?>
    <div class="col-md-6 col-xl-4">
        <div class="stat-card paket-card h-100 d-flex flex-column"
             onclick="location.href='<?= BASE_URL ?>/pelanggan/paket/<?= $p['id'] ?>'">
            <?php if ($p['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($p['foto']) ?>"
                 alt="<?= htmlspecialchars($p['nama_paket']) ?>"
                 class="img-cover-200">
            <?php else: ?>
            <div class="img-cover-200 bg-gradient-primary-alt d-flex align-items-center justify-content-center text-white fs-4rem">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-4 d-flex flex-column flex-fill">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-700 mb-0"><?= htmlspecialchars($p['nama_paket']) ?></h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2 fs-73">
                        <?= $p['durasi_hari'] ?> Hari
                    </span>
                </div>
                <div class="text-muted mb-2 fs-82">
                    <i class="bi bi-geo-alt-fill text-primary me-1"></i><?= htmlspecialchars($p['destinasi']) ?>
                </div>
                <p class="text-muted mb-3 flex-fill fs-82 line-clamp-2">
                    <?= htmlspecialchars($p['deskripsi'] ?? '') ?>
                </p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <div>
                        <div class="fs-115 fw-800 text-primary">
                            Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                        </div>
                        <div class="text-muted fs-72">/orang</div>
                    </div>
                    <span class="btn btn-primary btn-sm rounded-3 px-3">Lihat Detail</span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
