<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$msg = $err = '';

$doctors  = mysqli_query($conn, "SELECT doctor_id, name, specialization, consultation_fee FROM doctor ORDER BY name");
$patients = mysqli_query($conn, "SELECT patient_id, name FROM patient ORDER BY name");

if (isset($_POST['add_app'])) {
    $doctor_id  = (int)$_POST['doctor_id'];
    $patient_id = (int)$_POST['patient_id'];
    $date_time  = mysqli_real_escape_string($conn, trim($_POST['date_time']));
    $status     = mysqli_real_escape_string($conn, trim($_POST['status']));
    $reason     = mysqli_real_escape_string($conn, trim($_POST['reason']));
    $notes      = mysqli_real_escape_string($conn, trim($_POST['notes']));
    $follow_up  = mysqli_real_escape_string($conn, trim($_POST['follow_up_date']));

    $sql = "INSERT INTO appointments (doctor_id, patient_id, date_time, status, reason, notes, follow_up_date)
            VALUES ($doctor_id, $patient_id, '$date_time', '$status', '$reason', '$notes', '$follow_up')";

    mysqli_query($conn, $sql)
        ? header("Location: view_appointment.php?msg=added")
        : $err = mysqli_error($conn);
}

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-calendar-plus"></i> Book Appointment</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_appointment.php">Appointments</a> &rsaquo;
                Book
            </p>
        </div>
        <a href="view_appointment.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger">
            <i class="fas fa-times-circle"></i> <?= $err ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-calendar-check"></i> Appointment Details
            </div>
        </div>
        <div style="padding:1.5rem;">
            <form method="POST">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Patient <span style="color:red;">*</span></label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">-- Select Patient --</option>
                            <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                                <option value="<?= $p['patient_id'] ?>">
                                    <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor <span style="color:red;">*</span></label>
                        <select name="doctor_id" class="form-control" required id="doctor_select">
                            <option value="">-- Select Doctor --</option>
                            <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                                <option value="<?= $d['doctor_id'] ?>"
                                        data-fee="<?= $d['consultation_fee'] ?>"
                                        data-spec="<?= htmlspecialchars($d['specialization']) ?>">
                                    <?= htmlspecialchars($d['name']) ?>
                                    — <?= htmlspecialchars($d['specialization']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Consultation Fee</label>
                        <input type="text" id="fee_display" class="form-control"
                               readonly placeholder="Select doctor to see fee"
                               style="background:#f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label>Date & Time <span style="color:red;">*</span></label>
                        <input type="datetime-local" name="date_time"
                               class="form-control" required>
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
                        <input type="text" name="reason" class="form-control"
                               placeholder="e.g. Fever, Checkup">
                    </div>

                    <div class="form-group">
                        <label>Follow Up Date</label>
                        <input type="date" name="follow_up_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" name="notes" class="form-control"
                               placeholder="Any additional notes">
                    </div>

                </div>

                <div style="display:flex;gap:12px;margin-top:1.5rem;">
                    <button type="submit" name="add_app"
                            style="padding:12px 28px;background:linear-gradient(135deg,#4a0080,#6a0dad);color:#fff;border:none;border-radius:9px;font-size:1rem;font-weight:700;cursor:pointer;">
                        <i class="fas fa-save"></i> Book Appointment
                    </button>
                    <a href="view_appointment.php"
                       style="padding:12px 28px;background:#f0f0f0;color:#555;border-radius:9px;font-size:1rem;font-weight:700;text-decoration:none;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('doctor_select').addEventListener('change', function() {
    const fee = this.options[this.selectedIndex].getAttribute('data-fee');
    document.getElementById('fee_display').value = fee ? 'Rs. ' + parseFloat(fee).toLocaleString() : '';
});
</script>

<?php include '../includes/footer.php'; ?>