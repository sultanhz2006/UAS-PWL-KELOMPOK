<?php
// config/Database.php

class Database {
    private static ?PDO $instance = null;

    // --- Konfigurasi Koneksi ---
    private static string $host   = 'localhost';
    private static string $db     = 'vyantravel_db';
    private static string $user   = 'root';
    private static string $pass   = '';
    private static string $charset = 'utf8mb4';

    /**
     * Singleton: satu koneksi PDO dipakai seluruh aplikasi.
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::$host
                 . ";dbname=" . self::$db
                 . ";charset=" . self::$charset;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Native prepared statements
            ];

            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                // Jangan expose detail error di production!
                error_log("DB Connection Error: " . $e->getMessage());
                http_response_code(500);
                die(json_encode(['error' => 'Koneksi database gagal. Silakan coba lagi nanti.']));
            }
        }
        return self::$instance;
    }

    // Cegah instansiasi & kloning
    private function __construct() {}
    private function __clone() {}
}
