<!-- app/Views/pelanggan/booking.php -->
<?php if (!isset($bookings)) { $bookings = []; } ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-0">Riwayat Booking</h5>
        <p class="text-muted mb-0 fs-85">Total <?= count($bookings) ?> pesanan</p>
    </div>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg me-1"></i>Pesan Paket Baru
    </a>
</div>

<?php if (empty($bookings)): ?>
<div class="stat-card text-center py-5">
    <i class="bi bi-ticket-perforated fs-3rem text-secondary-soft d-block mb-3"></i>
    <h6 class="text-secondary-soft">Belum ada booking</h6>
    <p class="text-muted fs-85">Temukan paket wisata impianmu dan mulai perjalananmu!</p>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-primary rounded-3 mt-1">Jelajahi Paket Wisata</a>
</div>
<?php else: ?>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 fs-875">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 ps-3 fw-600 text-secondary">Kode Booking</th>
                    <th class="fw-600 text-secondary">Paket Wisata</th>
                    <th class="fw-600 text-secondary">Tanggal Pesan</th>
                    <th class="fw-600 text-secondary">Berangkat</th>
                    <th class="fw-600 text-secondary">Peserta</th>
                    <th class="fw-600 text-secondary">Total</th>
                    <th class="fw-600 text-secondary">Status</th>
                    <th class="fw-600 text-secondary">Hotel</th>
                    <th class="fw-600 text-secondary">Tiket</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td class="ps-3">
                    <code class="fs-78 bg-soft px-2 py-1 rounded"><?= htmlspecialchars($b['kode_booking']) ?></code>
                </td>
                <td>
                    <div class="fw-500"><?= htmlspecialchars($b['nama_paket']) ?></div>
                    <div class="fs-75 text-muted-soft">
                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($b['destinasi']) ?>
                    </div>
                </td>
                <td><?= date('d M Y', strtotime($b['created_at'])) ?></td>
                <td><?= date('d M Y', strtotime($b['tanggal_berangkat'])) ?></td>
                <td class="text-center"><?= $b['jumlah_peserta'] ?> org</td>
                <td><strong>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></strong></td>
                <td>
                    <?php
                    $statusConfig = [
                        'pending'      => ['warning', 'Menunggu', 'bi-hourglass-split'],
                        'dikonfirmasi' => ['success', 'Dikonfirmasi', 'bi-check-circle-fill'],
                        'dibatalkan'   => ['danger',  'Dibatalkan', 'bi-x-circle-fill'],
                    ];
                    [$sc, $label, $icon] = $statusConfig[$b['status']] ?? ['secondary', 'Unknown', 'bi-question'];
                    ?>
                    <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill px-2 fs-75">
                        <i class="<?= $icon ?> me-1"></i><?= $label ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/pelanggan/booking/<?= $b['id'] ?>/hotel"
                       class="btn btn-sm btn-outline-success rounded-2 btn-sm-xs"
                       title="Pilih penginapan">
                        <i class="bi bi-building me-1"></i>Hotel
                    </a>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/pelanggan/booking/<?= $b['id'] ?>/download"
                       class="btn btn-sm btn-outline-primary rounded-2 btn-sm-xs"
                       title="Unduh E-Ticket PDF">
                        <i class="bi bi-download me-1"></i>PDF
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
