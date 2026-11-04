<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$id   = (int)($_GET['id'] ?? 0);
$msg  = $err = '';

$test = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lab_tests WHERE lab_test_id=$id"));
if (!$test) { header("Location: view_lab_test.php"); exit(); }

if (isset($_POST['update'])) {
    $patient_id   = (int)$_POST['patient_id'];
    $name         = mysqli_real_escape_string($conn, $_POST['name']);
    $result       = mysqli_real_escape_string($conn, $_POST['result']);
    $test_date    = mysqli_real_escape_string($conn, $_POST['test_date']);
    $status       = mysqli_real_escape_string($conn, $_POST['status']);
    $normal_range = mysqli_real_escape_string($conn, $_POST['normal_range']);
    $remarks      = mysqli_real_escape_string($conn, $_POST['remarks']);

    $sql = "UPDATE lab_tests SET
                patient_id=$patient_id, name='$name', result='$result',
                test_date='$test_date', status='$status',
                normal_range='$normal_range', remarks='$remarks'
            WHERE lab_test_id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_lab_test.php?msg=updated");
        exit();
    } else {
        $err = mysqli_error($conn);
    }
}

$patients = mysqli_query($conn, "SELECT patient_id, name, email FROM patient ORDER BY name");

include '../includes/lab_header.php';
?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Lab Test</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_lab_test.php">Lab Tests</a> &rsaquo; Edit
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
            <div class="card-title"><i class="fas fa-flask"></i> Edit Lab Test #<?= $id ?></div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">-- Select Patient --</option>
                        <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id']==$test['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['email']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Test Name</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($test['name']) ?>">
                </div>

                <div class="form-group">
                    <label>Test Date</label>
                    <input type="date" name="test_date" class="form-control"
                        value="<?= $test['test_date'] ? date('Y-m-d', strtotime($test['test_date'])) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <?php foreach(['Pending','Completed','Cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $test['status']==$s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Normal Range</label>
                    <input type="text" name="normal_range" class="form-control"
                        value="<?= htmlspecialchars($test['normal_range']) ?>">
                </div>

                <div class="form-group">
                    <label>Result</label>
                    <input type="text" name="result" class="form-control"
                        value="<?= htmlspecialchars($test['result']) ?>">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control"
                        value="<?= htmlspecialchars($test['remarks']) ?>">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Lab Test
                </button>
                <a href="view_lab_test.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>