<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $spec           = mysqli_real_escape_string($conn, $_POST['specialization'] ?? '');
    $exp            = mysqli_real_escape_string($conn, $_POST['experience'] ?? '');
    $joining_date   = mysqli_real_escape_string($conn, $_POST['joining_date'] ?? '');
    $dob            = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $address        = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $email          = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone          = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $gender         = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $qualification  = mysqli_real_escape_string($conn, $_POST['qualification'] ?? '');
    $salary         = floatval($_POST['salary'] ?? 0);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status'] ?? '');

    // ── PHOTO UPLOAD ──
    $photo_name = NULL;
    if (!empty($_FILES['photo']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext  = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['photo']['size'];

        if (!in_array($ext, $allowed)) {
            $err = 'Sirf JPG, PNG, WEBP allowed hai.';
        } elseif ($size > 2 * 1024 * 1024) {
            $err = 'Photo size 2MB se kam honi chahiye.';
        } else {
            $photo_name = 'doc_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $upload_dir = '../assets/doctors/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo_name)) {
                $err = 'Photo upload failed. Folder permissions check karo.';
                $photo_name = NULL;
            }
        }
    }

    if (!$name || !$phone) {
        $err = 'Name and Phone are required.';
    } elseif (!$err) {
        $photo_val = $photo_name ? "'$photo_name'" : "NULL";
        $sql = "INSERT INTO doctor 
                    (name, specialization, experience, joining_date, dob, address, email, phone, gender, qualification, salary, marital_status, photo)
                VALUES 
                    ('$name', '$spec', '$exp', '$joining_date', '$dob', '$address', '$email', '$phone', '$gender', '$qualification', '$salary', '$marital_status', $photo_val)";

        if (mysqli_query($conn, $sql)) {
            header('Location: view_doctor.php?msg=added');
            exit;
        } else {
            $err = mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-md"></i> Add Doctor</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_doctor.php">Doctors</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_doctor.php" class="btn btn-outline">← Back</a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus-circle"></i> Doctor Information</div>
        </div>

        <!-- enctype ZAROOR chahiye photo upload ke liye -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name <span style="color:red">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="Dr. John Smith" required>
                </div>

                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" class="form-control"
                           value="<?= htmlspecialchars($_POST['specialization'] ?? '') ?>"
                           placeholder="e.g. Cardiologist">
                </div>

                <div class="form-group">
                    <label>Experience</label>
                    <input type="text" name="experience" class="form-control"
                           value="<?= htmlspecialchars($_POST['experience'] ?? '') ?>"
                           placeholder="e.g. 5 years">
                </div>

                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control"
                           value="<?= htmlspecialchars($_POST['qualification'] ?? '') ?>"
                           placeholder="e.g. MBBS, MD">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Select Gender --</option>
                        <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                        <option value="<?= $g ?>" <?= ($_POST['gender'] ?? '') === $g ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <select name="marital_status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <?php foreach (['Single', 'Married', 'Divorced', 'Widowed'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($_POST['marital_status'] ?? '') === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control"
                           value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" class="form-control"
                           value="<?= htmlspecialchars($_POST['joining_date'] ?? '') ?>">
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
                           placeholder="doctor@email.com">
                </div>

                <div class="form-group">
                    <label>Salary (Rs.)</label>
                    <input type="number" name="salary" class="form-control"
                           value="<?= htmlspecialchars($_POST['salary'] ?? '') ?>"
                           placeholder="e.g. 50000" step="0.01">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                           placeholder="Enter address">
                </div>

                <!-- ── PHOTO UPLOAD FIELD ── -->
                <div class="form-group" style="grid-column: span 2;">
                    <label><i class="fas fa-camera"></i> Doctor Photo</label>

                    <!-- Preview Box -->
                    <div style="margin-bottom:12px;">
                        <img id="photo_preview" src=""
                             style="width:110px; height:110px; object-fit:cover; border-radius:50%;
                                    border:3px solid #c9a84c; display:none; box-shadow:0 4px 15px rgba(0,0,0,0.15);">
                    </div>

                    <input type="file" name="photo" class="form-control"
                           accept="image/jpeg, image/png, image/webp"
                           onchange="previewPhoto(this)"
                           style="padding:8px;">

                    <small style="color:#888; margin-top:5px; display:block;">
                        <i class="fas fa-info-circle"></i> JPG, PNG, WEBP — max 2MB | Square photo best lagti hai
                    </small>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Doctor
                </button>
                <a href="view_doctor.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Photo Preview Script -->
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];

        // Size check (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Photo Size must be less then 200 MB .');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('photo_preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php include '../includes/footer.php'; ?>