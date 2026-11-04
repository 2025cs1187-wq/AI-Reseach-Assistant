<?php
require_once '../includes/auth_pharmacist.php';
require_once '../config/db.php';

$total_medicines     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines"))['total'];
$total_prescriptions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM prescriptions"))['total'];
$total_patients      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT patient_id) as total FROM prescriptions"))['total'];
$total_dispensed     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM prescriptions WHERE status='Dispensed'"))['total'];

// Total earnings - dispensed prescriptions ki medicines ki price
$earnings_result = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(m.price), 0) AS total_earnings
    FROM prescriptions p
    LEFT JOIN presc_med pm ON p.presc_id = pm.presc_id
    LEFT JOIN medicines m ON pm.medicine_id = m.medicine_id
    WHERE p.status = 'Dispensed'
"));
$total_earnings = $earnings_result['total_earnings'] ?? 0;

// Medicine wise stats
$medicine_stats = mysqli_query($conn, "
    SELECT m.ord_name AS name, m.price, m.stock, m.dosage_form,
           COUNT(CASE WHEN p.status = 'Dispensed' THEN 1 END) AS total_dispensed,
           COALESCE(SUM(CASE WHEN p.status = 'Dispensed' THEN m.price ELSE 0 END), 0) AS total_earned
    FROM medicines m
    LEFT JOIN presc_med pm ON m.medicine_id = pm.medicine_id
    LEFT JOIN prescriptions p ON pm.presc_id = p.presc_id
    GROUP BY m.medicine_id
    ORDER BY total_dispensed DESC
");

// Patient wise stats
$patient_stats = mysqli_query($conn, "
    SELECT pt.name AS patient_name, pt.email,
           COUNT(pr.presc_id) AS total_prescriptions,
           COUNT(CASE WHEN pr.status = 'Dispensed' THEN 1 END) AS total_dispensed,
           COALESCE(SUM(CASE WHEN pr.status = 'Dispensed' THEN m.price ELSE 0 END), 0) AS total_spent
    FROM patient pt
    LEFT JOIN prescriptions pr ON pt.patient_id = pr.patient_id
    LEFT JOIN presc_med pm ON pr.presc_id = pm.presc_id
    LEFT JOIN medicines m ON pm.medicine_id = m.medicine_id
    GROUP BY pt.patient_id
    ORDER BY total_dispensed DESC
");

include '../includes/pharmacist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-rupee-sign"></i> Earnings</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Earnings</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
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
            <div class="stat-icon" style="background:#e8f8f5;">
                <i class="fas fa-file-prescription" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_prescriptions ?></div>
                <div class="stat-label">Total Prescriptions</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff3e0;">
                <i class="fas fa-check-circle" style="color:#f39c12;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_dispensed ?></div>
                <div class="stat-label">Dispensed</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf0f8;">
                <i class="fas fa-rupee-sign" style="color:#9b59b6;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">Rs. <?= number_format($total_earnings, 0) ?></div>
                <div class="stat-label">Total Earnings</div>
            </div>
        </div>
    </div>

    <!-- Medicine wise Stats -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-pills"></i> Medicine wise Stats</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Times Dispensed</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($medicine_stats && mysqli_num_rows($medicine_stats) > 0):
                    $i = 1;
                    while ($r = mysqli_fetch_assoc($medicine_stats)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($r['name'] ?? '—') ?></strong></td>
                    <td><?= htmlspecialchars($r['dosage_form'] ?? '—') ?></td>
                    <td>Rs. <?= number_format($r['price'] ?? 0, 0) ?></td>
                    <td>
                        <?php if (($r['stock'] ?? 0) <= 10): ?>
                            <span class="badge badge-danger"><?= $r['stock'] ?> Low!</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $r['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $r['total_dispensed'] ?></td>
                    <td><strong style="color:#2ecc71;">Rs. <?= number_format($r['total_earned'], 0) ?></strong></td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-pills"></i>
                            <p>No medicines found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Patient wise Stats -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-users"></i> Patient wise Stats</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Total Prescriptions</th>
                        <th>Dispensed</th>
                        <th>Total Spent</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($patient_stats && mysqli_num_rows($patient_stats) > 0):
                    $i = 1;
                    while ($r = mysqli_fetch_assoc($patient_stats)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($r['patient_name'] ?? '—') ?></strong></td>
                    <td><?= htmlspecialchars($r['email'] ?? '—') ?></td>
                    <td><?= $r['total_prescriptions'] ?></td>
                    <td>
                        <span class="badge badge-success"><?= $r['total_dispensed'] ?></span>
                    </td>
                    <td><strong style="color:#9b59b6;">Rs. <?= number_format($r['total_spent'], 0) ?></strong></td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No patients found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>