<?php
session_start();
require_once 'config/db.php';

// Search/filter
$where = "WHERE 1=1";
if (!empty($_GET['search'])) {
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (name LIKE '%$s%' OR specialization LIKE '%$s%')";
}
if (!empty($_GET['gender'])) {
    $g = mysqli_real_escape_string($conn, $_GET['gender']);
    $where .= " AND gender='$g'";
}

$doctors = mysqli_query($conn, "SELECT * FROM doctor $where ORDER BY name ASC");
$total   = mysqli_num_rows($doctors);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Doctors — MedicaPro</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
    --navy: #0a1628;
    --navy-2: #112240;
    --navy-3: #1a3a5c;
    --gold: #c9a84c;
    --gold-light: #e8c96d;
    --gold-pale: #f5e9c8;
    --white: #ffffff;
    --off-white: #f8f6f0;
    --gray: #6b7280;
}
* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }
body { font-family:'DM Sans',sans-serif; color:var(--navy); background:var(--white); }

/* TOPBAR */
.topbar { background:var(--navy); padding:10px 80px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(201,168,76,0.3); }
.topbar-left { display:flex; gap:25px; }
.topbar-left a { color:rgba(255,255,255,0.65); font-size:.8rem; text-decoration:none; display:flex; align-items:center; gap:6px; }
.topbar-left a i { color:var(--gold); }
.topbar-right { display:flex; gap:12px; }
.topbar-right a { color:rgba(255,255,255,0.7); font-size:.8rem; font-weight:500; text-decoration:none; padding:4px 14px; border:1px solid rgba(201,168,76,0.4); border-radius:4px; transition:all .2s; }
.topbar-right a:hover { background:var(--gold); color:var(--navy); }

/* NAVBAR */
.navbar { background:var(--white); padding:0 80px; display:flex; justify-content:space-between; align-items:center; height:85px; box-shadow:0 2px 30px rgba(10,22,40,0.08); position:sticky; top:0; z-index:999; border-bottom:3px solid var(--gold); }
.nav-logo { display:flex; align-items:center; gap:14px; text-decoration:none; }
.nav-logo-icon { width:52px; height:52px; background:var(--navy); border-radius:10px; display:flex; align-items:center; justify-content:center; position:relative; }
.nav-logo-icon::after { content:''; position:absolute; inset:3px; border:1.5px solid var(--gold); border-radius:7px; }
.nav-logo-icon i { color:var(--gold); font-size:1.4rem; }
.nav-logo-text h1 { font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--navy); font-weight:700; line-height:1; }
.nav-logo-text h1 span { color:var(--gold); }
.nav-logo-text p { font-size:.7rem; color:var(--gray); letter-spacing:2px; text-transform:uppercase; margin-top:2px; }
.nav-menu { display:flex; gap:35px; }
.nav-menu a { text-decoration:none; color:var(--navy); font-size:.9rem; font-weight:500; position:relative; padding-bottom:4px; transition:color .2s; }
.nav-menu a::after { content:''; position:absolute; bottom:0; left:0; width:0; height:2px; background:var(--gold); transition:width .3s; }
.nav-menu a:hover, .nav-menu a.active { color:var(--gold); }
.nav-menu a:hover::after, .nav-menu a.active::after { width:100%; }
.btn-nav-book { background:var(--gold); color:var(--navy); padding:10px 22px; border-radius:6px; font-size:.85rem; font-weight:700; text-decoration:none; border:2px solid var(--gold); transition:all .2s; }
.btn-nav-book:hover { background:transparent; color:var(--gold); }

