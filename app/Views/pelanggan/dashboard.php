<!-- app/Views/pelanggan/dashboard.php -->
<div class="mb-4">
    <h5 style="font-weight:700;color:#1A2B3C">
        Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋
    </h5>
    <p class="text-muted mb-0" style="font-size:.9rem">Siap merencanakan perjalanan impianmu?</p>
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
    <h6 style="font-weight:700;color:#1A2B3C;margin:0">Paket Wisata Tersedia</h6>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-sm btn-outline-primary rounded-pill px-3"
       style="font-size:.78rem">Lihat Semua</a>
</div>
<div class="row g-4 mb-5">
    <?php foreach (array_slice($pakets, 0, 3) as $p): ?>
    <div class="col-md-4">
        <div class="stat-card p-0 overflow-hidden h-100" style="cursor:pointer"
             onclick="location.href='<?= BASE_URL ?>/pelanggan/paket/<?= $p['id'] ?>'">
            <?php if ($p['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($p['foto']) ?>"
                 alt="<?= htmlspecialchars($p['nama_paket']) ?>"
                 style="width:100%;height:180px;object-fit:cover">
            <?php else: ?>
            <div style="width:100%;height:180px;background:linear-gradient(135deg,#0A6CFF,#0052CC);
                        display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem">
                <i class="bi bi-airplane"></i>
            </div>
            <?php endif; ?>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong style="font-size:.93rem"><?= htmlspecialchars($p['nama_paket']) ?></strong>
                    <span class="badge bg-primary-subtle text-primary rounded-pill" style="font-size:.72rem">
                        <?= $p['durasi_hari'] ?>H
                    </span>
                </div>
                <div class="text-muted mb-2" style="font-size:.8rem">
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($p['destinasi']) ?>
                </div>
                <div style="font-size:1.05rem;font-weight:700;color:#0A6CFF">
                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                </div>
                <div class="text-muted" style="font-size:.75rem">/orang</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Booking Terbaru -->
<?php if (!empty($bookings)): ?>
<h6 style="font-weight:700;color:#1A2B3C;margin-bottom:12px">Booking Terakhir</h6>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
            <thead style="background:#F8FAFC">
                <tr>
                    <th class="py-2 ps-3" style="font-weight:600;color:#64748B">Kode</th>
                    <th style="font-weight:600;color:#64748B">Paket</th>
                    <th style="font-weight:600;color:#64748B">Berangkat</th>
                    <th style="font-weight:600;color:#64748B">Total</th>
                    <th style="font-weight:600;color:#64748B">Status</th>
                    <th style="font-weight:600;color:#64748B">Tiket</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach (array_slice($bookings, 0, 3) as $b): ?>
            <tr>
                <td class="ps-3"><code style="font-size:.78rem"><?= htmlspecialchars($b['kode_booking']) ?></code></td>
                <td><?= htmlspecialchars($b['nama_paket']) ?></td>
                <td><?= date('d M Y', strtotime($b['tanggal_berangkat'])) ?></td>
                <td>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <?php $sc = ['pending'=>'warning','dikonfirmasi'=>'success','dibatalkan'=>'danger'][$b['status']] ?? 'secondary' ?>
                    <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill" style="font-size:.73rem">
                        <?= ucfirst($b['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/pelanggan/booking/<?= $b['id'] ?>/download"
                       class="btn btn-sm btn-outline-primary rounded-2" style="font-size:.75rem">
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
