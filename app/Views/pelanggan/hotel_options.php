<!-- app/Views/pelanggan/hotel_options.php -->
<div class="mb-4">
    <a href="<?= BASE_URL ?>/pelanggan/booking" class="text-decoration-none text-muted" style="font-size:.85rem">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke riwayat booking
    </a>
</div>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h5 style="font-weight:700;color:#1A2B3C;margin:0">Pilih Penginapan</h5>
        <p class="text-muted mb-0" style="font-size:.85rem">
            Booking <?= htmlspecialchars($booking['kode_booking']) ?> -
            <?= htmlspecialchars($booking['destinasi']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/pelanggan/booking" class="btn btn-outline-secondary rounded-3">
        Lewati
    </a>
</div>

<div class="alert alert-info py-2" style="font-size:.82rem">
    <i class="bi bi-info-circle me-1"></i>
    Token Travelpayouts sudah disimpan di folder <code>api_keys</code>. Fitur booking hotel di aplikasi ini memakai data hotel lokal karena API hotel Travelpayouts/Hotellook tidak lagi melayani booking langsung.
</div>

<?php if ($hotelBooking): ?>
<div class="stat-card mb-4">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <div>
            <h6 style="font-weight:700;color:#1A2B3C;margin:0">Hotel sudah dipesan</h6>
            <p class="text-muted mb-0" style="font-size:.85rem">
                <?= htmlspecialchars($hotelBooking['kode_hotel_booking']) ?> -
                <?= htmlspecialchars($hotelBooking['nama_hotel']) ?>
            </p>
        </div>
        <span class="badge bg-warning-subtle text-warning rounded-pill align-self-start">
            <?= ucfirst($hotelBooking['status']) ?>
        </span>
    </div>
    <hr>
    <div class="row g-3" style="font-size:.85rem">
        <div class="col-md-3">
            <div class="text-muted">Check-in</div>
            <strong><?= date('d M Y', strtotime($hotelBooking['check_in'])) ?></strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted">Check-out</div>
            <strong><?= date('d M Y', strtotime($hotelBooking['check_out'])) ?></strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted">Kamar / Tamu</div>
            <strong><?= $hotelBooking['jumlah_kamar'] ?> kamar, <?= $hotelBooking['jumlah_tamu'] ?> tamu</strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted">Total</div>
            <strong>Rp <?= number_format($hotelBooking['total_harga'], 0, ',', '.') ?></strong>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (empty($hotels)): ?>
<div class="stat-card text-center py-5">
    <i class="bi bi-building-x" style="font-size:3rem;color:#CBD5E1;display:block;margin-bottom:12px"></i>
    <h6 style="color:#64748B">Belum ada hotel untuk destinasi ini</h6>
    <p class="text-muted" style="font-size:.85rem">Kamu bisa lanjut tanpa penginapan.</p>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($hotels as $hotel): ?>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div>
                    <h6 style="font-weight:700;color:#1A2B3C;margin:0">
                        <?= htmlspecialchars($hotel['nama_hotel']) ?>
                    </h6>
                    <div class="text-muted" style="font-size:.8rem">
                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($hotel['alamat']) ?>
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill">
                    <?= (int) $hotel['bintang'] ?> <i class="bi bi-star-fill"></i>
                </span>
            </div>
            <p style="font-size:.85rem;color:#64748B;min-height:46px">
                <?= htmlspecialchars($hotel['deskripsi']) ?>
            </p>
            <div class="mb-3">
                <div class="text-muted" style="font-size:.75rem">Mulai dari</div>
                <div style="font-size:1.25rem;font-weight:800;color:#0A6CFF">
                    Rp <?= number_format($hotel['harga_per_malam'], 0, ',', '.') ?>
                </div>
                <div class="text-muted" style="font-size:.75rem">/malam</div>
            </div>

            <?php if (!$hotelBooking): ?>
            <form action="<?= BASE_URL ?>/pelanggan/hotel-booking/store" method="POST">
                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem">Check-in</label>
                        <input type="date" name="check_in" class="form-control form-control-sm"
                               min="<?= date('Y-m-d') ?>"
                               value="<?= htmlspecialchars($booking['tanggal_berangkat']) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem">Check-out</label>
                        <input type="date" name="check_out" class="form-control form-control-sm"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               value="<?= date('Y-m-d', strtotime($booking['tanggal_berangkat'] . ' +' . (int) $booking['durasi_hari'] . ' days')) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem">Kamar</label>
                        <input type="number" name="jumlah_kamar" class="form-control form-control-sm" min="1" value="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem">Tamu</label>
                        <input type="number" name="jumlah_tamu" class="form-control form-control-sm" min="1" value="<?= (int) $booking['jumlah_peserta'] ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="font-size:.78rem">Catatan</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 rounded-3 mt-3">
                    <i class="bi bi-calendar-check me-1"></i>Pesan Hotel
                </button>
            </form>
            <?php else: ?>
            <button class="btn btn-outline-secondary w-100 rounded-3 mt-3" disabled>
                Hotel sudah dipilih
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
