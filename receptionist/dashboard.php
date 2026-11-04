<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$total_patients      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM patient"))['total'];
$total_appointments  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointment"))['total'];
$total_bills         = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bills"))['total'];
$total_doctors       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM doctor"))['total'];

// Recent appointments
$recent_appointments = mysqli_query($conn, "
    SELECT a.*, p.name AS patient_name, d.name AS doctor_name
    FROM appointment a
    LEFT JOIN patient p ON a.patient_id = p.patient_id
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    ORDER BY a.appointment_id DESC
    LIMIT 5
");

// Recent patients
$recent_patients = mysqli_query($conn, "
    SELECT * FROM patient
    ORDER BY patient_id DESC
    LIMIT 5
");

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-th-large"></i> Dashboard</h1>
            <p class="breadcrumb">Welcome, <?= htmlspecialchars($_SESSION['staff_name']) ?>!</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="fas fa-procedures" style="color:#3498db;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_patients ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f5;">
                <i class="fas fa-calendar-check" style="color:#2ecc71;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_appointments ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff3e0;">
                <i class="fas fa-file-invoice-dollar" style="color:#f39c12;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_bills ?></div>
                <div class="stat-label">Total Bills</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf0f8;">
                <i class="fas fa-user-md" style="color:#9b59b6;"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= $total_doctors ?></div>
                <div class="stat-label">Total Doctors</div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-calendar-check"></i> Recent Appointments</div>
            <a href="view_appointment.php" class="btn btn-sm">View All</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($recent_appointments && mysqli_num_rows($recent_appointments) > 0):
                    while ($r = mysqli_fetch_assoc($recent_appointments)): ?>
                <tr>
                    <td><?= $r['appointment_id'] ?></td>
                    <td><div class="cell-main"><?= htmlspecialchars($r['patient_name'] ?? '—') ?></div></td>
                    <td><?= htmlspecialchars($r['doctor_name'] ?? '—') ?></td>
                    <td><?= $r['date'] ? date('d-m-Y', strtotime($r['date'])) : '—' ?></td>
                    <td>
                        <span class="badge <?= ($r['status'] ?? '') == 'Confirmed' ? 'badge-success' : 'badge-warning' ?>">
                            <?= htmlspecialchars($r['status'] ?? 'Pending') ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-calendar"></i><p>No appointments found</p></div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-procedures"></i> Recent Patients</div>
            <a href="view_patient.php" class="btn btn-sm">View All</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($recent_patients && mysqli_num_rows($recent_patients) > 0):
                    while ($r = mysqli_fetch_assoc($recent_patients)): ?>
                <tr>
                    <td><?= $r['patient_id'] ?></td>
                    <td><div class="cell-main"><?= htmlspecialchars($r['name'] ?? '—') ?></div></td>
                    <td><?= htmlspecialchars($r['email'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="4"><div class="empty-state"><i class="fas fa-procedures"></i><p>No patients found</p></div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>