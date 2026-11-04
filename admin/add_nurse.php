<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

if (isset($_POST['add_nurse'])) {
    $name          = mysqli_real_escape_string($conn, $_POST['name']);
    $gender        = mysqli_real_escape_string($conn, $_POST['gender']);
    $shift         = mysqli_real_escape_string($conn, $_POST['shift']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $phone         = mysqli_real_escape_string($conn, $_POST['phone']);
    $experience    = mysqli_real_escape_string($conn, $_POST['experience']);
    $ward_id       = (int)$_POST['ward_id'];
    $ward_val      = $ward_id > 0 ? $ward_id : "NULL";

    $sql = "INSERT INTO nurse (name, gender, shift, qualification, phone, experience, ward_id)
            VALUES ('$name', '$gender', '$shift', '$qualification', '$phone', '$experience', $ward_val)";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_nurse.php?msg=added");
        exit;
    } else {
        $err = mysqli_error($conn);
    }
}

$wards = mysqli_query($conn, "SELECT * FROM wards ORDER BY ward_no");
include '../includes/header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-nurse"></i> Add Nurse</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_nurse.php">Nurses</a> &rsaquo; Add
            </p>
        </div>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus-circle"></i> Add New Nurse</div>
        </div>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Select Gender --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Shift</label>
                    <select name="shift" class="form-control">
                        <option value="">-- Select Shift --</option>
                        <option value="Morning">Morning</option>
                        <option value="Evening">Evening</option>
                        <option value="Night">Night</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control" placeholder="e.g. BSN, RN">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="03xx-xxxxxxx">
                </div>
                <div class="form-group">
                    <label>Experience</label>
                    <input type="text" name="experience" class="form-control" placeholder="e.g. 3 years">
                </div>
                <div class="form-group">
                    <label>Ward</label>
                    <select name="ward_id" class="form-control">
                        <option value="0">-- Select Ward --</option>
                        <?php while ($w = mysqli_fetch_assoc($wards)): ?>
                        <option value="<?= $w['ward_id'] ?>">
                            Ward <?= htmlspecialchars($w['ward_no']) ?> - <?= htmlspecialchars($w['ward_type']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="add_nurse" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Nurse
                </button>
                <a href="view_nurse.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>