/* PAGE HERO */
.page-hero {
    background: linear-gradient(to right, rgba(10,22,40,0.92) 50%, rgba(10,22,40,0.5) 100%),
                url('https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=1600&q=80') center/cover no-repeat;
    padding:80px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    min-height:280px;
}
.page-hero-content {}
.page-hero-content .breadcrumb { display:flex; align-items:center; gap:8px; margin-bottom:15px; }
.page-hero-content .breadcrumb a { color:rgba(255,255,255,0.5); font-size:.82rem; text-decoration:none; }
.page-hero-content .breadcrumb a:hover { color:var(--gold); }
.page-hero-content .breadcrumb span { color:rgba(255,255,255,0.3); font-size:.82rem; }
.page-hero-content .breadcrumb .current { color:var(--gold); font-size:.82rem; }
.page-hero-content h1 { font-family:'Playfair Display',serif; font-size:3rem; color:var(--white); font-weight:700; margin-bottom:12px; }
.page-hero-content h1 span { color:var(--gold); }
.page-hero-content p { color:rgba(255,255,255,0.65); font-size:.95rem; }
.page-hero-stats { display:flex; gap:40px; }
.page-hero-stat { text-align:center; }
.page-hero-stat .num { font-family:'Playfair Display',serif; font-size:2.5rem; color:var(--gold); font-weight:700; line-height:1; }
.page-hero-stat .lbl { color:rgba(255,255,255,0.5); font-size:.75rem; margin-top:5px; text-transform:uppercase; letter-spacing:1px; }

/* FILTER BAR */
.filter-bar { background:var(--navy-2); padding:25px 80px; display:flex; gap:15px; align-items:center; border-bottom:2px solid rgba(201,168,76,0.2); }
.filter-bar form { display:flex; gap:12px; align-items:center; width:100%; flex-wrap:wrap; }
.filter-input {
    padding:11px 18px;
    border:1px solid rgba(201,168,76,0.25);
    border-radius:6px;
    background:rgba(255,255,255,0.06);
    color:var(--white);
    font-size:.88rem;
    font-family:'DM Sans',sans-serif;
    outline:none;
    transition:border .2s;
    min-width:280px;
}
.filter-input::placeholder { color:rgba(255,255,255,0.35); }
.filter-input:focus { border-color:var(--gold); }
.filter-select {
    padding:11px 18px;
    border:1px solid rgba(201,168,76,0.25);
    border-radius:6px;
    background:rgba(255,255,255,0.06);
    color:var(--white);
    font-size:.88rem;
    font-family:'DM Sans',sans-serif;
    outline:none;
    cursor:pointer;
    transition:border .2s;
}
.filter-select option { background:var(--navy-2); color:var(--white); }
.filter-select:focus { border-color:var(--gold); }
.btn-filter { padding:11px 24px; background:var(--gold); color:var(--navy); border:none; border-radius:6px; font-size:.88rem; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; display:flex; align-items:center; gap:7px; transition:background .2s; }
.btn-filter:hover { background:var(--gold-light); }
.btn-reset { padding:11px 20px; background:transparent; color:rgba(255,255,255,0.5); border:1px solid rgba(255,255,255,0.15); border-radius:6px; font-size:.85rem; cursor:pointer; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
.btn-reset:hover { color:var(--white); border-color:rgba(255,255,255,0.4); }
.result-count { margin-left:auto; color:rgba(255,255,255,0.45); font-size:.85rem; white-space:nowrap; }
.result-count span { color:var(--gold); font-weight:700; }

/* MAIN CONTENT */
.main-content { padding:60px 80px; background:var(--off-white); min-height:500px; }

/* DOCTORS GRID */
.doctors-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; }

