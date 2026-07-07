<?php

require_once ROOT_PATH . '/config/Database.php';

class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nama_lengkap, email, password, role
               FROM users
              WHERE email = :email
              LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nama_lengkap, email, no_telp, role, created_at
               FROM users
              WHERE id = :id
              LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(string $role = ''): array {
        if ($role) {
            $stmt = $this->db->prepare(
                "SELECT id, nama_lengkap, email, no_telp, role, created_at
                   FROM users
                  WHERE role = :role
                  ORDER BY created_at DESC"
            );
            $stmt->execute([':role' => $role]);
        } else {
            $stmt = $this->db->query(
                "SELECT id, nama_lengkap, email, no_telp, role, created_at
                   FROM users
                  ORDER BY created_at DESC"
            );
        }
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (nama_lengkap, email, password, no_telp, role)
             VALUES (:nama_lengkap, :email, :password, :no_telp, :role)"
        );
        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':email'        => $data['email'],
            ':password'     => $data['password'],
            ':no_telp'      => $data['no_telp']  ?? null,
            ':role'         => $data['role']      ?? 'pelanggan',
        ]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE users
                SET nama_lengkap = :nama_lengkap,
                    no_telp      = :no_telp
              WHERE id = :id"
        );
        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':no_telp'      => $data['no_telp'] ?? null,
            ':id'           => $id,
        ]);
    }
}
