<?php

require_once ROOT_PATH . '/config/Database.php';

class BookingModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /** Generate kode booking unik: VT-YYYYMMDD-XXXXX */
    public static function generateKode(): string {
        return 'VT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    /** Buat booking baru. */
    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO bookings
                (kode_booking, user_id, paket_id, tanggal_pesan, tanggal_berangkat,
                 jumlah_peserta, total_harga, status, catatan)
             VALUES
                (:kode_booking, :user_id, :paket_id, :tanggal_pesan, :tanggal_berangkat,
                 :jumlah_peserta, :total_harga, 'pending', :catatan)"
        );
        $ok = $stmt->execute([
            ':kode_booking'      => $data['kode_booking'],
            ':user_id'           => $data['user_id'],
            ':paket_id'          => $data['paket_id'],
            ':tanggal_pesan'     => date('Y-m-d'),
            ':tanggal_berangkat' => $data['tanggal_berangkat'],
            ':jumlah_peserta'    => $data['jumlah_peserta'],
            ':total_harga'       => $data['total_harga'],
            ':catatan'           => $data['catatan'] ?? null,
        ]);
        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    /** Semua booking milik satu pelanggan. */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT b.*, p.nama_paket, p.destinasi, p.foto
               FROM bookings b
               JOIN paket_wisata p ON b.paket_id = p.id
              WHERE b.user_id = :user_id
              ORDER BY b.created_at DESC"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /** Semua booking (Admin). */
    public function getAll(): array {
        return $this->db->query(
            "SELECT b.*, p.nama_paket, p.destinasi, u.nama_lengkap, u.email
               FROM bookings b
               JOIN paket_wisata p ON b.paket_id = p.id
               JOIN users        u ON b.user_id  = u.id
              ORDER BY b.created_at DESC"
        )->fetchAll();
    }

    /** Detail satu booking. */
    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT b.*, p.nama_paket, p.destinasi, p.harga, p.foto, p.durasi_hari,
                    u.nama_lengkap, u.email, u.no_telp
               FROM bookings b
               JOIN paket_wisata p ON b.paket_id = p.id
               JOIN users        u ON b.user_id  = u.id
              WHERE b.id = :id
              LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Update status booking (Admin). */
    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET status = :status WHERE id = :id"
        );
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /** Simpan path PDF tiket. */
    public function savePdfPath(int $id, string $path): bool {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET pdf_path = :path WHERE id = :id"
        );
        return $stmt->execute([':path' => $path, ':id' => $id]);
    }

    /** Statistik untuk Admin dashboard. */
    public function countByStatus(): array {
        $stmt = $this->db->query(
            "SELECT status, COUNT(*) AS jumlah FROM bookings GROUP BY status"
        );
        $result = ['pending' => 0, 'dikonfirmasi' => 0, 'dibatalkan' => 0];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['status']] = (int) $row['jumlah'];
        }
        return $result;
    }

    /** Total pendapatan (booking dikonfirmasi). */
    public function totalPendapatan(): string {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total_harga), 0) FROM bookings WHERE status = 'dikonfirmasi'"
        );
        return (string) $stmt->fetchColumn();
    }
}
