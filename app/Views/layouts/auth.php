<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0D1B2A 0%, #1A3350 50%, #0A6CFF20 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: #fff; border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0,0,0,.25);
            width: 100%; max-width: 440px; padding: 40px 36px;
        }
        .auth-logo { font-size: 1.6rem; font-weight: 700; color: #0A6CFF; }
        .auth-logo i { margin-right: 8px; }
        .auth-subtitle { font-size: .85rem; color: #64748B; margin-top: 4px; }
        .form-label { font-weight: 500; font-size: .88rem; color: #374151; }
        .form-control {
            border-radius: 10px; border: 1.5px solid #E5E7EB;
            padding: 10px 14px; font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: #0A6CFF; box-shadow: 0 0 0 3px rgba(10,108,255,.12);
        }
        .btn-auth {
            background: #0A6CFF; color: #fff; border: none;
            border-radius: 10px; padding: 11px; font-weight: 600;
            font-size: .95rem; width: 100%;
            transition: background .2s, transform .1s;
        }
        .btn-auth:hover { background: #0052CC; transform: translateY(-1px); }
        .divider { text-align: center; color: #9CA3AF; font-size: .82rem; margin: 18px 0; }
        .input-group-text {
            border-radius: 10px 0 0 10px; background: #F9FAFB;
            border: 1.5px solid #E5E7EB; border-right: 0; color: #9CA3AF;
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="mb-4">
        <div class="auth-logo"><i class="bi bi-airplane-fill"></i>VyanTravel</div>
        <div class="auth-subtitle">Platform Perjalanan Wisata Terpercaya</div>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show py-2" role="alert" style="font-size:.85rem">
        <?= $_SESSION['flash']['message'] ?>
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <?= $content ?? '' ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
