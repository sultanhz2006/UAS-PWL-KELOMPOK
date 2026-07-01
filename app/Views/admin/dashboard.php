<!-- app/Views/admin/dashboard.php -->

<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-wrap" style="background:#EFF6FF">
                <i class="bi bi-map-fill" style="color:#0A6CFF"></i>
            </div>
            <div class="stat-value"><?= $total_paket ?></div>
            <div class="stat-label">Paket Wisata Aktif</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-wrap" style="background:#FFFBEB">
                <i class="bi bi-hourglass-split" style="color:#F59E0B"></i>
            </div>
            <div class="stat-value"><?= $booking_stats['pending'] ?></div>
            <div class="stat-label">Booking Pending</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-wrap" style="background:#F0FDF4">
                <i class="bi bi-people-fill" style="color:#22C55E"></i>
            </div>
            <div class="stat-value"><?= $total_user ?></div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-wrap" style="background:#FFF1F2">
                <i class="bi bi-cash-coin" style="color:#EF4444"></i>
            </div>
            <div class="stat-value" style="font-size:1.3rem">
                Rp <?= number_format((float)$pendapatan, 0, ',', '.') ?>
            </div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
</div>

<!-- Quick Actions + Recent Bookings -->
<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="stat-card h-100">
            <h6 class="fw-600 mb-3" style="color:#1A2B3C;font-weight:600">Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>/admin/paket/create" class="btn btn-primary rounded-3">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Paket Wisata
                </a>
                <a href="<?= BASE_URL ?>/admin/booking" class="btn btn-outline-warning rounded-3">
                    <i class="bi bi-calendar-check me-2"></i>Kelola Booking
                </a>
                <a href="<?= BASE_URL ?>/admin/paket" class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-grid me-2"></i>Lihat Semua Paket
                </a>
            </div>

            <hr class="my-3">
            <h6 class="fw-600 mb-3" style="color:#1A2B3C;font-weight:600">Status Booking</h6>
            <?php
            $total_booking = array_sum($booking_stats);
            foreach (['pending' => ['warning','Pending'], 'dikonfirmasi' => ['success','Dikonfirmasi'], 'dibatalkan' => ['danger','Dibatalkan']] as $key => [$color, $label]):
                $pct = $total_booking > 0 ? round($booking_stats[$key] / $total_booking * 100) : 0;
            ?>
            <div class="mb-2">
                <div class="d-flex justify-content-between mb-1" style="font-size:.83rem">
                    <span class="text-<?= $color ?>"><?= $label ?></span>
                    <span class="text-muted"><?= $booking_stats[$key] ?> (<?= $pct ?>%)</span>
                </div>
                <div class="progress" style="height:6px;border-radius:10px">
                    <div class="progress-bar bg-<?= $color ?>" style="width:<?= $pct ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="col-lg-8">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 style="font-weight:600;color:#1A2B3C;margin:0">Booking Terbaru</h6>
                <a href="<?= BASE_URL ?>/admin/booking" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                   style="font-size:.78rem">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.85rem">
                    <thead>
                        <tr style="background:#F8FAFC">
                            <th class="py-2 ps-3 text-muted fw-500" style="font-weight:500">Kode</th>
                            <th class="py-2 text-muted fw-500" style="font-weight:500">Pemesan</th>
                            <th class="py-2 text-muted fw-500" style="font-weight:500">Paket</th>
                            <th class="py-2 text-muted fw-500" style="font-weight:500">Total</th>
                            <th class="py-2 text-muted fw-500" style="font-weight:500">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($recent_booking)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada booking.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent_booking as $b): ?>
                        <tr>
                            <td class="ps-3"><code style="font-size:.78rem"><?= htmlspecialchars($b['kode_booking']) ?></code></td>
                            <td><?= htmlspecialchars($b['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($b['nama_paket']) ?></td>
                            <td>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $statusMap = ['pending'=>'warning','dikonfirmasi'=>'success','dibatalkan'=>'danger'];
                                $sc = $statusMap[$b['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill px-2" style="font-size:.73rem">
                                    <?= ucfirst($b['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
