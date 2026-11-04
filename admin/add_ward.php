<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$err = '';

if (isset($_POST['add_ward'])) {
    $ward_no        = mysqli_real_escape_string($conn, $_POST['ward_no']);
    $ward_type      = mysqli_real_escape_string($conn, $_POST['ward_type']);
    $capacity       = (int)$_POST['capacity'];
    $floor          = mysqli_real_escape_string($conn, $_POST['floor']);
    $available_beds = (int)$_POST['available_beds'];
    $nurse_station  = mysqli_real_escape_string($conn, $_POST['nurse_station']);

    $sql = "INSERT INTO wards (ward_no, ward_type, capacity, floor, available_beds, nurse_station)
            VALUES ('$ward_no','$ward_type',$capacity,'$floor',$available_beds,'$nurse_station')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_ward.php?msg=added");
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
            <h1><i class="fas fa-plus-circle"></i> Add Ward</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_ward.php">Wards</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_ward.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-building"></i> Ward Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Ward No <span class="req">*</span></label>
                    <input type="text" name="ward_no" class="form-control" placeholder="e.g. W-01" required>
                </div>

                <div class="form-group">
                    <label>Ward Type</label>
                    <select name="ward_type" class="form-control">
                        <option value="">-- Select Type --</option>
                        <?php foreach(['General','ICU','Emergency','Maternity','Pediatric','Surgical'] as $t): ?>
                        <option value="<?= $t ?>"><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" class="form-control" placeholder="e.g. 20">
                </div>

                <div class="form-group">
                    <label>Available Beds</label>
                    <input type="number" name="available_beds" class="form-control" placeholder="e.g. 15">
                </div>

                <div class="form-group">
                    <label>Floor</label>
                    <input type="text" name="floor" class="form-control" placeholder="e.g. Ground Floor">
                </div>

                <div class="form-group">
                    <label>Nurse Station</label>
                    <input type="text" name="nurse_station" class="form-control" placeholder="e.g. Station A">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_ward" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Ward
                </button>
                <a href="view_ward.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>