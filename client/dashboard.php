<?php
session_start();
require_once '../config/db.php';

$active_page  = 'dashboard';
$page_title   = 'Dashboard';
$top_bar_icon = 'th-large';

require_once __DIR__ . '/../includes/client_header.php';
$patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM patient WHERE patient_id=$patient_id"));

$appt_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointment WHERE patient_id=$patient_id"))['cnt'];
$bill_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bills WHERE patient_id=$patient_id"))['cnt'];
$presc_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM prescriptions WHERE patient_id=$patient_id"))['cnt'];
?>

<style>
    .welcome-card {
        background: linear-gradient(135deg, #0a1628 0%, #162848 60%, #0d1f3c 100%);
        color: #fff;
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(201,168,76,0.25);
        box-shadow: 0 8px 30px rgba(10,22,40,0.15);
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #c9a84c, #e8c97a, #c9a84c);
    }
    .welcome-card h2 { font-size:1.4rem; margin-bottom:6px; font-weight:800; }
    .welcome-card p  { font-size:.9rem; opacity:.75; }
    .welcome-card .wicon { font-size:3.5rem; color:rgba(201,168,76,0.3); }
    .options-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
    .option-card {
        background: #fff;
        border-radius: 14px;
        padding: 28px;
        text-decoration: none;
        color: #333;
        box-shadow: 0 2px 10px rgba(10,22,40,.06);
        transition: all .2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 14px;
        border: 2px solid transparent;
    }
    .option-card:hover {
        border-color: #c9a84c;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(10,22,40,.12);
    }
    .card-icon { width:68px; height:68px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; }
    .option-card h3 { font-size:1rem; font-weight:700; color:#0a1628; }
    .option-card p  { font-size:.82rem; color:#888; }
    .option-card span.count {
        font-size: .8rem;
        background: #fdf6e3;
        color: #a07000;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        border: 1px solid rgba(201,168,76,0.3);
    }
    .icon-blue   { background:#e3f2fd; color:#1565c0; }
    .icon-green  { background:#e8f5e9; color:#2e7d32; }
    .icon-purple { background:#fdf6e3; color:#a07000; }
    .icon-orange { background:#fff3e0; color:#e65100; }
</style>

<div class="welcome-card">
    <div>
        <h2>Welcome, <?= htmlspecialchars($patient_name) ?>! 👋</h2>
        <p>Select an option below to view your medical information.</p>
    </div>
    <div class="wicon"><i class="fas fa-hospital-alt"></i></div>
</div>

<div class="options-grid">
    <a href="my_appointments.php" class="option-card">
        <div class="card-icon icon-blue"><i class="fas fa-calendar-check"></i></div>
        <h3>My Appointments</h3>
        <p>Check your scheduled doctor appointments</p>
        <span class="count"><?= $appt_count ?> records</span>
    </a>
    <a href="my_bills.php" class="option-card">
        <div class="card-icon icon-green"><i class="fas fa-file-invoice-dollar"></i></div>
        <h3>My Bills</h3>
        <p>View your payment and billing history</p>
        <span class="count"><?= $bill_count ?> records</span>
    </a>
    <a href="my_prescription.php" class="option-card">
        <div class="card-icon icon-orange"><i class="fas fa-prescription-bottle-alt"></i></div>
        <h3>My Prescriptions</h3>
        <p>View medicines prescribed by doctors</p>
        <span class="count"><?= $presc_count ?> records</span>
    </a>
    <a href="my_profile.php" class="option-card">
        <div class="card-icon icon-purple"><i class="fas fa-user"></i></div>
        <h3>My Profile</h3>
        <p>View and update your personal information</p>
    </a>
</div>

<?php require_once __DIR__ . '/../includes/client_footer.php'; ?>