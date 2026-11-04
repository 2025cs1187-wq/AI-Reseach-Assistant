<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id   = (int)$_GET['id'];
$msg  = $err = '';
$bill = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bills WHERE bill_id=$id"));
if (!$bill) { header("Location: bills.php"); exit(); }

if (isset($_POST['update'])) {
    $pat_id           = (int)$_POST['patient_id'];
    $amount           = (float)$_POST['amount'];
    $discount         = (float)$_POST['discount'];
    $insurance_amount = (float)$_POST['insurance_amount'];
    $total_amount     = $amount - $discount - $insurance_amount;
    $pay_status       = mysqli_real_escape_string($conn, $_POST['pay_status']);
    $payment_method   = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $bill_date        = mysqli_real_escape_string($conn, $_POST['bill_date']);

    // SP sirf pay_status aur payment_method update karta hai
    // Baaki fields direct update karen
    $stmt = $conn->prepare("CALL sp_UpdateBillStatus(?, ?, ?)");
    $stmt->bind_param('iss', $id, $pay_status, $payment_method);

    if ($stmt->execute()) {
        // Baaki fields update karo (amount, discount, etc.)
        $sql = "UPDATE bills SET
                    patient_id=$pat_id, amount=$amount, discount=$discount,
                    insurance_amount=$insurance_amount, total_amount=$total_amount,
                    bill_date='$bill_date'
                WHERE bill_id=$id";
        mysqli_query($conn, $sql);
        $msg = "Bill updated successfully!";
        $bill = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bills WHERE bill_id=$id"));
    } else {
        $err = $conn->error;
    }
}

$patients = mysqli_query($conn, "SELECT patient_id, name, phone FROM patient ORDER BY name");
include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Bill</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; <a href="bills.php">Bills</a> &rsaquo; Edit</p>
        </div>
        <a href="bills.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div><?php endif; ?>
    <?php if ($err): ?><div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-file-invoice-dollar"></i> Edit Bill #<?= $id ?></div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Patient <span class="req">*</span></label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">-- Select Patient --</option>
                        <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $bill['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['phone'] ?? '') ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount (Rs.)</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                           value="<?= $bill['amount'] ?>" oninput="calcTotal()">
                </div>

                <div class="form-group">
                    <label>Discount (Rs.)</label>
                    <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                           value="<?= $bill['discount'] ?>" oninput="calcTotal()">
                </div>

                <div class="form-group">
                    <label>Insurance Amount (Rs.)</label>
                    <input type="number" name="insurance_amount" id="insurance_amount" class="form-control" step="0.01"
                           value="<?= $bill['insurance_amount'] ?>" oninput="calcTotal()">
                </div>

                <div class="form-group">
                    <label>Total Amount (Rs.)</label>
                    <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01"
                           value="<?= $bill['total_amount'] ?>" readonly style="background:#f5f5f5;">
                </div>

                <div class="form-group">
                    <label>Payment Status</label>
                    <select name="pay_status" class="form-control">
                        <?php foreach (['Paid', 'Unpaid', 'Partial'] as $s): ?>
                        <option value="<?= $s ?>" <?= $bill['pay_status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <?php foreach (['Cash', 'Card', 'Bank Transfer', 'Pending'] as $m): ?>
                        <option value="<?= $m ?>" <?= $bill['payment_method'] == $m ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bill Date</label>
                    <input type="date" name="bill_date" class="form-control" value="<?= $bill['bill_date'] ?>">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="bills.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function calcTotal() {
    const amount    = parseFloat(document.getElementById('amount').value)           || 0;
    const discount  = parseFloat(document.getElementById('discount').value)         || 0;
    const insurance = parseFloat(document.getElementById('insurance_amount').value) || 0;
    document.getElementById('total_amount').value = (amount - discount - insurance).toFixed(2);
}
</script>

<?php include '../includes/footer.php'; ?>
