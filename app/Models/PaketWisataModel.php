<?php

require_once ROOT_PATH . '/config/Database.php';

class PaketWisataModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

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

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_wisata WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

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

    public function update(int $id, array $data): bool {
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

    public function delete(int $id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM paket_wisata WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function countActive(): int {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM paket_wisata WHERE status = 'aktif'"
        );
        return (int) $stmt->fetchColumn();
    }

    public function search(string $keyword): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_wisata
              WHERE status = 'aktif'
                AND (nama_paket LIKE :kw OR destinasi LIKE :kw)
              ORDER BY nama_paket"
        );
        $stmt->execute([':kw' => "%{$keyword}%"]);
        return $stmt->fetchAll();
    }
}
