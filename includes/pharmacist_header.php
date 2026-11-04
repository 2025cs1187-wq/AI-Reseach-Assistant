<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/hospital_management/assets/css/style.css">
    <script src="/hospital_management/assets/js/main.js" defer></script>
</head>
<body>

<div class="topbar">
    <div style="display:flex;align-items:center;gap:12px;">
        <button id="sidebarToggle"
            style="background:rgba(255,255,255,0.12);border:none;color:#fff;width:36px;height:36px;border-radius:8px;cursor:pointer;font-size:1.1rem;"
            onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>
        <a href="/hospital_management/pharmacist/dashboard.php" class="logo">
            <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
            <span class="logo-text">Medica<span>Pro</span></span>
        </a>
    </div>
    <div class="topbar-right">
        <div class="admin-name">
            <i class="fas fa-pills"></i>
            <?php echo isset($_SESSION['staff_name']) ? htmlspecialchars($_SESSION['staff_name']) : 'Pharmacist'; ?>
        </div>
        <a href="/hospital_management/logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="sidebar" id="sidebar">
    <p class="nav-section-title">Main</p>
    <a href="/hospital_management/pharmacist/dashboard.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="fas fa-th-large"></i> Dashboard
    </a>

    <p class="nav-section-title">Pharmacy</p>
    <a href="/hospital_management/pharmacist/view_medicine.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_medicine.php'?'active':'' ?>">
        <i class="fas fa-pills"></i> Medicines
    </a>
    <a href="/hospital_management/pharmacist/view_prescription.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_prescription.php'?'active':'' ?>">
        <i class="fas fa-file-prescription"></i> Prescriptions
    </a>

    <p class="nav-section-title">Patients</p>
    <a href="/hospital_management/pharmacist/view_patient.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='view_patient.php'?'active':'' ?>">
        <i class="fas fa-procedures"></i> Patients
    </a>

    <p class="nav-section-title">Tools</p>
    <a href="/hospital_management/pharmacist/search.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='search.php'?'active':'' ?>">
        <i class="fas fa-search"></i> Search
    </a>
    <a href="/hospital_management/pharmacist/earnings.php"
       class="nav-item <?= basename($_SERVER['PHP_SELF'])=='earnings.php'?'active':'' ?>">
        <i class="fas fa-rupee-sign"></i> Earnings
    </a>
</div>