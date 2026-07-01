<?php
// app/Models/HotelBookingModel.php

require_once ROOT_PATH . '/config/Database.php';

class HotelBookingModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public static function generateKode(): string {
        return 'HTL-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO hotel_bookings
                (kode_hotel_booking, booking_id, user_id, hotel_id, check_in, check_out,
                 jumlah_kamar, jumlah_tamu, total_harga, status, catatan)
             VALUES
                (:kode_hotel_booking, :booking_id, :user_id, :hotel_id, :check_in, :check_out,
                 :jumlah_kamar, :jumlah_tamu, :total_harga, 'pending', :catatan)"
        );

        $ok = $stmt->execute([
            ':kode_hotel_booking' => $data['kode_hotel_booking'],
            ':booking_id'         => $data['booking_id'],
            ':user_id'            => $data['user_id'],
            ':hotel_id'           => $data['hotel_id'],
            ':check_in'           => $data['check_in'],
            ':check_out'          => $data['check_out'],
            ':jumlah_kamar'       => $data['jumlah_kamar'],
            ':jumlah_tamu'        => $data['jumlah_tamu'],
            ':total_harga'        => $data['total_harga'],
            ':catatan'            => $data['catatan'] ?? null,
        ]);

        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    public function getByBooking(int $bookingId, int $userId): array|false {
        $stmt = $this->db->prepare(
            "SELECT hb.*, h.nama_hotel, h.destinasi, h.bintang, h.alamat
               FROM hotel_bookings hb
               JOIN hotels h ON hb.hotel_id = h.id
              WHERE hb.booking_id = :booking_id
                AND hb.user_id = :user_id
              LIMIT 1"
        );
        $stmt->execute([':booking_id' => $bookingId, ':user_id' => $userId]);
        return $stmt->fetch();
    }
}
