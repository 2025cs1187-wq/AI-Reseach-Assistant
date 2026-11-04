<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id  = (int)($_GET['id'] ?? 0);
$msg = $err = '';

$med = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM medicines WHERE medicine_id=$id"));
if (!$med) { header("Location: view_medicine.php"); exit(); }

if (isset($_POST['update'])) {
    $name        = mysqli_real_escape_string($conn, trim($_POST['ord_name']));
    $type        = mysqli_real_escape_string($conn, trim($_POST['type']));
    $company     = mysqli_real_escape_string($conn, trim($_POST['company']));
    $stock       = (int)$_POST['stock'];
    $price       = (float)$_POST['price'];
    $expiry      = mysqli_real_escape_string($conn, trim($_POST['expiry_date']));
    $dosage_form = mysqli_real_escape_string($conn, trim($_POST['dosage_form']));

    $sql = "UPDATE medicines SET
                ord_name='$name', type='$type', company='$company',
                stock=$stock, price=$price, expiry_date='$expiry',
                dosage_form='$dosage_form'
            WHERE medicine_id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_medicine.php?msg=updated");
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
            <h1><i class="fas fa-edit"></i> Edit Medicine</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_medicine.php">Medicines</a> &rsaquo; Edit
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
            <div class="card-title"><i class="fas fa-pills"></i> Edit Medicine #<?= $id ?></div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Medicine Name <span class="req">*</span></label>
                    <input type="text" name="ord_name" class="form-control"
                        value="<?= htmlspecialchars($med['ord_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <?php foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drop','Inhaler','Other'] as $t): ?>
                        <option value="<?= $t ?>" <?= $med['type']==$t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="company" class="form-control"
                        value="<?= htmlspecialchars($med['company']) ?>">
                </div>

                <div class="form-group">
                    <label>Stock (Units)</label>
                    <input type="number" name="stock" class="form-control"
                        min="0" value="<?= $med['stock'] ?>">
                </div>

                <div class="form-group">
                    <label>Price (Rs.)</label>
                    <input type="number" name="price" class="form-control"
                        step="0.01" min="0" value="<?= $med['price'] ?>">
                </div>

                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control"
                        value="<?= $med['expiry_date'] ? date('Y-m-d', strtotime($med['expiry_date'])) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Dosage Form</label>
                    <input type="text" name="dosage_form" class="form-control"
                        value="<?= htmlspecialchars($med['dosage_form']) ?>">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Medicine
                </button>
                <a href="view_medicine.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>