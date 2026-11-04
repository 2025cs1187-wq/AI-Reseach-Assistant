<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$search_type = $_GET['search_type'] ?? '';
$search_val  = trim($_GET['search_val'] ?? '');

// Overall stats
$total_earning  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(b.total_amount),0) AS total FROM lab_tests lt LEFT JOIN bills b ON lt.bill_id = b.bill_id"))['total'];
$total_tests    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM lab_tests"))['total'];
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT patient_id) AS total FROM lab_tests"))['total'];

// Per test earnings
$test_earnings = mysqli_query($conn, "
    SELECT lt.name AS test_name,
           COUNT(*) AS total_count,
           SUM(CASE WHEN lt.status='Completed' THEN 1 ELSE 0 END) AS completed,
           SUM(CASE WHEN lt.status='Pending' THEN 1 ELSE 0 END) AS pending,
           COALESCE(SUM(b.total_amount), 0) AS earning
    FROM lab_tests lt
    LEFT JOIN bills b ON lt.bill_id = b.bill_id
    GROUP BY lt.name
    ORDER BY earning DESC
");

// Per patient earnings
$patient_earnings = mysqli_query($conn, "
    SELECT pt.name AS patient_name, pt.email,
           COUNT(*) AS total_tests,
           COALESCE(SUM(b.total_amount), 0) AS total_paid
    FROM lab_tests lt
    LEFT JOIN patient pt ON lt.patient_id = pt.patient_id
    LEFT JOIN bills b ON lt.bill_id = b.bill_id
    GROUP BY lt.patient_id
    ORDER BY total_paid DESC
");

include '../includes/lab_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-rupee-sign"></i> Earnings</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Earnings</p>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e5f5;">
                <i class="fas fa-rupee-sign" style="color:#9b59b6;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">Rs. <?= number_format($total_earning, 0) ?></div>
                <div class="stat-label">Total Earnings</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="fas fa-flask" style="color:#3498db;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_tests ?></div>
                <div class="stat-label">Total Tests</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f5;">
                <i class="fas fa-users" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_patients ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
        </div>
    </div>

    <!-- Per Test Earnings -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-flask"></i> Earnings by Test</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Test Name</th>
                        <th>Total Done</th>
                        <th>Completed</th>
                        <th>Pending</th>
                        <th>Total Earning</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; while ($r = mysqli_fetch_assoc($test_earnings)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($r['test_name'] ?? '—') ?></strong></td>
                    <td><?= $r['total_count'] ?></td>
                    <td><span class="badge badge-success"><?= $r['completed'] ?></span></td>
                    <td><span class="badge badge-warning"><?= $r['pending'] ?></span></td>
                    <td><strong style="color:#27ae60;">Rs. <?= number_format($r['earning'], 0) ?></strong></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Per Patient Earnings -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-users"></i> Earnings by Patient</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Total Tests</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; while ($r = mysqli_fetch_assoc($patient_earnings)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($r['patient_name'] ?? '—') ?></strong></td>
                    <td><?= htmlspecialchars($r['email'] ?? '—') ?></td>
                    <td><?= $r['total_tests'] ?></td>
                    <td><strong style="color:#27ae60;">Rs. <?= number_format($r['total_paid'], 0) ?></strong></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>