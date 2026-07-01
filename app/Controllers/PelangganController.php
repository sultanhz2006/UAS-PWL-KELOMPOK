<?php
// app/Controllers/PelangganController.php

require_once APP_PATH . '/Core/BaseController.php';
require_once APP_PATH . '/Models/PaketWisataModel.php';
require_once APP_PATH . '/Models/BookingModel.php';
require_once APP_PATH . '/Middleware/AuthMiddleware.php';

class PelangganController extends BaseController {
    private PaketWisataModel $paketModel;
    private BookingModel     $bookingModel;

    public function __construct() {
        AuthMiddleware::requireRole('pelanggan');
        $this->paketModel   = new PaketWisataModel();
        $this->bookingModel = new BookingModel();
    }

    // GET /pelanggan/dashboard
    public function dashboard(): void {
        $this->view('pelanggan/dashboard', [
            'title'    => 'Dashboard — ' . APP_NAME,
            'pakets'   => $this->paketModel->getAll('aktif'),
            'bookings' => $this->bookingModel->getByUser((int) $_SESSION['user_id']),
        ]);
    }

    // GET /pelanggan/paket
    public function paketList(): void {
        $keyword = trim($_GET['q'] ?? '');
        $pakets  = $keyword
            ? $this->paketModel->search($keyword)
            : $this->paketModel->getAll('aktif');

        $this->view('pelanggan/paket', [
            'title'   => 'Paket Wisata',
            'pakets'  => $pakets,
            'keyword' => $keyword,
        ]);
    }

    // GET /pelanggan/paket/:id
    public function paketDetail(string $id): void {
        $paket = $this->paketModel->findById((int) $id);
        if (!$paket || $paket['status'] !== 'aktif') {
            $this->flash('danger', 'Paket tidak ditemukan.');
            $this->redirect('/pelanggan/paket');
        }

        $this->view('pelanggan/paket_detail', [
            'title' => $paket['nama_paket'],
            'paket' => $paket,
        ]);
    }

    // POST /pelanggan/booking/store
    public function bookingStore(): void {
        $paketId  = (int) ($_POST['paket_id']          ?? 0);
        $tgl      = trim($_POST['tanggal_berangkat']    ?? '');
        $jumlah   = (int) ($_POST['jumlah_peserta']     ?? 1);
        $catatan  = trim($_POST['catatan']              ?? '');

        // Validasi
        $paket = $this->paketModel->findById($paketId);
        if (!$paket || $paket['status'] !== 'aktif') {
            $this->flash('danger', 'Paket tidak valid.');
            $this->redirect('/pelanggan/paket');
        }
        if (empty($tgl) || strtotime($tgl) < strtotime('tomorrow')) {
            $this->flash('danger', 'Tanggal keberangkatan minimal besok.');
            $this->redirect('/pelanggan/paket/' . $paketId);
        }
        if ($jumlah < 1 || $jumlah > $paket['kuota']) {
            $this->flash('danger', "Jumlah peserta harus antara 1 - {$paket['kuota']}.");
            $this->redirect('/pelanggan/paket/' . $paketId);
        }

        $totalHarga  = $paket['harga'] * $jumlah;
        $kodeBooking = BookingModel::generateKode();

        $bookingId = $this->bookingModel->create([
            'kode_booking'      => $kodeBooking,
            'user_id'           => (int) $_SESSION['user_id'],
            'paket_id'          => $paketId,
            'tanggal_berangkat' => $tgl,
            'jumlah_peserta'    => $jumlah,
            'total_harga'       => $totalHarga,
            'catatan'           => $catatan,
        ]);

        if ($bookingId) {
            // Generate PDF tiket otomatis
            $this->generatePdfTiket($bookingId);
            $this->flash('success', "Booking berhasil! Kode: <strong>{$kodeBooking}</strong>. Silakan tunggu konfirmasi admin.");
            $this->redirect('/pelanggan/booking');
        } else {
            $this->flash('danger', 'Booking gagal. Silakan coba lagi.');
            $this->redirect('/pelanggan/paket/' . $paketId);
        }
    }

    // GET /pelanggan/booking
    public function bookingList(): void {
        $this->view('pelanggan/booking', [
            'title'    => 'Riwayat Booking',
            'bookings' => $this->bookingModel->getByUser((int) $_SESSION['user_id']),
        ]);
    }

