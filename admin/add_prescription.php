<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

$patients = mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name");
$doctors  = mysqli_query($conn, "SELECT doctor_id, name, email FROM doctor ORDER BY name");

if (isset($_POST['add_presc'])) {
    $patient_id   = (int)$_POST['patient_id'];
    $doctor_id    = (int)$_POST['doctor_id'];
    $dosage       = mysqli_real_escape_string($conn, $_POST['dosage']);
    $duration     = mysqli_real_escape_string($conn, $_POST['duration']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $date         = mysqli_real_escape_string($conn, $_POST['date']);
    $refill_count = (int)$_POST['refill_count'];

    $sql = "INSERT INTO prescriptions (patient_id, doctor_id, dosage, duration, instructions, date, refill_count)
            VALUES ($patient_id, $doctor_id, '$dosage', '$duration', '$instructions', '$date', $refill_count)";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_prescription.php?msg=added");
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
            <h1><i class="fas fa-plus-circle"></i> Add Prescription</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_prescription.php">Prescriptions</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_prescription.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-file-prescription"></i> Prescription Details</div>
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
                    <label>Doctor <span class="req">*</span></label>
                    <select name="doctor_id" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                        <option value="<?= $d['doctor_id'] ?>">
                            <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['email']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dosage</label>
                    <input type="text" name="dosage" class="form-control" placeholder="e.g. 500mg twice daily">
                </div>

                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" class="form-control" placeholder="e.g. 7 days">
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Refill Count</label>
                    <input type="number" name="refill_count" class="form-control" value="0">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Instructions</label>
                    <input type="text" name="instructions" class="form-control" placeholder="e.g. Take after meals">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_presc" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Prescription
                </button>
                <a href="view_prescription.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>