<!-- app/Views/errors/404.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>404 Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;background:#F4F7FB;display:flex;align-items:center;min-height:100vh}</style>
</head>
<body>
<div class="container text-center py-5">
    <div style="font-size:5rem">🗺️</div>
    <h1 class="fw-bold" style="color:#0A6CFF">404</h1>
    <p class="lead text-muted">Halaman yang Anda cari tidak ditemukan.</p>
    <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>/login" class="btn btn-primary rounded-3">← Ke Beranda</a>
</div>
</body></html>
