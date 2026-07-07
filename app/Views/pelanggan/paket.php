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
    <h5 style="font-weight:700;color:#1A2B3C">Paket Wisata</h5>
    <!-- Search & Filter -->
    <form method="GET" action="<?= BASE_URL ?>/pelanggan/paket" class="stat-card p-3 mt-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" style="font-size:.8rem;color:#64748B">Cari Paket</label>
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
                <label class="form-label" style="font-size:.8rem;color:#64748B">Destinasi</label>
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
                <label class="form-label" style="font-size:.8rem;color:#64748B">Urutkan</label>
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
            <div class="text-muted" style="font-size:.82rem">
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
    <i class="bi bi-emoji-frown" style="font-size:3rem;color:#CBD5E1"></i>
    <p class="text-muted mt-2">Tidak ada paket wisata yang cocok dengan filter.</p>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-outline-primary rounded-3 mt-1">Reset Filter</a>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($pakets as $p): ?>
    <div class="col-md-6 col-xl-4">
        <div class="stat-card p-0 overflow-hidden h-100 d-flex flex-column"
             style="transition:transform .2s,box-shadow .2s;cursor:pointer"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.1)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''"
             onclick="location.href='<?= BASE_URL ?>/pelanggan/paket/<?= $p['id'] ?>'">
            <?php if ($p['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($p['foto']) ?>"
                 alt="<?= htmlspecialchars($p['nama_paket']) ?>"
                 style="width:100%;height:200px;object-fit:cover">
            <?php else: ?>
            <div style="width:100%;height:200px;background:linear-gradient(135deg,#0A6CFF,#06B6D4);
                        display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-4 d-flex flex-column flex-fill">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 style="font-weight:700;color:#1A2B3C;margin:0"><?= htmlspecialchars($p['nama_paket']) ?></h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2" style="white-space:nowrap;font-size:.73rem">
                        <?= $p['durasi_hari'] ?> Hari
                    </span>
                </div>
                <div class="text-muted mb-2" style="font-size:.82rem">
                    <i class="bi bi-geo-alt-fill text-primary me-1"></i><?= htmlspecialchars($p['destinasi']) ?>
                </div>
                <p class="text-muted mb-3 flex-fill" style="font-size:.82rem;line-height:1.5;
                   display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-clamp:2">
                    <?= htmlspecialchars($p['deskripsi'] ?? '') ?>
                </p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <div>
                        <div style="font-size:1.15rem;font-weight:800;color:#0A6CFF">
                            Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                        </div>
                        <div class="text-muted" style="font-size:.72rem">/orang</div>
                    </div>
                    <span class="btn btn-primary btn-sm rounded-3 px-3">Lihat Detail</span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
