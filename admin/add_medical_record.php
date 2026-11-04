<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

$patients = mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name");

if (isset($_POST['add_record'])) {
    $patient_id   = (int)$_POST['patient_id'];
    $date         = mysqli_real_escape_string($conn, $_POST['date']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $record_type  = mysqli_real_escape_string($conn, $_POST['record_type']);
    $doctor_notes = mysqli_real_escape_string($conn, $_POST['doctor_notes']);

    $sql = "INSERT INTO medical_records (patient_id, date, description, record_type, doctor_notes)
            VALUES ($patient_id, '$date', '$description', '$record_type', '$doctor_notes')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_medical_record.php?msg=added");
        exit();
    } else {
        $err = mysqli_error($conn);
    }
}

include '../includes/header.php';
?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-plus-circle"></i> Add Medical Record</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_medical_record.php">Medical Records</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_medical_record.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-file-medical"></i> Record Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient <span class="req">*</span></label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">-- Select Patient --</option>
                        <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?= $p['patient_id'] ?>">
                            <?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['email']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Record Type</label>
                    <select name="record_type" class="form-control">
                        <option value="">-- Select Type --</option>
                        <option value="Diagnosis">Diagnosis</option>
                        <option value="Lab Report">Lab Report</option>
                        <option value="Surgery">Surgery</option>
                        <option value="Checkup">Checkup</option>
                        <option value="Vaccination">Vaccination</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Brief description">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Doctor Notes</label>
                    <input type="text" name="doctor_notes" class="form-control" placeholder="Doctor's notes">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_record" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Record
                </button>
                <a href="view_medical_record.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>