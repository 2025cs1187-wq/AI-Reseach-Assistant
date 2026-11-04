<?php
session_start();
require_once 'config/db.php';

$error = '';
$selected_role = $_GET['role'] ?? 'staff';
$is_patient = ($selected_role === 'client');
$is_staff   = !$is_patient;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if ($role === 'admin' && $username === 'admin@hospital.com' && $password === 'admin1234') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name']      = 'Administrator';
        $_SESSION['role']            = 'admin';
        session_write_close();
        header("Location: admin/dashboard.php");
        exit();
    }
   elseif ($role === 'client') {
    $u = mysqli_real_escape_string($conn, $username);
    $user = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM patient WHERE username='$u' OR email='$u' LIMIT 1"));
    
    if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
        $_SESSION['client_logged_in'] = true;
        $_SESSION['user_id']          = $user['patient_id'];
        $_SESSION['user_name'] = $user['name'] ?? $user['email'];
        session_write_close();
        header("Location: client/dashboard.php");
        exit();
    } else { $error = "Invalid email or password."; }
    }
    elseif ($role === 'lab_technician') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? AND role='lab_technician'");
        $stmt->bind_param('s', $username); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if ($user && $password === $user['password']) {
            $_SESSION['staff_logged_in'] = true; $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_name'] = $user['name']; $_SESSION['role'] = 'lab_technician';
            session_write_close(); header("Location: lab_technician/dashboard.php"); exit();
        } else { $error = "Invalid email or password."; }
    }
    elseif ($role === 'pharmacist') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? AND role='pharmacist'");
        $stmt->bind_param('s', $username); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if ($user && $password === $user['password']) {
            $_SESSION['staff_logged_in'] = true; $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_name'] = $user['name']; $_SESSION['role'] = 'pharmacist';
            session_write_close(); header("Location: pharmacist/dashboard.php"); exit();
        } else { $error = "Invalid email or password."; }
    }
    elseif ($role === 'receptionist') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? AND role='receptionist'");
        $stmt->bind_param('s', $username); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if ($user && $password === $user['password']) {
            $_SESSION['staff_logged_in'] = true; $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_name'] = $user['name']; $_SESSION['role'] = 'receptionist';
            session_write_close(); header("Location: receptionist/dashboard.php"); exit();
        } else { $error = "Invalid email or password."; }
    }
    else { $error = "Invalid email or password."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_patient ? 'Patient Portal' : 'Staff Login' ?> — MedicaPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:      #0a1628;
            --gold:      #c9a84c;
            --gold-light:#e8c97a;
            --gold-pale: #fdf6e3;
        }
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'DM Sans',sans-serif;
            min-height:100vh;
            height:100vh;
            background:#ffffff;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
            padding:20px;
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

        .login-container {
            width:100%;
            max-width:clamp(460px, 40vw, 640px);
            background:#fff;
            border-radius:16px;
            box-shadow:0 30px 80px rgba(0,0,0,0.4);
            position:relative;
            z-index:1;
            overflow:hidden;
        }

        /* Top gold accent bar */
        .login-container::before {
            content:'';
            display:block;
            height:4px;
            background:linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        /* Logo area */
        .logo-section {
            text-align:center;
            padding:32px 24px 20px;
            border-bottom:1px solid #f0f0f0;
        }
        .logo-wrap {
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            margin-bottom:12px;
        }
        .logo-icon {
            width:44px;
            height:44px;
            background:linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:1.1rem;
            color:var(--navy);
        }
        .logo-text {
            font-family:'Playfair Display',serif;
            font-size:1.2rem;
            color:var(--navy);
            font-weight:700;
        }
        .logo-text span {
            color:var(--gold);
        }
        .logo-subtitle {
            font-size:.72rem;
            color:#94a3b8;
            letter-spacing:1px;
            text-transform:uppercase;
        }

        /* Form content */
        .form-section {
            padding:32px 28px;
        }

        .form-heading {
            margin-bottom:24px;
        }
        .form-heading .heading-icon {
            width:40px;
            height:40px;
            background:var(--gold-pale);
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:var(--gold);
            font-size:1rem;
            margin-bottom:12px;
        }
        .form-heading h3 {
            font-family:'Playfair Display',serif;
            font-size:1.4rem;
            color:var(--navy);
            margin-bottom:4px;
        }
        .form-heading p {
            font-size:.8rem;
            color:#94a3b8;
        }

        .alert-err {
            background:#fff0f0;
            color:#c62828;
            border-left:3px solid #c62828;
            padding:10px 14px;
            border-radius:8px;
            font-size:.82rem;
            margin-bottom:18px;
            display:flex;
            align-items:center;
            gap:8px;
        }

        .field {
            margin-bottom:10px;
        }
        .field label {
            display:block;
            font-size:.76rem;
            font-weight:700;
            color:#475569;
            margin-bottom:6px;
            text-transform:uppercase;
            letter-spacing:.5px;
        }
        .field label i {
            color:var(--gold);
            margin-right:3px;
        }
        .field-wrap {
            position:relative;
        }
        .field-wrap .fi {
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            color:#cbd5e1;
            font-size:.85rem;
            pointer-events:none;
        }
        .field-wrap input,
        .field-wrap select {
            width:100%;
    padding:8px 10px 8px 34px;   /* <-- left padding 34px kar diya icon k liye jagah */
            border:1.5px solid #e2e8f0;
            border-radius:8px;
            font-size:.8rem;
            font-family:'DM Sans',sans-serif;
            color:var(--navy);
            background:#fff;
            outline:none;
            transition:all .2s;
            appearance:none;
        }
        .field-wrap input:focus,
        .field-wrap select:focus {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,168,76,0.12);
        }
        .field-wrap input::placeholder {
            color:#cbd5e1;
        }

        /* Dropdown box width — isko chhota/bara karne ke liye 200px change karo */
        .select-wrap {
        }
        .select-wrap::after {
            content:'\f107';
            font-family:'Font Awesome 6 Free';
            font-weight:900;
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            color:#94a3b8;
            pointer-events:none;
            font-size:.8rem;
        }
        .eye-btn {
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            color:#cbd5e1;
            cursor:pointer;
            font-size:.85rem;
            transition:color .2s;
        }
        .eye-btn:hover {
            color:var(--gold);
        }

        .btn-signin {
            width:100%;
            padding:12px;
            background:var(--navy);
            color:var(--gold);
            border:2px solid var(--navy);
            border-radius:8px;
            font-size:.92rem;
            font-weight:700;
            font-family:'DM Sans',sans-serif;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            transition:all .25s;
            letter-spacing:.3px;
            margin-top:8px;
        }
        .btn-signin:hover {
            background:var(--gold);
            border-color:var(--gold);
            color:var(--navy);
        }

        .bottom-section {
            padding:0 28px 28px;
        }

     .bottom-links {
    margin-top:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:.75rem;      /* thoda chhota kiya taake ek line mein fit ho */
    flex-wrap:nowrap;      /* <-- wrap se nowrap kar diya */
    gap:8px;
}
        .bottom-links a {
            color:#94a3b8;
            text-decoration:none;
            transition:color .2s;
            display:flex;
            align-items:center;
            gap:5px;
        }
        .bottom-links a:hover {
            color:var(--navy);
        }
        .switch-link {
            color:#64748b;
            
            font-size:.8rem;
        }
        .switch-link a {
            color:var(--navy);
            font-weight:700;
            border-bottom:1.5px solid var(--gold);
            padding-bottom:1px;
            text-decoration:none;
        }
        .switch-link a:hover {
            color:var(--gold);
        }

        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding:15px;
            }
            .login-container {
                max-width:100%;
            }
            .logo-section {
                padding:24px 20px 16px;
            }
            .form-section {
                padding:24px 20px;
            }
            .bottom-section {
                padding:0 20px 20px;
            }
            .form-heading h3 {
                font-size:1.2rem;
            }
            .form-heading p {
                font-size:.75rem;
            }
            .field-wrap input,
            .field-wrap select {
                padding:10px 10px 10px 32px;
                font-size:.88rem;
            }
            .btn-signin {
                padding:11px;
                font-size:.88rem;
            }
            .bottom-links {
                flex-direction:column;
                font-size:.75rem;
            }
            .bottom-links a {
    font-size:.68rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding:12px;
            }
            .login-container {
                border-radius:12px;
            }
            .logo-section {
                padding:20px 16px 14px;
            }
            .logo-wrap {
                gap:8px;
            }
            .logo-icon {
                width:40px;
                height:40px;
                font-size:1rem;
            }
            .logo-text {
                font-size:1rem;
            }
            .logo-subtitle {
                font-size:.65rem;
            }
            .form-section {
                padding:20px 16px;
            }
            .bottom-section {
                padding:0 16px 16px;
            }
            .form-heading {
                margin-bottom:18px;
            }
            .form-heading .heading-icon {
                width:36px;
                height:36px;
                font-size:.9rem;
                margin-bottom:10px;
            }
            .form-heading h3 {
                font-size:1.1rem;
                margin-bottom:3px;
            }
            .form-heading p {
                font-size:.72rem;
            }
            .field {
                margin-bottom:14px;
            }
            .field label {
                font-size:.7rem;
                margin-bottom:5px;
            }
            .field-wrap input,
            .field-wrap select {
                padding:9px 9px 9px 30px;
                font-size:.86rem;
            }
            .btn-signin {
                padding:10px;
                font-size:.85rem;
                margin-top:6px;
            }
            .bottom-links {
                gap:6px;
                font-size:.62rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">

    <!-- LOGO & BRANDING -->
    <div class="logo-section">
        <div class="logo-wrap">
            <div class="logo-icon"><i class="fas fa-hospital-alt"></i></div>
            <div>
                <div class="logo-text">Medica<span>Pro</span></div>
                <div class="logo-subtitle">Hospital Management</div>
            </div>
        </div>
    </div>

    <!-- LOGIN FORM -->
    <div class="form-section">

        <div class="form-heading">
            <div class="heading-icon">
                <i class="fas fa-<?= $is_patient ? 'user' : 'user-shield' ?>"></i>
            </div>
            <h3><?= $is_patient ? 'Patient Login' : 'Staff Login' ?></h3>
            <p><?= $is_patient ? 'Access your medical records' : 'Secure staff access' ?></p>
        </div>

        <?php if ($error): ?>
        <div class="alert-err">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST">

            <?php if ($is_patient): ?>
                <input type="hidden" name="role" value="client">
            <?php else: ?>
                <div class="field">
                    <label><i class="fas fa-id-badge"></i> Login As</label>
                    <div class="field-wrap select-wrap">
                        <i class="fas fa-users fi"></i>
                        <select name="role">
                            <option value="admin">Administrator</option>
                            <option value="lab_technician">Lab Technician</option>
                            <option value="pharmacist">Pharmacist</option>
                            <option value="receptionist">Receptionist</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="field">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <div class="field-wrap">
                    <i class="fas fa-envelope fi"></i>
                    <input type="text" name="username" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="field">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="field-wrap">
                    <i class="fas fa-lock fi"></i>
                    <input type="password" name="password" id="passInput" placeholder="Enter your password" required>
                    <i class="fas fa-eye eye-btn" id="eyeBtn"></i>
                </div>
            </div>

            <button type="submit" class="btn-signin">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

    </div>

    <!-- BOTTOM LINKS -->
    <div class="bottom-section">
        <div class="bottom-links">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Website</a>
            <?php if ($is_patient): ?>
            <span class="switch-link">New patient? <a href="register.php">Create account</a></span>
            <?php else: ?>
            <span class="switch-link">Patient? <a href="login.php?role=client">Patient Portal</a></span>
            <?php endif; ?>
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