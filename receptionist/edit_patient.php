<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id  = (int)$_GET['id'];
$msg = $err = '';
$pat = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM patient WHERE patient_id=$id"));
if (!$pat) { header("Location: view_patient.php"); exit(); }

if (isset($_POST['update'])) {
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $dob    = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $addr   = mysqli_real_escape_string($conn, $_POST['address']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);
    $blood  = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $marital = mysqli_real_escape_string($conn, $_POST['marital_status']);

    $sql = "UPDATE patient SET 
                name='$name',
                dob='$dob',
                gender='$gender',
                address='$addr',
                email='$email',
                phone='$phone',
                blood_group='$blood',
                marital_status='$marital'
            WHERE patient_id=$id";

    mysqli_query($conn,$sql) ? $msg = "Patient updated successfully!" : $err = mysqli_error($conn);
    $pat = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM patient WHERE patient_id=$id"));
}

include '../includes/receptionist_header.php';
?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Patient</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_patient.php">Patients</a> &rsaquo; Edit
            </p>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-edit"></i> Edit Patient Details</div>
        </div>
        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($pat['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control"
                        value="<?= htmlspecialchars($pat['dob']) ?>">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <?php foreach(['Male','Female','Other'] as $g): ?>
                        <option value="<?= $g ?>" <?= $pat['gender']==$g ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" class="form-control">
                        <option value="">-- Select Blood Group --</option>
                        <?php foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                        <option value="<?= $bg ?>" <?= $pat['blood_group']==$bg ? 'selected' : '' ?>>
                            <?= $bg ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <select name="marital_status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <?php foreach(['Single','Married','Divorced','Widowed'] as $ms): ?>
                        <option value="<?= $ms ?>" <?= $pat['marital_status']==$ms ? 'selected' : '' ?>>
                            <?= $ms ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?= htmlspecialchars($pat['phone']) ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($pat['email']) ?>">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                        value="<?= htmlspecialchars($pat['address']) ?>">
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="view_patient.php" class="btn btn-outline">Back</a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>