<?php
// app/Controllers/PelangganController.php

require_once APP_PATH . '/Core/BaseController.php';
require_once APP_PATH . '/Models/PaketWisataModel.php';
require_once APP_PATH . '/Models/BookingModel.php';
require_once APP_PATH . '/Models/HotelModel.php';
require_once APP_PATH . '/Models/HotelBookingModel.php';
require_once APP_PATH . '/Middleware/AuthMiddleware.php';

class PelangganController extends BaseController {
    private PaketWisataModel $paketModel;
    private BookingModel     $bookingModel;
    private HotelModel       $hotelModel;
    private HotelBookingModel $hotelBookingModel;

    public function __construct() {
        AuthMiddleware::requireRole('pelanggan');
        $this->paketModel        = new PaketWisataModel();
        $this->bookingModel      = new BookingModel();
        $this->hotelModel        = new HotelModel();
        $this->hotelBookingModel = new HotelBookingModel();
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
        $filters = [
            'keyword'   => trim($_GET['q'] ?? ''),
            'destinasi' => trim($_GET['destinasi'] ?? ''),
            'sort'      => trim($_GET['sort'] ?? ''),
        ];

        $this->view('pelanggan/paket', [
            'title'      => 'Paket Wisata',
            'pakets'     => $this->paketModel->filterActive($filters),
            'filters'    => $filters,
            'keyword'    => $filters['keyword'],
            'destinasi'  => $filters['destinasi'],
            'sort'       => $filters['sort'],
            'destinasis' => $this->paketModel->getActiveDestinations(),
        ]);
    }