/* DOCTOR CARD */
.doctor-card { background:var(--white); border-radius:14px; overflow:hidden; border:1px solid rgba(10,22,40,0.07); transition:all .3s; }
.doctor-card:hover { box-shadow:0 20px 50px rgba(10,22,40,0.12); transform:translateY(-5px); border-color:rgba(201,168,76,0.3); }
.doctor-photo { height:230px; position:relative; overflow:hidden; }
.doctor-photo img { width:100%; height:100%; object-fit:cover; object-position:top center; transition:transform .4s; }
.doctor-card:hover .doctor-photo img { transform:scale(1.04); }
.doctor-photo-badge { position:absolute; top:12px; right:12px; background:var(--gold); color:var(--navy); font-size:.68rem; font-weight:700; padding:4px 10px; border-radius:4px; }
.doctor-gender-badge { position:absolute; top:12px; left:12px; font-size:.68rem; font-weight:700; padding:4px 10px; border-radius:4px; }
.doctor-gender-badge.male { background:rgba(10,22,40,0.8); color:rgba(255,255,255,0.85); }
.doctor-gender-badge.female { background:rgba(180,80,120,0.8); color:#fff; }
.doctor-body { padding:20px; }
.doctor-body h3 { font-family:'Playfair Display',serif; font-size:1rem; font-weight:600; color:var(--navy); margin-bottom:4px; }
.doctor-spec { color:var(--gold); font-size:.8rem; font-weight:600; margin-bottom:8px; display:block; }
.doctor-qual { color:var(--gray); font-size:.78rem; margin-bottom:5px; display:flex; align-items:center; gap:5px; }
.doctor-qual i { color:var(--gold); font-size:.75rem; }
.doctor-exp { color:var(--gray); font-size:.78rem; margin-bottom:12px; display:flex; align-items:center; gap:5px; }
.doctor-exp i { color:var(--gold); font-size:.75rem; }
.doctor-divider { height:1px; background:rgba(10,22,40,0.07); margin:12px 0; }
.doctor-fee { color:var(--navy); font-size:.88rem; font-weight:700; display:flex; align-items:center; justify-content:space-between; }
.doctor-fee span { color:var(--gray); font-weight:400; font-size:.75rem; }
.btn-doctor { display:block; margin-top:12px; padding:10px; background:var(--navy); color:var(--gold); border-radius:6px; text-align:center; text-decoration:none; font-size:.82rem; font-weight:700; transition:all .2s; border:2px solid var(--navy); }
.btn-doctor:hover { background:transparent; color:var(--navy); }

/* NO RESULTS */
.no-results { grid-column:span 4; text-align:center; padding:80px 20px; }
.no-results i { font-size:4rem; color:rgba(10,22,40,0.1); margin-bottom:20px; display:block; }
.no-results h3 { font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--navy); margin-bottom:10px; }
.no-results p { color:var(--gray); margin-bottom:25px; }
.no-results a { display:inline-flex; align-items:center; gap:8px; background:var(--navy); color:var(--gold); padding:12px 28px; border-radius:6px; text-decoration:none; font-weight:700; }

/* FOOTER */
footer { background:var(--navy); padding:50px 80px 25px; border-top:4px solid var(--gold); }
.footer-bottom { display:flex; justify-content:space-between; align-items:center; padding-top:25px; border-top:1px solid rgba(255,255,255,0.08); margin-top:30px; }
.footer-bottom p { color:rgba(255,255,255,0.3); font-size:.8rem; }
.footer-bottom a { color:var(--gold); text-decoration:none; font-size:.8rem; }
.footer-info { display:flex; gap:50px; margin-bottom:30px; }
.footer-info div h4 { font-size:.8rem; font-weight:700; color:var(--gold); text-transform:uppercase; letter-spacing:2px; margin-bottom:15px; }
.footer-info div a { display:block; color:rgba(255,255,255,0.45); font-size:.85rem; text-decoration:none; margin-bottom:8px; transition:color .2s; }
.footer-info div a:hover { color:var(--gold); }
.footer-brand p { color:rgba(255,255,255,0.4); font-size:.85rem; line-height:1.8; max-width:300px; }
.footer-brand h2 { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--white); font-weight:700; margin-bottom:12px; }
.footer-brand h2 span { color:var(--gold); }
</style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <a href="#"><i class="fas fa-phone"></i> Emergency: (042) 111 000 111</a>
        <a href="#"><i class="fas fa-clock"></i> Mon–Sat: 8am – 10pm</a>
        <a href="#"><i class="fas fa-map-marker-alt"></i> 123 Medical Street, Lahore</a>
    </div>
    <div class="topbar-right">
        <a href="login.php?role=client">Patient Portal</a>
        <a href="login.php">Staff Login</a>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="nav-logo">
        <div class="nav-logo-icon"><i class="fas fa-hospital-alt"></i></div>
        <div class="nav-logo-text">
            <h1>Medica<span>Pro</span></h1>
            <p>Advanced Healthcare</p>
        </div>
    </a>
    <div class="nav-menu">
        <a href="index.php">Home</a>
        <a href="index.php#about">About</a>
        <a href="index.php#services">Services</a>
        <a href="index.php#departments">Departments</a>
        <a href="doctors.php" class="active">Doctors</a>
        <a href="index.php#contact">Contact</a>
    </div>
    <a href="index.php#appointment" class="btn-nav-book"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
