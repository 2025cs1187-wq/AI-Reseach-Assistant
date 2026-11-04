<?php
// ============================================================
// FILE: index.php
// PURPOSE: Main homepage of MedicaPro Hospital website
// This file displays: Hero, About, Services, Departments,
// Doctors, Stats, Why Us, Appointment Form, Testimonials,
// Contact, and Footer sections.
// ============================================================

session_start(); // Start PHP session to check if patient is logged in
require_once 'config/db.php'; // Include database connection file
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedicaPro — Premium Healthcare</title>

<!-- Font Awesome: Icons library for all icons used on the page -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Google Fonts: Playfair Display for headings, DM Sans for body text -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* ── CSS VARIABLES ─────────────────────────────────────────
   Global color palette used throughout the entire website.
   Navy = dark blue background, Gold = accent color
   ───────────────────────────────────────────────────────── */
:root {
    --navy: #0a1628;        /* Main dark navy blue */
    --navy-2: #112240;      /* Slightly lighter navy */
    --navy-3: #1a3a5c;      /* Medium navy for gradients */
    --navy-4: #1e4976;      /* Lighter navy shade */
    --gold: #c9a84c;        /* Main gold accent color */
    --gold-light: #e8c96d;  /* Lighter gold for hover effects */
    --gold-pale: #f5e9c8;   /* Very light gold for backgrounds */
    --white: #ffffff;       /* Pure white */
    --off-white: #f8f6f0;   /* Slightly warm white for section backgrounds */
    --gray: #6b7280;        /* Body text gray */
    --light: #f0ede4;       /* Light beige background */
}

/* ── RESET & BASE ──────────────────────────────────────────
   Remove default browser margins/padding, use border-box
   sizing for all elements, enable smooth scroll
   ───────────────────────────────────────────────────────── */
* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; } /* Smooth scroll when clicking anchor links */
body { font-family:'DM Sans',sans-serif; color:var(--navy); background:var(--white); overflow-x:hidden; }
</style>
</head>
<body>


<?php
// ============================================================
// TOPBAR
// Thin dark bar at very top showing hospital info + login buttons
// Patient Portal → login.php?role=client (patient login)
// Staff Login    → login.php?role=staff  (staff/admin login)
// ============================================================
?>
<style>
/* ── TOPBAR ────────────────────────────────────────────────
   The thin dark bar at the very top of the page.
   Shows: emergency number, hours, address, social icons,
   Patient Portal button, Staff Login button
   ───────────────────────────────────────────────────────── */
.topbar {
    background:var(--navy);
    padding:10px 80px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid rgba(201,168,76,0.3); /* Subtle gold bottom border */
}
.topbar-left { display:flex; gap:25px; }
.topbar-left a { color:rgba(255,255,255,0.65); font-size:.8rem; text-decoration:none; display:flex; align-items:center; gap:6px; transition:color .2s; }
.topbar-left a i { color:var(--gold); } /* Icons in gold color */
.topbar-left a:hover { color:#fff; }
.topbar-right { display:flex; gap:15px; align-items:center; }
.topbar-right a { color:rgba(255,255,255,0.7); font-size:.8rem; font-weight:500; text-decoration:none; padding:4px 14px; border:1px solid rgba(201,168,76,0.4); border-radius:4px; transition:all .2s; }
.topbar-right a:hover { background:var(--gold); color:var(--navy); border-color:var(--gold); }
.topbar-social { display:flex; gap:10px; } /* Social media icons row */
.topbar-social a { color:rgba(255,255,255,0.5); font-size:.85rem; border:none; padding:0; transition:color .2s; }
.topbar-social a:hover { background:transparent; color:var(--gold); }
</style>

<div class="topbar">
    <div class="topbar-left">
        <!-- Emergency phone number -->
        <a href="#"><i class="fas fa-phone"></i> Emergency: (042) 112 000 111</a>
        <!-- Working hours -->
        <a href="#"><i class="fas fa-clock"></i> Mon–Sat: 8am – 10pm</a>
        <!-- Hospital address -->
        <a href="#"><i class="fas fa-map-marker-alt"></i> 123 Medical Street, Lahore</a>
    </div>
    <div class="topbar-right">
        <!-- Social media icons -->
        <div class="topbar-social">
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
        <!-- Staff login button → goes to login page with role=staff preset (shows dropdown for Admin/Lab/Pharma/Receptionist) -->
        <a href="login.php?role=staff" style="margin-left:12px;">Staff Login</a>
        <!-- Patient login button → goes to login page with role=client preset -->
        <a href="login.php?role=client">Patient Portal</a>
    </div>
</div>


<?php
// ============================================================
// MAIN NAVBAR
// Sticky navigation bar with logo, menu links, Book Appointment
// ============================================================
?>
<style>
/* ── NAVBAR ────────────────────────────────────────────────
   Main sticky navigation bar below topbar.
   Shows: Logo, nav menu links, phone number, Book Appointment button
   position:sticky keeps it visible while scrolling
   ───────────────────────────────────────────────────────── */
.navbar {
    background:var(--white);
    padding:0 80px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    height:85px;
    box-shadow:0 2px 30px rgba(10,22,40,0.08);
    position:sticky; /* Sticks to top when user scrolls */
    top:0;
    z-index:999; /* High z-index so it stays above all other content */
    border-bottom:3px solid var(--gold); /* Gold bottom border accent */
}
/* Logo container with gold border inner effect */
.nav-logo { display:flex; align-items:center; gap:14px; text-decoration:none; }
.nav-logo-icon { width:52px; height:52px; background:var(--navy); border-radius:10px; display:flex; align-items:center; justify-content:center; position:relative; }
.nav-logo-icon::after { content:''; position:absolute; inset:3px; border:1.5px solid var(--gold); border-radius:7px; } /* Inner gold border on logo */
.nav-logo-icon i { color:var(--gold); font-size:1.4rem; }
.nav-logo-text h1 { font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--navy); font-weight:700; line-height:1; }
.nav-logo-text h1 span { color:var(--gold); } /* "Pro" part in gold */
.nav-logo-text p { font-size:.7rem; color:var(--gray); letter-spacing:2px; text-transform:uppercase; margin-top:2px; }
/* Navigation menu links with animated gold underline on hover */
.nav-menu { display:flex; gap:35px; }
.nav-menu a { text-decoration:none; color:var(--navy); font-size:.9rem; font-weight:500; letter-spacing:.3px; position:relative; padding-bottom:4px; transition:color .2s; }
.nav-menu a::after { content:''; position:absolute; bottom:0; left:0; width:0; height:2px; background:var(--gold); transition:width .3s; } /* Gold underline animation */
.nav-menu a:hover { color:var(--gold); }
.nav-menu a:hover::after { width:100%; } /* Expand underline on hover */
.nav-actions { display:flex; gap:12px; align-items:center; }
.nav-phone { font-size:.85rem; font-weight:600; color:var(--navy); }
.nav-phone i { color:var(--gold); margin-right:5px; }
/* Gold "Book Appointment" button in navbar */
.btn-nav-book { background:var(--gold); color:var(--navy); padding:10px 22px; border-radius:6px; font-size:.85rem; font-weight:700; text-decoration:none; letter-spacing:.3px; transition:all .2s; border:2px solid var(--gold); }
.btn-nav-book:hover { background:transparent; color:var(--gold); }
/* Hamburger button: sirf mobile/tablet par dikhega (media query me flex hoga) */
.nav-hamburger { display:none; background:none; border:none; font-size:1.5rem; color:var(--navy); cursor:pointer; padding:8px; }
/* Yeh link sirf mobile dropdown menu me dikhega, desktop par hidden */
.nav-book-mobile { display:none; }
</style>

<nav class="navbar">
    <!-- Hospital Logo -->
    <a href="index.php" class="nav-logo">
        <div class="nav-logo-icon"><i class="fas fa-hospital-alt"></i></div>
        <div class="nav-logo-text">
            <h1>Medica<span>Pro</span></h1>
            <p>Advanced Healthcare</p>
        </div>
    </a>
    <!-- Navigation menu links (all anchor links to page sections) -->
    <div class="nav-menu">
        <a href="#home">Home</a>
      
        <a href="#about">About</a>
        
        <a href="#services">Services</a>
        <a href="#departments">Departments</a>
        <a href="#doctors">Doctors</a>
        <a href="#contact">Contact</a>
        <!-- Sirf mobile menu k andar dikhega: Book Appointment shortcut -->
        <a href="#appointment" class="nav-book-mobile"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    </div>
    <!-- Right side: phone number + Book Appointment button -->
    <div class="nav-actions">
        <span class="nav-phone"><i class="fas fa-phone"></i> (042) 111 000 111</span>
        <a href="#appointment" class="btn-nav-book"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    </div>
    <!-- Hamburger button: mobile/tablet par nav-menu open/close karta hai -->
    <button class="nav-hamburger" id="navHamburger" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
