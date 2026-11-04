<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$msg = $err = '';

$patients = mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name");
$bills    = mysqli_query($conn, "SELECT bill_id FROM bills ORDER BY bill_id DESC");

if (isset($_POST['add_lab'])) {
    $patient_id   = (int)$_POST['patient_id'];
    $bill_id      = (int)$_POST['bill_id'];
    $name         = mysqli_real_escape_string($conn, $_POST['name']);
    $result       = mysqli_real_escape_string($conn, $_POST['result']);
    $test_date    = mysqli_real_escape_string($conn, $_POST['test_date']);
    $status       = mysqli_real_escape_string($conn, $_POST['status']);
    $normal_range = mysqli_real_escape_string($conn, $_POST['normal_range']);
    $remarks      = mysqli_real_escape_string($conn, $_POST['remarks']);
    $bill_val     = $bill_id > 0 ? $bill_id : "NULL";

    $sql = "INSERT INTO lab_tests (patient_id, bill_id, name, result, test_date, status, normal_range, remarks)
            VALUES ($patient_id, $bill_val, '$name', '$result', '$test_date', '$status', '$normal_range', '$remarks')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_lab_test.php?msg=added");
        exit();
    } else {
        $err = mysqli_error($conn);
    }
}

include '../includes/lab_header.php';
?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-plus-circle"></i> Add Lab Test</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_lab_test.php">Lab Tests</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_lab_test.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-flask"></i> Lab Test Details</div>
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
                    <label>Bill</label>
                    <select name="bill_id" class="form-control">
                        <option value="0">-- Select Bill --</option>
                        <?php while ($b = mysqli_fetch_assoc($bills)): ?>
                        <option value="<?= $b['bill_id'] ?>">Bill #<?= $b['bill_id'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Test Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Blood Test">
                </div>

                <div class="form-group">
                    <label>Test Date</label>
                    <input type="date" name="test_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Normal Range</label>
                    <input type="text" name="normal_range" class="form-control" placeholder="e.g. 70-100 mg/dL">
                </div>

                <div class="form-group">
                    <label>Result</label>
                    <input type="text" name="result" class="form-control" placeholder="e.g. Normal">
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Additional remarks">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_lab" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Lab Test
                </button>
                <a href="view_lab_test.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>