</nav>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="page-hero-content">
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <span class="current">Our Doctors</span>
        </div>
        <h1>Our Expert <span>Physicians</span></h1>
        <p>Internationally trained specialists dedicated to your health and wellbeing</p>
    </div>
    <div class="page-hero-stats">
        <div class="page-hero-stat">
            <div class="num"><?= $total ?>+</div>
            <div class="lbl">Doctors</div>
        </div>
        <div class="page-hero-stat">
            <div class="num">15+</div>
            <div class="lbl">Specialties</div>
        </div>
        <div class="page-hero-stat">
            <div class="num">24/7</div>
            <div class="lbl">Available</div>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="filter-bar">
    <form method="GET">
        <input type="text" name="search" class="filter-input"
               placeholder="&#xf002;  Search by doctor name or specialization..."
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="gender" class="filter-select">
            <option value="">All Genders</option>
            <option value="Male" <?= ($_GET['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male Doctors</option>
            <option value="Female" <?= ($_GET['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female Doctors</option>
        </select>
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Search</button>
        <a href="doctors.php" class="btn-reset"><i class="fas fa-times"></i> Reset</a>
        <div class="result-count"><span><?= $total ?></span> Doctor(s) Found</div>
    </form>
</div>

<!-- DOCTORS GRID -->
<div class="main-content">
    <div class="doctors-grid">
        <?php if($total > 0):
            mysqli_data_seek($doctors, 0);
            while($d = mysqli_fetch_assoc($doctors)):
                $photo_path = 'assets/doctors/' . $d['photo'];
                $has_photo  = !empty($d['photo']) && file_exists($photo_path);
                $avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($d['name']) . '&size=400&background=1a3a5c&color=c9a84c&bold=true&format=svg';
                $gender_class = strtolower($d['gender'] ?? 'male') === 'female' ? 'female' : 'male';
        ?>
        <div class="doctor-card">
            <div class="doctor-photo">
                <img src="<?= $has_photo ? htmlspecialchars($photo_path) : $avatar_url ?>"
                     alt="<?= htmlspecialchars($d['name']) ?>">
                <span class="doctor-photo-badge">Available</span>
                <?php if(!empty($d['gender'])): ?>
                <span class="doctor-gender-badge <?= $gender_class ?>">
                    <i class="fas <?= $gender_class === 'female' ? 'fa-venus' : 'fa-mars' ?>"></i>
                    <?= htmlspecialchars($d['gender']) ?>
                </span>
                <?php endif; ?>
            </div>
            <div class="doctor-body">
                <h3><?= htmlspecialchars($d['name']) ?></h3>
                <span class="doctor-spec"><?= htmlspecialchars($d['specialization'] ?? 'General Physician') ?></span>
                <?php if(!empty($d['qualification'])): ?>
                <div class="doctor-qual"><i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($d['qualification']) ?></div>
                <?php endif; ?>
                <?php if(!empty($d['experience'])): ?>
                <div class="doctor-exp"><i class="fas fa-briefcase-medical"></i> <?= htmlspecialchars($d['experience']) ?> Years Experience</div>
                <?php endif; ?>
                <div class="doctor-divider"></div>
                <div class="doctor-fee">
                    Rs. <?= number_format($d['consultation_fee'] ?? 0, 0) ?>
                    <span>/ consultation</span>
                </div>
                <a href="index.php#appointment" class="btn-doctor">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="no-results">
            <i class="fas fa-user-md"></i>
            <h3>No Doctors Found</h3>
            <p>No doctors match your search. Please try different keywords.</p>
            <a href="doctors.php"><i class="fas fa-arrow-left"></i> View All Doctors</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-info">
        <div class="footer-brand">
            <h2>Medica<span>Pro</span></h2>
            <p>Delivering world-class healthcare with compassion and excellence. Your health is our highest priority.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <a href="index.php">Home</a>
            <a href="index.php#about">About Us</a>
            <a href="index.php#services">Services</a>
            <a href="doctors.php">Our Doctors</a>
            <a href="index.php#appointment">Book Appointment</a>
        </div>
        <div>
            <h4>Contact</h4>
            <a href="#"><i class="fas fa-phone"></i> (042) 111 000 111</a>
            <a href="#"><i class="fas fa-envelope"></i> info@medicapro.pk</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> 123 Medical Street, Lahore</a>
            <a href="#"><i class="fas fa-clock"></i> Mon–Sat: 8am – 10pm</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> MedicaPro Hospital. All Rights Reserved.</p>
        <a href="index.php">← Back to Home</a>
    </div>
</footer>

</body>
</html>