<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id  = (int)($_GET['id'] ?? 0);
$msg = $err = '';

$appt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM appointment WHERE app_id=$id"));
if (!$appt) { header("Location: view_appointment.php"); exit(); }

if (isset($_POST['update'])) {
    $date_time      = mysqli_real_escape_string($conn, $_POST['date_time']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);
    $doctor_id      = (int)$_POST['doctor_id'];
    $patient_id     = (int)$_POST['patient_id'];
    $reason         = mysqli_real_escape_string($conn, $_POST['reason']);
    $notes          = mysqli_real_escape_string($conn, $_POST['notes']);
    $follow_up_date = !empty($_POST['follow_up_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['follow_up_date']) . "'" : "NULL";

    $sql = "UPDATE appointment SET
                date_time='$date_time', status='$status',
                doctor_id=$doctor_id, patient_id=$patient_id,
                reason='$reason', notes='$notes',
                follow_up_date=$follow_up_date
            WHERE app_id=$id";

    if (mysqli_query($conn, $sql)) {
        $msg  = "Appointment updated successfully!";
        $appt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM appointment WHERE app_id=$id"));
    } else {
        $err = mysqli_error($conn);
    }
}

$doctors  = mysqli_fetch_all(mysqli_query($conn, "SELECT doctor_id, name, specialization FROM doctor ORDER BY name"), MYSQLI_ASSOC);
$patients = mysqli_fetch_all(mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name"), MYSQLI_ASSOC);

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Appointment</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> ›
                <a href="view_appointment.php">Appointments</a> › Edit
            </p>
        </div>
        <a href="view_appointment.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-calendar-edit"></i> Edit Appointment #<?= $id ?></div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient <span class="req">*</span></label>
                    <select name="patient_id" id="patient_select" class="form-control" required onchange="fillPatientEmail(this)">
                        <option value="">-- Select Patient --</option>
                        <?php foreach ($patients as $p): ?>
                        <option value="<?= $p['patient_id'] ?>"
                                data-email="<?= htmlspecialchars($p['email'] ?? '') ?>"
                                <?= $appt['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['email']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Patient Email</label>
                    <input type="text" id="show_email" class="form-control" readonly style="background:#f5f5f5;">
                </div>

                <div class="form-group">
                    <label>Doctor <span class="req">*</span></label>
                    <select name="doctor_id" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        <?php foreach ($doctors as $d): ?>
                        <option value="<?= $d['doctor_id'] ?>"
                                <?= $appt['doctor_id'] == $d['doctor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['specialization']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="date_time" class="form-control"
                        value="<?= $appt['date_time'] ? date('Y-m-d\TH:i', strtotime($appt['date_time'])) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <?php foreach(['Scheduled','Completed','Cancelled','No Show'] as $s): ?>
                        <option value="<?= $s ?>" <?= $appt['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control"
                        value="<?= htmlspecialchars($appt['reason'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Follow Up Date</label>
                    <input type="date" name="follow_up_date" class="form-control"
                        value="<?= $appt['follow_up_date'] ? date('Y-m-d', strtotime($appt['follow_up_date'])) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control"
                        value="<?= htmlspecialchars($appt['notes'] ?? '') ?>">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Appointment
                </button>
                <a href="view_appointment.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function fillPatientEmail(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('show_email').value = opt.dataset.email || '';
}
</script>

<?php include '../includes/footer.php'; ?>