<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

if (isset($_POST['add_app'])) {
    $patient_id = (int)$_POST['patient_id'];
    $doctor_id  = (int)$_POST['doctor_id'];
    $date_time  = $_POST['date_time'];
    $reason     = mysqli_real_escape_string($conn, $_POST['reason']);
    $notes      = mysqli_real_escape_string($conn, $_POST['notes']);

    $stmt = $conn->prepare("CALL sp_BookAppointment(?, ?, ?, ?, ?)");
    $stmt->bind_param('iisss', $doctor_id, $patient_id, $date_time, $reason, $notes);

    if ($stmt->execute()) {
        $stmt->close();
        $msg = "Appointment added successfully!";
    } else {
        $err = $conn->error;
        $stmt->close();
    }
}

$patients_arr = mysqli_fetch_all(mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name"), MYSQLI_ASSOC);
$doctors_arr  = mysqli_fetch_all(mysqli_query($conn, "SELECT doctor_id, name, specialization FROM doctor ORDER BY name"), MYSQLI_ASSOC);

include '../includes/header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-plus-circle"></i> Add Appointment</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> ›
                <a href="view_appointment.php">Appointments</a> › Add
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
            <div class="card-title"><i class="fas fa-calendar-plus"></i> Appointment Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient <span class="req">*</span></label>
                    <select name="patient_id" id="patient_select" class="form-control" required onchange="fillPatientEmail(this)">
                        <option value="">-- Select Patient --</option>
                        <?php foreach ($patients_arr as $p): ?>
                        <option value="<?= $p['patient_id'] ?>" data-email="<?= htmlspecialchars($p['email'] ?? '') ?>">
                            <?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['email']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Patient Email</label>
                    <input type="text" id="show_email" class="form-control" readonly style="background:#f5f5f5;" placeholder="Auto filled">
                </div>

                <div class="form-group">
                    <label>Doctor <span class="req">*</span></label>
                    <select name="doctor_id" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        <?php foreach ($doctors_arr as $d): ?>
                        <option value="<?= $d['doctor_id'] ?>">
                            <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['specialization']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date & Time <span class="req">*</span></label>
                    <input type="datetime-local" name="date_time" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="No Show">No Show</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" placeholder="Reason for appointment">
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control" placeholder="Additional notes">
                </div>

                <div class="form-group">
                    <label>Follow Up Date</label>
                    <input type="date" name="follow_up_date" class="form-control">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_app" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Appointment
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