<?php
require_once '../includes/auth_pharmacist.php';
require_once '../config/db.php';

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'dispensed') $msg = "Medicine dispensed successfully!";

// Dispense button click hone par
if (isset($_GET['dispense'])) {
    $presc_id = (int)$_GET['dispense'];

    // Prescription ka data lo
    $presc = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT pm.medicine_id
        FROM presc_med pm
        WHERE pm.presc_id = $presc_id
    "));

    if ($presc) {
        // Stock kam karo
        mysqli_query($conn, "
            UPDATE medicines
            SET stock = stock - 1
            WHERE medicine_id = {$presc['medicine_id']}
            AND stock > 0
        ");
    }

    // Status update karo
    mysqli_query($conn, "
        UPDATE prescriptions
        SET status = 'Dispensed'
        WHERE presc_id = $presc_id
    ");

    header("Location: view_prescription.php?msg=dispensed");
    exit();
}

$prescriptions = mysqli_query($conn, "
    SELECT p.*, pt.name AS patient_name, pt.email AS patient_email,
           d.name AS doctor_name,
           m.ord_name AS medicine_name,
           m.stock AS medicine_stock
    FROM prescriptions p
    LEFT JOIN patient pt ON p.patient_id = pt.patient_id
    LEFT JOIN doctor d ON p.doctor_id = d.doctor_id
    LEFT JOIN presc_med pm ON p.presc_id = pm.presc_id
    LEFT JOIN medicines m ON pm.medicine_id = m.medicine_id
    ORDER BY p.presc_id DESC
");

if (!$prescriptions) {
    die("<b>Query Error:</b> " . mysqli_error($conn));
}

include '../includes/pharmacist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-file-prescription"></i> Prescriptions</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Prescriptions</p>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $msg ?>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <?php
        $total    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM prescriptions"))['t'];
        $pending  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM prescriptions WHERE status='Pending'"))['t'];
        $dispensed= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM prescriptions WHERE status='Dispensed'"))['t'];
        ?>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="fas fa-file-prescription" style="color:#3498db;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total ?></div>
                <div class="stat-label">Total Prescriptions</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff3e0;">
                <i class="fas fa-clock" style="color:#f39c12;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $pending ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f5;">
                <i class="fas fa-check-circle" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $dispensed ?></div>
                <div class="stat-label">Dispensed</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Prescriptions</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Patient Email</th>
                        <th>Doctor</th>
                        <th>Medicine</th>
                        <th>Stock</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Date</th>
                        <th>Refill Count</th>
                        <th>Instructions</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($prescriptions) > 0):
                    while ($r = mysqli_fetch_assoc($prescriptions)): ?>
                <tr>
                    <td><?= $r['presc_id'] ?></td>
                    <td><div class="cell-main"><?= htmlspecialchars($r['patient_name'] ?? '—') ?></div></td>
                    <td><?= htmlspecialchars($r['patient_email'] ?? '—') ?></td>
                    <td><div class="cell-main"><?= htmlspecialchars($r['doctor_name'] ?? '—') ?></div></td>
                    <td><?= htmlspecialchars($r['medicine_name'] ?? '—') ?></td>
                    <td>
                        <?php if (($r['medicine_stock'] ?? 0) <= 10): ?>
                            <span class="badge badge-danger"><?= $r['medicine_stock'] ?? 0 ?> Low!</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $r['medicine_stock'] ?? '—' ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($r['dosage'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['duration'] ?? '—') ?></td>
                    <td><?= $r['date'] ? date('d-m-Y', strtotime($r['date'])) : '—' ?></td>
                    <td><?= $r['refill_count'] ?? '0' ?></td>
                    <td><?= htmlspecialchars($r['instructions'] ?? '—') ?></td>
                    <td>
                        <?php if ($r['status'] === 'Dispensed'): ?>
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Dispensed
                            </span>
                        <?php else: ?>
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($r['status'] === 'Pending'): ?>
                            <a href="?dispense=<?= $r['presc_id'] ?>"
                               class="btn btn-sm"
                               style="background:#2ecc71;color:#fff;padding:5px 12px;border-radius:7px;text-decoration:none;font-size:.82rem;font-weight:600;"
                               onclick="return confirm('Dispense medicine for <?= htmlspecialchars($r['patient_name'] ?? '') ?>?')">
                                <i class="fas fa-pills"></i> Dispense
                            </a>
                        <?php else: ?>
                            <span style="color:#aaa;font-size:.82rem;">
                                <i class="fas fa-check"></i> Done
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <i class="fas fa-file-prescription"></i>
                            <p>No prescriptions found</p>
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