<!-- app/Views/admin/booking/index.php -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;color:#1A2B3C;margin:0">Manajemen Booking</h5>
        <p class="text-muted mb-0" style="font-size:.85rem">Total <?= count($bookings) ?> transaksi</p>
    </div>
</div>

<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
            <thead style="background:#F8FAFC">
                <tr>
                    <th class="py-3 ps-3" style="font-weight:600;color:#64748B">Kode</th>
                    <th style="font-weight:600;color:#64748B">Pemesan</th>
                    <th style="font-weight:600;color:#64748B">Paket</th>
                    <th style="font-weight:600;color:#64748B">Berangkat</th>
                    <th style="font-weight:600;color:#64748B">Peserta</th>
                    <th style="font-weight:600;color:#64748B">Total</th>
                    <th style="font-weight:600;color:#64748B">Status</th>
                    <th style="font-weight:600;color:#64748B">Ubah Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                        Belum ada data booking.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td class="ps-3"><code style="font-size:.78rem"><?= htmlspecialchars($b['kode_booking']) ?></code></td>
                    <td>
                        <div style="font-weight:500"><?= htmlspecialchars($b['nama_lengkap']) ?></div>
                        <div style="font-size:.75rem;color:#94A3B8"><?= htmlspecialchars($b['email']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($b['nama_paket']) ?></td>
                    <td><?= date('d M Y', strtotime($b['tanggal_berangkat'])) ?></td>
                    <td class="text-center"><?= $b['jumlah_peserta'] ?></td>
                    <td>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <?php $sc = ['pending'=>'warning','dikonfirmasi'=>'success','dibatalkan'=>'danger'][$b['status']] ?? 'secondary' ?>
                        <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> rounded-pill" style="font-size:.73rem">
                            <?= ucfirst($b['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form action="<?= BASE_URL ?>/admin/booking/<?= $b['id'] ?>/status" method="POST" class="d-flex gap-1">
                            <select name="status" class="form-select form-select-sm rounded-2" style="font-size:.78rem;max-width:130px">
                                <option value="pending"       <?= $b['status']==='pending'       ? 'selected':'' ?>>Pending</option>
                                <option value="dikonfirmasi"  <?= $b['status']==='dikonfirmasi'  ? 'selected':'' ?>>Konfirmasi</option>
                                <option value="dibatalkan"    <?= $b['status']==='dibatalkan'    ? 'selected':'' ?>>Batalkan</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary rounded-2" style="font-size:.75rem">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