</nav>
<script>
// Mobile menu toggle: hamburger click par .nav-menu open/close hoti hai
document.getElementById('navHamburger').addEventListener('click', function(){
    document.querySelector('.nav-menu').classList.toggle('active');
    var icon = this.querySelector('i');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
});
// Link par tap karne k baad menu apne aap band ho jaye
document.querySelectorAll('.nav-menu a').forEach(function(link){
    link.addEventListener('click', function(){
        document.querySelector('.nav-menu').classList.remove('active');
        document.querySelector('#navHamburger i').classList.add('fa-bars');
        document.querySelector('#navHamburger i').classList.remove('fa-times');
    });
});
</script>


<?php
// ============================================================
// HERO SECTION
// Full-screen section with hospital background image.
// Shows headline, description, 2 CTA buttons, stats, and 4 quick cards
// ============================================================
?>
<style>
/* ── HERO SECTION ──────────────────────────────────────────
   Full-screen hero with hospital background image.
   Left side: headline, buttons, stats
   Right side: 4 quick access cards (Find Doctor, Lab Reports, etc.)
   Background: Real hospital image from Unsplash with dark overlay
   ───────────────────────────────────────────────────────── */
.hero {
    min-height:92vh;
    display:flex;
    align-items:center;
    padding:80px;
    position:relative;
    overflow:hidden;
    /* Dark gradient overlay on left for text readability + hospital image */
    background: linear-gradient(to right, rgba(10,22,40,0.75) 45%, rgba(10,22,40,0.25) 100%),
                url('https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=1600&q=80') center/cover no-repeat;
}
/* Subtle gold grid pattern overlay for texture */
.hero-bg-pattern { position:absolute; inset:0; background-image:linear-gradient(rgba(201,168,76,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,0.04) 1px,transparent 1px); background-size:60px 60px; pointer-events:none; }
.hero-content { max-width:620px; z-index:2; } /* Main text content on left */
/* Gold pill badge above headline */
.hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.15); border:1px solid rgba(201,168,76,0.4); color:var(--gold-light); padding:7px 18px; border-radius:30px; font-size:.8rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:28px; }
.hero h1 { font-family:'Playfair Display',serif; font-size:3.8rem; color:var(--white); font-weight:700; line-height:1.15; margin-bottom:22px; }
.hero h1 .gold { color:var(--gold); } /* Gold colored word in headline */
.hero p { color:rgba(255,255,255,0.75); font-size:1rem; line-height:1.8; margin-bottom:40px; max-width:500px; }
.hero-btns { display:flex; gap:15px; flex-wrap:wrap; margin-bottom:55px; }
/* Primary CTA button: gold background */
.btn-hero-primary { background:var(--gold); color:var(--navy); padding:15px 32px; border-radius:6px; font-size:.95rem; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:8px; transition:all .2s; }
.btn-hero-primary:hover { background:var(--gold-light); }
/* Secondary CTA button: transparent with white border */
.btn-hero-outline { border:2px solid rgba(255,255,255,0.35); color:var(--white); padding:15px 32px; border-radius:6px; font-size:.95rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:8px; transition:all .2s; }
.btn-hero-outline:hover { border-color:var(--gold); color:var(--gold); }
/* Statistics row at bottom of hero (Patients, Doctors, etc.) */
.hero-stats { display:flex; gap:50px; padding-top:38px; border-top:1px solid rgba(255,255,255,0.12); }
.hero-stat-num { font-family:'Playfair Display',serif; font-size:2.2rem; color:var(--gold); font-weight:700; line-height:1; }
.hero-stat-label { color:rgba(255,255,255,0.5); font-size:.78rem; margin-top:5px; letter-spacing:.5px; }
/* 4 quick access cards on right side of hero */
.hero-right { position:absolute; right:80px; top:50%; transform:translateY(-50%); display:grid; grid-template-columns:1fr 1fr; gap:15px; z-index:2; }
.hero-card { background:rgba(255,255,255,0.06); border:1px solid rgba(201,168,76,0.25); border-radius:12px; padding:22px; text-align:center; backdrop-filter:blur(12px); transition:all .3s; text-decoration:none; }
.hero-card:hover { background:rgba(201,168,76,0.12); border-color:rgba(201,168,76,0.6); transform:translateY(-3px); }
.hero-card i { font-size:1.8rem; color:var(--gold); margin-bottom:10px; display:block; }
.hero-card span { color:rgba(255,255,255,0.85); font-size:.85rem; font-weight:500; }
</style>

<?php
// ── FETCH STATS ────────────────────────────────────────────
// Get total counts for displaying in hero and stats strip
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM patient"))['t'];
$total_doctors  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM doctor"))['t'];
$total_appts    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointment"))['t'];
?>

<section class="hero" id="home">
    <!-- Subtle gold grid overlay pattern -->
    <div class="hero-bg-pattern"></div>

    <!-- Left side: main content -->
    <div class="hero-content">
        <!-- Gold pill badge -->
        <div class="hero-badge"><i class="fas fa-award"></i> Pakistan's Premier Healthcare Provider</div>

        <!-- Main headline -->
        <h1>Excellence in <br class="hero-br-1">Healthcare, <br><span class="gold">Compassion in Care</span></h1>

        <!-- Short description -->
        <p>MedicaPro delivers world-class medical services with internationally trained physicians, cutting-edge diagnostics, and patient-centered treatment protocols.</p>

        <!-- Two CTA buttons -->
        <div class="hero-btns">
            <a href="#appointment" class="btn-hero-primary"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
            <a href="#doctors" class="btn-hero-outline"><i class="fas fa-user-md"></i> Find a Doctor</a>
        </div>

        <!-- Live statistics from database -->
        <div class="hero-stats">
            <div>
                <div class="hero-stat-num"><?= $total_patients ?>+</div>
                <div class="hero-stat-label">Patients Served</div>
            </div>
            <div>
                <div class="hero-stat-num"><?= $total_doctors ?>+</div>
                <div class="hero-stat-label">Expert Doctors</div>
            </div>
            <div>
                <div class="hero-stat-num">24/7</div>
                <div class="hero-stat-label">Emergency Care</div>
            </div>
            <div>
                <div class="hero-stat-num">15+</div>
                <div class="hero-stat-label">Specialties</div>
            </div>
             <div>
                <div class="hero-stat-num"><?= $total_appts ?>+</div>
                <div class="hero-stat-label">Appointments</div>
            </div>
        </div>
    </div>

    <!-- Right side: 4 quick access cards -->
    <div class="hero-right">
        <a href="#doctors" class="hero-card"><i class="fas fa-user-md"></i><span>Find a Doctor</span></a>
        <!-- Lab Reports → requires patient login -->
        <a href="login.php?role=client" class="hero-card"><i class="fas fa-flask"></i><span>Lab Reports</span></a>
        <a href="#appointment" class="hero-card"><i class="fas fa-calendar-check"></i><span>Book Appointment</span></a>
        <a href="#contact" class="hero-card"><i class="fas fa-ambulance"></i><span>Emergency</span></a>
    </div>
</section>


<?php
// ============================================================
// QUICK STRIP
// Gold bar with 4 shortcut links below hero
// ============================================================
?>
<style>
/* ── QUICK STRIP ───────────────────────────────────────────
   Gold strip below hero with 4 quick access items:
   Find Doctor, Patient Portal, Lab Reports, Contact Numbers
   ───────────────────────────────────────────────────────── */
.quick-strip { background:var(--gold); padding:0 80px; display:flex; justify-content:space-between; }
.quick-item { display:flex; align-items:center; gap:12px; padding:22px 0; text-decoration:none; border-right:1px solid rgba(10,22,40,0.15); flex:1; justify-content:center; transition:background .2s; }
.quick-item:last-child { border-right:none; }
.quick-item:hover { background:rgba(10,22,40,0.08); }
.quick-item i { font-size:1.4rem; color:var(--navy); }
.quick-item-text strong { display:block; color:var(--navy); font-size:.9rem; font-weight:700; }
.quick-item-text span { color:rgba(10,22,40,0.6); font-size:.78rem; }
</style>

<div class="quick-strip">
    <a href="#doctors" class="quick-item">
        <i class="fas fa-user-md"></i>
        <div class="quick-item-text"><strong>Find a Doctor</strong><span>Search by specialty</span></div>
    </a>
    <a href="login.php?role=client" class="quick-item">
        <i class="fas fa-file-medical"></i>
        <div class="quick-item-text"><strong>Patient Portal</strong><span>Access your records</span></div>
    </a>
    <a href="login.php?role=client" class="quick-item">
        <i class="fas fa-flask"></i>
        <div class="quick-item-text"><strong>Lab & Reports</strong><span>Request tests online</span></div>
    </a>
    <a href="#contact" class="quick-item">
        <i class="fas fa-phone-alt"></i>
        <div class="quick-item-text"><strong>Contact Numbers</strong><span>Get in touch</span></div>
    </a>
</div>


<?php
// ============================================================
// ABOUT SECTION
// Two-column: hospital image on left, text content on right
// ============================================================
?>
<style>
/* ── SECTION COMMON STYLES ─────────────────────────────────
   Shared styles used across all content sections.
   All sections have 90px padding top/bottom, 80px left/right
   ───────────────────────────────────────────────────────── */
