<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$msg = $err = '';

$patients = mysqli_query($conn, "SELECT patient_id, name FROM patient ORDER BY name");
$doctors  = mysqli_query($conn, "SELECT doctor_id, name, consultation_fee FROM doctor ORDER BY name");

if (isset($_POST['add_bill'])) {
    $patient_id       = (int)$_POST['patient_id'];
    $amount           = (float)$_POST['amount'];
    $discount         = (float)$_POST['discount'];
    $insurance_amount = (float)$_POST['insurance_amount'];
    $total_amount     = (float)$_POST['total_amount'];
    $pay_status       = mysqli_real_escape_string($conn, trim($_POST['pay_status']));
    $bill_date        = mysqli_real_escape_string($conn, trim($_POST['bill_date']));
    $payment_method   = mysqli_real_escape_string($conn, trim($_POST['payment_method']));

    $sql = "INSERT INTO bills
                (patient_id, amount, discount, insurance_amount, total_amount, pay_status, bill_date, payment_method)
            VALUES
                ($patient_id, $amount, $discount, $insurance_amount, $total_amount,
                 '$pay_status', '$bill_date', '$payment_method')";

    mysqli_query($conn, $sql)
        ? header("Location: view_bill.php?msg=added")
        : $err = mysqli_error($conn);
}

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-file-invoice-dollar"></i> Generate Bill</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_bill.php">Bills</a> &rsaquo;
                Generate
            </p>
        </div>
        <a href="view_bill.php" class="btn btn-outline">
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
                <i class="fas fa-file-invoice-dollar"></i> Bill Details
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
                        <label>Doctor (Fee Reference)</label>
                        <select class="form-control" id="doc_select">
                            <option value="">-- Select Doctor --</option>
                            <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                                <option value="<?= $d['doctor_id'] ?>"
                                        data-fee="<?= $d['consultation_fee'] ?>">
                                    <?= htmlspecialchars($d['name']) ?>
                                    (Rs. <?= number_format($d['consultation_fee'], 0) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bill Date <span style="color:red;">*</span></label>
                        <input type="date" name="bill_date" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Amount (Rs.) <span style="color:red;">*</span></label>
                        <input type="number" step="0.01" name="amount"
                               class="form-control" placeholder="e.g. 5000"
                               required id="amount">
                    </div>

                    <div class="form-group">
                        <label>Discount (Rs.)</label>
                        <input type="number" step="0.01" name="discount"
                               class="form-control" value="0" id="discount">
                    </div>

                    <div class="form-group">
                        <label>Insurance Amount (Rs.)</label>
                        <input type="number" step="0.01" name="insurance_amount"
                               class="form-control" value="0" id="insurance">
                    </div>

                    <div class="form-group">
                        <label>Total Amount (Rs.)</label>
                        <input type="number" step="0.01" name="total_amount"
                               class="form-control" id="total"
                               readonly style="background:#f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label>Payment Method <span style="color:red;">*</span></label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">-- Select Method --</option>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Payment Status <span style="color:red;">*</span></label>
                        <select name="pay_status" class="form-control" required>
                            <option value="">-- Select Status --</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Partial">Partial</option>
                            <option value="Insurance">Insurance</option>
                        </select>
                    </div>

                </div>

                <div style="display:flex;gap:12px;margin-top:1.5rem;">
                    <button type="submit" name="add_bill"
                            style="padding:12px 28px;background:linear-gradient(135deg,#4a0080,#6a0dad);color:#fff;border:none;border-radius:9px;font-size:1rem;font-weight:700;cursor:pointer;">
                        <i class="fas fa-save"></i> Generate Bill
                    </button>
                    <a href="view_bill.php"
                       style="padding:12px 28px;background:#f0f0f0;color:#555;border-radius:9px;font-size:1rem;font-weight:700;text-decoration:none;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('doc_select').addEventListener('change', function() {
    const fee = this.options[this.selectedIndex].getAttribute('data-fee');
    if (fee) {
        document.getElementById('amount').value = parseFloat(fee).toFixed(2);
        calcTotal();
    }
});
function calcTotal() {
    const amount    = parseFloat(document.getElementById('amount').value)    || 0;
    const discount  = parseFloat(document.getElementById('discount').value)  || 0;
    const insurance = parseFloat(document.getElementById('insurance').value) || 0;
    document.getElementById('total').value = (amount - discount - insurance).toFixed(2);
}
document.getElementById('amount').addEventListener('input', calcTotal);
document.getElementById('discount').addEventListener('input', calcTotal);
document.getElementById('insurance').addEventListener('input', calcTotal);
</script>

<?php include '../includes/footer.php'; ?>