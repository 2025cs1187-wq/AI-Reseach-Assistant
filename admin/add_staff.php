<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];

    // Email check
    $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $check->bind_param('s', $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "This email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param('sssss', $name, $email, $password, $phone, $role);
        if ($stmt->execute()) {
            header("Location: view_staff.php?msg=added");
            exit();
        } else {
            $error = "Something went wrong. Try again!";
        }
    }
}

include '../includes/header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-plus"></i> Add Staff Member</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_staff.php">Staff</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_staff.php" class="btn btn-outline">
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
            <div class="card-title"><i class="fas fa-user-plus"></i> Staff Details</div>
        </div>

        <form method="POST">
            <div style="padding:1.8rem;">

                <!-- Row 1: Name | Email | Phone -->
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.3rem;margin-bottom:1.3rem;">

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Full Name <span style="color:red">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email <span style="color:red">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="text" name="phone" class="form-control"
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                               placeholder="03xx-xxxxxxx">
                    </div>

                </div>

                <!-- Row 2: Role | Password -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.3rem;margin-bottom:1.8rem;">

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users-cog"></i> Role <span style="color:red">*</span>
                        </label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="pharmacist"     <?= ($_POST['role'] ?? '')==='pharmacist'     ? 'selected':'' ?>>Pharmacist</option>
                            <option value="lab_technician" <?= ($_POST['role'] ?? '')==='lab_technician' ? 'selected':'' ?>>Lab Technician</option>
                            <option value="receptionist"   <?= ($_POST['role'] ?? '')==='receptionist'   ? 'selected':'' ?>>Receptionist</option>
                            <option value="nurse"          <?= ($_POST['role'] ?? '')==='nurse'          ? 'selected':'' ?>>Nurse</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Password <span style="color:red">*</span>
                        </label>
                        <input type="password" name="password" class="form-control" required
                               placeholder="Enter password">
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Staff Member
                    </button>
                    <a href="view_staff.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>