section { padding:90px 80px; }
.section-tag { color:var(--gold); font-size:.78rem; font-weight:700; letter-spacing:3px; text-transform:uppercase; display:block; margin-bottom:12px; } /* Small gold label above section title */
.section-title { font-family:'Playfair Display',serif; font-size:2.4rem; color:var(--navy); font-weight:700; margin-bottom:15px; line-height:1.3; }
.section-sub { color:var(--gray); font-size:.95rem; line-height:1.7; max-width:550px; }
.section-header { margin-bottom:55px; }
.section-header-center { text-align:center; } /* Centered section header variant */
.section-header-center .section-sub { margin:0 auto; }
.divider { width:60px; height:3px; background:var(--gold); margin:18px 0; } /* Short gold divider line */
.divider-center { margin:18px auto; } /* Centered divider variant */

/* ── ABOUT SECTION ─────────────────────────────────────────
   Two-column layout: left = hospital image with badge,
   right = text content with features and CTA button
   ───────────────────────────────────────────────────────── */
.about-grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center; }
.about-img-box { position:relative; height:540px; }
/* Main hospital building image */
.about-img-main { position:absolute; left:0; top:0; width:82%; height:88%; border-radius:14px; overflow:hidden; box-shadow:0 25px 60px rgba(10,22,40,0.2); }
.about-img-main img { width:100%; height:100%; object-fit:cover; object-position:center; }
/* Gold "15+ Years" badge overlapping the image */
.about-img-badge { position:absolute; right:0; bottom:35px; background:var(--gold); border-radius:10px; padding:22px 28px; text-align:center; box-shadow:0 10px 40px rgba(201,168,76,0.3); }
.about-img-badge .num { font-family:'Playfair Display',serif; font-size:2.5rem; color:var(--navy); font-weight:800; line-height:1; }
.about-img-badge p { color:var(--navy); font-size:.8rem; font-weight:600; margin-top:4px; }
/* 2x2 grid of hospital features (Accredited, Emergency, etc.) */
.about-features { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin:30px 0; }
.about-feat { display:flex; align-items:center; gap:10px; padding:12px 16px; background:var(--off-white); border-radius:8px; border-left:3px solid var(--gold); }
.about-feat i { color:var(--gold); font-size:1rem; }
.about-feat span { font-size:.88rem; font-weight:600; color:var(--navy); }
</style>

<section id="about">
    <div class="about-grid">
        <!-- Left: Hospital building image + years badge -->
        <div class="about-img-box">
            <div class="about-img-main">
                <img src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=800&q=80" alt="MedicaPro Hospital Building">
            </div>
            <!-- Gold badge showing years of experience -->
            <div class="about-img-badge">
                <div class="num">15+</div>
                <p>Years of Excellence</p>
            </div>
        </div>

        <!-- Right: Text content -->
        <div>
            <span class="section-tag">About MedicaPro</span>
            <h2 class="section-title">Trusted Healthcare Partner Since 2010</h2>
            <div class="divider"></div>
            <p style="color:var(--gray);line-height:1.8;margin-bottom:20px;">MedicaPro is a premier multi-specialty hospital committed to delivering exceptional healthcare services. Our state-of-the-art facility combines advanced medical technology with compassionate care.</p>
            <p style="color:var(--gray);line-height:1.8;margin-bottom:25px;">We operate with international standards, ensuring every patient receives accurate diagnosis, effective treatment, and a smooth recovery experience.</p>

            <!-- 4 feature highlights in 2x2 grid -->
            <div class="about-features">
                <?php foreach([['fa-certificate','JCI Accredited'],['fa-clock','24/7 Emergency'],['fa-user-md','Expert Specialists'],['fa-shield-alt','Patient Safety']] as $f): ?>
                <div class="about-feat"><i class="fas <?= $f[0] ?>"></i><span><?= $f[1] ?></span></div>
                <?php endforeach; ?>
            </div>

            <!-- CTA button linking to appointment section -->
            <a href="#appointment" style="display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:var(--gold);padding:13px 28px;border-radius:6px;text-decoration:none;font-weight:700;border:2px solid var(--navy);transition:all .2s;" onmouseover="this.style.background='transparent';this.style.color='var(--navy)';" onmouseout="this.style.background='var(--navy)';this.style.color='var(--gold)';">
                <i class="fas fa-calendar-plus"></i> Book an Appointment
            </a>
        </div>
    </div>
</section>


<?php
// ============================================================
// SERVICES SECTION
// 8 hardcoded service cards in 4-column grid
// Each has a real Unsplash image, icon, title, description
// ============================================================
?>
<style>
/* ── SERVICES SECTION ──────────────────────────────────────
   4-column grid of 8 medical service cards.
   Each card has: real image, icon badge, title, description
   Section has off-white background to differentiate from about
   ───────────────────────────────────────────────────────── */
.services-bg { background:var(--off-white); }
.services-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; }
.service-card { background:var(--white); border-radius:12px; overflow:hidden; border:1px solid rgba(10,22,40,0.07); transition:all .3s; cursor:pointer; }
.service-card:hover { border-color:var(--gold); transform:translateY(-6px); box-shadow:0 20px 50px rgba(201,168,76,0.15); }
.service-img { height:140px; overflow:hidden; position:relative; }
.service-img img { width:100%; height:100%; object-fit:cover; transition:transform .4s; }
.service-card:hover .service-img img { transform:scale(1.07); } /* Zoom image on card hover */
.service-img-overlay { position:absolute; inset:0; background:linear-gradient(to bottom, rgba(10,22,40,0.1), rgba(10,22,40,0.5)); } /* Dark gradient over image */
/* Gold icon badge at bottom-left of service image */
.service-img-icon { position:absolute; bottom:12px; left:16px; width:40px; height:40px; background:var(--gold); border-radius:8px; display:flex; align-items:center; justify-content:center; }
.service-img-icon i { color:var(--navy); font-size:1rem; }
.service-body { padding:18px 20px 22px; }
.service-body h3 { font-size:.95rem; font-weight:700; color:var(--navy); margin-bottom:7px; }
.service-body p { color:var(--gray); font-size:.82rem; line-height:1.6; }
</style>

<section id="services" class="services-bg">
    <div class="section-header section-header-center">
        <span class="section-tag">Our Services</span>
        <h2 class="section-title">Comprehensive Medical Services</h2>
        <div class="divider divider-center"></div>
        <p class="section-sub">We offer a full spectrum of medical services with world-class specialists and advanced diagnostic equipment.</p>
    </div>
    <div class="services-grid">
        <?php
        // Hardcoded services array: [icon, title, description, image_url]
        $services = [
            ['fa-heartbeat','Cardiology','Advanced heart care & cardiac surgery','https://images.unsplash.com/photo-1628348070889-cb656235b4eb?w=400&q=75'],
            ['fa-brain','Neurology','Brain & nervous system treatment','https://images.unsplash.com/photo-1559757175-5700dde675bc?w=400&q=75'],
            ['fa-bone','Orthopedics','Bone, joint & spine specialists','https://images.unsplash.com/photo-1530026405186-ed1f139313f3?w=400&q=75'],
            ['fa-baby','Pediatrics','Expert care for children & infants','https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?w=400&q=75'],
            ['fa-eye','Ophthalmology','Complete eye care & surgery','https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=400&q=75'],
            ['fa-flask','Laboratory','Pathology & diagnostic testing','https://images.unsplash.com/photo-1576671081837-49000212a370?w=400&q=75'],
            ['fa-ambulance','Emergency','Round-the-clock critical care','https://images.unsplash.com/photo-1504813184591-01572f98c85f?w=400&q=75'],
            ['fa-x-ray','Radiology','MRI, CT Scan, X-Ray & Ultrasound','https://images.unsplash.com/photo-1516069677018-378515003435?w=400&q=75'],
        ];
        // Loop through services array and output each card
        foreach($services as $s): ?>
        <div class="service-card">
            <div class="service-img">
                <img src="<?= $s[3] ?>" alt="<?= $s[1] ?>">
                <div class="service-img-overlay"></div>
                <!-- Gold icon badge bottom-left of image -->
                <div class="service-img-icon"><i class="fas <?= $s[0] ?>"></i></div>
            </div>
            <div class="service-body">
                <h3><?= $s[1] ?></h3>
                <p><?= $s[2] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>


<?php
// ============================================================
// DEPARTMENTS SECTION
// Data fetched from 'departments' table in database.
// Uses same card layout as doctors section.
// Auto-matches department name to relevant icon + image.
// Shows max 8 active departments. "View All" button links to departments.php
// ============================================================
?>


