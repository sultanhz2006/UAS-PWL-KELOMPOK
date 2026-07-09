<?php

require_once APP_PATH . '/Core/BaseController.php';
require_once APP_PATH . '/Models/PaketWisataModel.php';
require_once APP_PATH . '/Models/BookingModel.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Middleware/AuthMiddleware.php';

class AdminController extends BaseController {
    private PaketWisataModel $paketModel;
    private BookingModel     $bookingModel;
    private UserModel        $userModel;

    public function __construct() {
        AuthMiddleware::requireRole('admin');
        $this->paketModel   = new PaketWisataModel();
        $this->bookingModel = new BookingModel();
        $this->userModel    = new UserModel();
    }

    public function dashboard(): void {
        $data = [
            'title'          => 'Dashboard Admin — ' . APP_NAME,
            'total_paket'    => $this->paketModel->countActive(),
            'booking_stats'  => $this->bookingModel->countByStatus(),
            'total_user'     => count($this->userModel->getAll('pelanggan')),
            'pendapatan'     => $this->bookingModel->totalPendapatan(),
            'recent_booking' => array_slice($this->bookingModel->getAll(), 0, 5),
        ];
        $this->view('admin/dashboard', $data);
    }

    public function paketIndex(): void {
        $this->view('admin/paket/index', [
            'title'  => 'Manajemen Paket Wisata',
            'pakets' => $this->paketModel->getAll('all'),
        ]);
    }

    public function paketCreate(): void {
        $this->view('admin/paket/create', ['title' => 'Tambah Paket Wisata']);
    }

    public function paketStore(): void {
        $fotoFilename = null;

        if (!empty($_FILES['foto']['name'])) {
            $fotoFilename = $this->handleUpload($_FILES['foto']);
            if ($fotoFilename === false) {
                $this->redirect('/admin/paket/create');
            }
        }

        $ok = $this->paketModel->create([
            'nama_paket'  => trim($_POST['nama_paket']  ?? ''),
            'destinasi'   => trim($_POST['destinasi']   ?? ''),
            'harga'       => (float) ($_POST['harga']   ?? 0),
            'deskripsi'   => trim($_POST['deskripsi']   ?? ''),
            'foto'        => $fotoFilename,
            'durasi_hari' => (int) ($_POST['durasi_hari'] ?? 1),
            'kuota'       => (int) ($_POST['kuota']       ?? 10),
            'status'      => $_POST['status'] ?? 'aktif',
        ]);

        if ($ok) {
            $this->flash('success', 'Paket wisata berhasil ditambahkan!');
        } else {
            $this->flash('danger', 'Gagal menambahkan paket wisata.');
        }
        $this->redirect('/admin/paket');
    }

    public function paketEdit(string $id): void {
        $paket = $this->paketModel->findById((int) $id);
        if (!$paket) {
            $this->flash('danger', 'Paket tidak ditemukan.');
            $this->redirect('/admin/paket');
        }
        $this->view('admin/paket/edit', ['title' => 'Edit Paket Wisata', 'paket' => $paket]);
    }

    public function paketUpdate(string $id): void {
        $paket = $this->paketModel->findById((int) $id);
        if (!$paket) {
            $this->flash('danger', 'Paket tidak ditemukan.');
            $this->redirect('/admin/paket');
        }

        $fotoFilename = $paket['foto'];

        if (!empty($_FILES['foto']['name'])) {
            $newFoto = $this->handleUpload($_FILES['foto']);
            if ($newFoto !== false) {
                if ($paket['foto'] && file_exists(UPLOAD_PATH . '/' . $paket['foto'])) {
                    unlink(UPLOAD_PATH . '/' . $paket['foto']);
                }
                $fotoFilename = $newFoto;
            } else {
                $this->redirect('/admin/paket/' . $id . '/edit');
            }
        }

        $ok = $this->paketModel->update((int) $id, [
            'nama_paket'  => trim($_POST['nama_paket']  ?? ''),
            'destinasi'   => trim($_POST['destinasi']   ?? ''),
            'harga'       => (float) ($_POST['harga']   ?? 0),
            'deskripsi'   => trim($_POST['deskripsi']   ?? ''),
            'foto'        => $fotoFilename,
            'durasi_hari' => (int) ($_POST['durasi_hari'] ?? 1),
            'kuota'       => (int) ($_POST['kuota']       ?? 10),
            'status'      => $_POST['status'] ?? 'aktif',
        ]);

        $this->flash($ok ? 'success' : 'danger', $ok ? 'Paket berhasil diperbarui!' : 'Gagal memperbarui paket.');
        $this->redirect('/admin/paket');
    }

    public function paketDelete(string $id): void {
        $paket = $this->paketModel->findById((int) $id);
        if ($paket) {
            if ($paket['foto'] && file_exists(UPLOAD_PATH . '/' . $paket['foto'])) {
                unlink(UPLOAD_PATH . '/' . $paket['foto']);
            }
            $this->paketModel->delete((int) $id);
            $this->flash('success', 'Paket wisata berhasil dihapus.');
        } else {
            $this->flash('danger', 'Paket tidak ditemukan.');
        }
        $this->redirect('/admin/paket');
    }

    public function bookingIndex(): void {
        $this->view('admin/booking/index', [
            'title'    => 'Manajemen Booking',
            'bookings' => $this->bookingModel->getAll(),
        ]);
    }

    public function bookingUpdateStatus(string $id): void {
        $status  = $_POST['status'] ?? '';
        $allowed = ['pending', 'dikonfirmasi', 'dibatalkan'];
        if (!in_array($status, $allowed, true)) {
            $this->flash('danger', 'Status tidak valid.');
            $this->redirect('/admin/booking');
        }
        $this->bookingModel->updateStatus((int) $id, $status);
        $this->flash('success', 'Status booking diperbarui.');
        $this->redirect('/admin/booking');
    }

    private function handleUpload(array $file): string|false {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->flash('danger', 'Terjadi kesalahan saat upload file.');
            return false;
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            $this->flash('danger', 'Ukuran file maksimal 2 MB.');
            return false;
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_IMG_EXT, true)) {
            $this->flash('danger', 'Ekstensi file harus: ' . implode(', ', ALLOWED_IMG_EXT));
            return false;
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!str_starts_with($mime, 'image/')) {
            $this->flash('danger', 'File harus berupa gambar yang valid.');
            return false;
        }

        $filename = 'paket_' . uniqid() . '_' . time() . '.' . $ext;
        $dest     = UPLOAD_PATH . '/' . $filename;

        if (!is_dir(UPLOAD_PATH) && !mkdir(UPLOAD_PATH, 0777, true)) {
            $this->flash('danger', 'Folder upload tidak ditemukan dan tidak bisa dibuat. Cek permission folder uploads/.');
            return false;
        }

        if (!is_writable(UPLOAD_PATH)) {
            $this->flash('danger', 'Folder uploads tidak dapat ditulis. Cek permission folder uploads/.');
            return false;
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->flash('danger', 'Gagal menyimpan file. Cek permission folder uploads/.');
            return false;
        }
        return $filename;
    }
}
