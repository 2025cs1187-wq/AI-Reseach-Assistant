<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

$patients_arr = mysqli_fetch_all(mysqli_query($conn, "SELECT patient_id, name, email, doctor_name FROM patient ORDER BY name"), MYSQLI_ASSOC);
$wards_arr    = mysqli_fetch_all(mysqli_query($conn, "SELECT ward_id, ward_no, ward_type FROM wards ORDER BY ward_no"), MYSQLI_ASSOC);
$nurses_arr   = mysqli_fetch_all(mysqli_query($conn, "SELECT nurse_id, name FROM nurse ORDER BY name"), MYSQLI_ASSOC);

if (isset($_POST['add_adm'])) {
    $patient_id     = (int)$_POST['patient_id'];
    $ward_id        = (int)$_POST['ward_id'];
    $nurse_id       = (int)$_POST['nurse_id'];
    $adm_date       = mysqli_real_escape_string($conn, $_POST['adm_date']);
    $discharge_date = mysqli_real_escape_string($conn, $_POST['discharge_date']);
    $reason         = mysqli_real_escape_string($conn, $_POST['reason']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);
    $referred_by    = mysqli_real_escape_string($conn, $_POST['referred_by']);

    $nurse_val     = $nurse_id > 0 ? $nurse_id : "NULL";
    $ward_val      = $ward_id > 0  ? $ward_id  : "NULL";
    $discharge_val = !empty($_POST['discharge_date']) ? "'$discharge_date'" : "NULL";

    $sql = "INSERT INTO admissions (patient_id, ward_id, nurse_id, adm_date, discharge_date, reason, status, referred_by)
            VALUES ($patient_id, $ward_val, $nurse_val, '$adm_date', $discharge_val, '$reason', '$status', '$referred_by')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_admission.php?msg=added");
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
            <h1><i class="fas fa-plus-circle"></i> Add Admission</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_admission.php">Admissions</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_admission.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-bed"></i> Admission Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient <span class="req">*</span></label>
                    <select name="patient_id" id="patient_select" class="form-control" required onchange="fillPatientInfo(this)">
                        <option value="">-- Select Patient --</option>
                        <?php foreach ($patients_arr as $p): ?>
                        <option value="<?= $p['patient_id'] ?>"
                                data-email="<?= htmlspecialchars($p['email'] ?? '') ?>"
                                data-doctor="<?= htmlspecialchars($p['doctor_name'] ?? '') ?>">
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
                    <label>Doctor</label>
                    <input type="text" id="show_doctor" class="form-control" readonly style="background:#f5f5f5;" placeholder="Auto filled">
                </div>

                <div class="form-group">
                    <label>Ward</label>
                    <select name="ward_id" class="form-control">
                        <option value="0">-- Select Ward --</option>
                        <?php foreach ($wards_arr as $w): ?>
                        <option value="<?= $w['ward_id'] ?>">Ward <?= htmlspecialchars($w['ward_no']) ?> - <?= htmlspecialchars($w['ward_type']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nurse</label>
                    <select name="nurse_id" class="form-control">
                        <option value="0">-- Select Nurse --</option>
                        <?php foreach ($nurses_arr as $n): ?>
                        <option value="<?= $n['nurse_id'] ?>"><?= htmlspecialchars($n['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Admission Date</label>
                    <input type="date" name="adm_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Discharge Date</label>
                    <input type="date" name="discharge_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <option value="Admitted">Admitted</option>
                        <option value="Discharged">Discharged</option>
                        <option value="Under Observation">Under Observation</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Referred By</label>
                    <input type="text" name="referred_by" class="form-control" placeholder="e.g. Dr. Ahmed">
                </div>

                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" placeholder="Reason for admission">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_adm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Admission
                </button>
                <a href="view_admission.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function fillPatientInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('show_email').value  = opt.dataset.email  || '';
    document.getElementById('show_doctor').value = opt.dataset.doctor || '';
}
</script>
<?php include '../includes/footer.php'; ?>