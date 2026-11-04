<?php
session_start();
require_once '../config/db.php';

$active_page  = 'profile';
$page_title   = 'My Profile';
$top_bar_icon = 'user';

require_once __DIR__ . '/../includes/client_header.php';

$success = '';
$patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM patient WHERE patient_id=$patient_id"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob     = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender  = mysqli_real_escape_string($conn, $_POST['gender']);
    $blood   = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $marital = mysqli_real_escape_string($conn, $_POST['marital_status']);

    if (!empty($_POST['new_password'])) {
        $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE patient SET name='$name', email='$email', phone='$phone', address='$address', dob='$dob', gender='$gender', blood_group='$blood', marital_status='$marital', password='$new_pass' WHERE patient_id=$patient_id");
    } else {
        mysqli_query($conn, "UPDATE patient SET name='$name', email='$email', phone='$phone', address='$address', dob='$dob', gender='$gender', blood_group='$blood', marital_status='$marital' WHERE patient_id=$patient_id");
    }

    $_SESSION['user_name'] = $name;
    $success = "Profile updated successfully!";
    $patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM patient WHERE patient_id=$patient_id"));
}
?>

<div class="profile-card">
    <div class="profile-avatar"><?= strtoupper(substr($patient_name, 0, 1)) ?></div>

    <?php if ($success): ?>
        <div class="alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <p class="section-title"><i class="fas fa-user"></i> Personal Information</p>
        <div class="form-row">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($patient['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Username (Email)</label>
                <input type="text" value="<?= htmlspecialchars($patient['username'] ?? '') ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($patient['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?= $patient['dob'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                    <option value="">-- Select --</option>
                    <?php foreach (['Male','Female','Other'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($patient['gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Blood Group</label>
                <select name="blood_group">
                    <option value="">-- Select --</option>
                    <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                    <option value="<?= $bg ?>" <?= ($patient['blood_group'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Marital Status</label>
                <select name="marital_status">
                    <option value="">-- Select --</option>
                    <?php foreach (['Single','Married','Divorced','Widowed'] as $ms): ?>
                    <option value="<?= $ms ?>" <?= ($patient['marital_status'] ?? '') === $ms ? 'selected' : '' ?>><?= $ms ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address"><?= htmlspecialchars($patient['address'] ?? '') ?></textarea>
        </div>

        <hr class="divider">
        <p class="section-title"><i class="fas fa-lock"></i> Change Password (optional)</p>
        <div class="form-row">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="Leave blank to keep current">
            </div>
        </div>

        <button type="submit" class="btn-purple">
            <i class="fas fa-save"></i> Update Profile
        </button>
    </form>
</div>

<?php 
require_once __DIR__ . '/../includes/client_footer.php';

 ?>
