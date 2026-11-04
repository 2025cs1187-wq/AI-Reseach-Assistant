<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$msg = $err = '';

// Filters
$from_date  = $_GET['from_date'] ?? date('Y-m-01'); // Month start
$to_date    = $_GET['to_date']   ?? date('Y-m-d');  // Today
$doctor_id  = (int)($_GET['doctor_id'] ?? 0);

$doctors = mysqli_query($conn, "SELECT doctor_id, name, specialization, consultation_fee FROM doctor ORDER BY name");

// Build query
$where = "WHERE DATE(a.date_time) BETWEEN '$from_date' AND '$to_date'";
if ($doctor_id > 0) {
    $where .= " AND a.doctor_id = $doctor_id";
}

$earnings = mysqli_query($conn, "
    SELECT 
        d.doctor_id,
        d.name AS doctor_name,
        d.specialization,
        d.consultation_fee,
        COUNT(a.app_id) AS total_appointments,
        (COUNT(a.app_id) * d.consultation_fee) AS total_earnings
    FROM appointments a
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    $where
    GROUP BY d.doctor_id, d.name, d.specialization, d.consultation_fee
    ORDER BY total_earnings DESC
");

// Total summary
$summary = mysqli_query($conn, "
    SELECT 
        COUNT(a.app_id) AS grand_appointments,
        SUM(d.consultation_fee) AS grand_earnings
    FROM appointments a
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    $where
");
$summary_row = mysqli_fetch_assoc($summary);

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> Doctor Earnings</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Earnings</p>
        </div>
    </div>

    <!-- FILTER FORM -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-filter"></i> Filter Earnings</div>
        </div>
        <div style="padding:1.5rem;">
            <form method="GET">
                <div class="form-grid">

                    <div class="form-group">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control"
                               value="<?= $from_date ?>">
                    </div>

                    <div class="form-group">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control"
                               value="<?= $to_date ?>">
                    </div>

                    <div class="form-group">
                        <label>Doctor (Optional)</label>
                        <select name="doctor_id" class="form-control">
                            <option value="0">-- All Doctors --</option>
                            <?php
                            mysqli_data_seek($doctors, 0);
                            while ($d = mysqli_fetch_assoc($doctors)): ?>
                                <option value="<?= $d['doctor_id'] ?>"
                                    <?= $doctor_id == $d['doctor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['name']) ?>
                                    (<?= htmlspecialchars($d['specialization']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Show Earnings
                    </button>
                    <a href="earnings.php" class="btn btn-outline">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="fas fa-calendar-check" style="color:#3498db;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $summary_row['grand_appointments'] ?? 0 ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f0;">
                <i class="fas fa-money-bill-wave" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">
                    Rs. <?= number_format($summary_row['grand_earnings'] ?? 0, 0) ?>
                </div>
                <div class="stat-label">Total Earnings</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff3e0;">
                <i class="fas fa-calendar-alt" style="color:#f39c12;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= date('d M Y', strtotime($from_date)) ?></div>
                <div class="stat-label">From Date</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf0f8;">
                <i class="fas fa-calendar-alt" style="color:#9b59b6;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= date('d M Y', strtotime($to_date)) ?></div>
                <div class="stat-label">To Date</div>
            </div>
        </div>
    </div>

    <!-- EARNINGS TABLE -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-list"></i> Earnings Report
                (<?= date('d M Y', strtotime($from_date)) ?> —
                 <?= date('d M Y', strtotime($to_date)) ?>)
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor Name</th>
                        <th>Specialization</th>
                        <th>Consultation Fee</th>
                        <th>Total Appointments</th>
                        <th>Total Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($earnings) > 0):
                        $i = 1; while ($row = mysqli_fetch_assoc($earnings)): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <div style="font-weight:600;">
                                <?= htmlspecialchars($row['doctor_name']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['specialization']) ?></td>
                        <td>Rs. <?= number_format($row['consultation_fee'], 0) ?></td>
                        <td>
                            <span class="badge badge-success">
                                <?= $row['total_appointments'] ?> appointments
                            </span>
                        </td>
                        <td>
                            <strong style="color:#6a0dad; font-size:1.1rem;">
                                Rs. <?= number_format($row['total_earnings'], 0) ?>
                            </strong>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#999;">
                            <i class="fas fa-chart-line" style="font-size:2rem;"></i>
                            <p>No earnings data found for selected period</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (mysqli_num_rows($earnings) > 0): ?>
                <tfoot>
                    <tr style="background:#f5f0ff;font-weight:700;">
                        <td colspan="4" style="text-align:right;padding:12px;">
                            <strong>TOTAL:</strong>
                        </td>
                        <td>
                            <span class="badge badge-success">
                                <?= $summary_row['grand_appointments'] ?? 0 ?> appointments
                            </span>
                        </td>
                        <td style="color:#6a0dad;font-size:1.1rem;">
                            Rs. <?= number_format($summary_row['grand_earnings'] ?? 0, 0) ?>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>