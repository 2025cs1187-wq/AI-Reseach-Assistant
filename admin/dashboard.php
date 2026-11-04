<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

// Stats
$total_doctors      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM doctor"))['t'];
$total_patients     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM patient"))['t'];
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment"))['t'];
$total_bills        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM bills"))['t'];
$total_nurses       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM nurse"))['t'];
$total_wards        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM wards"))['t'];
$today = date('Y-m-d');
$pending_count   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment WHERE status='Pending'"))['t'];
$scheduled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment WHERE status='Scheduled'"))['t'];
$cancelled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment WHERE status='Cancelled'"))['t'];
$completed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment WHERE status='Completed'"))['t'];
// Today's appointments
$today = date('Y-m-d');
$today_appts = mysqli_fetch_all(mysqli_query($conn,
    "SELECT a.*, p.name as patient_name, d.name as doctor_name
     FROM appointment a
     LEFT JOIN patient p ON a.patient_id = p.patient_id
     LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
     WHERE DATE(a.date_time) = '$today'
     ORDER BY a.date_time DESC"), MYSQLI_ASSOC);

// Recent patients
$recent_patients = mysqli_fetch_all(mysqli_query($conn,
    "SELECT * FROM patient ORDER BY patient_id DESC LIMIT 5"), MYSQLI_ASSOC);

include '../includes/header.php';
?>

<style>
/* ---- Dashboard responsive grids ---- */
.status-strip {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
    margin-bottom:24px;
}
.stats-grid-4 {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
}
.stats-grid-3 {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-top:-8px;
}
.bottom-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
    margin-top:8px;
}
.page-header-row {
    display:flex;
    gap:10px;
}

@media (max-width: 900px) {
    .status-strip,
    .stats-grid-4 {
        grid-template-columns:repeat(2,1fr);
    }
    .stats-grid-3 {
        grid-template-columns:repeat(2,1fr);
    }
    .bottom-grid {
        grid-template-columns:1fr;
    }
}

@media (max-width: 480px) {
    .status-strip,
    .stats-grid-4,
    .stats-grid-3 {
        grid-template-columns:1fr;
    }
    .page-header {
        flex-direction:column;
        align-items:flex-start;
        gap:12px;
    }
    .page-header-row {
        width:100%;
    }
    .page-header-row a {
        flex:1;
        justify-content:center;
    }
    table {
        font-size:.78rem;
    }
}
</style>

<div class="main-content">
<!-- APPOINTMENT STATUS STRIP -->
<div class="status-strip">
    
    <a href="view_appointment.php?filter=Pending" style="text-decoration:none;">
        <div class="stat-card" style="border-left:4px solid #f59e0b;">
            <div class="stat-icon" style="background:#fff8e1;color:#a07000;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?= $pending_count ?></h3>
                <p>Pending Requests</p>
            </div>
        </div>
    </a>

    <a href="view_appointment.php?filter=Scheduled" style="text-decoration:none;">
        <div class="stat-card" style="border-left:4px solid #3b82f6;">
            <div class="stat-icon" style="background:#e3f2fd;color:#1565c0;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $scheduled_count ?></h3>
                <p>Scheduled</p>
            </div>
        </div>
    </a>

    <a href="view_appointment.php?filter=Completed" style="text-decoration:none;">
        <div class="stat-card" style="border-left:4px solid #22c55e;">
            <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $completed_count ?></h3>
                <p>Completed</p>
            </div>
        </div>
    </a>

    <a href="view_appointment.php?filter=Cancelled" style="text-decoration:none;">
        <div class="stat-card" style="border-left:4px solid #ef4444;">
            <div class="stat-icon" style="background:#fdecea;color:#c62828;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $cancelled_count ?></h3>
                <p>Cancelled</p>
            </div>
        </div>
    </a>

</div>
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-th-large"></i> Dashboard</h1>
            <p class="breadcrumb">Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>!</p>
        </div>
        <div class="page-header-row">
            <a href="add_patient.php" class="btn btn-outline btn-sm">
                <i class="fas fa-user-plus"></i> Add Patient
            </a>
            <a href="add_appointment.php" class="btn btn-primary btn-sm">
                <i class="fas fa-calendar-plus"></i> New Appointment
            </a>
        </div>
    </div>

    <!-- STATS ROW 1 -->
    <div class="stats-grid-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f2fd;color:#1565c0;">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_doctors ?></h3>
                <p>Total Doctors</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;">
                <i class="fas fa-procedures"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_patients ?></h3>
                <p>Total Patients</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--gold-pale);color:#a07000;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_appointments ?></h3>
                <p>Appointments</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdecea;color:#c62828;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_bills ?></h3>
                <p>Total Bills</p>
            </div>
        </div>
    </div>

    <!-- STATS ROW 2 -->
    <div class="stats-grid-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e5f5;color:#7b1fa2;">
                <i class="fas fa-user-nurse"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_nurses ?></h3>
                <p>Nurses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5e9;color:#388e3c;">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_wards ?></h3>
                <p>Wards</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f2fd;color:#1976d2;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($today_appts) ?></h3>
                <p>Today's Appointments</p>
            </div>
        </div>
    </div>

    <!-- BOTTOM GRID -->
    <div class="bottom-grid">

        <!-- Today's Appointments -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-calendar-day"></i> Today's Appointments
                </div>
                <a href="view_appointment.php" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div style="padding:0;overflow-x:auto;">
                <?php if (empty($today_appts)): ?>
                <div class="empty-state" style="padding:30px;">
                    <i class="fas fa-calendar-times"></i>
                    No appointments today
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($today_appts as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['patient_name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($a['doctor_name'] ?? '—') ?></td>
                            <td><?= date('h:i A', strtotime($a['date_time'])) ?></td>
                            <td>
                                <?php
                                $s = $a['status'] ?? 'Scheduled';
                                $cls = match($s) {
                                    'Completed' => 'badge-green',
                                    'Cancelled' => 'badge-red',
                                    'No Show'   => 'badge-grey',
                                    default     => 'badge-blue'
                                };
                                ?>
                                <span class="badge <?= $cls ?>"><?= $s ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-user-plus"></i> Recent Patients
                </div>
                <a href="view_patient.php" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div style="padding:0;overflow-x:auto;">
                <?php if (empty($recent_patients)): ?>
                <div class="empty-state" style="padding:30px;">
                    <i class="fas fa-user-slash"></i>
                    No patients yet
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Blood</th>
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_patients as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['phone'] ?? '—') ?></td>
                            <td>
                                <span class="badge badge-red"><?= htmlspecialchars($p['blood_group'] ?? '—') ?></span>
                            </td>
                            <td><?= htmlspecialchars($p['gender'] ?? '—') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>