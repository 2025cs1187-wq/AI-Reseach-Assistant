<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $dob            = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $gender         = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $blood_group    = mysqli_real_escape_string($conn, $_POST['blood_group'] ?? '');
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status'] ?? '');
    $phone          = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $email          = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $address        = mysqli_real_escape_string($conn, $_POST['address'] ?? '');

    if (!$name || !$phone) {
        $error = 'Name and Phone are required.';
    } else {
        $sql = "INSERT INTO patient (name, blood_group, dob, address, gender, email, phone, marital_status)
                VALUES ('$name', '$blood_group', '$dob', '$address', '$gender', '$email', '$phone', '$marital_status')";

        if (mysqli_query($conn, $sql)) {
            header('Location: view_patient.php?msg=added');
            exit;
        } else {
            $error = mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-plus"></i> Add Patient</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_patient.php">Patients</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_patient.php" class="btn btn-outline">← Back</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus-circle"></i> Patient Information</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name <span style="color:red">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control"
                           value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Select Gender --</option>
                        <?php foreach (['Male','Female','Other'] as $g): ?>
                        <option value="<?= $g ?>" <?= ($_POST['gender'] ?? '') === $g ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" class="form-control">
                        <option value="">-- Select Blood Group --</option>
                        <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                        <option value="<?= $bg ?>" <?= ($_POST['blood_group'] ?? '') === $bg ? 'selected' : '' ?>>
                            <?= $bg ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <select name="marital_status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <?php foreach (['Single','Married','Divorced','Widowed'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($_POST['marital_status'] ?? '') === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phone <span style="color:red">*</span></label>
                    <input type="text" name="phone" class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                           placeholder="03xx-xxxxxxx" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="example@email.com">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                           placeholder="Enter address">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Patient
                </button>
                <a href="view_patient.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>