    // GET /pelanggan/booking/:id/download
    public function downloadTiket(string $id): void {
        $booking = $this->bookingModel->findById((int) $id);

        // Pastikan booking milik user yang sedang login
        if (!$booking || (int) $booking['user_id'] !== (int) $_SESSION['user_id']) {
            $this->flash('danger', 'Akses ditolak.');
            $this->redirect('/pelanggan/booking');
        }

        // Buat PDF jika belum ada
        if (empty($booking['pdf_path']) || !file_exists(TICKET_PATH . '/' . $booking['pdf_path'])) {
            $this->generatePdfTiket((int) $id);
            $booking = $this->bookingModel->findById((int) $id);
        }

        $filePath = TICKET_PATH . '/' . $booking['pdf_path'];
        if (!file_exists($filePath)) {
            $this->flash('danger', 'File tiket tidak ditemukan.');
            $this->redirect('/pelanggan/booking');
        }

        // Stream PDF sebagai download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="tiket-' . $booking['kode_booking'] . '.pdf"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    // ================================================================
    //  PDF TIKET GENERATOR (Sederhana, tanpa library eksternal)
    //  Untuk production: gunakan TCPDF atau DomPDF
    // ================================================================
    private function generatePdfTiket(int $bookingId): void {
        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) return;

        $filename = 'tiket-' . $booking['kode_booking'] . '-' . time() . '.pdf';
        $filePath = TICKET_PATH . '/' . $filename;

        // Buat PDF sederhana dengan konten raw (gunakan TCPDF di production)
        // Ini adalah placeholder — install TCPDF via Composer untuk PDF real
        $htmlContent = $this->buildTiketHtml($booking);

        // Simpan sebagai HTML sementara (ganti dengan PDF library di production)
        $htmlFile = TICKET_PATH . '/' . str_replace('.pdf', '.html', $filename);
        file_put_contents($htmlFile, $htmlContent);

        // Di production dengan TCPDF:
        // require_once ROOT_PATH . '/vendor/autoload.php';
        // $pdf = new TCPDF();
        // $pdf->writeHTML($htmlContent);
        // $pdf->Output($filePath, 'F');

        // Untuk demo: simpan sebagai .html dan rename .pdf
        rename($htmlFile, $filePath);

        $this->bookingModel->savePdfPath($bookingId, $filename);
    }

    private function buildTiketHtml(array $b): string {
        $harga = 'Rp ' . number_format($b['total_harga'], 0, ',', '.');
        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {$b['kode_booking']}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; border-bottom: 3px solid #0066cc; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #0066cc; }
        .ticket-box { border: 2px dashed #0066cc; padding: 24px; margin: 24px 0; border-radius: 8px; }
        .kode { font-size: 22px; font-weight: bold; color: #0066cc; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px 12px; vertical-align: top; }
        td:first-child { font-weight: bold; width: 40%; color: #555; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; background: #ffc107; color: #333; font-size: 13px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">✈ VyanTravel</div>
        <p>E-Ticket / Bukti Pemesanan Perjalanan</p>
    </div>
    <div class="ticket-box">
        <div class="kode">{$b['kode_booking']}</div>
        <table>
            <tr><td>Nama Pemesan</td><td>{$b['nama_lengkap']}</td></tr>
            <tr><td>Email</td><td>{$b['email']}</td></tr>
            <tr><td>Paket Wisata</td><td>{$b['nama_paket']}</td></tr>
            <tr><td>Destinasi</td><td>{$b['destinasi']}</td></tr>
            <tr><td>Tanggal Berangkat</td><td>{$b['tanggal_berangkat']}</td></tr>
            <tr><td>Durasi</td><td>{$b['durasi_hari']} Hari</td></tr>
            <tr><td>Jumlah Peserta</td><td>{$b['jumlah_peserta']} Orang</td></tr>
            <tr><td>Total Pembayaran</td><td><strong>{$harga}</strong></td></tr>
            <tr><td>Status</td><td><span class="status">{$b['status']}</span></td></tr>
        </table>
    </div>
    <div class="footer">
        <p>Dicetak: {$b['created_at']} | VyanTravel © 2025</p>
        <p>Tunjukkan e-ticket ini saat keberangkatan.</p>
    </div>
</body>
</html>
HTML;
    }
}
