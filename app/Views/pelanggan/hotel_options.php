<!-- app/Views/pelanggan/hotel_options.php -->
<div class="mb-4">
    <a href="<?= BASE_URL ?>/pelanggan/booking" class="text-decoration-none text-muted fs-85">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke riwayat booking
    </a>
</div>

<?php if (empty($booking)): ?>
<div class="alert alert-warning py-2 fs-82">
    Data booking tidak tersedia. Silakan kembali ke <a href="<?= BASE_URL ?>/pelanggan/booking" class="text-decoration-none">riwayat booking</a>.
</div>
<?php return; ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h5 class="fw-700 mb-0">Pilih Penginapan</h5>
        <p class="text-muted mb-0 fs-85">
            Booking <?= htmlspecialchars($booking['kode_booking']) ?> -
            <?= htmlspecialchars($booking['destinasi']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/pelanggan/booking" class="btn btn-outline-secondary rounded-3">
        Lewati
    </a>
</div>

<div class="alert alert-info py-2 fs-82">
    <i class="bi bi-info-circle me-1"></i>
    Pencarian hotel menggunakan integrasi live Booking.com via RapidAPI.
</div>

<?php $hotelBooking = $hotelBooking ?? null; ?>
<?php if ($hotelBooking): ?>
<div class="stat-card mb-4">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="fw-700 mb-0">Hotel sudah dipesan</h6>
            <p class="text-muted mb-0 fs-85">
                <?= htmlspecialchars($hotelBooking['kode_hotel_booking']) ?> -
                <?= htmlspecialchars($hotelBooking['nama_hotel']) ?>
            </p>
        </div>
        <span class="badge bg-warning-subtle text-warning rounded-pill align-self-start">
            <?= ucfirst($hotelBooking['status']) ?>
        </span>
    </div>
    <hr>
    <div class="row g-3 fs-85">
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
    <i class="bi bi-building-x fs-3rem text-secondary-soft d-block mb-3"></i>
    <h6 class="text-secondary-soft">Belum ada hotel untuk destinasi ini</h6>
    <p class="text-muted fs-85">Kamu bisa lanjut tanpa penginapan.</p>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($hotels as $hotel): ?>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div>
                    <h6 class="fw-700 mb-0">
                        <?= htmlspecialchars($hotel['nama_hotel']) ?>
                    </h6>
                    <div class="text-muted fs-8">
                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($hotel['alamat']) ?>
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill">
                    <?= (int) $hotel['bintang'] ?> <i class="bi bi-star-fill"></i>
                </span>
            </div>
            <p class="fs-85 text-secondary-soft min-h-46">
                <?= htmlspecialchars($hotel['deskripsi']) ?>
            </p>
            <div class="mb-3">
                <div class="text-muted fs-75">Mulai dari</div>
                <div class="fs-125 fw-800 text-primary">
                    Rp <?= number_format($hotel['harga_per_malam'], 0, ',', '.') ?>
                </div>
                <div class="text-muted fs-75">/malam</div>
            </div>

            <?php if (!$hotelBooking): ?>
            <form action="<?= BASE_URL ?>/pelanggan/hotel-booking/store" method="POST">
                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fs-78">Check-in</label>
                        <input type="date" name="check_in" class="form-control form-control-sm"
                               min="<?= date('Y-m-d') ?>"
                               value="<?= htmlspecialchars($booking['tanggal_berangkat']) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-78">Check-out</label>
                        <input type="date" name="check_out" class="form-control form-control-sm"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               value="<?= date('Y-m-d', strtotime($booking['tanggal_berangkat'] . ' +' . (int) $booking['durasi_hari'] . ' days')) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-78">Kamar</label>
                        <input type="number" name="jumlah_kamar" class="form-control form-control-sm" min="1" value="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-78">Tamu</label>
                        <input type="number" name="jumlah_tamu" class="form-control form-control-sm" min="1" value="<?= (int) $booking['jumlah_peserta'] ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fs-78">Catatan</label>
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