<section id="departments">
    <div class="section-header section-header-center">
        <span class="section-tag">Our Departments</span>
        <h2 class="section-title">World-Class Facilities</h2>
        <div class="divider divider-center"></div>
        <p class="section-sub" style="margin:0 auto;">
            Equipped with the latest technology and staffed by internationally trained medical professionals.
        </p>
    </div>

    <div class="doctors-grid">
        <?php
        // Fetch max 8 active departments from database
        $depts_home = mysqli_query($conn, "SELECT * FROM departments WHERE status='Active' ORDER BY department_id ASC LIMIT 8");

        // Keyword-to-icon mapping: matches department name keywords to Font Awesome icons
        $dept_icons = [
            'cardio' => 'fa-heartbeat', 'heart' => 'fa-heartbeat',
            'neuro'  => 'fa-brain',     'ortho' => 'fa-bone',
            'pediat' => 'fa-child',     'child' => 'fa-child',
            'derma'  => 'fa-allergies', 'skin'  => 'fa-allergies',
            'surg'   => 'fa-user-md',   'ent'   => 'fa-head-side-cough',
            'radiol' => 'fa-x-ray',     'emerg' => 'fa-ambulance',
            'gynae'  => 'fa-venus',     'gynec' => 'fa-venus',
            'eye'    => 'fa-eye',       'ophthal' => 'fa-eye',
            'lab'    => 'fa-flask',     'pharm' => 'fa-pills',
            'icu'    => 'fa-bed',       'pulmo' => 'fa-lungs',
            'dental' => 'fa-tooth',
        ];
        $default_icon = 'fa-hospital'; // Default icon if no keyword matches

        // Keyword-to-image mapping: matches department name to relevant Unsplash image
        $dept_imgs = [
            'cardio' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80',
            'heart'  => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80',
            'neuro'  => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=600&q=80',
            'ortho'  => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=600&q=80',
            'pediat' => 'https://images.unsplash.com/photo-1531983412531-1f49a365ffed?w=600&q=80',
            'child'  => 'https://images.unsplash.com/photo-1531983412531-1f49a365ffed?w=600&q=80',
            'derma'  => 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=600&q=80',
            'skin'   => 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=600&q=80',
            'surg'   => 'https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=600&q=80',
            'ent'    => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=600&q=80',
            'radiol' => 'https://images.unsplash.com/photo-1516069677018-378961e35248?w=600&q=80',
            'emerg'  => 'https://images.unsplash.com/photo-1587745416684-47953f16f02f?w=600&q=80',
            'gynae'  => 'https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?w=600&q=80',
            'gynec'  => 'https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?w=600&q=80',
            'eye'    => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?w=600&q=80',
            'ophthal'=> 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?w=600&q=80',
            'lab'    => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=600&q=80',
            'pharm'  => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=600&q=80',
            'icu'    => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&q=80',
            'pulmo'  => 'https://images.unsplash.com/photo-1631815589968-fdb09a223b1e?w=600&q=80',
            'dental' => 'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=600&q=80',
        ];
        $default_img = 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&q=80'; // Default image

        if ($depts_home && mysqli_num_rows($depts_home) > 0):
            while ($dep = mysqli_fetch_assoc($depts_home)):
                $dname = htmlspecialchars($dep['dept_name']);
                $lower = strtolower($dep['dept_name']); // Lowercase for keyword matching

                // Find best matching icon based on department name keywords
                $icon = $default_icon;
                $best_len = 0;
                foreach ($dept_icons as $key => $val) {
                    if (str_contains($lower, $key) && strlen($key) > $best_len) {
                        $icon = $val;
                        $best_len = strlen($key);
                    }
                }

                // Find best matching image based on department name keywords
                $img_url = $default_img;
                $best_len = 0;
                foreach ($dept_imgs as $key => $url) {
                    if (str_contains($lower, $key) && strlen($key) > $best_len) {
                        $img_url = $url;
                        $best_len = strlen($key);
                    }
                }

                // If admin uploaded a custom photo, use it. Otherwise use Unsplash image
                $photo_path = 'assets/departments/' . ($dep['photo'] ?? '');
                if (!empty($dep['photo']) && file_exists($photo_path)) {
                    $img = htmlspecialchars($photo_path); // Use uploaded photo
                } else {
                    $img = $img_url; // Use Unsplash image
                }
        ?>
        <div class="doctor-card">
            <div class="doctor-photo" style="position:relative;">
                <!-- Department image (uploaded or Unsplash) -->
                <img src="<?= $img ?>" alt="<?= $dname ?>"
                     style="width:100%;height:100%;object-fit:cover;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <!-- Fallback: shown if image fails to load -->
                <div style="display:none;width:100%;height:100%;background:var(--navy);align-items:center;justify-content:center;">
                    <i class="fas <?= $icon ?>" style="font-size:3.5rem;color:var(--gold);opacity:0.7;"></i>
                </div>

                <!-- Gold icon badge at bottom-left of image -->
                <div style="position:absolute;bottom:12px;left:12px;width:40px;height:40px;background:var(--gold);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--navy);font-size:1rem;box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                    <i class="fas <?= $icon ?>"></i>
                </div>

                <span class="doctor-photo-badge">Active</span>
            </div>
            <div class="doctor-body">
                <h3><?= $dname ?></h3>
                <!-- Department description -->
                <span class="doctor-spec"><?= htmlspecialchars($dep['description'] ?? '') ?></span>
                <!-- Head doctor name -->
                <div class="doctor-fee" style="font-size:.85rem;">
                    <i class="fas fa-user-md" style="color:var(--gold);margin-right:5px;"></i>
                    <?= htmlspecialchars($dep['head_doctor'] ?? '—') ?>
                </div>
                <a href="#appointment" class="btn-doctor">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>
        <?php endwhile; else: ?>
            <p style="color:var(--gray);grid-column:span 4;text-align:center;padding:40px;">No departments found.</p>
        <?php endif; ?>

       
    </div>
</section>


<?php
// ============================================================
// DOCTORS SECTION
// Fetched from database (latest 8 doctors, ordered by doctor_id DESC)
// Shows: photo or initials avatar, name, specialization, fee, book button
// "View All Doctors" button links to doctors.php
// ============================================================

// ── FETCH DOCTORS ──────────────────────────────────────────
// Get latest 8 doctors from database to display on homepage
$doctors = mysqli_query($conn, "SELECT doctor_id, name, specialization, consultation_fee, photo FROM doctor ORDER BY doctor_id DESC LIMIT 8");
?>
<style>
/* ── DOCTORS SECTION ───────────────────────────────────────
   4-column grid of doctor cards fetched from database.
   Each card: photo (or initials avatar), name, specialization,
   consultation fee, Book Appointment button.
   Also reused for Departments section with same card style.
   ───────────────────────────────────────────────────────── */
.doctors-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:25px; }
.doctor-card { background:var(--white); border-radius:12px; overflow:hidden; border:1px solid rgba(10,22,40,0.07); transition:all .3s; }
.doctor-card:hover { box-shadow:0 20px 50px rgba(10,22,40,0.12); transform:translateY(-5px); }
.doctor-photo { height:220px; background:linear-gradient(135deg,var(--navy) 0%,var(--navy-3) 100%); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
.doctor-photo img { width:100%; height:100%; object-fit:cover; object-position:top center; }
/* Fallback when no photo: navy background with generic icon */
.doctor-photo-fallback { width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--navy),var(--navy-3)); }
.doctor-photo-fallback i { font-size:5rem; color:rgba(201,168,76,0.4); }
/* "Available" badge on top-right of doctor photo */
.doctor-photo-badge { position:absolute; top:12px; right:12px; background:var(--gold); color:var(--navy); font-size:.68rem; font-weight:700; padding:4px 10px; border-radius:4px; z-index:2; }
.doctor-body { padding:20px; }
.doctor-body h3 { font-family:'Playfair Display',serif; font-size:1rem; font-weight:600; color:var(--navy); margin-bottom:4px; }
.doctor-spec { color:var(--gold); font-size:.8rem; font-weight:600; margin-bottom:12px; display:block; } /* Specialization in gold */
.doctor-fee { color:var(--navy); font-size:.85rem; font-weight:600; }
.doctor-fee span { color:var(--gray); font-weight:400; font-size:.78rem; }
/* Book Appointment button on each doctor card */
.btn-doctor { display:block; margin-top:14px; padding:9px; border:2px solid var(--navy); color:var(--navy); border-radius:6px; text-align:center; text-decoration:none; font-size:.82rem; font-weight:700; transition:all .2s; }
.btn-doctor:hover { background:var(--navy); color:var(--gold); }
</style>

