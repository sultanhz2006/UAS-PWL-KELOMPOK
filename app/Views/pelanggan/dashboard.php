<!-- app/Views/pelanggan/dashboard.php -->
<div class="mb-4">
    <h5 class="fw-700 mb-0">
        Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>!
    </h5>
    <p class="text-muted mb-0 fs-9">Siap merencanakan perjalanan impianmu?</p>
</div>

<!-- Summary Booking User -->
<?php
$bookings = $bookings ?? [];
$pakets   = $pakets ?? [];
$pending       = array_filter($bookings, fn($b) => $b['status'] === 'pending');
$dikonfirmasi  = array_filter($bookings, fn($b) => $b['status'] === 'dikonfirmasi');
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-primary"><?= count($bookings) ?></div>
            <div class="stat-label">Total Booking</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-warning"><?= count($pending) ?></div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-success"><?= count($dikonfirmasi) ?></div>
            <div class="stat-label">Dikonfirmasi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-info"><?= count($pakets) ?></div>
            <div class="stat-label">Paket Tersedia</div>
        </div>
    </div>
</div>

<!-- Paket Wisata Cards -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-700 mb-0">Paket Wisata Tersedia</h6>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-sm btn-outline-primary rounded-pill px-3 fs-78">Lihat Semua</a>
</div>
<div class="row g-4 mb-5">
    <?php foreach (array_slice($pakets, 0, 3) as $p): ?>
    <div class="col-md-4">
        <div class="stat-card p-0 overflow-hidden h-100 card-hoverable"
             onclick="location.href='<?= BASE_URL ?>/pelanggan/paket/<?= $p['id'] ?>'">
            <?php if ($p['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($p['foto']) ?>"
                 alt="<?= htmlspecialchars($p['nama_paket']) ?>"
                 class="img-cover-180">
            <?php else: ?>
            <div class="img-cover-180 bg-gradient-primary d-flex align-items-center justify-content-center text-white fs-2-5">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong class="fs-93"> <?= htmlspecialchars($p['nama_paket']) ?></strong>
                    <span class="badge bg-primary-subtle text-primary rounded-pill fs-72">
                        <?= $p['durasi_hari'] ?>H
                    </span>
                </div>
                <div class="text-muted mb-2 fs-8">
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($p['destinasi']) ?>
                </div>
                <div class="fs-105 fw-700 text-primary">
                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                </div>
                <div class="text-muted fs-75">/orang</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Booking Terbaru -->
<?php if (!empty($bookings)): ?>
<h6 class="fw-700 mb-3">Booking Terakhir</h6>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 fs-85">
            <thead class="bg-light">
                <tr>
                    <th class="py-2 ps-3 fw-600 text-secondary">Kode</th>
                    <th class="fw-600 text-secondary">Paket</th>
                    <th class="fw-600 text-secondary">Berangkat</th>
                    <th class="fw-600 text-secondary">Total</th>
                    <th class="fw-600 text-secondary">Status</th>
                    <th class="fw-600 text-secondary">Tiket</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach (array_slice($bookings, 0, 3) as $b): ?>
            <tr>
                <td class="ps-3"><code class="fs-78"><?= htmlspecialchars($b['kode_booking']) ?></code></td>
                <td><?= htmlspecialchars($b['nama_paket']) ?></td>
                <td><?= date('d M Y', strtotime($b['tanggal_berangkat'])) ?></td>
                <td>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <?php $sc = ['pending'=>'warning','dikonfirmasi'=>'success','dibatalkan'=>'danger'][$b['status']] ?? 'secondary' ?>
                    <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill fs-73">
                        <?= ucfirst($b['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/pelanggan/booking/<?= $b['id'] ?>/download"
                       class="btn btn-sm btn-outline-primary rounded-2 btn-sm-xs">
                        <i class="bi bi-download me-1"></i>Tiket
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
