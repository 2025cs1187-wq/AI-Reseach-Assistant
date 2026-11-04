<?php
require_once 'config/db.php';

// Fetch all departments from DB
$depts = mysqli_query($conn, "SELECT * FROM department ORDER BY department_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Departments — MedicaPro</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
    --navy:       #0a1628;
    --navy-mid:   #112240;
    --navy-light: #1a3a5c;
    --gold:       #c9a84c;
    --gold-light: #e2c97e;
    --white:      #ffffff;
    --gray:       #f4f6f9;
    --text-dark:  #1a1a2e;
    --text-muted: #6c757d;
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--gray);
    color: var(--text-dark);
}

/* ── NAVBAR ── */
.navbar {
    background: var(--navy);
    padding: 0 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 20px rgba(0,0,0,.4);
}

.navbar-brand {
    font-family: 'Playfair Display', serif;
    color: var(--gold);
    font-size: 1.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-brand i { font-size: 1.4rem; }

.nav-links {
    display: flex;
    gap: 28px;
    list-style: none;
}

.nav-links a {
    color: rgba(255,255,255,.8);
    text-decoration: none;
    font-size: .88rem;
    font-weight: 500;
    transition: color .2s;
    letter-spacing: .3px;
}

.nav-links a:hover,
.nav-links a.active { color: var(--gold); }

/* ── PAGE HERO ── */
.page-hero {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1400&q=60') center/cover;
    opacity: .12;
}

.page-hero-content { position: relative; z-index: 1; }

.page-hero .tag {
    display: inline-block;
    background: rgba(201,168,76,.15);
    border: 1px solid rgba(201,168,76,.4);
    color: var(--gold-light);
    padding: 6px 20px;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: 2.6rem;
    color: var(--white);
    margin-bottom: 14px;
}

.page-hero h1 span { color: var(--gold); }

.page-hero p {
    color: rgba(255,255,255,.7);
    font-size: .95rem;
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.7;
}

/* ── BREADCRUMB ── */
.breadcrumb-bar {
    background: var(--white);
    padding: 12px 40px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .82rem;
    color: var(--text-muted);
}

.breadcrumb-bar a { color: var(--navy); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--gold); }
.breadcrumb-bar i { font-size: .7rem; color: var(--text-muted); }

/* ── MAIN CONTENT ── */
.container { max-width: 1200px; margin: 0 auto; padding: 50px 30px 70px; }

.section-header {
    text-align: center;
    margin-bottom: 42px;
}

.section-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: var(--navy);
    margin-bottom: 10px;
}

.section-header h2 span { color: var(--gold); }

.section-header p {
    color: var(--text-muted);
    font-size: .93rem;
    max-width: 520px;
    margin: 0 auto;
}

.gold-line {
    width: 60px; height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    border-radius: 2px;
    margin: 12px auto 0;
}

/* ── SEARCH BAR ── */
.search-wrap {
    max-width: 480px;
    margin: 0 auto 40px;
    position: relative;
}

.search-wrap input {
    width: 100%;
    padding: 14px 50px 14px 20px;
    border: 2px solid #e2e8f0;
    border-radius: 50px;
    font-size: .93rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    background: var(--white);
    transition: border-color .2s, box-shadow .2s;
    color: var(--text-dark);
}

.search-wrap input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(201,168,76,.15);
}

.search-wrap i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold);
    font-size: .95rem;
}

/* ── GRID ── */
.dept-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* ── DEPT CARD ── */
.dept-card {
    background: var(--white);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
    border: 1px solid #e8edf4;
}

.dept-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(10,22,40,.12);
}

.dept-img-wrap {
    position: relative;
    height: 190px;
    overflow: hidden;
}

.dept-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}

.dept-card:hover .dept-img-wrap img { transform: scale(1.05); }

.dept-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,22,40,.7) 0%, transparent 55%);
}

.dept-icon-badge {
    position: absolute;
    bottom: 14px;
    left: 16px;
    width: 42px; height: 42px;
    background: var(--gold);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--navy);
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,.3);
}

.dept-body {
    padding: 20px;
}