<section id="doctors">
    <div class="section-header section-header-center">
        <span class="section-tag">Our Team</span>
        <h2 class="section-title">Meet Our Expert Physicians</h2>
        <div class="divider divider-center"></div>
        <p class="section-sub">Our team of internationally trained doctors brings expertise, compassion, and dedication to every patient.</p>
    </div>
    <div class="doctors-grid">
        <?php if($doctors && mysqli_num_rows($doctors) > 0):
            while($d = mysqli_fetch_assoc($doctors)): ?>
        <div class="doctor-card">
            <div class="doctor-photo">
                <?php
                // Check if doctor has uploaded photo in assets/doctors/ folder
                $photo_path = 'assets/doctors/' . $d['photo'];
                if(!empty($d['photo']) && file_exists($photo_path)): ?>
                    <!-- Use actual uploaded doctor photo -->
                    <img src="<?= htmlspecialchars($photo_path) ?>" alt="<?= htmlspecialchars($d['name']) ?>">
                <?php else: ?>
                    <!-- No photo: use ui-avatars.com to generate initials avatar -->
                    <div class="doctor-photo-fallback">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($d['name']) ?>&size=300&background=1a3a5c&color=c9a84c&bold=true&format=svg"
                             style="width:100%;height:100%;object-fit:cover;" alt="<?= htmlspecialchars($d['name']) ?>">
                    </div>
                <?php endif; ?>
                <!-- "Available" badge top-right of photo -->
                <span class="doctor-photo-badge">Available</span>
            </div>
            <div class="doctor-body">
                <h3><?= htmlspecialchars($d['name']) ?></h3>
                <span class="doctor-spec"><?= htmlspecialchars($d['specialization'] ?? 'General Physician') ?></span>
                <!-- Consultation fee from database -->
                <div class="doctor-fee">Rs. <?= number_format($d['consultation_fee'] ?? 0, 0) ?> <span>/ consultation</span></div>
                <!-- Button scrolls to appointment section -->
                <a href="#appointment" class="btn-doctor"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
            </div>
        </div>
        <?php endwhile; else: ?>
        <p style="color:var(--gray);grid-column:span 4;text-align:center;padding:40px;">No doctors found. Please add doctors from admin panel.</p>
        <?php endif; ?>

        <!-- View All Doctors button → links to doctors.php public page -->
        <div style="text-align:center;margin-top:40px;grid-column:1/-1;">
            <a href="doctors.php" style="display:inline-flex;align-items:center;gap:20px;background:var(--navy);color:var(--gold);padding:14px 35px;border-radius:6px;text-decoration:none;font-weight:700;font-size:.95rem;border:2px solid var(--navy);transition:all .2s;"
               onmouseover="this.style.background='transparent';this.style.color='var(--navy)';"
               onmouseout="this.style.background='var(--navy)';this.style.color='var(--gold)';">
                <i class="fas fa-user-md"></i> View All Doctors
            </a>
        </div>
    </div>
</section>


<?php
// ============================================================
// STATS STRIP
// Full-width dark navy strip with 4 live statistics
// Numbers come from database queries at top of file
// ============================================================
?>
<style>
/* ── STATS STRIP ───────────────────────────────────────────
   Dark navy full-width strip showing 4 hospital statistics:
   Patients Treated, Doctors, Appointments, Emergency 24/7
   Has subtle background hospital image with very low opacity
   ───────────────────────────────────────────────────────── */
.stats-strip { background:var(--navy); padding:0 80px; display:grid; grid-template-columns:repeat(4,1fr); border-top:4px solid var(--gold); border-bottom:4px solid var(--gold); position:relative; overflow:hidden; }
/* Very faint hospital background image for texture */
.stats-strip::before { content:''; position:absolute; inset:0; background:url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600&q=70') center/cover; opacity:0.06; }
.stat-item { padding:60px 20px; text-align:center; border-right:1px solid rgba(201,168,76,0.15); position:relative; z-index:1; }
.stat-item:last-child { border-right:none; }
.stat-num { font-family:'Playfair Display',serif; font-size:3rem; color:var(--gold); font-weight:700; line-height:1; margin-bottom:8px; } /* Large gold number */
.stat-label { color:rgba(255,255,255,0.5); font-size:.82rem; letter-spacing:1px; text-transform:uppercase; }
</style>

<div class="stats-strip">
    <div class="stat-item">
        <!-- Total patients from database -->
        <div class="stat-num"><?= $total_patients ?>+</div>
        <div class="stat-label">Patients Treated</div>
    </div>
    <div class="stat-item">
        <!-- Total doctors from database -->
        <div class="stat-num"><?= $total_doctors ?>+</div>
        <div class="stat-label">Expert Doctors</div>
    </div>
    <div class="stat-item">
        <!-- Total appointments from database -->
        <div class="stat-num"><?= $total_appts ?>+</div>
        <div class="stat-label">Appointments</div>
    </div>
    <div class="stat-item">
        <!-- Static value: always 24/7 -->
        <div class="stat-num">24/7</div>
        <div class="stat-label">Emergency Care</div>
    </div>
</div>


<?php
// ============================================================
// WHY CHOOSE US SECTION
// Left: 5 feature items with gold icons
// Right: Medical team photo with certification badges overlay
// ============================================================
?>
<style>
/* ── WHY CHOOSE US SECTION ─────────────────────────────────
   Two-column: left = text + 5 feature list items,
   right = medical team photo with certifications overlay
   ───────────────────────────────────────────────────────── */
.why-grid { display:grid; grid-template-columns:1fr 1fr; gap:70px; align-items:center; }
.why-list { list-style:none; margin-top:25px; }
/* Each feature item: gold icon box + title + description */
.why-item { display:flex; gap:18px; padding:20px 0; border-bottom:1px solid rgba(10,22,40,0.07); align-items:flex-start; }
.why-item:last-child { border-bottom:none; }
.why-icon { width:46px; height:46px; background:var(--gold-pale); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.why-icon i { color:var(--gold); font-size:1.1rem; }
.why-text strong { display:block; font-size:.95rem; font-weight:600; color:var(--navy); margin-bottom:4px; }
.why-text span { color:var(--gray); font-size:.85rem; line-height:1.6; }
/* Right side: tall image with dark gradient overlay */
.why-img-box { border-radius:16px; height:500px; overflow:hidden; position:relative; box-shadow:0 30px 70px rgba(10,22,40,0.2); }
.why-img-box img { width:100%; height:100%; object-fit:cover; }
.why-img-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(10,22,40,0.7) 0%,transparent 50%); }
/* Certification badges at bottom of image (JCI, ISO, PMDC) */
.why-certif { position:absolute; bottom:25px; left:25px; right:25px; display:flex; gap:10px; flex-wrap:wrap; }
.certif-badge { background:rgba(201,168,76,0.2); border:1px solid rgba(201,168,76,0.5); color:var(--gold-light); padding:6px 14px; border-radius:4px; font-size:.75rem; font-weight:600; backdrop-filter:blur(8px); }
</style>

<section>
    <div class="why-grid">
        <div>
            <span class="section-tag">Why Choose Us</span>
            <h2 class="section-title">The MedicaPro Difference</h2>
            <div class="divider"></div>
            <p style="color:var(--gray);line-height:1.8;">We set the standard for healthcare excellence in Pakistan, combining international best practices with personalized patient care.</p>

            <!-- 5 feature items: [icon, title, description] -->
            <ul class="why-list">
                <?php
                $whys = [
                    ['fa-award','International Accreditation','JCI accredited, meeting global healthcare standards'],
                    ['fa-microscope','Advanced Technology','Latest diagnostic & surgical equipment'],
                    ['fa-user-md','Expert Medical Team','Internationally trained & certified specialists'],
                    ['fa-shield-alt','Patient Safety First','Rigorous safety protocols & quality control'],
                    ['fa-clock','24/7 Availability','Round-the-clock emergency & critical care'],
                ];
                foreach($whys as $w): ?>
                <li class="why-item">
                    <div class="why-icon"><i class="fas <?= $w[0] ?>"></i></div>
                    <div class="why-text">
                        <strong><?= $w[1] ?></strong>
                        <span><?= $w[2] ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Right: photo with overlay and certification badges -->
        <div class="why-img-box">
            <img src="https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=800&q=80" alt="Medical Team">
            <div class="why-img-overlay"></div>
            <!-- Certification badges at bottom of photo -->
            <div class="why-certif">
                <span class="certif-badge">JCI Accredited</span>
                <span class="certif-badge">ISO 9001:2015</span>
                <span class="certif-badge">PMDC Approved</span>
            </div>
        </div>
    </div>
</section>


<?php
// ============================================================
// APPOINTMENT SECTION
// Left: 4-step instructions + contact info
// Right: Smart form - shows different content based on login status:
//   - If logged in: Doctor select + Date + Reason form (submits to book_appointment.php)
//   - If not logged in: Lock icon + Login button + Register button
// ============================================================
?>
<style>
/* ── APPOINTMENT SECTION ───────────────────────────────────
   Two-column: left = how-to steps + contact info,
   right = booking form (shows login prompt if not logged in)
   Dark navy form box with gold top border
   ───────────────────────────────────────────────────────── */
