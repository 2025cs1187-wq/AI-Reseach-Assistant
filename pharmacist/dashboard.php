<?php
require_once '../includes/auth_pharmacist.php';
require_once '../config/db.php';

$total_medicines     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines"))['total'];
$low_stock           = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines WHERE stock <= 10"))['total'];
$total_prescriptions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM prescriptions"))['total'];

include '../includes/pharmacist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-pills"></i> Pharmacy Dashboard</h1>
            <p class="breadcrumb">Home &rsaquo; Dashboard</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="fas fa-pills" style="color:#3498db;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_medicines ?></div>
                <div class="stat-label">Total Medicines</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdecea;">
                <i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $low_stock ?></div>
                <div class="stat-label">Low Stock</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f5;">
                <i class="fas fa-file-prescription" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_prescriptions ?></div>
                <div class="stat-label">Total Prescriptions</div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>