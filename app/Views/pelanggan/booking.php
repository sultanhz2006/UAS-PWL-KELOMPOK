<!-- app/Views/pelanggan/booking.php -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;color:#1A2B3C;margin:0">Riwayat Booking</h5>
        <p class="text-muted mb-0" style="font-size:.85rem">Total <?= count($bookings) ?> pesanan</p>
    </div>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg me-1"></i>Pesan Paket Baru
    </a>
</div>

<?php if (empty($bookings)): ?>
<div class="stat-card text-center py-5">
    <i class="bi bi-ticket-perforated" style="font-size:3rem;color:#CBD5E1;display:block;margin-bottom:12px"></i>
    <h6 style="color:#64748B">Belum ada booking</h6>
    <p class="text-muted" style="font-size:.85rem">Temukan paket wisata impianmu dan mulai perjalananmu!</p>
    <a href="<?= BASE_URL ?>/pelanggan/paket" class="btn btn-primary rounded-3 mt-1">Jelajahi Paket Wisata</a>
</div>
<?php else: ?>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#F8FAFC">
                <tr>
                    <th class="py-3 ps-3" style="font-weight:600;color:#64748B">Kode Booking</th>
                    <th style="font-weight:600;color:#64748B">Paket Wisata</th>
                    <th style="font-weight:600;color:#64748B">Tanggal Pesan</th>
                    <th style="font-weight:600;color:#64748B">Berangkat</th>
                    <th style="font-weight:600;color:#64748B">Peserta</th>
                    <th style="font-weight:600;color:#64748B">Total</th>
                    <th style="font-weight:600;color:#64748B">Status</th>
                    <th style="font-weight:600;color:#64748B">Tiket</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td class="ps-3">
                    <code style="font-size:.78rem;background:#F0F9FF;padding:3px 6px;border-radius:4px">
                        <?= htmlspecialchars($b['kode_booking']) ?>
                    </code>
                </td>
                <td>
                    <div style="font-weight:500"><?= htmlspecialchars($b['nama_paket']) ?></div>
                    <div style="font-size:.75rem;color:#94A3B8">
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
                    <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill px-2" style="font-size:.75rem">
                        <i class="<?= $icon ?> me-1"></i><?= $label ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/pelanggan/booking/<?= $b['id'] ?>/download"
                       class="btn btn-sm btn-outline-primary rounded-2"
                       title="Unduh E-Ticket PDF"
                       style="font-size:.75rem">
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