.appt-grid { display:grid; grid-template-columns:1fr 1fr; gap:70px; align-items:start; }
.appt-info h3 { font-family:'Playfair Display',serif; font-size:1.1rem; color:var(--navy); font-weight:600; margin-bottom:20px; }
/* Numbered steps list (1,2,3,4) */
.appt-steps { list-style:none; margin-bottom:35px; }
.appt-steps li { display:flex; align-items:center; gap:15px; padding:14px 0; border-bottom:1px solid rgba(10,22,40,0.07); }
.appt-steps li:last-child { border-bottom:none; }
.step-num { width:32px; height:32px; background:var(--gold); color:var(--navy); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:800; flex-shrink:0; } /* Gold circle number */
.step-text strong { display:block; font-size:.9rem; font-weight:600; color:var(--navy); }
.step-text span { color:var(--gray); font-size:.82rem; }
/* Phone + email contact info below steps */
.appt-contact { display:flex; gap:25px; }
.appt-contact-item { display:flex; align-items:center; gap:10px; }
.appt-contact-item i { color:var(--gold); font-size:1.1rem; }
.appt-contact-item strong { display:block; font-size:.85rem; color:var(--navy); font-weight:600; }
.appt-contact-item span { color:var(--gray); font-size:.78rem; }
/* Dark navy form box on right side */
.appt-form-box { background:var(--navy); border-radius:14px; padding:40px; border-top:4px solid var(--gold); }
.appt-form-box h3 { font-family:'Playfair Display',serif; font-size:1.4rem; color:var(--white); font-weight:700; margin-bottom:28px; }
.appt-form-box h3 span { color:var(--gold); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
/* Form field group styles (label + input/select) */
.fg { margin-bottom:16px; }
.fg label { display:block; font-size:.78rem; font-weight:600; color:rgba(255,255,255,0.5); margin-bottom:7px; letter-spacing:.5px; text-transform:uppercase; }
.fg select, .fg input { width:100%; padding:11px 14px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:6px; font-size:.88rem; color:var(--white); outline:none; font-family:'DM Sans',sans-serif; transition:border .2s; }
.fg select:focus, .fg input:focus { border-color:var(--gold); }
.fg select option { background:var(--navy-2); }
/* Gold submit button */
.btn-book-submit { width:100%; padding:14px; background:var(--gold); color:var(--navy); border:none; border-radius:6px; font-size:.95rem; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; margin-top:6px; transition:background .2s; }
.btn-book-submit:hover { background:var(--gold-light); }
/* Success message after booking */
.appt-success { background:rgba(201,168,76,0.15); border:1px solid var(--gold); color:var(--gold-light); padding:12px 16px; border-radius:6px; margin-bottom:15px; font-size:.88rem; }
</style>

<section id="appointment" style="background:var(--off-white);">
    <div class="section-header">
        <span class="section-tag">Book Appointment</span>
        <h2 class="section-title">Schedule Your Visit</h2>
        <div class="divider"></div>
    </div>
    <div class="appt-grid">
        <!-- Left: How-to steps -->
        <div class="appt-info">
            <h3>How to Book an Appointment</h3>
            <ul class="appt-steps">
                <li><div class="step-num">1</div><div class="step-text"><strong>Choose Your Doctor</strong><span>Browse our specialists and select the right doctor for your needs.</span></div></li>
                <li><div class="step-num">2</div><div class="step-text"><strong>Select Date & Time</strong><span>Pick a convenient appointment slot for you from available timings.</span></div></li>
                <li><div class="step-num">3</div><div class="step-text"><strong>Confirm Your Booking</strong><span>Submit your appointment request and receive your confirmation .</span></div></li>
                <li><div class="step-num">4</div><div class="step-text"><strong>Visit & Consult</strong><span>Arrive at the hospital and meet your doctor at the scheduled time.</span></div></li>
            </ul>
            <!-- Contact info below steps -->
            <div class="appt-contact">
                <div class="appt-contact-item">
                    <i class="fas fa-phone"></i>
                    <div><strong>(042) 111 000 111</strong><span>Call for appointments</span></div>
                </div>
                <div class="appt-contact-item">
                    <i class="fas fa-envelope"></i>
                    <div><strong>info@medicapro.pk</strong><span>Email us anytime</span></div>
                </div>
            </div>
        </div>

        <!-- Right: Smart appointment form box -->
        <div class="appt-form-box">
            <h3>Book <span>Appointment</span></h3>

            <?php if(isset($_SESSION['client_logged_in']) && $_SESSION['client_logged_in']): ?>
            <!-- ── LOGGED IN: Show appointment booking form ── -->
            <?php if(isset($appt_msg)): ?>
            <div class="appt-success"><i class="fas fa-check-circle"></i> <?= $appt_msg ?></div>
            <?php endif; ?>
            <?php if(isset($appt_err) && $appt_err): ?>
            <div style="background:rgba(231,76,60,0.15);border:1px solid rgba(231,76,60,0.4);color:#ff6b6b;padding:12px;border-radius:6px;margin-bottom:15px;font-size:.85rem;"><?= $appt_err ?></div>
            <?php endif; ?>

            <!-- Form submits to book_appointment.php which handles the booking logic -->
            <form method="POST" action="book_appointment.php">
                <!-- Doctor dropdown: fetched from database -->
                <div class="fg">
                    <label>Select Doctor</label>
                    <select name="doctor_id" required>
                        <option value="">-- Select Doctor --</option>
                        <?php
                        $doc_list = mysqli_query($conn, "SELECT doctor_id, name, specialization FROM doctor ORDER BY name");
                        while($dl = mysqli_fetch_assoc($doc_list)): ?>
                        <option value="<?= $dl['doctor_id'] ?>"><?= htmlspecialchars($dl['name']) ?> — <?= htmlspecialchars($dl['specialization']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Date and time picker (minimum = current date/time) -->
                <div class="fg">
                    <label>Preferred Date & Time</label>
                    <input type="datetime-local" name="date_time" required min="<?= date('Y-m-d\TH:i') ?>">
                </div>
                <!-- Reason for visit -->
                <div class="fg">
                    <label>Reason for Visit</label>
                    <input type="text" name="reason" placeholder="e.g. General checkup, Follow-up, Fever">
                </div>
                <button type="submit" name="book_appt" class="btn-book-submit">
                    <i class="fas fa-calendar-check"></i> Request Appointment
                </button>
            </form>

            <?php else: ?>
            <!-- ── NOT LOGGED IN: Show login prompt instead of form ── -->
            <div style="text-align:center;padding:30px 0;">
                <!-- Lock icon -->
                <div style="width:70px;height:70px;background:rgba(201,168,76,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <i class="fas fa-user-lock" style="font-size:1.8rem;color:var(--gold);"></i>
                </div>
                <h4 style="color:#fff;font-size:1.1rem;margin-bottom:10px;">Login Required</h4>
                <p style="color:rgba(255,255,255,0.5);font-size:.88rem;margin-bottom:25px;line-height:1.6;">Please login to your patient account to book an appointment.</p>
                <!-- Login button → redirects to patient login page -->
                <a href="login.php?role=client&redirect=index.php%23appointment"
                   style="display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--navy);padding:12px 28px;border-radius:6px;text-decoration:none;font-weight:700;font-size:.9rem;margin-bottom:14px;width:100%;justify-content:center;">
                    <i class="fas fa-sign-in-alt"></i> Login to Book Appointment
                </a>
                <!-- Register button → for new patients -->
                <a href="register.php"
                   style="display:inline-flex;align-items:center;gap:8px;background:transparent;color:rgba(255,255,255,0.6);padding:10px 28px;border-radius:6px;text-decoration:none;font-size:.85rem;border:1px solid rgba(255,255,255,0.15);width:100%;justify-content:center;">
                    <i class="fas fa-user-plus"></i> New Patient? Register Here
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php
// ============================================================
// TESTIMONIALS SECTION
// 3 hardcoded patient reviews with photos from Unsplash
// ============================================================
?>
<style>
/* ── TESTIMONIALS SECTION ──────────────────────────────────
   3-column grid of patient review cards.
   Each card: stars, review text, patient photo + name
   Off-white background differentiates this section
   ───────────────────────────────────────────────────────── */
.test-bg { background:var(--off-white); }
.test-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:25px; }
.test-card { background:var(--white); border-radius:12px; padding:30px; border:1px solid rgba(10,22,40,0.07); position:relative; }
.test-card::before { content:'"'; position:absolute; top:20px; right:25px; font-family:'Playfair Display',serif; font-size:5rem; color:var(--gold-pale); line-height:1; } /* Large decorative quote mark */
.test-stars { color:var(--gold); font-size:.85rem; margin-bottom:15px; } /* 5 gold stars */
.test-card p { color:var(--gray); font-size:.88rem; line-height:1.7; margin-bottom:20px; }
.test-author { display:flex; align-items:center; gap:12px; }
.test-avatar { width:46px; height:46px; border-radius:50%; overflow:hidden; } /* Circular patient photo */
.test-avatar img { width:100%; height:100%; object-fit:cover; }
.test-name { font-size:.88rem; font-weight:600; color:var(--navy); }
.test-role { font-size:.75rem; color:var(--gray); }
</style>

<section class="test-bg">
    <div class="section-header section-header-center">
        <span class="section-tag">Testimonials</span>
        <h2 class="section-title">What Our Patients Say</h2>
        <div class="divider divider-center"></div>
    </div>
    <div class="test-grid">
        <?php
        // Hardcoded testimonials: [review text, patient name, role, photo url]
        $tests = [
            ['The doctors at MedicaPro are exceptional. The treatment I received was world-class and the staff was incredibly caring and professional throughout my stay.','Ahmad Raza','Cardiac Patient','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80'],
            ['Outstanding facilities and highly skilled medical team. My surgery went smoothly and the post-operative care was excellent. Highly recommend MedicaPro.','Sara Khan','Orthopedic Patient','https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80'],
            ['I was impressed by the modern equipment and the efficiency of the diagnostic lab. Results came quickly and the doctors explained everything clearly.','Dr. Fatima Ali','General Checkup','https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80'],
        ];
        foreach($tests as $t): ?>
        <div class="test-card">
            <div class="test-stars">★★★★★</div>
            <p><?= $t[0] ?></p>
            <div class="test-author">
                <div class="test-avatar"><img src="<?= $t[3] ?>" alt="<?= $t[1] ?>"></div>
                <div>
                    <div class="test-name"><?= $t[1] ?></div>
                    <div class="test-role"><?= $t[2] ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>


<?php
// ============================================================
// CONTACT SECTION
// Left: 4 contact info cards (address, phone, email, hours)
// right = map image with overlay showing address + Google Maps link
// ============================================================
?>
<style>
/* ── CONTACT SECTION ───────────────────────────────────────
   Two-column: left = 4 contact info cards (address, phone, email, hours)
   right = map image with overlay showing address + Google Maps link
   ───────────────────────────────────────────────────────── */
.contact-grid { display:grid; grid-template-columns:1fr 1fr; gap:60px; }
.contact-cards { display:flex; flex-direction:column; gap:18px; }
/* Each contact card with gold left border */
.contact-card { display:flex; gap:18px; align-items:flex-start; padding:22px; background:var(--off-white); border-radius:10px; border-left:4px solid var(--gold); }
.contact-card i { color:var(--gold); font-size:1.2rem; margin-top:2px; }
.contact-card strong { display:block; font-size:.9rem; font-weight:700; color:var(--navy); margin-bottom:4px; }
.contact-card span { color:var(--gray); font-size:.85rem; line-height:1.6; }
/* Map image on right with dark overlay at bottom */
.contact-map { border-radius:14px; overflow:hidden; height:100%; min-height:380px; position:relative; box-shadow:0 15px 40px rgba(10,22,40,0.15); }
.contact-map img { width:100%; height:100%; object-fit:cover; }
.contact-map-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(10,22,40,0.8),transparent 50%); display:flex; flex-direction:column; align-items:center; justify-content:flex-end; padding:30px; text-align:center; }
.contact-map-overlay i { font-size:2.5rem; color:var(--gold); margin-bottom:10px; }
.contact-map-overlay p { color:rgba(255,255,255,0.8); font-size:.9rem; margin-bottom:12px; }
.contact-map-overlay a { color:var(--gold); font-size:.85rem; font-weight:600; text-decoration:none; border:1px solid var(--gold); padding:8px 20px; border-radius:4px; transition:all .2s; }
.contact-map-overlay a:hover { background:var(--gold); color:var(--navy); }
</style>

