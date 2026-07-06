<?php
// app/Models/PaketWisataModel.php

require_once ROOT_PATH . '/config/Database.php';

class PaketWisataModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /** Ambil semua paket (bisa difilter by status). */
    public function getAll(string $status = 'aktif'): array {
        if ($status === 'all') {
            $stmt = $this->db->query(
                "SELECT * FROM paket_wisata ORDER BY created_at DESC"
            );
        } else {
            $stmt = $this->db->prepare(
                "SELECT * FROM paket_wisata WHERE status = :status ORDER BY created_at DESC"
            );
            $stmt->execute([':status' => $status]);
        }
        return $stmt->fetchAll();
    }

    /** Cari paket berdasarkan ID. */
    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_wisata WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Buat paket baru (Admin). */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO paket_wisata
                (nama_paket, destinasi, harga, deskripsi, foto, durasi_hari, kuota, status)
             VALUES
                (:nama_paket, :destinasi, :harga, :deskripsi, :foto, :durasi_hari, :kuota, :status)"
        );
        return $stmt->execute([
            ':nama_paket'  => $data['nama_paket'],
            ':destinasi'   => $data['destinasi'],
            ':harga'       => $data['harga'],
            ':deskripsi'   => $data['deskripsi']  ?? null,
            ':foto'        => $data['foto']        ?? null,
            ':durasi_hari' => $data['durasi_hari'] ?? 1,
            ':kuota'       => $data['kuota']       ?? 10,
            ':status'      => $data['status']      ?? 'aktif',
        ]);
    }

    /** Update paket (Admin). */
    public function update(int $id, array $data): bool {
        // Jika ada foto baru, update foto; jika tidak, biarkan tetap
        if (!empty($data['foto'])) {
            $stmt = $this->db->prepare(
                "UPDATE paket_wisata
                    SET nama_paket  = :nama_paket,
                        destinasi   = :destinasi,
                        harga       = :harga,
                        deskripsi   = :deskripsi,
                        foto        = :foto,
                        durasi_hari = :durasi_hari,
                        kuota       = :kuota,
                        status      = :status
                  WHERE id = :id"
            );
            $params = [':foto' => $data['foto']];
        } else {
            $stmt = $this->db->prepare(
                "UPDATE paket_wisata
                    SET nama_paket  = :nama_paket,
                        destinasi   = :destinasi,
                        harga       = :harga,
                        deskripsi   = :deskripsi,
                        durasi_hari = :durasi_hari,
                        kuota       = :kuota,
                        status      = :status
                  WHERE id = :id"
            );
            $params = [];
        }

        return $stmt->execute(array_merge($params, [
            ':nama_paket'  => $data['nama_paket'],
            ':destinasi'   => $data['destinasi'],
            ':harga'       => $data['harga'],
            ':deskripsi'   => $data['deskripsi']  ?? null,
            ':durasi_hari' => $data['durasi_hari'] ?? 1,
            ':kuota'       => $data['kuota']       ?? 10,
            ':status'      => $data['status']      ?? 'aktif',
            ':id'          => $id,
        ]));
    }

    /** Hapus paket (Admin). */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM paket_wisata WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /** Total paket aktif (untuk dashboard). */
    public function countActive(): int {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM paket_wisata WHERE status = 'aktif'"
        );
        return (int) $stmt->fetchColumn();
    }

    /** Cari paket berdasarkan keyword. */
    public function search(string $keyword): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_wisata
              WHERE status = 'aktif'
                AND (nama_paket LIKE :kw_nama OR destinasi LIKE :kw_destinasi)
              ORDER BY nama_paket"
        );
        $stmt->execute([
            ':kw_nama'      => "%{$keyword}%",
            ':kw_destinasi' => "%{$keyword}%",
        ]);
        return $stmt->fetchAll();
    }

    /** Ambil daftar destinasi paket aktif untuk filter pelanggan. */
    public function getActiveDestinations(): array {
        $stmt = $this->db->query(
            "SELECT DISTINCT destinasi
               FROM paket_wisata
              WHERE status = 'aktif' AND destinasi IS NOT NULL AND destinasi <> ''
              ORDER BY destinasi ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Filter paket aktif berdasarkan keyword, destinasi, dan urutan. */
    public function filterActive(array $filters = []): array {
        $sql = "SELECT * FROM paket_wisata WHERE status = 'aktif'";
        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND (nama_paket LIKE :kw_nama OR destinasi LIKE :kw_destinasi)";
            $params[':kw_nama'] = '%' . $filters['keyword'] . '%';
            $params[':kw_destinasi'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['destinasi'])) {
            $sql .= " AND destinasi = :destinasi";
            $params[':destinasi'] = $filters['destinasi'];
        }

        $orderBy = match ($filters['sort'] ?? '') {
            'harga_asc'  => 'harga ASC',
            'harga_desc' => 'harga DESC',
            'durasi_asc' => 'durasi_hari ASC',
            'durasi_desc'=> 'durasi_hari DESC',
            default      => 'created_at DESC',
        };

        $stmt = $this->db->prepare($sql . " ORDER BY {$orderBy}");
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
