<?php
session_start();
require_once 'config/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['username']);
$password = $_POST['password'];
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT patient_id FROM patient WHERE username='".mysqli_real_escape_string($conn, $email)."'"));
    if ($check) {
        $error = "This email is already registered! Please login.";
    } else {
        $sql = "INSERT INTO patient (username, password) VALUES ('".mysqli_real_escape_string($conn, $email)."', '$password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully! You can now login.";
        } else {
            $error = mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — MedicaPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:      #0a1628;
            --navy-mid:  #0d1f3c;
            --navy-light:#162848;
            --gold:      #c9a84c;
            --gold-light:#e8c97a;
            --gold-pale: #fdf6e3;
        }
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'DM Sans',sans-serif;
            min-height:100vh;
            background:var(--navy);
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }
        body::before {
            content:'';
            position:absolute;
            width:600px; height:600px;
            border-radius:50%;
            border:80px solid rgba(201,168,76,0.05);
            top:-200px; right:-200px;
            pointer-events:none;
        }
        body::after {
            content:'';
            position:absolute;
            width:400px; height:400px;
            border-radius:50%;
            border:60px solid rgba(201,168,76,0.04);
            bottom:-150px; left:-100px;
            pointer-events:none;
        }

        .login-card {
            display:flex;
            width:860px;
            min-height:520px;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 30px 80px rgba(0,0,0,0.5);
            position:relative;
            z-index:1;
        }

        /* ── LEFT ── */
        .card-left {
            width:340px;
            background:linear-gradient(160deg, var(--navy-light) 0%, var(--navy-mid) 100%);
            padding:44px 36px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            border-right:1px solid rgba(201,168,76,0.15);
            position:relative;
            overflow:hidden;
        }
        .card-left::after {
            content:'';
            position:absolute;
            width:250px; height:250px;
            border-radius:50%;
            border:35px solid rgba(201,168,76,0.07);
            bottom:-60px; right:-60px;
            pointer-events:none;
        }
        .logo-wrap { display:flex; align-items:center; gap:12px; }
        .logo-icon {
            width:46px; height:46px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; color:var(--navy);
        }
        .logo-text { font-family:'Playfair Display',serif; font-size:1.3rem; color:#fff; }
        .logo-text span { color:var(--gold); }

        .card-left-body { flex:1; display:flex; flex-direction:column; justify-content:center; padding:32px 0; }
        .card-left-body h2 {
            font-family:'Playfair Display',serif;
            font-size:1.9rem; color:#fff; line-height:1.3; margin-bottom:14px;
        }
        .card-left-body h2 em { font-style:normal; color:var(--gold); }
        .card-left-body p { font-size:.85rem; color:rgba(255,255,255,0.45); line-height:1.7; margin-bottom:32px; }

        .mode-badge {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(201,168,76,0.12);
            border:1px solid rgba(201,168,76,0.25);
            color:var(--gold-light);
            padding:7px 14px; border-radius:20px;
            font-size:.78rem; font-weight:600;
            margin-bottom:24px; width:fit-content;
        }

        .feat-list { display:flex; flex-direction:column; gap:13px; }
        .feat-item { display:flex; align-items:center; gap:12px; font-size:.82rem; color:rgba(255,255,255,0.6); }
        .feat-dot { width:6px; height:6px; border-radius:50%; background:var(--gold); flex-shrink:0; }

        .card-left-footer { font-size:.72rem; color:rgba(255,255,255,0.2); letter-spacing:.5px; }

        /* ── RIGHT ── */
        .card-right {
            flex:1;
            background:#f8fafc;
            padding:44px 40px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .card-right::before {
            content:'';
            display:block;
            height:3px;
            background:linear-gradient(90deg,var(--gold),var(--gold-light),var(--gold));
            margin:-44px -40px 36px;
        }

        .form-heading { margin-bottom:24px; }
        .form-heading .heading-icon {
            width:42px; height:42px;
            background:var(--gold-pale);
            border-radius:11px;
            display:flex; align-items:center; justify-content:center;
            color:var(--gold); font-size:1.1rem;
            margin-bottom:12px;
        }
        .form-heading h3 { font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--navy); margin-bottom:5px; }
        .form-heading p { font-size:.83rem; color:#94a3b8; }

        .alert-danger {
            background:#fff0f0; color:#c62828;
            border-left:3px solid #c62828;
            padding:10px 14px; border-radius:8px;
            font-size:.83rem; margin-bottom:18px;
            display:flex; align-items:center; gap:8px;
        }
        .alert-success {
            background:#f0fdf4; color:#2e7d32;
            border-left:3px solid #2e7d32;
            padding:10px 14px; border-radius:8px;
            font-size:.83rem; margin-bottom:18px;
            display:flex; align-items:center; gap:8px; flex-wrap:wrap;
        }
        .alert-success a { color:#2e7d32; font-weight:700; margin-left:6px; }

        .field { margin-bottom:18px; }
        .field label {
            display:block; font-size:.78rem; font-weight:700;
            color:#475569; margin-bottom:7px;
            text-transform:uppercase; letter-spacing:.6px;
        }
        .field label i { color:var(--gold); margin-right:5px; }
        .field-wrap { position:relative; }
        .field-wrap .fi {
            position:absolute; left:13px; top:50%;
            transform:translateY(-50%);
            color:#cbd5e1; font-size:.88rem; pointer-events:none;
        }
        .field-wrap input {
            width:100%; padding:12px 14px 12px 38px;
            border:1.5px solid #e2e8f0; border-radius:9px;
            font-size:.92rem; font-family:'DM Sans',sans-serif;
            color:var(--navy); background:#fff; outline:none;
            transition:border .2s, box-shadow .2s;
        }
        .field-wrap input:focus {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,168,76,0.12);
        }
        .field-wrap input::placeholder { color:#cbd5e1; }
        .eye-btn {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            color:#cbd5e1; cursor:pointer; font-size:.88rem; transition:color .2s;
        }
        .eye-btn:hover { color:var(--gold); }
        .hint { font-size:.75rem; color:#b0bec5; margin-top:5px; }

        .btn-signup {
            width:100%; padding:13px;
            background:var(--navy); color:var(--gold);
            border:2px solid var(--navy); border-radius:9px;
            font-size:.95rem; font-weight:700;
            font-family:'DM Sans',sans-serif;
            cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:9px;
            transition:all .25s; letter-spacing:.3px; margin-top:4px;
        }
        .btn-signup:hover { background:var(--gold); border-color:var(--gold); color:var(--navy); }

        .bottom-links {
            margin-top:20px;
            display:flex; justify-content:space-between; align-items:center;
            font-size:.82rem;
        }
        .bottom-links a { color:#94a3b8; text-decoration:none; transition:color .2s; display:flex; align-items:center; gap:5px; }
        .bottom-links a:hover { color:var(--navy); }
        .login-txt { color:#64748b; }
        .login-txt a {
            color:var(--navy); font-weight:700;
            border-bottom:1.5px solid var(--gold); padding-bottom:1px;
            text-decoration:none;
        }
        .login-txt a:hover { color:var(--gold); }
    </style>
</head>
<body>

<div class="login-card">

    <!-- LEFT -->
    <div class="card-left">
        <div class="logo-wrap">
            <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
            <div class="logo-text">Medica<span>Pro</span></div>
        </div>

        <div class="card-left-body">
            <div class="mode-badge"><i class="fas fa-user-plus"></i> New Patient</div>
            <h2>Join <em>MedicaPro</em><br>Today</h2>
            <p>Create your free patient account and get instant access to your medical portal.</p>
            <div class="feat-list">
                <div class="feat-item"><div class="feat-dot"></div> Book appointments online</div>
                <div class="feat-item"><div class="feat-dot"></div> View prescriptions anytime</div>
                <div class="feat-item"><div class="feat-dot"></div> Access billing history</div>
                <div class="feat-item"><div class="feat-dot"></div> Download lab reports</div>
            </div>
        </div>

        <div class="card-left-footer">© <?= date('Y') ?> MedicaPro. All rights reserved.</div>
    </div>

    <!-- RIGHT -->
    <div class="card-right">

        <div class="form-heading">
            <div class="heading-icon"><i class="fas fa-user-plus"></i></div>
            <h3>Create Account</h3>
            <p>Register as a new patient — it's free</p>
        </div>

        <?php if ($error): ?>
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            <a href="login.php?role=client">Login Now →</a>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <div class="field-wrap">
                    <i class="fas fa-envelope fi"></i>
                    <input type="email" name="username" placeholder="Enter your email address" required>
                </div>
                <p class="hint">This email will be used to login</p>
            </div>

            <div class="field">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="field-wrap">
                    <i class="fas fa-lock fi"></i>
                    <input type="password" name="password" id="passInput" placeholder="Choose a strong password" required>
                    <i class="fas fa-eye eye-btn" id="eyeBtn"></i>
                </div>
            </div>

            <button type="submit" class="btn-signup">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="bottom-links">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Website</a>
            <span class="login-txt">Have an account? <a href="login.php?role=client">Login here</a></span>
        </div>

    </div>
</div>

<script>
    document.getElementById('eyeBtn').addEventListener('click', function(){
        const inp = document.getElementById('passInput');
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        this.classList.toggle('fa-eye', !show);
        this.classList.toggle('fa-eye-slash', show);
    });
</script>
</body>
</html>