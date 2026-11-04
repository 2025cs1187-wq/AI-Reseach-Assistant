<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$total_tests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lab_tests"))['total'];
$pending     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lab_tests WHERE status='Pending'"))['total'];
$completed   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lab_tests WHERE status='Completed'"))['total'];

include '../includes/lab_header.php';
?>

<style>
:root {
    --navy:      #0a1628;
    --gold:      #c9a84c;
    --gold-light:#e8c97a;
    --gold-pale: #fdf6e3;
}

.main-content .stats-grid {
    display:grid !important;
    grid-template-columns:repeat(3,1fr) !important;
    gap:16px !important;
}

.main-content .stats-grid .stat-card {
    background:#fff !important;
    border:1px solid #eee !important;
    border-left:4px solid var(--gold) !important;
    border-radius:10px !important;
    padding:18px !important;
    display:flex !important;
    align-items:center !important;
    gap:14px !important;
    box-shadow:0 2px 10px rgba(0,0,0,0.04) !important;
}

.main-content .stats-grid .stat-icon {
    width:48px !important;
    height:48px !important;
    border-radius:10px !important;
    display:flex !important;
    align-items:center !important;
    justify-content:center !important;
    font-size:1.2rem !important;
    background:var(--gold-pale) !important;
}
.main-content .stats-grid .stat-icon i {
    color:var(--gold) !important;
}

.main-content .stat-number {
    font-family:'Playfair Display',serif !important;
    font-size:1.6rem !important;
    font-weight:700 !important;
    color:var(--navy) !important;
    line-height:1.1 !important;
}

.main-content .stat-label {
    font-size:.8rem !important;
    color:#94a3b8 !important;
    margin-top:2px !important;
}

.page-header h1 {
    color:var(--navy) !important;
}
.page-header h1 i {
    color:var(--gold) !important;
    margin-right:6px !important;
}
.breadcrumb {
    color:#94a3b8 !important;
    font-size:.85rem !important;
}

@media (max-width: 768px) {
    .main-content .stats-grid {
        grid-template-columns:repeat(2,1fr) !important;
    }
}

@media (max-width: 480px) {
    .main-content .stats-grid {
        grid-template-columns:1fr !important;
    }
    .main-content .stats-grid .stat-card {
        padding:14px !important;
    }
    .main-content .stat-number {
        font-size:1.4rem !important;
    }
}
</style>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-flask"></i> Lab Dashboard</h1>
            <p class="breadcrumb">Home &rsaquo; Dashboard</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_tests ?></div>
                <div class="stat-label">Total Tests</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $pending ?></div>
                <div class="stat-label">Pending Tests</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $completed ?></div>
                <div class="stat-label">Completed Tests</div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>