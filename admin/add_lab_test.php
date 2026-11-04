<?php
require_once __DIR__ . '/../includes/auth_admin.php';
require_once __DIR__ . '/../config/db.php';
$error = '';

// Patients aur Bills fetch karo
$patients = $conn->query("SELECT patient_id, name, phone FROM patient ORDER BY name");
$bills    = $conn->query("SELECT bill_id, patient_id, total_amount FROM bills ORDER BY bill_id DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id   = (int)($_POST['patient_id']   ?? 0);
    $bill_id      = (int)($_POST['bill_id']       ?? 0);
    $name         = trim($_POST['name']           ?? '');
    $result       = trim($_POST['result']         ?? '');
    $test_date    = $_POST['test_date']           ?? date('Y-m-d');
    $status       = $_POST['status']              ?? 'Pending';
    $normal_range = trim($_POST['normal_range']   ?? '');
    $remarks      = trim($_POST['remarks']        ?? '');

    if (!$patient_id || !$name) {
        $error = 'Patient and Test Name are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO lab_tests (name, result, test_date, status, normal_range, remarks, patient_id, bill_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssii', $name, $result, $test_date, $status, $normal_range, $remarks, $patient_id, $bill_id);
        if ($stmt->execute()) {
            header('Location: view_lab_test.php?msg=added');
            exit;
        } else {
            $error = 'Database error: ' . $conn->error;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="main-content">

    <div class="page-header">
        <div>
            <h1><i class="fas fa-flask"></i> Add Lab Test</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_lab_test.php">Lab Tests</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_lab_test.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-flask"></i> Lab Test Details</div>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-grid">

                    <!-- Row 1: Patient | Test Name | Test Date -->
                    <div class="form-group">
                        <label>Select Patient <span style="color:red">*</span></label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">-- Select Patient --</option>
                            <?php if ($patients): while ($p = $patients->fetch_assoc()): ?>
                            <option value="<?= $p['patient_id'] ?>"
                                <?= ($_POST['patient_id'] ?? '') == $p['patient_id'] ? 'selected' : '' ?>>
                                [<?= $p['patient_id'] ?>] <?= htmlspecialchars($p['name']) ?>
                                — <?= htmlspecialchars($p['phone'] ?? '') ?>
                            </option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Test Name <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               placeholder="e.g. CBC, MRI Brain, ECG">
                    </div>

                    <div class="form-group">
                        <label>Test Date</label>
                        <input type="date" name="test_date" class="form-control"
                               value="<?= htmlspecialchars($_POST['test_date'] ?? date('Y-m-d')) ?>">
                    </div>

                    <!-- Row 2: Status | Normal Range | Bill -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach (['Pending', 'Completed', 'Cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($_POST['status'] ?? 'Pending') === $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Normal Range</label>
                        <input type="text" name="normal_range" class="form-control"
                               value="<?= htmlspecialchars($_POST['normal_range'] ?? '') ?>"
                               placeholder="e.g. WBC 4000-11000">
                    </div>

                    <div class="form-group">
                        <label>Bill (Optional)</label>
                        <select name="bill_id" class="form-control">
                            <option value="0">-- No Bill --</option>
                            <?php if ($bills): while ($b = $bills->fetch_assoc()): ?>
                            <option value="<?= $b['bill_id'] ?>"
                                <?= ($_POST['bill_id'] ?? '') == $b['bill_id'] ? 'selected' : '' ?>>
                                Bill #<?= $b['bill_id'] ?> — Patient #<?= $b['patient_id'] ?>
                                (PKR <?= number_format($b['total_amount'], 0) ?>)
                            </option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>

                    <!-- Row 3: Result | Remarks -->
                    <div class="form-group">
                        <label>Result</label>
                        <input type="text" name="result" class="form-control"
                               value="<?= htmlspecialchars($_POST['result'] ?? '') ?>"
                               placeholder="e.g. WBC elevated, No lesions found">
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control"
                               value="<?= htmlspecialchars($_POST['remarks'] ?? '') ?>"
                               placeholder="e.g. Follow up needed">
                    </div>

                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Lab Test
                    </button>
                    <a href="view_lab_test.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>