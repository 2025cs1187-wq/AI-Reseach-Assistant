<?php
require_once __DIR__ . '/../includes/auth_admin.php';
require_once __DIR__ . '/../config/db.php';
$pageTitle = 'Add Bill';
$error = '';

$patients = $conn->query("SELECT patient_id, name, phone, email FROM patient ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id       = (int)($_POST['patient_id'] ?? 0);
    $amount           = floatval($_POST['amount'] ?? 0);
    $discount         = floatval($_POST['discount'] ?? 0);
    $insurance_amount = floatval($_POST['insurance_amount'] ?? 0);
    $bill_date        = $_POST['bill_date'] ?? date('Y-m-d');
    $pay_status       = $_POST['pay_status'] ?? 'Unpaid';
    $payment_method   = trim($_POST['payment_method'] ?? '');

    if (!$patient_id || !$amount) {
        $error = 'Patient and Amount are required.';
    } else {
        $stmt = $conn->prepare("CALL sp_AddBill(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iddddss', $patient_id, $amount, $discount, $insurance_amount, $bill_date, $pay_status, $payment_method);
        if ($stmt->execute()) {
            header('Location: view_bill.php?msg=added');
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
            <h1><i class="fas fa-file-invoice-dollar"></i> Add Bill</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_bill.php">Bills</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_bill.php" class="btn btn-outline">
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
            <div class="card-title"><i class="fas fa-plus"></i> Bill Information</div>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Select Patient <span style="color:red">*</span></label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">-- Select Patient --</option>
                            <?php if ($patients): while ($p = $patients->fetch_assoc()): ?>
                            <option value="<?= $p['patient_id'] ?>"
                                <?= ($_POST['patient_id'] ?? '') == $p['patient_id'] ? 'selected' : '' ?>>
                                [<?= $p['patient_id'] ?>] <?= htmlspecialchars($p['name']) ?>
                                — <?= htmlspecialchars($p['phone']) ?>
                                <?php if ($p['email']): ?> | <?= htmlspecialchars($p['email']) ?><?php endif; ?>
                            </option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Amount (PKR) <span style="color:red">*</span></label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                               value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>"
                               oninput="calcTotal()" required>
                    </div>

                    <div class="form-group">
                        <label>Discount (PKR)</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                               value="<?= htmlspecialchars($_POST['discount'] ?? '0') ?>"
                               oninput="calcTotal()">
                    </div>

                    <div class="form-group">
                        <label>Insurance Amount (PKR)</label>
                        <input type="number" step="0.01" name="insurance_amount" id="insurance_amount" class="form-control"
                               value="<?= htmlspecialchars($_POST['insurance_amount'] ?? '0') ?>"
                               oninput="calcTotal()">
                    </div>

                    <div class="form-group">
                        <label>Total Amount (PKR)</label>
                        <input type="number" step="0.01" id="preview_total" class="form-control"
                               readonly style="background:#f5f5f5;" placeholder="Auto calculated">
                    </div>

                    <div class="form-group">
                        <label>Bill Date</label>
                        <input type="date" name="bill_date" class="form-control"
                               value="<?= htmlspecialchars($_POST['bill_date'] ?? date('Y-m-d')) ?>">
                    </div>

                    <div class="form-group">
                        <label>Payment Status</label>
                        <select name="pay_status" class="form-control">
                            <?php foreach (['Unpaid', 'Partial', 'Paid'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($_POST['pay_status'] ?? 'Unpaid') === $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <?php foreach (['Cash', 'Card', 'Pending'] as $m): ?>
                            <option value="<?= $m ?>" <?= ($_POST['payment_method'] ?? '') === $m ? 'selected' : '' ?>>
                                <?= $m ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Bill
                    </button>
                    <a href="view_bill.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function calcTotal() {
    const amount    = parseFloat(document.getElementById('amount').value)           || 0;
    const discount  = parseFloat(document.getElementById('discount').value)         || 0;
    const insurance = parseFloat(document.getElementById('insurance_amount').value) || 0;
    document.getElementById('preview_total').value = (amount - discount - insurance).toFixed(2);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>