.dept-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.12rem;
    color: var(--navy);
    margin-bottom: 8px;
    font-weight: 700;
}

.dept-desc {
    font-size: .83rem;
    color: var(--text-muted);
    line-height: 1.65;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.dept-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #f0f4f8;
}

.dept-head {
    font-size: .78rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

.dept-head i { color: var(--gold); font-size: .75rem; }

.dept-btn {
    background: var(--navy);
    color: var(--gold);
    padding: 7px 16px;
    border-radius: 6px;
    font-size: .78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    border: 1.5px solid var(--navy);
    letter-spacing: .3px;
}

.dept-btn:hover {
    background: transparent;
    color: var(--navy);
}

/* No results */
.no-results {
    grid-column: 1/-1;
    text-align: center;
    padding: 60px 20px;
}

.no-results i { font-size: 3rem; color: var(--gold); opacity: .5; margin-bottom: 14px; display: block; }
.no-results p { color: var(--text-muted); font-size: .93rem; }

/* ── FOOTER ── */
.footer {
    background: var(--navy);
    color: rgba(255,255,255,.65);
    text-align: center;
    padding: 24px 40px;
    font-size: .83rem;
    border-top: 2px solid var(--gold);
}

.footer a { color: var(--gold); text-decoration: none; }

/* ── RESPONSIVE ── */
@media(max-width:900px)  { .dept-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:580px)  {
    .dept-grid { grid-template-columns: 1fr; }
    .page-hero h1 { font-size: 1.9rem; }
    .navbar { padding: 0 16px; }
    .nav-links { gap: 14px; }
    .breadcrumb-bar { padding: 10px 16px; }
    .container { padding: 30px 16px 50px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="navbar-brand">
        <i class="fas fa-hospital-alt"></i> MedicaPro
    </a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="departments.php" class="active">Departments</a></li>
        <li><a href="index.php#contact">Contact</a></li>
    </ul>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="tag"><i class="fas fa-hospital"></i> &nbsp;Our Departments</span>
        <h1>World-Class <span>Medical</span> Departments</h1>
        <p>Each department staffed by expert specialists dedicated to delivering the highest standard of patient care.</p>
    </div>
</section>

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
    <a href="index.php"><i class="fas fa-home"></i> Home</a>
    <i class="fas fa-chevron-right"></i>
    <span>Departments</span>
</div>

<!-- MAIN -->
<div class="container">

    <div class="section-header">
        <h2>Browse Our <span>Departments</span></h2>
        <p>Comprehensive healthcare services across all major medical specialties</p>
        <div class="gold-line"></div>
    </div>

    <!-- Search -->
    <div class="search-wrap">
        <input type="text" id="searchInput" placeholder="Search department..." oninput="filterDepts()">
        <i class="fas fa-search"></i>
    </div>

    <!-- Grid -->
    <div class="dept-grid" id="deptGrid">

    <?php
    // Department icons mapped by keywords
    $icons = [
        'cardio'   => 'fa-heartbeat',
        'heart'    => 'fa-heartbeat',
        'neuro'    => 'fa-brain',
        'ortho'    => 'fa-bone',
        'pediat'   => 'fa-child',
        'oncol'    => 'fa-ribbon',
        'gynae'    => 'fa-venus',
        'gynec'    => 'fa-venus',
        'eye'      => 'fa-eye',
        'ophthal'  => 'fa-eye',
        'ent'      => 'fa-head-side-cough',
        'dental'   => 'fa-tooth',
        'radiol'   => 'fa-x-ray',
        'lab'      => 'fa-flask',
        'surg'     => 'fa-user-md',
        'emer'     => 'fa-ambulance',
        'skin'     => 'fa-allergies',
        'derma'    => 'fa-allergies',
        'psych'    => 'fa-brain',
        'pulmo'    => 'fa-lungs',
        'urol'     => 'fa-kidneys',
        'gastro'   => 'fa-stomach',
        'nephro'   => 'fa-tint',
        'endocr'   => 'fa-dna',
        'rheum'    => 'fa-joint',
        'physio'   => 'fa-walking',
    ];

    // Department images mapped by keywords
    $images = [
        'cardio'   => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=600&q=70',
        'heart'    => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=600&q=70',
        'neuro'    => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=600&q=70',
        'ortho'    => 'https://images.unsplash.com/photo-1559757175-5b3e7f3a3e2c?w=600&q=70',
        'pediat'   => 'https://images.unsplash.com/photo-1581056771107-24ca5f033842?w=600&q=70',
        'oncol'    => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&q=70',
        'gynae'    => 'https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?w=600&q=70',
        'gynec'    => 'https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?w=600&q=70',
        'eye'      => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?w=600&q=70',
        'ophthal'  => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?w=600&q=70',
        'ent'      => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=600&q=70',
        'dental'   => 'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=600&q=70',
        'radiol'   => 'https://images.unsplash.com/photo-1530026405186-ed1f139313f3?w=600&q=70',
        'lab'      => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=600&q=70',
        'emer'     => 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=600&q=70',
        'surg'     => 'https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=600&q=70',
        'skin'     => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=600&q=70',
        'derma'    => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=600&q=70',
        'pulmo'    => 'https://images.unsplash.com/photo-1631815589968-fdb09a223b1e?w=600&q=70',
        'physio'   => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=70',
    ];

    $default_img  = 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&q=70';
    $default_icon = 'fa-hospital';

    function getByKeyword($name, $map, $default) {
        $lower = strtolower($name);
        foreach ($map as $key => $val) {
            if (str_contains($lower, $key)) return $val;
        }
        return $default;
    }

    if (mysqli_num_rows($depts) > 0):
        while ($d = mysqli_fetch_assoc($depts)):
            $name      = htmlspecialchars($d['name'] ?? $d['department_name'] ?? 'Department');
            $desc      = htmlspecialchars($d['description'] ?? 'Comprehensive specialized medical care.');
            $head      = htmlspecialchars($d['head_doctor'] ?? $d['head'] ?? '');
            $icon      = getByKeyword($name, $icons, $default_icon);
            $img       = getByKeyword($name, $images, $default_img);
            $id        = $d['department_id'] ?? $d['id'] ?? '';
    ?>
        <div class="dept-card" data-name="<?= strtolower($name) ?>">
            <div class="dept-img-wrap">
                <img src="<?= $img ?>" alt="<?= $name ?>"
                     onerror="this.src='<?= $default_img ?>'">
                <div class="dept-img-overlay"></div>
                <div class="dept-icon-badge">
                    <i class="fas <?= $icon ?>"></i>
                </div>
            </div>
            <div class="dept-body">
                <div class="dept-name"><?= $name ?></div>
                <div class="dept-desc"><?= $desc ?></div>
                <div class="dept-footer">
                    <?php if ($head): ?>
                    <div class="dept-head">
                        <i class="fas fa-user-md"></i> <?= $head ?>
                    </div>
                    <?php else: ?>
                    <div class="dept-head">
                        <i class="fas fa-hospital"></i> Specialized Care
                    </div>
                    <?php endif; ?>
                    <a href="index.php#appointment" class="dept-btn">Book Now</a>
                </div>
            </div>
        </div>
    <?php
        endwhile;
    else: ?>
        <div class="no-results">
            <i class="fas fa-hospital"></i>
            <p>No departments found.</p>
        </div>
    <?php endif; ?>

    </div><!-- /dept-grid -->
</div>

<!-- FOOTER -->
<footer class="footer">
    <p>&copy; <?= date('Y') ?> MedicaPro Hospital Management System. All rights reserved.
    &nbsp;|&nbsp; <a href="index.php">Home</a></p>
</footer>

<script>
function filterDepts() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.dept-card');
    let visible = 0;
    cards.forEach(card => {
        const match = card.dataset.name.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    // Show no-results if nothing matches
    const nr = document.querySelector('.no-results');
    if (nr) nr.style.display = visible === 0 ? 'block' : 'none';
}
</script>

</body>
</html>
<?php mysqli_close($conn); ?>