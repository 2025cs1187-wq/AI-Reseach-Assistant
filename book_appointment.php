<?php
session_start();
require_once 'config/db.php';

// Login check
if (!isset($_SESSION['client_logged_in'])) {
    header("Location: login.php?role=client");
    exit();
}

$patient_id   = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];
$msg = $err = '';

// Patient detail fetch
$patient = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM patient WHERE patient_id=$patient_id"));

// Doctors list
$doctors_arr = mysqli_fetch_all(mysqli_query($conn,
    "SELECT doctor_id, name, specialization, consultation_fee FROM doctor ORDER BY name"), MYSQLI_ASSOC);

// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = (int)$_POST['doctor_id'];
    $date_time = $_POST['date_time'];
    $reason    = mysqli_real_escape_string($conn, trim($_POST['reason'] ?? ''));
    $notes     = mysqli_real_escape_string($conn, trim($_POST['notes'] ?? ''));

    $stmt = $conn->prepare("CALL sp_BookAppointment(?, ?, ?, ?, ?)");
    $stmt->bind_param('iisss', $doctor_id, $patient_id, $date_time, $reason, $notes);

    if ($stmt->execute()) {
        $stmt->close();
        while ($conn->more_results() && $conn->next_result()) {
            if ($res = $conn->store_result()) $res->free();
        }
$msg = "Your appointment request has been submitted! We will confirm your slot shortly.";    } else {
        $err = $conn->error;
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment — MedicaPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:#0a1628; --navy-mid:#0d1f3c; --navy-light:#162848;
            --gold:#c9a84c; --gold-light:#e8c97a; --gold-pale:#fdf6e3;
            --border:#e2e8f0; --text-muted:#8a9ab5;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:#f0f4f8; min-height:100vh; }
        .navbar { background:var(--navy); padding:14px 40px; display:flex; align-items:center; justify-content:space-between; border-bottom:2px solid rgba(201,168,76,0.2); position:sticky; top:0; z-index:100; }
        .nav-logo { font-family:'Playfair Display',serif; font-size:1.2rem; color:#fff; display:flex; align-items:center; gap:10px; text-decoration:none; }
        .nav-logo .logo-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--gold),var(--gold-light)); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.95rem; color:var(--navy); }
        .nav-logo span { color:var(--gold); }
        .nav-right { display:flex; align-items:center; gap:16px; }
        .nav-user { display:flex; align-items:center; gap:9px; color:rgba(255,255,255,0.7); font-size:.87rem; }
        .nav-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:var(--navy); font-weight:800; font-size:.85rem; display:flex; align-items:center; justify-content:center; }
        .nav-btn { background:rgba(201,168,76,0.15); border:1px solid rgba(201,168,76,0.3); color:var(--gold-light); padding:7px 16px; border-radius:7px; font-size:.82rem; font-weight:600; text-decoration:none; transition:all .2s; }
        .nav-btn:hover { background:var(--gold); color:var(--navy); }
        .page-wrap { max-width:820px; margin:40px auto; padding:0 20px 60px; }
        .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
        .page-header h1 { font-family:'Playfair Display',serif; font-size:1.7rem; color:var(--navy); display:flex; align-items:center; gap:10px; }
        .page-header h1 i { color:var(--gold); font-size:1.4rem; }
        .page-header p { font-size:.85rem; color:var(--text-muted); margin-top:4px; }
        .back-btn { display:flex; align-items:center; gap:7px; color:var(--text-muted); font-size:.85rem; text-decoration:none; transition:color .2s; }
        .back-btn:hover { color:var(--navy); }
        .patient-card { background:var(--navy); border-radius:14px; padding:20px 24px; margin-bottom:24px; display:flex; align-items:center; gap:16px; border:1px solid rgba(201,168,76,0.2); position:relative; overflow:hidden; }
        .patient-card::after { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,var(--gold),var(--gold-light),var(--gold)); }
        .patient-avatar { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:var(--navy); font-weight:800; font-size:1.3rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .patient-info h3 { color:#fff; font-size:1rem; font-weight:700; margin-bottom:3px; }
        .patient-info p { color:var(--text-muted); font-size:.83rem; }
        .patient-badge { margin-left:auto; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.25); color:var(--gold); padding:5px 14px; border-radius:20px; font-size:.75rem; font-weight:700; }
        .form-card { background:#fff; border-radius:14px; padding:32px; box-shadow:0 2px 16px rgba(10,22,40,0.07); border:1px solid var(--border); }
        .form-card-title { font-size:.78rem; font-weight:700; color:var(--navy); text-transform:uppercase; letter-spacing:.8px; margin-bottom:22px; display:flex; align-items:center; gap:8px; padding-bottom:14px; border-bottom:1px solid var(--border); }
        .form-card-title i { color:var(--gold); }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        .form-group { display:flex; flex-direction:column; gap:7px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:.78rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.5px; }
        .form-group label i { color:var(--gold); margin-right:4px; }
        .req { color:#e53935; }
        .form-group input, .form-group select, .form-group textarea { padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:.92rem; font-family:'DM Sans',sans-serif; color:var(--navy); outline:none; transition:border .2s, box-shadow .2s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,0.12); }
        .form-group textarea { resize:none; height:80px; }
        .form-group select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; padding-right:36px; }
        .doctor-fee { font-size:.75rem; color:var(--text-muted); margin-top:4px; display:flex; align-items:center; gap:5px; }
        .doctor-fee span { color:var(--gold); font-weight:700; }
        .alert { padding:13px 16px; border-radius:9px; font-size:.88rem; margin-bottom:22px; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .alert-success { background:#f0fdf4; color:#2e7d32; border-left:3px solid #2e7d32; }
        .alert-danger  { background:#fff0f0; color:#c62828; border-left:3px solid #c62828; }
        .form-actions { display:flex; gap:12px; margin-top:28px; padding-top:22px; border-top:1px solid var(--border); }
        .btn-book { flex:1; padding:13px; background:var(--navy); color:var(--gold); border:2px solid var(--navy); border-radius:9px; font-size:.95rem; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .25s; display:flex; align-items:center; justify-content:center; gap:9px; }
        .btn-book:hover { background:var(--gold); border-color:var(--gold); color:var(--navy); }
        .btn-cancel { padding:13px 24px; background:transparent; color:#64748b; border:2px solid var(--border); border-radius:9px; font-size:.92rem; font-weight:600; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .2s; text-decoration:none; display:flex; align-items:center; gap:7px; }
        .btn-cancel:hover { border-color:#94a3b8; color:var(--navy); }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">
        <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
        Medica<span>Pro</span>
    </a>
    <div class="nav-right">
        <div class="nav-user">
            <div class="nav-avatar"><?= strtoupper(substr($patient_name, 0, 1)) ?></div>
            <?= htmlspecialchars($patient_name) ?>
        </div>
        <a href="client/dashboard.php" class="nav-btn"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="page-wrap">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-calendar-plus"></i> Book Appointment</h1>
            <p>Fill in the details below to schedule your appointment</p>
        </div>
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Website</a>
    </div>

    <div class="patient-card">
        <div class="patient-avatar"><?= strtoupper(substr($patient_name, 0, 1)) ?></div>
        <div class="patient-info">
            <h3><?= htmlspecialchars($patient_name) ?></h3>
            <p><?= htmlspecialchars($patient['email'] ?? $patient['username'] ?? '') ?></p>
        </div>
        <div class="patient-badge"><i class="fas fa-user"></i> Patient</div>
    </div>

    <?php if ($msg): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $msg ?>
        <a href="client/my_appointments.php" style="margin-left:10px;color:#2e7d32;font-weight:700;">View My Appointments →</a>
    </div>
    <?php endif; ?>

    <?php if ($err): ?>
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i> <?= htmlspecialchars($err) ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <div class="form-card-title">
            <i class="fas fa-calendar-plus"></i> Appointment Details
        </div>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group full">
                    <label><i class="fas fa-user-md"></i> Select Doctor <span class="req">*</span></label>
                    <select name="doctor_id" id="doctorSelect" required onchange="showFee(this)">
                        <option value="">-- Select a Doctor --</option>
                        <?php foreach ($doctors_arr as $d): ?>
                        <option value="<?= $d['doctor_id'] ?>"
                                data-fee="<?= $d['consultation_fee'] ?>"
                                data-spec="<?= htmlspecialchars($d['specialization']) ?>">
                            Dr. <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['specialization']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="doctor-fee" id="feeInfo" style="display:none;">
                        <i class="fas fa-tag"></i> Consultation Fee: <span id="feeAmt"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Date & Time <span class="req">*</span></label>
                    <input type="datetime-local" name="date_time" required min="<?= date('Y-m-d\TH:i') ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Reason for Visit <span class="req">*</span></label>
                    <input type="text" name="reason" placeholder="e.g. Fever, checkup, follow-up" required>
                </div>
                <div class="form-group full">
                    <label><i class="fas fa-sticky-note"></i> Additional Notes</label>
                    <textarea name="notes" placeholder="Any additional information for the doctor..."></textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-book">
                    <i class="fas fa-calendar-check"></i> Request Appointment
                </button>
                <a href="index.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function showFee(sel) {
    const opt = sel.options[sel.selectedIndex];
    const fee = opt.dataset.fee;
    const info = document.getElementById('feeInfo');
    if (fee) {
        document.getElementById('feeAmt').textContent = 'Rs. ' + fee;
        info.style.display = 'flex';
    } else {
        info.style.display = 'none';
    }
}
</script>
</body>
</html>