<section id="contact">
    <div class="section-header">
        <span class="section-tag">Contact Us</span>
        <h2 class="section-title">Get In Touch</h2>
        <div class="divider"></div>
    </div>
    <div class="contact-grid">
        <!-- Left: Contact info cards -->
        <div class="contact-cards">
            <?php
            // Hardcoded contact details: [icon, title, content]
            $contacts = [
                ['fa-map-marker-alt','Our Location','123 Medical Street, Gulberg III\nLahore, Pakistan'],
                ['fa-phone','Phone Numbers','Emergency: (042) 111 000 111\nAppointments: (042) 111 222 333'],
                ['fa-envelope','Email Address','info@medicapro.pk\nappointments@medicapro.pk'],
                ['fa-clock','Working Hours','Monday – Saturday: 8:00am – 10:00pm\nEmergency & ICU: 24 Hours / 7 Days'],
            ];
            foreach($contacts as $c): ?>
            <div class="contact-card">
                <i class="fas <?= $c[0] ?>"></i>
                <div><strong><?= $c[1] ?></strong><span><?= nl2br($c[2]) ?></span></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Right: Map image with address overlay -->
        <div class="contact-map">
            <img src="https://images.unsplash.com/photo-1564069114553-7215e1ff1890?w=800&q=80" alt="Lahore Map">
            <div class="contact-map-overlay">
                <i class="fas fa-map-marker-alt"></i>
                <p>123 Medical Street, Gulberg III, Lahore</p>
                <a href="#">View on Google Maps →</a>
            </div>
        </div>
    </div>
</section>


<?php
// ============================================================
// FOOTER
// 4-column layout: Brand | Quick Links | Services | Newsletter+Portal
// Bottom bar: copyright + Staff Portal link
// ============================================================
?>
<style>
/* ── FOOTER ────────────────────────────────────────────────
   Dark navy footer with 4 columns:
   1. Brand info + social icons
   2. Quick links
   3. Services list
   4. Newsletter + Patient Portal links
   Gold top border, copyright bar at very bottom
   ───────────────────────────────────────────────────────── */
