<?php
// client/includes/client_header.php

if (!isset($_SESSION['client_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$patient_id   = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];
// Profile complete check
$p_check = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT phone FROM patient WHERE patient_id=$patient_id"));
if (empty($p_check['phone']) && basename($_SERVER['PHP_SELF']) !== 'complete_profile.php') {
    header("Location: complete_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'MedicaPro' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:        #0a1628;
            --navy-mid:    #0d1f3c;
            --navy-light:  #162848;
            --gold:        #c9a84c;
            --gold-light:  #e8c97a;
            --gold-pale:   #fdf6e3;
            --gold-border: rgba(201,168,76,0.25);
            --white:       #ffffff;
            --text-muted:  #8a9ab5;
            --bg:          #f0f4f8;
            --card-bg:     #ffffff;
            --border:      #e4eaf2;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--navy);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(201,168,76,0.15);
        }

        /* Gold top accent line */
        .sidebar::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        .sidebar-logo {
            padding: 22px 22px 18px;
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--gold-border);
            letter-spacing: 0.3px;
        }
        .sidebar-logo i {
            color: var(--gold);
            font-size: 1.3rem;
        }
        .sidebar-logo span { color: var(--gold); }

        .user-info {
            padding: 16px 22px;
            border-bottom: 1px solid var(--gold-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.2);
        }
        .user-info .uname {
            font-weight: 700;
            font-size: .88rem;
            color: var(--white);
            line-height: 1.3;
        }
        .user-info .urole {
            font-size: .73rem;
            color: var(--gold);
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .menu-label {
            padding: 18px 22px 7px;
            font-size: .68rem;
            font-weight: 700;
            color: rgba(201,168,76,0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-menu { flex: 1; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 22px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: .89rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar-menu a:hover {
            background: rgba(201,168,76,0.08);
            color: var(--gold-light);
            border-left-color: var(--gold);
        }
        .sidebar-menu a.active {
            background: rgba(201,168,76,0.12);
            color: var(--gold);
            font-weight: 700;
            border-left-color: var(--gold);
        }
        .sidebar-menu i {
            width: 18px;
            font-size: .92rem;
        }
        .sidebar-menu a.logout-link {
            color: #e57373;
            margin-top: 8px;
            border-top: 1px solid var(--gold-border);
            padding-top: 14px;
        }
        .sidebar-menu a.logout-link:hover {
            background: rgba(229,115,115,0.08);
            border-left-color: #e57373;
            color: #ef9a9a;
        }

        /* Hospital badge at bottom */
        .sidebar-bottom {
            padding: 16px 22px;
            border-top: 1px solid var(--gold-border);
            text-align: center;
        }
        .sidebar-bottom p {
            font-size: .7rem;
            color: rgba(201,168,76,0.4);
            letter-spacing: .5px;
        }

        /* ── MAIN CONTENT ── */
        .main {
            margin-left: 250px;
            padding: 28px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        /* ── TOP BAR ── */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--navy);
            color: var(--white);
            padding: 14px 24px;
            border-radius: 12px;
            margin-bottom: 28px;
            border: 1px solid var(--gold-border);
            box-shadow: 0 4px 20px rgba(10,22,40,0.12);
            position: relative;
            overflow: hidden;
        }
        /* Gold shimmer accent */
        .top-bar::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }
        .top-bar h2 {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: .3px;
        }
        .top-bar h2 i { color: var(--gold); }
        .top-bar .user-right {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .87rem;
            color: var(--text-muted);
        }
        .top-bar .user-right i { color: var(--gold); }
        .logout-btn {
            background: rgba(201,168,76,0.15);
            border: 1px solid var(--gold-border);
            color: var(--gold-light);
            padding: 6px 14px;
            border-radius: 6px;
            font-size: .82rem;
            text-decoration: none;
            font-weight: 600;
            transition: all .2s;
        }
        .logout-btn:hover {
            background: var(--gold);
            color: var(--navy);
        }

        /* ── SHARED CARD STYLES ── */
        .table-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(10,22,40,0.07);
            border: 1px solid var(--border);
        }
        .table-card h3 {
            color: var(--navy);
            margin-bottom: 18px;
            font-size: .97rem;
            font-weight: 700;
            border-bottom: 2px solid var(--gold-pale);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-card h3 i { color: var(--gold); }

        /* ── STATS ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 10px rgba(10,22,40,0.06);
            border: 1px solid var(--border);
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(10,22,40,0.10);
        }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-icon.gold   { background: var(--gold-pale);  color: var(--gold); }
        .stat-icon.navy   { background: rgba(10,22,40,.08); color: var(--navy); }
        .stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
        .stat-icon.blue   { background: #e3f2fd; color: #1565c0; }
        .stat-icon.red    { background: #fdecea; color: #c62828; }
        .stat-card h3 { font-size: 1.35rem; font-weight: 800; color: var(--navy); }
        .stat-card p  { font-size: .79rem; color: var(--text-muted); }

        /* ── TABLE ── */
        table { width:100%; border-collapse:collapse; }
        th {
            background: var(--navy);
            color: var(--gold);
            padding: 10px 14px;
            text-align: left;
            font-size: .81rem;
            font-weight: 700;
            letter-spacing: .4px;
        }
        th:first-child { border-radius: 8px 0 0 0; }
        th:last-child  { border-radius: 0 8px 0 0; }
        td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f4f8;
            font-size: .86rem;
            color: #444;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--gold-pale); }

        /* ── BADGES ── */
        .badge { padding:4px 10px; border-radius:20px; font-size:.75rem; font-weight:600; display:inline-block; }
        .badge.green  { background:#e8f5e9; color:#2e7d32; }
        .badge.orange { background:#fff3e0; color:#e65100; }
        .badge.red    { background:#fdecea; color:#c62828; }
        .badge.blue   { background:#e3f2fd; color:#1565c0; }
        .badge.gold   { background:var(--gold-pale); color:#a07000; }

        /* ── EMPTY STATE ── */
        .empty-state { text-align:center; padding:40px; color:#aaa; }
        .empty-state i { font-size:2.5rem; margin-bottom:10px; display:block; color:var(--gold); opacity:.5; }

        /* ── FORM / PROFILE ── */
        .profile-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(10,22,40,.07);
            border: 1px solid var(--border);
            max-width: 720px;
        }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-weight:600; font-size:.84rem; color:#555; margin-bottom:6px; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width:100%; padding:10px 14px;
            border:1.5px solid #dde4ef;
            border-radius:8px; font-size:.9rem;
            outline:none; transition:border .2s;
            font-family:'DM Sans',sans-serif;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.1);
        }
        .form-group input[readonly] { background:#f9f9f9; color:#888; cursor:not-allowed; }
        .form-group textarea { height:80px; resize:none; }
        .section-title {
            font-weight:700; color:var(--navy);
            font-size:.8rem; margin-bottom:14px;
            text-transform:uppercase; letter-spacing:.8px;
            display:flex; align-items:center; gap:8px;
        }
        .section-title::after {
            content:''; flex:1; height:1px;
            background:linear-gradient(90deg,var(--gold-border),transparent);
        }
        .divider { border:none; border-top:1px solid #eef1f7; margin:22px 0; }

        .btn-primary {
            background: var(--navy);
            color: var(--gold);
            border: 2px solid var(--navy);
            padding: 11px 28px;
            border-radius: 8px;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
            letter-spacing: .3px;
        }
        .btn-primary:hover {
            background: transparent;
            color: var(--navy);
        }

        /* Keep old class names working too */
        .btn-purple { background:var(--navy); color:var(--gold); border:2px solid var(--navy); padding:11px 28px; border-radius:8px; font-size:.92rem; font-weight:700; cursor:pointer; transition:all .2s; }
        .btn-purple:hover { background:transparent; color:var(--navy); }

        .alert-success {
            background:#e8f5e9; color:#2e7d32;
            border-left:4px solid #2e7d32;
            padding:11px 14px; border-radius:8px;
            font-size:.88rem; margin-bottom:18px;
            display:flex; align-items:center; gap:8px;
        }
        .profile-avatar {
            width:72px; height:72px; border-radius:50%;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            display:flex; align-items:center; justify-content:center;
            font-size:1.8rem; color:var(--navy);
            margin-bottom:20px; font-weight:800;
            box-shadow:0 4px 16px rgba(201,168,76,.3);
        }

        /* ── MOBILE SIDEBAR TOGGLE ── */
        #sidebarToggle {
            display: none;
            background: rgba(201,168,76,0.15);
            border: 1px solid var(--gold-border);
            color: var(--gold-light);
            width: 36px;
            height: 36px;
            border-radius: 7px;
            font-size: 1.05rem;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 99;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .3s ease;
                z-index: 200;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.35);
            }
            .main {
                margin-left: 0;
                width: 100%;
                padding: 18px;
            }
            #sidebarToggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .stats {
                grid-template-columns: repeat(2,1fr);
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr;
            }
            .top-bar .user-right span { display:none; }
        }
    </style>
</head>
<body>

<!-- ═══════════ SIDEBAR ═══════════ -->
<div class="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-hospital-alt"></i> Medica<span>Pro</span>
    </div>

    <div class="user-info">
        <div class="user-avatar"><?= strtoupper(substr($patient_name, 0, 1)) ?></div>
        <div>
            <div class="uname"><?= htmlspecialchars($patient_name) ?></div>
            <div class="urole">Patient</div>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-label">Navigation</div>

        <a href="dashboard.php" class="<?= ($active_page ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="my_appointments.php" class="<?= ($active_page ?? '') === 'appointments' ? 'active' : '' ?>">
            <i class="fas fa-calendar-check"></i> My Appointments
        </a>
        <a href="my_bills.php" class="<?= ($active_page ?? '') === 'bills' ? 'active' : '' ?>">
            <i class="fas fa-file-invoice-dollar"></i> My Bills
        </a>
        <a href="my_prescription.php" class="<?= ($active_page ?? '') === 'prescriptions' ? 'active' : '' ?>">
            <i class="fas fa-prescription-bottle-alt"></i> My Prescriptions
        </a>
        <a href="my_profile.php" class="<?= ($active_page ?? '') === 'profile' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> My Profile
        </a>

        <a href="../logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="sidebar-bottom">
        <p>MedicaPro &copy; <?= date('Y') ?> &nbsp;|&nbsp; Patient Portal</p>
    </div>
</div>

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('show')"></div>

<!-- ═══════════ MAIN ═══════════ -->
<div class="main">
    <div class="top-bar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarToggle" onclick="document.querySelector('.sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <h2>
                <i class="fas fa-<?= $top_bar_icon ?? 'th-large' ?>"></i>
                <?= $page_title ?? 'Dashboard' ?>
            </h2>
        </div>
        <div class="user-right">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($patient_name) ?>
            <a href="../logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>