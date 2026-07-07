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
            color: #1A2B3C;
            min-height: 100vh;
        }
        body.dark-mode {
            background: #090b0f;
            color: #e2e8f0;
        }
        body.dark-mode #sidebar {
            background: #111827;
        }
        body.dark-mode .nav-link-item {
            color: #cbd5e1;
        }
        body.dark-mode .nav-link-item:hover {
            color: #fff;
            background: rgba(255,255,255,.08);
        }
        body.dark-mode .nav-link-item.active {
            color: #fff;
            background: rgba(96,165,250,.2);
        }
        body.dark-mode .nav-link-item.active::before {
            background: #60a5fa;
        }
        body.dark-mode .sidebar-footer {
            border-top-color: rgba(148,163,184,.15);
            color: #cbd5e1;
        }
        body.dark-mode .sidebar-footer .user-name {
            color: #fff;
        }
        body.dark-mode .topbar {
            background: #111827;
            border-color: rgba(148,163,184,.15);
        }
        body.dark-mode .topbar-title {
            color: #f8fafc;
        }
        body.dark-mode .btn-logout {
            color: #e2e8f0;
            border-color: rgba(148,163,184,.2);
            background: #1f2937;
        }
        body.dark-mode .btn-logout:hover {
            background: #374151;
            color: #fff;
        }
        body.dark-mode .page-body,
        body.dark-mode .stat-card,
        body.dark-mode .paket-card {
            background: #111827;
            border-color: rgba(255,255,255,.08);
            color: #e2e8f0;
        }
        body.dark-mode .stat-card .stat-value,
        body.dark-mode .stat-card .stat-label,
        body.dark-mode .stat-card code {
            color: #e2e8f0;
        }
        body.dark-mode .stat-card .stat-label,
        body.dark-mode .flash-container .alert {
            color: #cbd5e1;
        }
        body.dark-mode .flash-container .alert {
            background: rgba(15,23,42,.98);
            border-color: rgba(148,163,184,.1);
        }
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: #0f172a;
            border-color: rgba(148,163,184,.2);
            color: #e2e8f0;
        }
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 0.2rem rgba(96,165,250,.25);
        }
        body.dark-mode .btn-outline-secondary {
            color: #e2e8f0;
            border-color: rgba(148,163,184,.25);
        }
        body.dark-mode .btn-outline-secondary:hover {
            background: rgba(96,165,250,.12);
            color: #fff;
        }
        body.dark-mode .table,
        body.dark-mode .table-responsive,
        body.dark-mode .table-responsive table {
            color: #e2e8f0;
        }
        body.dark-mode .table th,
        body.dark-mode .table td,
        body.dark-mode .table thead th,
        body.dark-mode .table tbody td {
            background: #0f172a;
            border-color: rgba(148,163,184,.2);
            color: #e2e8f0;
        }
        body.dark-mode .table thead th {
            background: #111827;
            color: #e2e8f0;
        }
        body.dark-mode .table-hover tbody tr:hover {
            background: rgba(96,165,250,.12);
        }
        body.dark-mode .table thead th,
        body.dark-mode .table tbody tr {
            border-color: rgba(148,163,184,.15);
        }
        body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        body.dark-mode h5[style],
        body.dark-mode h6[style],
        body.dark-mode p[style],
        body.dark-mode strong[style],
        body.dark-mode div[style*="color:#1A2B3C"],
        body.dark-mode div[style*="color:#64748B"],
        body.dark-mode span[style*="color:#0A6CFF"],
        body.dark-mode code[style],
        body.dark-mode th[style],
        body.dark-mode td[style] {
            color: #e2e8f0 !important;
        }
        body.dark-mode .table thead th[style],
        body.dark-mode .table thead[style] {
            background: #111827 !important;
        }
        body.dark-mode [style*="color:"] {
            color: #e2e8f0 !important;
        }
        body.dark-mode [style*="background:"] {
            background: none !important;
            background-color: transparent !important;
            background-image: none !important;
        }
        body.dark-mode .bg-white,
        body.dark-mode .bg-light,
        body.dark-mode .bg-primary-subtle,
        body.dark-mode .bg-warning-subtle,
        body.dark-mode .bg-secondary,
        body.dark-mode .bg-info,
        body.dark-mode .bg-success,
        body.dark-mode .bg-danger {
            background-color: #111827 !important;
            color: #e2e8f0 !important;
            border-color: rgba(148,163,184,.12) !important;
        }
        body.dark-mode .badge.bg-primary-subtle,
        body.dark-mode .badge.bg-warning-subtle,
        body.dark-mode .badge.bg-secondary,
        body.dark-mode .badge.bg-info,
        body.dark-mode .badge.bg-success,
        body.dark-mode .badge.bg-danger,
        body.dark-mode .badge.bg-light {
            background-color: rgba(148,163,184,.16) !important;
            color: #e2e8f0 !important;
        }
        body.dark-mode .alert.alert-info,
        body.dark-mode .alert.alert-warning,
        body.dark-mode .alert.alert-danger,
        body.dark-mode .alert.alert-success,
        body.dark-mode .alert.alert-secondary {
            background-color: #111827 !important;
            color: #e2e8f0 !important;
            border-color: rgba(148,163,184,.14) !important;
        }
        body.dark-mode .text-primary,
        body.dark-mode .text-warning,
        body.dark-mode .text-success,
        body.dark-mode .text-info,
        body.dark-mode .text-danger,
        body.dark-mode .text-secondary,
        body.dark-mode .text-muted {
            color: inherit !important;
        }
        body.dark-mode .badge {
            color: #e2e8f0 !important;
        }
        body.dark-mode .btn-outline-primary,
        body.dark-mode .btn-outline-success,
        body.dark-mode .btn-outline-warning,
        body.dark-mode .btn-outline-danger {
            color: #e2e8f0 !important;
        }
        body.dark-mode .table-responsive table {
            background: #0f172a !important;
        }
        body.dark-mode .table th,
        body.dark-mode .table td {
            background: #0f172a !important;
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
        .stat-card .stat-value { font-size: 1.9rem; font-weight: 700; color: inherit; }
        .stat-card .stat-label { font-size: .8rem; color: inherit; font-weight: 500; }
        .stat-card code { font-size: .85rem; }
        .fs-3rem { font-size: 3rem !important; }
        .fs-4rem { font-size: 4rem !important; }
        .fs-2-5 { font-size: 2.5rem !important; }
        .fs-18 { font-size: 1.8rem !important; }
        .fs-15 { font-size: 1.5rem !important; }
        .fs-115 { font-size: 1.15rem !important; }
        .fs-125 { font-size: 1.25rem !important; }
        .fs-105 { font-size: 1.05rem !important; }
        .fs-93 { font-size: .93rem !important; }
        .fs-92 { font-size: .92rem !important; }
        .fs-875 { font-size: .875rem !important; }
        .fs-85 { font-size: .85rem !important; }
        .fs-82 { font-size: .82rem !important; }
        .fs-8 { font-size: .8rem !important; }
        .fs-78 { font-size: .78rem !important; }
        .fs-75 { font-size: .75rem !important; }
        .fs-73 { font-size: .73rem !important; }
        .fs-72 { font-size: .72rem !important; }
        .fw-800 { font-weight: 800 !important; }
        .fw-700 { font-weight: 700 !important; }
        .fw-600 { font-weight: 600 !important; }
        .fw-500 { font-weight: 500 !important; }
        .lh-17 { line-height: 1.7 !important; }
        .lh-15 { line-height: 1.5 !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .bg-soft { background-color: #F0F9FF !important; }
        .text-secondary-soft { color: #64748B !important; }
        .text-muted-soft { color: #94A3B8 !important; }
        .btn-sm-xs { font-size: .75rem !important; }
        .img-cover-180,
        .img-cover-200,
        .img-cover-300 {
            width: 100%;
            object-fit: cover;
        }
        .img-cover-180 { height: 180px; }
        .img-cover-200 { height: 200px; }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0A6CFF, #0052CC);
        }
        .bg-gradient-primary-alt {
            background: linear-gradient(135deg, #0A6CFF, #06B6D4);
        }
        .card-hoverable {
            transition: transform .2s, box-shadow .2s;
            cursor: pointer;
        }
        .card-hoverable:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,.1);
        }
        .sticky-top-80 {
            position: sticky;
            top: 80px;
        }
        .min-h-46 { min-height: 46px; }
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
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="darkModeToggle" type="button" aria-label="Toggle dark mode">
                <i class="bi bi-moon-stars"></i>
            </button>
            <a href="<?= BASE_URL ?>/logout" class="btn-logout">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
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

function applyTheme(theme) {
    const isDark = theme === 'dark';
    document.body.classList.toggle('dark-mode', isDark);
    const icon = document.getElementById('darkModeToggle').querySelector('i');
    icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
}

function loadTheme() {
    const stored = localStorage.getItem('vyantravel_theme');
    const theme = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    applyTheme(theme);
}

const darkModeToggle = document.getElementById('darkModeToggle');
darkModeToggle.addEventListener('click', () => {
    const isDark = !document.body.classList.contains('dark-mode');
    applyTheme(isDark ? 'dark' : 'light');
    localStorage.setItem('vyantravel_theme', isDark ? 'dark' : 'light');
});

loadTheme();

// Auto-dismiss flash messages
setTimeout(() => {
    document.querySelectorAll('.flash-container .alert').forEach(el => {
        bootstrap.Alert.getOrCreateInstance(el).close();
    });
}, 4500);
</script>
</body>
</html>
