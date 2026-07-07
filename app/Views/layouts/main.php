<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --vt-primary:   #0A6CFF;
            --vt-primary-d: #0052CC;
            --vt-accent:    #FF6B35;
            --vt-sidebar-w: 260px;
            --vt-sidebar-bg:#0D1B2A;
            --vt-sidebar-txt:#B8C9D9;
            --vt-sidebar-active:#0A6CFF;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #F4F7FB;
            min-height: 100vh;
        }

        /* ---- Sidebar ---- */
        #sidebar {
            width: var(--vt-sidebar-w);
            min-height: 100vh;
            background: var(--vt-sidebar-bg);
            position: fixed; top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s ease;
            display: flex; flex-direction: column;
            transform: translateX(0);
            will-change: transform;
        }
        .sidebar-brand {
            padding: 22px 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand .brand-name {
            font-size: 1.25rem; font-weight: 700;
            color: #fff; letter-spacing: -.3px;
        }
        .sidebar-brand .brand-sub {
            font-size: .72rem; color: var(--vt-sidebar-txt);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section-label {
            font-size: .65rem; text-transform: uppercase; letter-spacing: 1.2px;
            color: #526070; padding: 18px 24px 6px; font-weight: 600;
        }
        .nav-link-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 24px; color: var(--vt-sidebar-txt);
            text-decoration: none; font-size: .88rem; font-weight: 500;
            border-radius: 0; transition: all .2s;
            position: relative;
            word-break: break-word;
        }
        .nav-link-item:hover {
            color: #fff; background: rgba(255,255,255,.06);
        }
        .nav-link-item.active {
            color: #fff;
            background: rgba(10,108,255,.2);
        }
        .nav-link-item.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: var(--vt-primary); border-radius: 0 2px 2px 0;
        }
        .nav-link-item i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: .8rem; color: var(--vt-sidebar-txt);
        }
        .sidebar-footer .user-name { color: #fff; font-weight: 600; }

        /* ---- Table Responsive ---- */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ---- Form Responsive ---- */
        @media (min-width: 992px) {
            .form-select, .form-control {
                min-height: auto;
            }
            .btn {
                min-height: auto;
            }
        }

        /* ---- Main Content ---- */
        #main-content {
            margin-left: var(--vt-sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #E8EDF2;
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            gap: 12px;
        }
        .topbar-title { font-size: 1.05rem; font-weight: 600; color: #1A2B3C; }
        .topbar .btn-logout {
            font-size: .82rem; color: #64748B;
            border: 1px solid #E2E8F0; border-radius: 8px;
            padding: 8px 14px; text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
            min-height: 36px;
            display: flex;
            align-items: center;
        }
        .topbar .btn-logout:hover { background: #FEE2E2; color: #DC2626; border-color: #FCA5A5; }
        .page-body { padding: 28px; flex: 1; }

        /* ---- Cards ---- */
        .stat-card {
            background: #fff; border-radius: 14px; padding: 22px 24px;
            border: 1px solid #E8EDF2; position: relative; overflow: hidden;
        }
        .stat-card .icon-wrap {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 14px;
        }
        .stat-card .stat-value { font-size: 1.9rem; font-weight: 700; color: #1A2B3C; }
        .stat-card .stat-label { font-size: .8rem; color: #64748B; font-weight: 500; }
        .stat-card code { font-size: .85rem; }
        @media (max-width: 991.98px) {
            .stat-card code {
                font-size: 0.7rem !important;
            }
        }
        
        /* ---- Paket Card Grid ---- */
        .paket-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E8EDF2;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .paket-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        @media (max-width: 991.98px) {
            .paket-card {
                border-radius: 8px;
            }
        }

        /* ---- Flash Messages ---- */
        .flash-container { 
            position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;
            max-width: 90vw;
        }
        @media (max-width: 991.98px) {
            .flash-container { 
                top: 70px; right: 10px; left: 10px; min-width: auto;
                width: calc(100% - 20px);
                max-width: none;
            }
        }

        /* ---- Responsive ---- */
        /* DESKTOP (>= 992px): Sidebar ALWAYS visible */
        @media (min-width: 992px) {
            #sidebar {
                transform: translateX(0) !important;
                display: flex !important;
            }
            #main-content {
                margin-left: var(--vt-sidebar-w) !important;
            }
            .btn-sidebar-toggle { 
                display: none !important;
            }
            .sidebar-overlay { 
                display: none !important;
                opacity: 0 !important;
            }
        }
        
        /* TABLET/MOBILE (< 992px): Sidebar HIDDEN by default, toggle to show */
        @media (max-width: 991.98px) {
            #sidebar { 
                transform: translateX(-100%) !important;
            }
            #sidebar.show { 
                transform: translateX(0) !important;
            }
            #main-content { 
                margin-left: 0 !important;
            }
            .btn-sidebar-toggle { 
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            .page-body { padding: 16px; }
            .topbar { padding: 12px 16px; }
            .topbar-title { font-size: 0.95rem; }
            .stat-card { padding: 16px; margin-bottom: 12px; }
            .sidebar-footer {
                padding: 12px 16px;
                font-size: 0.75rem;
            }
            .sidebar-footer .d-flex { gap: 8px; }
            .table-responsive thead {
                font-size: 0.75rem;
            }
            .table-responsive table td,
            .table-responsive table th {
                padding: 0.5rem 0.25rem;
                font-size: 0.8rem;
            }
            .form-select, .form-control {
                min-height: 40px;
                font-size: 0.95rem;
            }
            .btn {
                min-height: 40px;
                font-size: 0.9rem;
            }
        }
        
        /* SMALL MOBILE (< 576px): Extra compact */
        @media (max-width: 575.98px) {
            .page-body { padding: 12px; }
            .topbar { padding: 10px 12px; gap: 8px !important; }
            .topbar-title { font-size: 0.85rem; }
            .stat-card { padding: 14px 12px; }
            .btn-logout { font-size: 0.7rem; padding: 5px 10px; }
            .sidebar-footer {
                padding: 10px 12px;
                font-size: 0.7rem;
            }
            .sidebar-footer > div > div:last-child {
                display: none;
            }
            .flash-container { 
                top: 65px;
                width: calc(100% - 16px);
            }
        }
        .sidebar-overlay {
            display: none; 
            position: fixed; 
            inset: 0;
            background: rgba(0,0,0,.5); 
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }
        
        /* On mobile, overlay element exists but stays hidden until .show is added */
        @media (max-width: 991.98px) {
            .sidebar-overlay {
                display: block !important;
            }
        }
        .btn-sidebar-toggle {
            background: none; border: none; font-size: 1.4rem; color: #64748B;
            padding: 8px; line-height: 1; cursor: pointer;
            border-radius: 6px; transition: background .2s;
            min-width: 44px; min-height: 44px;
            display: none !important;
        }
        .btn-sidebar-toggle:hover { background: rgba(100, 116, 139, 0.1); }
        
        /* Show toggle button only on tablet/mobile (< 992px) */
        @media (max-width: 991.98px) {
            .btn-sidebar-toggle { 
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ============================================================ SIDEBAR -->
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name"><i class="bi bi-airplane-fill text-primary me-2"></i>VyanTravel</div>
        <div class="brand-sub">E-Travel Management</div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="nav-section-label">Utama</div>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section-label">Manajemen</div>
        <a href="<?= BASE_URL ?>/admin/paket" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/paket') ? 'active' : '' ?>">
            <i class="bi bi-map"></i> Paket Wisata
        </a>
        <a href="<?= BASE_URL ?>/admin/booking" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/admin/booking') ? 'active' : '' ?>">
            <i class="bi bi-calendar-check"></i> Booking
        </a>

        <?php else: ?>
        <div class="nav-section-label">Utama</div>
        <a href="<?= BASE_URL ?>/pelanggan/dashboard" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/pelanggan/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house"></i> Beranda
        </a>
        <a href="<?= BASE_URL ?>/pelanggan/paket" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/pelanggan/paket') ? 'active' : '' ?>">
            <i class="bi bi-compass"></i> Paket Wisata
        </a>
        <a href="<?= BASE_URL ?>/pelanggan/booking" class="nav-link-item <?= str_contains($_SERVER['REQUEST_URI'], '/pelanggan/booking') ? 'active' : '' ?>">
            <i class="bi bi-ticket-perforated"></i> Booking Saya
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                 style="width:34px;height:34px;font-size:.9rem;font-weight:700;flex-shrink:0;">
                <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
            </div>
            <div>
                <div class="user-name" style="font-size:.83rem"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                <div style="font-size:.7rem"><?= ucfirst($_SESSION['user_role']) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ MAIN -->
<div id="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title"><?= htmlspecialchars($title ?? APP_NAME) ?></span>
        </div>
        <a href="<?= BASE_URL ?>/logout" class="btn-logout">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-container">
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= $_SESSION['flash']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <!-- Page Content -->
    <div class="page-body">
        <?= isset($content) ? $content : '' ?>
    </div>
</div><!-- /main-content -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Close sidebar when clicking overlay
document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

// Close sidebar when clicking on a nav link (mobile)
document.querySelectorAll('.nav-link-item').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
    });
});

// Auto-dismiss flash messages
setTimeout(() => {
    document.querySelectorAll('.flash-container .alert').forEach(el => {
        bootstrap.Alert.getOrCreateInstance(el).close();
    });
}, 4500);
</script>
</body>
</html>
