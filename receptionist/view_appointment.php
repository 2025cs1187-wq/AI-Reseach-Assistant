<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$msg = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'added') {
    $msg = "Appointment added successfully!";
}
if (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
    $msg = "Appointment updated successfully!";
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $msg = "Appointment deleted.";
}

$appointments = mysqli_query($conn, "
    SELECT a.*, pt.name AS patient_name, pt.email AS patient_email,
           d.name AS doctor_name, d.specialization
    FROM appointment a
    LEFT JOIN patient pt ON a.patient_id = pt.patient_id
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    ORDER BY a.app_id ASC
");

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-calendar-check"></i> Appointments</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Appointments</p>
        </div>
        <a href="add_appointment.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Appointment
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Appointments</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($appointments) > 0):
                    while ($r = mysqli_fetch_assoc($appointments)): ?>
                    <tr>
                        <td><?= $r['app_id'] ?></td>
                        <td><div class="cell-main"><?= htmlspecialchars($r['patient_name']) ?></div></td>
                        <td><div class="cell-main"><?= htmlspecialchars($r['doctor_name']) ?></div></td>
                        <td><?= htmlspecialchars($r['specialization'] ?? '—') ?></td>
                        <td><?= $r['date_time'] ? date('d-m-Y h:i A', strtotime($r['date_time'])) : '—' ?></td>
                        <td>
                            <?php
                            $sc = ['Scheduled'=>'badge-info','Completed'=>'badge-success','Cancelled'=>'badge-danger','No Show'=>'badge-warning'];
                            $c  = $sc[$r['status']] ?? 'badge-purple';
                            ?>
                            <span class="badge <?= $c ?>"><?= htmlspecialchars($r['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['reason'] ?? '—') ?></td>
                        <td>
                            <a href="edit_appointment.php?id=<?= $r['app_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_appointment.php?id=<?= $r['app_id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this appointment?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-calendar-check"></i>
                                <p>No appointments found</p>
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