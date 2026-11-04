<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

if (isset($_POST['add_med'])) {
    $name        = mysqli_real_escape_string($conn, trim($_POST['ord_name']));
    $type        = mysqli_real_escape_string($conn, trim($_POST['type']));
    $company     = mysqli_real_escape_string($conn, trim($_POST['company']));
    $stock       = (int)$_POST['stock'];
    $price       = (float)$_POST['price'];
    $expiry      = mysqli_real_escape_string($conn, trim($_POST['expiry_date']));
    $dosage_form = mysqli_real_escape_string($conn, trim($_POST['dosage_form']));

    $sql = "INSERT INTO medicines (ord_name, type, company, stock, price, expiry_date, dosage_form)
            VALUES ('$name','$type','$company',$stock,$price,'$expiry','$dosage_form')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_medicine.php?msg=added");
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
            <h1><i class="fas fa-plus-circle"></i> Add Medicine</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_medicine.php">Medicines</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_medicine.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-pills"></i> Medicine Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Medicine Name <span class="req">*</span></label>
                    <input type="text" name="ord_name" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="">-- Select Type --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Capsule">Capsule</option>
                        <option value="Syrup">Syrup</option>
                        <option value="Injection">Injection</option>
                        <option value="Cream">Cream</option>
                        <option value="Drop">Drop</option>
                        <option value="Inhaler">Inhaler</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="company" class="form-control" placeholder="e.g. Abbott, Pfizer">
                </div>

                <div class="form-group">
                    <label>Stock (Units)</label>
                    <input type="number" name="stock" class="form-control" min="0" value="0">
                </div>

                <div class="form-group">
                    <label>Price (Rs.)</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="0.00">
                </div>

                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Dosage Form</label>
                    <input type="text" name="dosage_form" class="form-control" placeholder="e.g. 500mg, 10ml">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_med" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Medicine
                </button>
                <a href="view_medicine.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>