<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['client_logged_in'])) {
    header("Location: ../login.php?role=client");
    exit();
}

$patient_id   = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Agar profile already complete hai to dashboard pe bhejo
$check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT phone FROM patient WHERE patient_id=$patient_id"));
if (!empty($check['phone'])) {
    header("Location: dashboard.php");
    exit();
}

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone          = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $dob            = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender         = mysqli_real_escape_string($conn, $_POST['gender']);
    $blood_group    = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $address        = mysqli_real_escape_string($conn, trim($_POST['address']));
    $email          = mysqli_real_escape_string($conn, trim($_POST['email']));

    if (empty($name) || empty($phone)) {
        $error = "Name and Phone are required!";
    } else {
        $sql = "UPDATE patient SET 
                    name='$name', phone='$phone', dob='$dob',
                    gender='$gender', blood_group='$blood_group',
                    marital_status='$marital_status', address='$address',
                    email='$email'
                WHERE patient_id=$patient_id";

        if (mysqli_query($conn, $sql)) {
            // Session mein naam update karo
            $_SESSION['user_name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = mysqli_error($conn);
        }
    }
}

// Patient ka existing data fetch karo
$patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM patient WHERE patient_id=$patient_id"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile — MedicaPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:#0a1628; --navy-mid:#0d1f3c; --navy-light:#162848;
            --gold:#c9a84c; --gold-light:#e8c97a; --gold-pale:#fdf6e3;
            --border:#e2e8f0;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'DM Sans',sans-serif;
            min-height:100vh;
            background: var(--navy);
            display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            padding:40px 30px;
            position:relative; overflow-x:hidden;
        }
        body::before {
            content:'';
            position:fixed; width:500px; height:500px;
            border-radius:50%;
            border:70px solid rgba(201,168,76,0.05);
            top:-150px; right:-150px; pointer-events:none;
        }
        body::after {
            content:'';
            position:fixed; width:350px; height:350px;
            border-radius:50%;
            border:50px solid rgba(201,168,76,0.04);
            bottom:-100px; left:-80px; pointer-events:none;
        }

        /* TOP LOGO */
        .top-logo {
            display:flex; align-items:center; gap:10px;
            margin-bottom:30px; text-decoration:none;
        }
        .logo-icon {
            width:42px; height:42px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:11px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; color:var(--navy);
        }
        .logo-text {
            font-family:'Playfair Display',serif;
            font-size:1.3rem; color:#fff;
        }
        .logo-text span { color:var(--gold); }

        /* CARD */
        .profile-card {
            background:#f8fafc;
            border-radius:20px;
            width:100%; max-width:680px;
            box-shadow:0 30px 80px rgba(0,0,0,0.4);
            overflow:hidden;
            position:relative; z-index:1;
        }

        /* Gold top line */
        .profile-card::before {
            content:'';
            display:block; height:4px;
            background:linear-gradient(90deg,var(--gold),var(--gold-light),var(--gold));
        }

        .card-body { padding:36px 40px; }

        .card-heading { margin-bottom:28px; text-align:center; }
        .card-heading .step-badge {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--gold-pale);
            border:1px solid rgba(201,168,76,0.3);
            color:#a07000; padding:6px 16px;
            border-radius:20px; font-size:.75rem; font-weight:700;
            margin-bottom:14px; letter-spacing:.5px;
        }
        .card-heading h2 {
            font-family:'Playfair Display',serif;
            font-size:1.6rem; color:var(--navy); margin-bottom:6px;
        }
        .card-heading p { font-size:.85rem; color:#8a9ab5; }

        /* Progress bar */
        .progress-wrap { margin-bottom:28px; }
        .progress-label {
            display:flex; justify-content:space-between;
            font-size:.75rem; color:#94a3b8; margin-bottom:7px;
        }
        .progress-bar {
            height:6px; background:#e2e8f0; border-radius:10px; overflow:hidden;
        }
        .progress-fill {
            height:100%; width:50%;
            background:linear-gradient(90deg,var(--gold),var(--gold-light));
            border-radius:10px;
            transition:width .4s;
        }

        /* ALERT */
        .alert-err {
            background:#fff0f0; color:#c62828;
            border-left:3px solid #c62828;
            padding:11px 14px; border-radius:8px;
            font-size:.84rem; margin-bottom:20px;
            display:flex; align-items:center; gap:8px;
        }

        /* FORM */
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label {
            font-size:.76rem; font-weight:700;
            color:#475569; text-transform:uppercase; letter-spacing:.6px;
        }
        .form-group label i { color:var(--gold); margin-right:4px; }
        .req { color:#e53935; }
        .form-group input,
        .form-group select {
            padding:11px 14px;
            border:1.5px solid var(--border);
            border-radius:9px; font-size:.91rem;
            font-family:'DM Sans',sans-serif;
            color:var(--navy); background:#fff; outline:none;
            transition:border .2s, box-shadow .2s;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,168,76,0.12);
        }
        .form-group input[readonly] {
            background:#f1f5f9; color:#94a3b8; cursor:not-allowed;
        }

        .section-divider {
            font-size:.72rem; font-weight:700;
            color:var(--navy); text-transform:uppercase;
            letter-spacing:.8px; margin:22px 0 16px;
            display:flex; align-items:center; gap:10px;
        }
        .section-divider::after {
            content:''; flex:1; height:1px;
            background:linear-gradient(90deg,var(--border),transparent);
        }
        .section-divider i { color:var(--gold); }

        .btn-save {
            width:100%; padding:13px;
            background:var(--navy); color:var(--gold);
            border:2px solid var(--navy); border-radius:9px;
            font-size:.95rem; font-weight:700;
            font-family:'DM Sans',sans-serif;
            cursor:pointer; transition:all .25s;
            display:flex; align-items:center; justify-content:center; gap:9px;
            margin-top:24px;
        }
        .btn-save:hover { background:var(--gold); border-color:var(--gold); color:var(--navy); }

        .logout-link {
            display:flex; align-items:center; gap:6px;
            color:rgba(255,255,255,0.35); font-size:.8rem;
            text-decoration:none; margin-top:20px;
            transition:color .2s;
        }
        .logout-link:hover { color:rgba(255,255,255,0.7); }
    </style>
</head>
<body>

<a href="../index.php" class="top-logo">
    <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
    <div class="logo-text">Medica<span>Pro</span></div>
</a>

<div class="profile-card">
    <div class="card-body">

        <div class="card-heading">
            <div class="step-badge"><i class="fas fa-user-edit"></i> Step 2 of 2</div>
            <h2>Complete Your Profile</h2>
            <p>Please fill in your details to continue to your dashboard</p>
        </div>

        <div class="progress-wrap">
            <div class="progress-label">
                <span>Account Created ✓</span>
                <span>Profile Details</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <?php if ($error): ?>
        <div class="alert-err">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST">

            <div class="section-divider"><i class="fas fa-user"></i> Personal Information</div>

            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="req">*</span></label>
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($patient['name'] ?? '') ?>"
                           placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Username (Email)</label>
                    <input type="text" value="<?= htmlspecialchars($patient['username'] ?? '') ?>" readonly>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($patient['email'] ?? $patient['username'] ?? '') ?>"
                           placeholder="Your email address">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number <span class="req">*</span></label>
                    <input type="text" name="phone"
                           value="<?= htmlspecialchars($patient['phone'] ?? '') ?>"
                           placeholder="03xx-xxxxxxx" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Date of Birth</label>
                    <input type="date" name="dob"
                           value="<?= htmlspecialchars($patient['dob'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <select name="gender">
                        <option value="">-- Select --</option>
                        <?php foreach(['Male','Female','Other'] as $g): ?>
                        <option value="<?= $g ?>" <?= ($patient['gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-divider"><i class="fas fa-heartbeat"></i> Medical Information</div>

            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-tint"></i> Blood Group</label>
                    <select name="blood_group">
                        <option value="">-- Select --</option>
                        <?php foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                        <option value="<?= $bg ?>" <?= ($patient['blood_group'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-heart"></i> Marital Status</label>
                    <select name="marital_status">
                        <option value="">-- Select --</option>
                        <?php foreach(['Single','Married','Divorced','Widowed'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($patient['marital_status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full">
                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                    <input type="text" name="address"
                           value="<?= htmlspecialchars($patient['address'] ?? '') ?>"
                           placeholder="Your home address">
                </div>
            </div>

            <button type="submit" class="btn-save">
                <i class="fas fa-check-circle"></i> Save & Continue to Dashboard
            </button>
        </form>
    </div>
</div>

<a href="../logout.php" class="logout-link">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>

</body>
</html>