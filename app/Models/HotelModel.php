<?php

require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/api_keys/api_keys.php';

class HotelModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function callAPI(string $path, array $queryParams): ?array {
        $url = "https://" . RAPIDAPI_BOOKING_HOST . $path . "?" . http_build_query($queryParams);
        $ch  = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "x-rapidapi-host: " . RAPIDAPI_BOOKING_HOST,
                "x-rapidapi-key: "  . RAPIDAPI_BOOKING_KEY,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err || $httpCode !== 200) {
            error_log("RapidAPI Booking Error ({$httpCode}): {$err}");
            return null;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data['status'])) {
            return null;
        }

        return $data;
    }

    public function getByDestinasi(string $destinasi, ?string $checkInDate = null, ?string $checkOutDate = null): array {
        $kota = trim(explode(',', $destinasi)[0]);
        if (empty($kota)) {
            return [];
        }

        $destData = $this->callAPI("/api/v1/hotels/searchDestination", ['query' => $kota]);

        if (!$destData || empty($destData['data'])) {
            return [];
        }

        $targetDest = null;
        foreach ($destData['data'] as $dest) {
            if (isset($dest['dest_type']) && in_array($dest['dest_type'], ['city', 'region'], true)) {
                $targetDest = $dest;
                break;
            }
        }
        if (!$targetDest) {
            $targetDest = $destData['data'][0];
        }

        $destId     = $targetDest['dest_id'];
        $searchType = $targetDest['search_type'] ?? $targetDest['dest_type'] ?? 'city';

        if (!$checkInDate) {
            $checkInDate = date('Y-m-d', strtotime('+1 day'));
        }
        if (!$checkOutDate) {
            $checkOutDate = date('Y-m-d', strtotime('+2 days'));
        }

        $checkInTimestamp  = strtotime($checkInDate);
        $checkOutTimestamp = strtotime($checkOutDate);
        $nights = max(1, (int) (($checkOutTimestamp - $checkInTimestamp) / 86400));

        $hotelData = $this->callAPI("/api/v1/hotels/searchHotels", [
            'dest_id'          => $destId,
            'search_type'      => $searchType,
            'arrival_date'     => $checkInDate,
            'departure_date'   => $checkOutDate,
            'adults'           => '1',
            'room_qty'         => '1',
            'page_number'      => '1',
            'units'            => 'metric',
            'temperature_unit' => 'c',
            'languagecode'     => 'id',
            'currency_code'    => 'IDR',
        ]);

        if (!$hotelData || empty($hotelData['data']['hotels'])) {
            return [];
        }

        $mappedHotels = [];
        foreach ($hotelData['data']['hotels'] as $h) {
            if (empty($h['property'])) {
                continue;
            }
            $prop = $h['property'];

            $grossPrice = 0;
            if (isset($prop['priceBreakdown']['grossPrice']['value'])) {
                $grossPrice = (float) $prop['priceBreakdown']['grossPrice']['value'];
            }
            $hargaPerMalam = $nights > 0 ? ($grossPrice / $nights) : $grossPrice;

            $bintang = 3;
            if (isset($prop['propertyClass']) && $prop['propertyClass'] > 0) {
                $bintang = (int) $prop['propertyClass'];
            } elseif (isset($prop['reviewScore'])) {
                $bintang = (int) round($prop['reviewScore'] / 2);
            }
            $bintang = max(1, min(5, $bintang));

            $alamat = $prop['wishlistName'] ?? '';
            if (isset($prop['countryCode'])) {
                $alamat .= ', ' . strtoupper($prop['countryCode']);
            }

            $reviewWord  = $prop['reviewScoreWord'] ?? '';
            $reviewScore = $prop['reviewScore'] ?? '';
            $deskripsi   = $reviewWord
                ? "Akomodasi berperingkat {$reviewWord} dengan skor ulasan {$reviewScore}/10."
                : "Penginapan nyaman untuk perjalanan Anda.";

            $foto = null;
            if (!empty($prop['photoUrls']) && is_array($prop['photoUrls'])) {
                $foto = $prop['photoUrls'][0];
            }

            $mappedHotels[] = [
                'id'              => (int) $prop['id'],
                'nama_hotel'      => $prop['name'],
                'destinasi'       => $destinasi,
                'harga_per_malam' => $hargaPerMalam,
                'bintang'         => $bintang,
                'alamat'          => $alamat,
                'deskripsi'       => $deskripsi,
                'foto'            => $foto,
                'status'          => 'aktif',
            ];
        }

        return $mappedHotels;
    }

    public function findById(int $id, ?string $checkInDate = null, ?string $checkOutDate = null): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM hotels WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $localHotel = $stmt->fetch();

        if ($localHotel) {
            return $localHotel;
        }

        if (!$checkInDate) {
            $checkInDate = date('Y-m-d', strtotime('+1 day'));
        }
        if (!$checkOutDate) {
            $checkOutDate = date('Y-m-d', strtotime('+2 days'));
        }

        $checkInTimestamp  = strtotime($checkInDate);
        $checkOutTimestamp = strtotime($checkOutDate);
        $nights = max(1, (int) (($checkOutTimestamp - $checkInTimestamp) / 86400));

        $detailsData = $this->callAPI("/api/v1/hotels/getHotelDetails", [
            'hotel_id'         => (string) $id,
            'arrival_date'     => $checkInDate,
            'departure_date'   => $checkOutDate,
            'adults'           => '1',
            'room_qty'         => '1',
            'units'            => 'metric',
            'temperature_unit' => 'c',
            'languagecode'     => 'id',
            'currency_code'    => 'IDR',
        ]);

        if (!$detailsData || empty($detailsData['data'])) {
            return false;
        }

        $d = $detailsData['data'];

        $namaHotel = $d['hotel_name'] ?? 'Hotel ' . $id;
        $destinasi = ($d['city'] ?? 'Destinasi') . ', ' . ($d['country_trans'] ?? 'Indonesia');

        $grossPrice = 0;
        if (isset($d['composite_price_breakdown']['gross_amount_per_night']['value'])) {
            $grossPrice = (float) $d['composite_price_breakdown']['gross_amount_per_night']['value'];
        } elseif (isset($d['composite_price_breakdown']['all_inclusive_amount']['value'])) {
            $grossPrice = (float) $d['composite_price_breakdown']['all_inclusive_amount']['value'] / $nights;
        } else {
            $grossPrice = 500000;
        }

        $bintang   = isset($d['review_nr']) ? 4 : 3;
        $alamat    = ($d['address'] ?? '') . ', ' . ($d['city'] ?? '');
        $deskripsi = "Hotel nyaman di " . ($d['city'] ?? 'kota tujuan');

        $stmtInsert = $this->db->prepare(
            "INSERT INTO hotels
                (id, nama_hotel, destinasi, harga_per_malam, bintang, alamat, deskripsi, status)
             VALUES
                (:id, :nama_hotel, :destinasi, :harga_per_malam, :bintang, :alamat, :deskripsi, 'aktif')"
        );

        $ok = $stmtInsert->execute([
            ':id'              => $id,
            ':nama_hotel'      => $namaHotel,
            ':destinasi'       => $destinasi,
            ':harga_per_malam' => $grossPrice,
            ':bintang'         => $bintang,
            ':alamat'          => $alamat,
            ':deskripsi'       => $deskripsi,
        ]);

        if ($ok) {
            $stmtSelect = $this->db->prepare("SELECT * FROM hotels WHERE id = :id LIMIT 1");
            $stmtSelect->execute([':id' => $id]);
            return $stmtSelect->fetch();
        }

        return false;
    }
}
