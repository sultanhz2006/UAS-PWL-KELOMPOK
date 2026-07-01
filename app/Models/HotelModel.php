<?php
// app/Models/HotelModel.php

require_once ROOT_PATH . '/config/Database.php';

class HotelModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByDestinasi(string $destinasi): array {
        $kota = trim(explode(',', $destinasi)[0]);
        $stmt = $this->db->prepare(
            "SELECT * FROM hotels
              WHERE status = 'aktif'
                AND destinasi LIKE :destinasi
              ORDER BY bintang DESC, harga_per_malam ASC"
        );
        $stmt->execute([':destinasi' => '%' . $kota . '%']);
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM hotels WHERE id = :id AND status = 'aktif' LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