footer { background:var(--navy); padding:70px 80px 30px; border-top:4px solid var(--gold); }
.footer-grid { display:grid; grid-template-columns:2.5fr 1fr 1fr 1.5fr; gap:50px; margin-bottom:50px; }
.footer-brand h2 { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--white); font-weight:700; margin-bottom:15px; }
.footer-brand h2 span { color:var(--gold); }
.footer-brand p { color:rgba(255,255,255,0.45); font-size:.85rem; line-height:1.8; margin-bottom:22px; max-width:280px; }
/* Social media icon row */
.footer-social { display:flex; gap:10px; }
.footer-social a { width:36px; height:36px; background:rgba(255,255,255,0.07); border:1px solid rgba(201,168,76,0.2); border-radius:6px; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.5); text-decoration:none; transition:all .2s; }
.footer-social a:hover { background:var(--gold); color:var(--navy); border-color:var(--gold); }
footer h4 { font-size:.8rem; font-weight:700; color:var(--gold); text-transform:uppercase; letter-spacing:2px; margin-bottom:20px; }
footer ul { list-style:none; }
footer ul li { margin-bottom:10px; }
footer ul li a { color:rgba(255,255,255,0.45); font-size:.85rem; text-decoration:none; transition:color .2s; }
footer ul li a:hover { color:var(--gold); }
/* Newsletter email input + subscribe button */
.footer-newsletter input { width:100%; padding:10px 14px; background:rgba(255,255,255,0.06); border:1px solid rgba(201,168,76,0.2); border-radius:6px 6px 0 0; color:#fff; font-size:.85rem; font-family:'DM Sans',sans-serif; outline:none; margin-bottom:-1px; }
.footer-newsletter button { width:100%; padding:10px; background:var(--gold); border:none; border-radius:0 0 6px 6px; color:var(--navy); font-weight:700; font-size:.85rem; cursor:pointer; font-family:'DM Sans',sans-serif; }
/* Bottom copyright bar */
.footer-bottom { border-top:1px solid rgba(255,255,255,0.08); padding-top:25px; display:flex; justify-content:space-between; align-items:center; }
.footer-bottom p { color:rgba(255,255,255,0.3); font-size:.8rem; }
.footer-bottom a { color:var(--gold); text-decoration:none; font-size:.8rem; }
</style>

<footer>
    <div class="footer-grid">
        <!-- Column 1: Brand info + social icons -->
        <div class="footer-brand">
            <h2>Medica<span>Pro</span></h2>
            <p>Delivering world-class healthcare with compassion and excellence. Your health is our highest priority.</p>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Column 2: Quick navigation links -->
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#departments">Departments</a></li>
                <li><a href="#doctors">Our Doctors</a></li>
                <li><a href="#appointment">Book Appointment</a></li>
            </ul>
        </div>

        <!-- Column 3: Services list -->
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#services">Cardiology</a></li>
                <li><a href="#services">Neurology</a></li>
                <li><a href="#services">Orthopedics</a></li>
                <li><a href="#services">Pediatrics</a></li>
                <li><a href="#services">Emergency</a></li>
                <li><a href="#services">Laboratory</a></li>
            </ul>
        </div>

        <!-- Column 4: Newsletter subscription + Patient Portal links -->
        <div>
            <h4>Newsletter</h4>
            <p style="color:rgba(255,255,255,0.4);font-size:.82rem;margin-bottom:15px;line-height:1.6;">Subscribe for health tips and hospital updates.</p>
            <!-- Newsletter email form (frontend only, no backend) -->
            <div class="footer-newsletter">
                <input type="email" placeholder="Your email address">
                <button><i class="fas fa-paper-plane"></i> Subscribe</button>
            </div>

            <!-- Patient Portal quick links -->
            <div style="margin-top:20px;">
                <h4>Patient Portal</h4>
                <ul>
                    <li><a href="login.php?role=client">Patient Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php?role=staff">Staff Login</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom copyright bar -->
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> MedicaPro Hospital. All Rights Reserved.</p>
        <!-- Staff Portal link in bottom bar -->
        <a href="login.php?role=staff">Staff Portal</a>
    </div>
</footer>

<?php
// ============================================================
// RESPONSIVE / MOBILE CSS
// Yahan sari media queries hain jo laptop wala layout tablet
// aur mobile par bhi sahi dikhati hain. Har window size par
// (desktop, tablet ~1024px, mobile ~768px, chhota mobile ~480px)
// content stack/resize hota hai taake kuch overlap ya cut na ho.
// ============================================================
?>
<style>
/* ============================================================
   TABLET (max-width: 1024px)
   Padding kam, kuch grids 2-column, hero-right chhota
   ============================================================ */
@media (max-width: 1024px) {
    .topbar, .navbar, .quick-strip, .stats-strip, footer { padding-left:40px; padding-right:40px; }
    /* Topbar: items tablet width mein fit nahi hote (text beech mein toot raha
       tha), isliye yahan se hi column mein stack kar dete hain */
    .topbar { flex-direction:column; gap:8px; padding-top:10px; padding-bottom:10px; }
    .topbar-left { flex-wrap:wrap; justify-content:flex-start; gap:12px; }
    .topbar-right { flex-wrap:wrap; justify-content:flex-start; gap:8px; }
    .topbar-right a { padding:4px 10px; font-size:.72rem; }
    .topbar-left a, .topbar-right a { white-space:nowrap; }
    /* Navbar: logo + 6 links + phone + button tablet width mein fit nahi hoti,
       isliye hamburger yahin se (1024px) show kar dete hain */
    .nav-phone { display:none; }
    .nav-hamburger { display:flex; align-items:center; justify-content:center; }
    .nav-actions .btn-nav-book { display:none; } /* Top bar wala gold button hata dete hain, jagah bachane k liye */
    .nav-book-mobile { display:flex; align-items:center; justify-content:center; gap:8px; background:var(--gold); color:var(--navy); font-weight:700; padding:14px 0 !important; border-radius:6px; margin-top:12px; border-bottom:none !important; }
    .nav-menu {
        display:flex;
        flex-direction:column;
        position:absolute;
        top:70px;
        left:0;
        right:0;
        background:var(--white);
        padding:10px 20px 20px;
        gap:0;
        box-shadow:0 10px 20px rgba(10,22,40,0.12);
        max-height:0;
        overflow:hidden;
        transition:max-height .3s ease;
    }
    .nav-menu.active { max-height:400px; }
    .nav-menu a { padding:12px 0; border-bottom:1px solid rgba(10,22,40,0.06); }
    .navbar { height:70px; }
    .hero { padding:110px 40px 60px; flex-direction:column; align-items:flex-start; }
    .hero h1 { font-size:2.8rem; }
    .hero-content { width:100%; max-width:100%; }
    .hero-right { position:static; transform:none; margin-top:40px; grid-template-columns:1fr 1fr; width:100%; }
    section { padding-left:40px; padding-right:40px; }
    .services-grid, .doctors-grid { grid-template-columns:repeat(2,1fr) !important; }
    .test-grid { grid-template-columns:1fr 1fr !important; }
    /* Agar teesra (akela) card bacha reh jaye to use dono columns k beech center kar dein */
    .test-grid > *:last-child:nth-child(odd) { grid-column:1 / -1; max-width:calc(50% - 12.5px); margin:0 auto; }
    .footer-grid { grid-template-columns:1fr 1fr !important; gap:35px !important; }
}

/* ============================================================
   MOBILE / SMALL TABLET (max-width: 768px)
   Nav menu hamburger k peeche, saari grids 1-column stack
   ============================================================ */
@media (max-width: 768px) {
    /* Chhoti mobile screen par address bhi hide taake aur jagah bache */
    .topbar { padding:10px 20px; }
    .topbar-left a:last-child { display:none; }

    /* Navbar: logo bayen, hamburger right corner, Book button hamburger k pass */
    .navbar { padding:0 20px; }
    .nav-logo-text p { display:none; }
    .btn-nav-book { padding:9px 16px; font-size:.8rem; }

    /* Hero: left-aligned/side-by-side rakhein (baaqi sections center hi rahenge) */
    .hero { min-height:auto; padding:100px 20px 50px; text-align:left; align-items:flex-start; }
    .hero-content { width:100%; }
    .hero-badge { margin-left:0; margin-right:auto; }
    .hero h1 { font-size:2.1rem; }
    .hero-br-1 { display:none; } /* "Excellence in Healthcare" ab pehli line par hi aayega */
    .hero p { font-size:1rem; text-align:justify; margin-left:0; margin-right:0; }
    .hero-btns { flex-direction:row; flex-wrap:wrap; justify-content:flex-start; }
    /* Buttons chhote taake side-by-side theek fit ho jayein */
    .hero-btns a { width:auto; padding:11px 18px; font-size:.82rem; justify-content:center; }
    .hero-stats { flex-wrap:wrap; justify-content:flex-start; gap:20px; text-align:left; }
    .hero-right { grid-template-columns:repeat(4,1fr); gap:8px; }
    .hero-card { padding:10px 6px; }
    .hero-card i { font-size:1.2rem; margin-bottom:6px; }
    .hero-card span { font-size:.65rem; line-height:1.2; }

    /* Quick strip stacks vertically */
    .quick-strip { display:grid; grid-template-columns:1fr 1fr; padding:15px 20px; gap:0; }
    .quick-item { border-right:none; border-bottom:1px solid rgba(10,22,40,0.15); justify-content:flex-start; padding:12px 8px; gap:8px; }
    .quick-item:nth-child(odd) { border-right:1px solid rgba(10,22,40,0.15); }
    .quick-item:nth-last-child(-n+2) { border-bottom:none; }
    .quick-item i { font-size:1.1rem; }
    .quick-item-text strong { font-size:.8rem; }
    .quick-item-text span { font-size:.68rem; }

    section { padding:60px 20px !important; }

    /* All 2-column / 4-column grids collapse to single column */
    .about-grid, .why-grid, .appt-grid, .contact-grid,
    .form-row, .footer-grid { grid-template-columns:1fr !important; gap:30px !important; }
    /* About k 4 features 2-column mein hi rahenge (2 lines), 1-1 karke ganda nahi lagega */
    .about-features { grid-template-columns:1fr 1fr !important; gap:12px !important; }
    .about-feat { padding:10px 10px; }
    .about-feat span { font-size:.78rem; }
    .services-grid, .doctors-grid, .test-grid { grid-template-columns:1fr !important; }
    /* Chhoti mobile screen par test-grid wapas single column hai, is liye centering wala fix yahan zaroori nahi */
    .test-grid > *:last-child:nth-child(odd) { grid-column:auto; max-width:none; margin:0; }
    .stats-strip { grid-template-columns:1fr 1fr !important; padding:30px 20px !important; }
    .stat-num { font-size:2rem !important; } /* Mobile par numbers bahut bare lag rahe thay, chota kar diya */
    .stat-item { padding:30px 15px !important; }

    /* Tamam section headers aur paragraphs mobile par center align */
    .section-header { text-align:center; }
    .section-header .divider, section .divider { margin-left:auto; margin-right:auto; }
    section p { text-align:justify; margin-left:auto; margin-right:auto; }
    .service-img { height:210px; } /* Chhoti height mein images landscape/cut lag rahi thin, badha diya */

    /* About / Why-us / feature rows: icon+text ko bhi center kar dein */
    .about-grid > div { text-align:center; }
    .about-feat { justify-content:center; }
    .why-item { flex-direction:column; align-items:center; text-align:center; }
    .why-icon { margin:0 auto; }
    .appt-contact { display:grid; grid-template-columns:1fr 1fr; gap:15px; } /* Phone/email dono barabar width mein aligned */
    .appt-contact-item { text-align:left; }
    .appt-steps li { justify-content:center; text-align:left; }
    .contact-card { flex-direction:column; align-items:center; text-align:center; }

    /* Inline-styled CTA buttons (jaise "Book an Appointment") ko center karein */
    section a[style*="inline-flex"] { margin-left:auto; margin-right:auto; }

    footer { padding:50px 20px 25px; }
    .footer-grid { text-align:center; gap:35px !important; }
    /* Har column k beech divider taake alag-alag saaf dikhein */
    .footer-grid > div { padding-top:28px; border-top:1px solid rgba(255,255,255,0.08); }
    .footer-grid > div:first-child { padding-top:0; border-top:none; }
    .footer-brand p { margin-left:auto; margin-right:auto; }
    .footer-social { justify-content:center; }
    .footer-newsletter { max-width:300px; margin:0 auto; }
    .footer-bottom { flex-direction:column; gap:10px; text-align:center; }
}

/* ============================================================
   SMALL MOBILE (max-width: 480px)
   Font sizes aur spacing aur kam taake chhoti screen par
   sab kuch aasani se fit ho jaye
   ============================================================ */
@media (max-width: 480px) {
    .hero h1 { font-size:1.7rem; }
    .hero-right { grid-template-columns:repeat(4,1fr); gap:6px; }
    .hero-card { padding:8px 4px; }
    .hero-card i { font-size:1rem; margin-bottom:4px; }
    .hero-card span { font-size:.58rem; }
    .hero-stats { gap:18px; }
    .hero-stat-num { font-size:1.6rem; }
    .stats-strip { grid-template-columns:1fr !important; }
    .section-title { font-size:1.6rem; }
    .topbar-left a { font-size:.7rem; }
    .btn-hero-primary, .btn-hero-outline { padding:10px 14px; font-size:.78rem; }
}
</style>

</body>
</html>