    // GET /pelanggan/paket/:id
    public function paketDetail(string $id): void {
        $paket = $this->paketModel->findById((int) $id);
        if (!$paket || $paket['status'] !== 'aktif') {
            $this->flash('danger', 'Paket tidak ditemukan.');
            $this->redirect('/pelanggan/paket');
        }

        $penerbangan = $this->getInfoPenerbangan($paket['destinasi']);

        $this->view('pelanggan/paket_detail', [
            'title'       => $paket['nama_paket'],
            'paket'       => $paket,
            'penerbangan' => $penerbangan,
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
            $this->flash('success', "Booking berhasil! Kode: <strong>{$kodeBooking}</strong>. Kamu bisa lanjut pilih penginapan.");
            $this->redirect('/pelanggan/booking/' . $bookingId . '/hotel');
        } else {
            $this->flash('danger', 'Booking gagal. Silakan coba lagi.');
            $this->redirect('/pelanggan/paket/' . $paketId);
        }
    }

    // GET /pelanggan/booking/:id/hotel
    public function hotelOptions(string $id): void {
        $booking = $this->bookingModel->findById((int) $id);
        if (!$booking || (int) $booking['user_id'] !== (int) $_SESSION['user_id']) {
            $this->flash('danger', 'Akses booking tidak valid.');
            $this->redirect('/pelanggan/booking');
        }

        $hotelBooking = $this->hotelBookingModel->getByBooking((int) $id, (int) $_SESSION['user_id']);
        $checkIn = $booking['tanggal_berangkat'];
        $checkOut = date('Y-m-d', strtotime($booking['tanggal_berangkat'] . ' +' . (int) $booking['durasi_hari'] . ' days'));
        $hotels = $this->hotelModel->getByDestinasi($booking['destinasi'], $checkIn, $checkOut);

        $this->view('pelanggan/hotel_options', [
            'title'        => 'Pilih Penginapan',
            'booking'      => $booking,
            'hotelBooking' => $hotelBooking,
            'hotels'       => $hotels,
        ]);
    }

    // POST /pelanggan/hotel-booking/store
    public function hotelBookingStore(): void {
        $bookingId   = (int) ($_POST['booking_id'] ?? 0);
        $hotelId     = (int) ($_POST['hotel_id'] ?? 0);
        $checkIn     = trim($_POST['check_in'] ?? '');
        $checkOut    = trim($_POST['check_out'] ?? '');
        $jumlahKamar = (int) ($_POST['jumlah_kamar'] ?? 1);
        $jumlahTamu  = (int) ($_POST['jumlah_tamu'] ?? 1);
        $catatan     = trim($_POST['catatan'] ?? '');

        $booking = $this->bookingModel->findById($bookingId);
        $hotel   = $this->hotelModel->findById($hotelId, $checkIn, $checkOut);

        if (!$booking || (int) $booking['user_id'] !== (int) $_SESSION['user_id'] || !$hotel) {
            $this->flash('danger', 'Data booking atau hotel tidak valid.');
            $this->redirect('/pelanggan/booking');
        }

        if ($this->hotelBookingModel->getByBooking($bookingId, (int) $_SESSION['user_id'])) {
            $this->flash('info', 'Booking hotel untuk perjalanan ini sudah ada.');
            $this->redirect('/pelanggan/booking/' . $bookingId . '/hotel');
        }

        if (empty($checkIn) || empty($checkOut) || strtotime($checkIn) < strtotime('today') || strtotime($checkOut) <= strtotime($checkIn)) {
            $this->flash('danger', 'Tanggal check-in dan check-out tidak valid.');
            $this->redirect('/pelanggan/booking/' . $bookingId . '/hotel');
        }

        if ($jumlahKamar < 1 || $jumlahTamu < 1) {
            $this->flash('danger', 'Jumlah kamar dan tamu minimal 1.');
            $this->redirect('/pelanggan/booking/' . $bookingId . '/hotel');
        }

        $malam = max(1, (int) ((strtotime($checkOut) - strtotime($checkIn)) / 86400));
        $totalHarga = $hotel['harga_per_malam'] * $jumlahKamar * $malam;

        $ok = $this->hotelBookingModel->create([
            'kode_hotel_booking' => HotelBookingModel::generateKode(),
            'booking_id'         => $bookingId,
            'user_id'            => (int) $_SESSION['user_id'],
            'hotel_id'           => $hotelId,
            'check_in'           => $checkIn,
            'check_out'          => $checkOut,
            'jumlah_kamar'       => $jumlahKamar,
            'jumlah_tamu'        => $jumlahTamu,
            'total_harga'        => $totalHarga,
            'catatan'            => $catatan,
        ]);

        $this->flash($ok ? 'success' : 'danger', $ok ? 'Booking hotel berhasil dibuat.' : 'Booking hotel gagal.');
        $this->redirect('/pelanggan/booking/' . $bookingId . '/hotel');
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
    //  INTEGRASI API: Aviationstack
    // ================================================================
    /**
     * Mapping nama destinasi paket wisata ke kode bandara IATA.
     */
    private function getKodeBandara(string $destinasi): ?string {
        $kota = strtolower(trim(explode(',', $destinasi)[0]));

        $map = [
            'bali'         => 'DPS',
            'raja ampat'   => 'SOQ',
            'yogyakarta'   => 'JOG',
            'labuan bajo'  => 'LBJ',
            'lombok'       => 'LOP',
            'malang'       => 'MLG',
            'bandung'      => 'BDO',
            'medan'        => 'KNO',
            'makassar'     => 'UPG',
            'belitung'     => 'TJQ',
        ];

        return $map[$kota] ?? null;
    }

    /**
     * Ambil info penerbangan ke bandara destinasi via Aviationstack API.
     * Endpoint: http://api.aviationstack.com/v1/flights
     */
    private function getInfoPenerbangan(string $destinasi): ?array {
        $kodeBandara = $this->getKodeBandara($destinasi);
        if (!$kodeBandara) {
            return null;
        }

        $params = http_build_query([
            'access_key' => AVIATIONSTACK_API_KEY,
            'arr_iata'   => $kodeBandara,
            'limit'      => 5,
        ]);

        $url = AVIATIONSTACK_BASE_URL . '/flights?' . $params;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode !== 200) {
            error_log("Aviationstack API error ({$httpCode}): {$error}");
            return null;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data['data'])) {
            return null;
        }

        $penerbangan = [];
        foreach ($data['data'] as $flight) {
            $penerbangan[] = [
                'maskapai'        => $flight['airline']['name'] ?? '-',
                'nomor'           => $flight['flight']['iata'] ?? $flight['flight']['number'] ?? '-',
                'dari'            => $flight['departure']['iata'] ?? '-',
                'bandara_asal'    => $flight['departure']['airport'] ?? '-',
                'waktu_berangkat' => $flight['departure']['scheduled'] ?? '-',
                'waktu_tiba'      => $flight['arrival']['scheduled'] ?? '-',
                'status'          => $flight['flight_status'] ?? '-',
            ];
        }

        return [
            'bandara_kode' => $kodeBandara,
            'bandara_nama' => $data['data'][0]['arrival']['airport'] ?? $kodeBandara,
            'penerbangan'  => $penerbangan,
        ];
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
