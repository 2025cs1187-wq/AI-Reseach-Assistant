<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicaPro — Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="/hospital_management/assets/js/main.js" defer></script>
    <style>
        :root {
            --navy:       #0a1628;
            --navy-mid:   #0d1f3c;
            --navy-light: #162848;
            --gold:       #c9a84c;
            --gold-light: #e8c97a;
            --gold-pale:  #fdf6e3;
            --gold-border:rgba(201,168,76,0.2);
            --sidebar-w:  250px;
            --topbar-h:   58px;
            --bg:         #f0f4f8;
            --white:      #ffffff;
            --border:     #e2e8f0;
            --text:       #1e293b;
            --muted:      #8a9ab5;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg); min-height:100vh; }

        /* ══════════ TOPBAR ══════════ */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 200;
            border-bottom: 2px solid var(--gold-border);
        }
        .topbar::after {
            content:'';
            position:absolute;
            bottom:0; left:0; right:0;
            height:2px;
            background:linear-gradient(90deg,transparent,var(--gold),transparent);
        }
        .topbar-left { display:flex; align-items:center; gap:12px; }

        /* Mobile toggle */
        #sidebarToggle {
            display:none;
            background:rgba(255,255,255,0.08);
            border:1px solid var(--gold-border);
            color:var(--gold);
            width:36px; height:36px;
            border-radius:8px; cursor:pointer;
            font-size:1rem;
        }

        .logo {
            display:flex; align-items:center; gap:10px;
            text-decoration:none;
        }
        .logo-icon {
            width:36px; height:36px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:9px;
            display:flex; align-items:center; justify-content:center;
            font-size:.95rem; color:var(--navy);
        }
        .logo-text {
            font-family:'Playfair Display',serif;
            font-size:1.15rem; color:#fff; letter-spacing:.3px;
        }
        .logo-text span { color:var(--gold); }

        .topbar-right { display:flex; align-items:center; gap:14px; }
        .admin-name {
            display:flex; align-items:center; gap:8px;
            color:rgba(255,255,255,0.7); font-size:.85rem;
        }
        .admin-name i { color:var(--gold); }
        .btn-logout {
            background:rgba(201,168,76,0.12);
            border:1px solid var(--gold-border);
            color:var(--gold-light);
            padding:7px 16px; border-radius:7px;
            font-size:.82rem; font-weight:600;
            text-decoration:none; transition:all .2s;
            display:flex; align-items:center; gap:7px;
        }
        .btn-logout:hover { background:var(--gold); color:var(--navy); }

        /* ══════════ SIDEBAR ══════════ */
        .sidebar {
            position: fixed;
            top: var(--topbar-h); left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--topbar-h));
            background: var(--navy);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            padding-bottom: 24px;
            border-right: 1px solid var(--gold-border);
            scrollbar-width: thin;
            scrollbar-color: rgba(201,168,76,0.2) transparent;
        }
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-thumb { background:rgba(201,168,76,0.2); border-radius:4px; }

        .nav-section-title {
            padding: 18px 20px 6px;
            font-size: .67rem;
            font-weight: 700;
            color: rgba(201,168,76,0.45);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: .87rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
        }
        .nav-item i { width:16px; font-size:.88rem; flex-shrink:0; }
        .nav-item:hover {
            background: rgba(201,168,76,0.07);
            color: var(--gold-light);
            border-left-color: var(--gold);
        }
        .nav-item.active {
            background: rgba(201,168,76,0.12);
            color: var(--gold);
            font-weight: 700;
            border-left-color: var(--gold);
        }

        /* ══════════ MAIN CONTENT ══════════ */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 28px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ══════════ PAGE HEADER ══════════ */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 26px;
        }
        .page-header h1 {
            font-family:'Playfair Display',serif;
            font-size: 1.5rem;
            color: var(--navy);
            display: flex; align-items: center; gap: 10px;
        }
        .page-header h1 i { color:var(--gold); font-size:1.2rem; }
        .breadcrumb { font-size:.82rem; color:var(--muted); margin-top:4px; }
        .breadcrumb a { color:var(--navy); text-decoration:none; font-weight:600; }
        .breadcrumb a:hover { color:var(--gold); }

        /* ══════════ STATS GRID ══════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 14px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(10,22,40,0.07);
            border: 1px solid var(--border);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content:'';
            position:absolute;
            top:0; left:0; right:0;
            height:3px;
            background:linear-gradient(90deg,var(--gold),var(--gold-light));
            opacity:0;
            transition:opacity .2s;
        }
        .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(10,22,40,.10); }
        .stat-card:hover::after { opacity:1; }
        .stat-icon {
            width:52px; height:52px;
            border-radius:13px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.3rem; flex-shrink:0;
        }
        .stat-info h3 { font-size:1.6rem; font-weight:800; color:var(--navy); line-height:1; }
        .stat-info p  { font-size:.78rem; color:var(--muted); margin-top:3px; }

        /* ══════════ CARDS ══════════ */
        .card {
            background: var(--white);
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(10,22,40,0.07);
            border: 1px solid var(--border);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-weight: 700;
            color: var(--navy);
            font-size: .92rem;
            display: flex; align-items: center; gap: 8px;
        }
        .card-title i { color:var(--gold); }
        .card form, .card > div:not(.card-header) { padding: 22px; }

        /* ══════════ FORM ══════════ */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group label {
            font-size:.8rem; font-weight:700;
            color:#475569; text-transform:uppercase; letter-spacing:.5px;
        }
        .req { color:#e53935; }
        .form-control {
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: .91rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--navy);
            outline: none;
            transition: border .2s, box-shadow .2s;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
        }
        .form-control[readonly] { background:#f8fafc; color:#94a3b8; }
        .form-actions {
            display: flex; gap: 12px;
            padding: 18px 22px;
            border-top: 1px solid var(--border);
            background: #fafbfc;
        }

        /* ══════════ BUTTONS ══════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px; border-radius: 8px;
            font-size: .88rem; font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer; transition: all .2s;
            text-decoration: none; border: 2px solid transparent;
        }
        .btn-primary {
            background: var(--navy); color: var(--gold);
            border-color: var(--navy);
        }
        .btn-primary:hover { background:var(--gold); border-color:var(--gold); color:var(--navy); }
        .btn-outline {
            background: transparent; color: var(--navy);
            border-color: var(--border);
        }
        .btn-outline:hover { border-color:var(--navy); }
        .btn-danger {
            background:#fff0f0; color:#c62828;
            border-color:#fca5a5;
        }
        .btn-danger:hover { background:#c62828; color:#fff; border-color:#c62828; }
        .btn-sm { padding:6px 14px; font-size:.8rem; }

        /* ══════════ TABLE ══════════ */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead th {
            background: var(--navy);
            color: var(--gold);
            padding: 11px 14px;
            text-align: left;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        thead th:first-child { border-radius:8px 0 0 0; }
        thead th:last-child  { border-radius:0 8px 0 0; }
        tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f0f4f8;
            font-size: .87rem;
            color: var(--text);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover td { background:var(--gold-pale); }

        /* ══════════ BADGES ══════════ */
        .badge {
            padding: 4px 10px; border-radius: 20px;
            font-size: .73rem; font-weight: 700;
            display: inline-block; white-space:nowrap;
        }
        .badge-green  { background:#e8f5e9; color:#2e7d32; }
        .badge-red    { background:#fdecea; color:#c62828; }
        .badge-orange { background:#fff3e0; color:#e65100; }
        .badge-blue   { background:#e3f2fd; color:#1565c0; }
        .badge-gold   { background:var(--gold-pale); color:#a07000; }
        .badge-grey   { background:#f1f5f9; color:#64748b; }

        /* ══════════ ALERTS ══════════ */
        .alert {
            padding: 12px 16px; border-radius: 9px;
            font-size: .88rem; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background:#f0fdf4; color:#2e7d32; border-left:3px solid #2e7d32; }
        .alert-danger  { background:#fff0f0; color:#c62828; border-left:3px solid #c62828; }
        .alert-info    { background:var(--gold-pale); color:#a07000; border-left:3px solid var(--gold); }

        /* ══════════ SEARCH BAR ══════════ */
        .search-bar {
            display: flex; gap: 10px; align-items: center;
            margin-bottom: 18px; flex-wrap: wrap;
        }
        .search-bar input {
            padding: 9px 14px 9px 36px;
            border: 1.5px solid var(--border);
            border-radius: 9px; font-size: .88rem;
            font-family: 'DM Sans', sans-serif;
            outline: none; width: 260px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%238a9ab5' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 11px center;
            transition: border .2s;
        }
        .search-bar input:focus { border-color:var(--gold); }

        /* ══════════ EMPTY STATE ══════════ */
        .empty-state {
            text-align:center; padding:50px 20px; color:var(--muted);
        }
        .empty-state i { font-size:2.8rem; color:var(--gold); opacity:.4; display:block; margin-bottom:12px; }

        /* ══════════ RESPONSIVE ══════════ */
        @media(max-width:900px){
            #sidebarToggle { display:flex !important; align-items:center; justify-content:center; }
            .sidebar { left:-var(--sidebar-w); transform:translateX(-100%); transition:transform .3s; }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-left:0; }
            .stats-grid { grid-template-columns:1fr 1fr; }
            .form-grid { grid-template-columns:1fr; }
        }
        .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}
    </style>
</head>
<body>

<!-- ══════════ TOPBAR ══════════ -->
<div class="topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>
        <a href="/hospital_management/admin/dashboard.php" class="logo">
            <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
            <span class="logo-text">Medica<span>Pro</span></span>
        </a>
    </div>
    <div class="topbar-right">
        <div class="admin-name">
            <i class="fas fa-user-shield"></i>
            <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?>
        </div>
        <a href="/hospital_management/logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<!-- ══════════ SIDEBAR ══════════ -->
<div class="sidebar" id="sidebar">
    <p class="nav-section-title">Main</p>
    <a href="/hospital_management/admin/dashboard.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="fas fa-th-large"></i> Dashboard
    </a>

    <p class="nav-section-title">People</p>
    <a href="/hospital_management/admin/view_doctor.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_doctor.php'?'active':'' ?>">
        <i class="fas fa-user-md"></i> Doctors
    </a>
    <a href="/hospital_management/admin/view_patient.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_patient.php'?'active':'' ?>">
        <i class="fas fa-procedures"></i> Patients
    </a>
    <a href="/hospital_management/admin/view_nurse.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_nurse.php'?'active':'' ?>">
        <i class="fas fa-user-nurse"></i> Nurses
    </a>
    <a href="/hospital_management/admin/view_staff.php"
       class="nav-item <?= in_array(basename($_SERVER['PHP_SELF']),['view_staff.php','add_staff.php','edit_staff.php'])?'active':'' ?>">
        <i class="fas fa-users-cog"></i> Staff
    </a>

    <p class="nav-section-title">Clinical</p>
    <a href="/hospital_management/admin/view_appointment.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_appointment.php'?'active':'' ?>">
        <i class="fas fa-calendar-check"></i> Appointments
    </a>
    <a href="/hospital_management/admin/view_prescription.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_prescription.php'?'active':'' ?>">
        <i class="fas fa-file-prescription"></i> Prescriptions
    </a>
    <a href="/hospital_management/admin/view_medical_record.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_medical_record.php'?'active':'' ?>">
        <i class="fas fa-notes-medical"></i> Medical Records
    </a>
    <a href="/hospital_management/admin/view_lab_test.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_lab_test.php'?'active':'' ?>">
        <i class="fas fa-flask"></i> Lab Tests
    </a>

    <p class="nav-section-title">Hospital</p>
    <a href="/hospital_management/admin/view_admission.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_admission.php'?'active':'' ?>">
        <i class="fas fa-bed"></i> Admissions
    </a>
    <a href="/hospital_management/admin/view_ward.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_ward.php'?'active':'' ?>">
        <i class="fas fa-building"></i> Wards
    </a>
    <a href="/hospital_management/admin/view_department.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_department.php'?'active':'' ?>">
        <i class="fas fa-sitemap"></i> Departments
    </a>

    <p class="nav-section-title">Finance</p>
    <a href="/hospital_management/admin/view_bill.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_bill.php'?'active':'' ?>">
        <i class="fas fa-file-invoice-dollar"></i> Bills
    </a>
    <a href="/hospital_management/admin/view_medicine.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_medicine.php'?'active':'' ?>">
        <i class="fas fa-pills"></i> Medicines
    </a>
    <a href="/hospital_management/admin/search.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='search.php'?'active':'' ?>">
        <i class="fas fa-search"></i> Search
    </a>
</div>