<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$err = '';

if (isset($_POST['add_dept'])) {
    $name   = mysqli_real_escape_string($conn, trim($_POST['dept_name']));
    $desc   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $loc    = mysqli_real_escape_string($conn, trim($_POST['location']));
    $phone  = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email  = mysqli_real_escape_string($conn, trim($_POST['email']));
    $year   = (int)$_POST['established_year'];
    $staff  = (int)$_POST['total_staff'];
    $head   = mysqli_real_escape_string($conn, trim($_POST['head_doctor']));
    $status = mysqli_real_escape_string($conn, trim($_POST['status']));

    $sql = "INSERT INTO departments (dept_name, description, location, phone, email, established_year, total_staff, head_doctor, status)
            VALUES ('$name','$desc','$loc','$phone','$email',$year,$staff,'$head','$status')";

    if (mysqli_query($conn, $sql)) {
        $new_id = mysqli_insert_id($conn);

        if (!empty($_FILES['photo']['name'])) {
            $upload_dir = '../assets/departments/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (in_array($ext, $allowed)) {
                $new_name = 'dept_' . $new_id . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_name)) {
                    $photo_safe = mysqli_real_escape_string($conn, $new_name);
                    mysqli_query($conn, "UPDATE departments SET photo='$photo_safe' WHERE department_id=$new_id");
                }
            }
        }

        header("Location: view_department.php?msg=added");
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
            <h1><i class="fas fa-plus-circle"></i> Add Department</h1>
            <p class="breadcrumb">
                <a href="dashboard.php">Home</a> &rsaquo;
                <a href="view_department.php">Departments</a> &rsaquo; Add
            </p>
        </div>
        <a href="view_department.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= $err ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-sitemap"></i> Department Details</div>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <div class="form-group">
                    <label>Department Name <span class="req">*</span></label>
                    <input type="text" name="dept_name" class="form-control"
                           placeholder="e.g. Cardiology" required>
                </div>

                <div class="form-group">
                    <label>Head Doctor</label>
                    <input type="text" name="head_doctor" class="form-control"
                           placeholder="e.g. Dr. Ahmed Raza">
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control"
                           placeholder="e.g. Block A, Floor 2">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control"
                           placeholder="e.g. 021-1234567">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="e.g. dept@hospital.com">
                </div>

                <div class="form-group">
                    <label>Established Year</label>
                    <input type="number" name="established_year" class="form-control"
                           placeholder="e.g. 2005" min="1900" max="2099">
                </div>

                <div class="form-group">
                    <label>Total Staff</label>
                    <input type="number" name="total_staff" class="form-control"
                           placeholder="e.g. 25" min="0">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="e.g. Heart and cardiovascular disease diagnosis and treatment"
                              style="resize:vertical;"></textarea>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Department Photo</label>
                    <div id="uploadArea"
                         onclick="document.getElementById('photoInput').click()"
                         style="width:100%;max-width:400px;height:160px;
                                border:2px dashed var(--border);border-radius:12px;
                                display:flex;flex-direction:column;align-items:center;
                                justify-content:center;cursor:pointer;
                                background:var(--card-bg);transition:border-color .2s;
                                color:var(--text-muted);"
                         onmouseover="this.style.borderColor='var(--primary)'"
                         onmouseout="this.style.borderColor='var(--border)'">
                        <i class="fas fa-cloud-upload-alt"
                           style="font-size:2rem;margin-bottom:8px;color:var(--primary);"></i>
                        <span style="font-size:13px;font-weight:600;">Click to upload photo</span>
                        <span style="font-size:11px;margin-top:4px;">JPG, PNG, WEBP — max 5MB</span>
                    </div>

                    <input type="file" id="photoInput" name="photo"
                           accept="image/jpeg,image/png,image/webp,image/gif"
                           style="display:none;"
                           onchange="previewPhoto(this)">

                    <div id="previewWrap" style="display:none;margin-top:12px;">
                        <img id="photoPreview" src="#" alt="Preview"
                             style="width:180px;height:120px;object-fit:cover;
                                    border-radius:10px;border:2px solid var(--primary);">
                        <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">
                            Preview — looks good? Save to upload.
                        </p>
                        <button type="button" onclick="resetPhoto()"
                                style="margin-top:6px;background:none;border:none;
                                       color:var(--danger);font-size:12px;cursor:pointer;">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" name="add_dept" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Department
                </button>
                <a href="view_department.php" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const wrap    = document.getElementById('previewWrap');
    const area    = document.getElementById('uploadArea');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src        = e.target.result;
            wrap.style.display = 'block';
            area.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function resetPhoto() {
    document.getElementById('photoInput').value        = '';
    document.getElementById('previewWrap').style.display = 'none';
    document.getElementById('uploadArea').style.display  = 'flex';
}
</script>

<?php include '../includes/footer.php'; ?>