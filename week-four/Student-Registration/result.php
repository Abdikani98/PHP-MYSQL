<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
}

$student = $_SESSION['student'];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$sections = [
    'Personal Information' => [
        'name' => 'Full Name',
        'pob' => 'Place of Birth',
        'dob' => 'Date of Birth',
        'address' => 'Address',
        'gender' => 'Gender',
        'telephone' => 'Telephone',
        'email' => 'Email'
    ],
    'Parent Information' => [
        'parent_name' => 'Parent Name',
        'parent_telephone' => 'Parent Telephone',
        'relationship' => 'Relationship'
    ],
    'Academic Information' => [
        'student_id' => 'Student ID',
        'faculty' => 'Faculty',
        'department' => 'Department',
        'class' => 'Class',
        'registration_date' => 'Registration Date'
    ]
];

/* Presentation only: icon per section */
$sectionIcons = [
    'Personal Information' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
    'Parent Information'   => '<circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.7 3-6 7-6s7 2.3 7 6"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5c2.2.6 4 2.3 4 5.5"/>',
    'Academic Information' => '<path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"/>'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration Result</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        :root {
            --ink: #0b2538;
            --ink-2: #123650;
            --tide: #0d7a82;
            --tide-2: #12a1a6;
            --foam: #e4f3f2;
            --sun: #f5b335;
            --paper: #f3f6f8;
            --line: #d6e1e6;
            --text: #1b2f3d;
            --muted: #62798a;
            --ok: #1a9a6a;
        }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--paper);
            color: var(--text);
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5 { font-family: 'Bricolage Grotesque', 'DM Sans', sans-serif; }

        /* ---------- Hero ---------- */
        .hero {
            position: relative;
            background:
                radial-gradient(800px 420px at 0% 0%, rgba(18,161,166,.38), transparent 60%),
                radial-gradient(600px 380px at 100% 100%, rgba(245,179,53,.18), transparent 60%),
                linear-gradient(170deg, var(--ink) 0%, var(--ink-2) 100%);
            color: #fff;
            padding: 52px 0 120px;
            overflow: hidden;
        }
        .hero::after {
            content: ""; position: absolute; left: 0; right: 0; bottom: -2px; height: 130px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 140' preserveAspectRatio='none'%3E%3Cpath d='M0 70 C100 20 200 120 300 70 S500 20 600 70 S750 110 800 70 V140 H0Z' fill='%23ffffff' fill-opacity='.05'/%3E%3Cpath d='M0 95 C120 50 220 140 340 95 S560 50 680 95 S780 120 800 100 V140 H0Z' fill='%23ffffff' fill-opacity='.05'/%3E%3C/svg%3E") center/cover no-repeat;
            pointer-events: none;
        }
        .hero .container { position: relative; z-index: 1; }

        /* The one memorable moment: drawing check mark */
        .check {
            width: 76px; height: 76px; margin: 0 auto 18px;
            border-radius: 50%;
            background: rgba(245,179,53,.14);
            border: 2px solid var(--sun);
            display: grid; place-items: center;
            animation: pop .6s cubic-bezier(.2,1.2,.3,1) both;
        }
        .check svg { width: 38px; height: 38px; }
        .check path {
            stroke: var(--sun); stroke-width: 3.2; fill: none;
            stroke-linecap: round; stroke-linejoin: round;
            stroke-dasharray: 30; stroke-dashoffset: 30;
            animation: draw .6s .45s ease forwards;
        }
        @keyframes pop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        @keyframes draw { to { stroke-dashoffset: 0; } }

        .hero h1 { font-weight: 800; letter-spacing: -.5px; font-size: clamp(1.8rem, 4vw, 2.6rem); margin-bottom: 8px; }
        .hero p { color: #b9d0de; margin: 0; font-size: 1.05rem; }

        /* ---------- Profile card (overlaps hero) ---------- */
        .profile {
            margin-top: -78px; position: relative; z-index: 2;
            background: #fff; border: 1px solid var(--line);
            border-radius: 22px; padding: 28px;
            box-shadow: 0 22px 44px -22px rgba(11,37,56,.35);
        }
        .student-photo {
            width: 132px; height: 132px; object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 0 0 3px var(--tide-2), 0 12px 24px -10px rgba(11,37,56,.4);
            background: var(--foam);
        }
        .profile h4 { font-weight: 800; color: var(--ink); letter-spacing: -.3px; margin-bottom: 4px; }
        .profile .sub { color: var(--muted); }
        .id-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--foam); color: var(--tide);
            font-weight: 700; padding: 7px 14px; border-radius: 99px; font-size: .9rem;
        }
        .id-badge svg { width: 16px; height: 16px; }
        .chip {
            display: inline-flex; align-items: center; gap: 6px;
            border: 1px solid var(--line); color: var(--muted);
            padding: 6px 12px; border-radius: 99px; font-size: .85rem; font-weight: 500; background: #fbfdfe;
        }

        /* ---------- Info sections ---------- */
        .info-card {
            height: 100%;
            background: #fff; border: 1px solid var(--line);
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 14px 34px -20px rgba(11,37,56,.2);
        }
        .info-head { display: flex; align-items: center; gap: 14px; padding: 20px 22px; border-bottom: 1px solid var(--line); }
        .info-ico {
            width: 44px; height: 44px; border-radius: 13px; flex: none;
            background: var(--foam); color: var(--tide); display: grid; place-items: center;
        }
        .info-ico svg { width: 23px; height: 23px; }
        .info-head h5 { margin: 0; font-size: 1.15rem; font-weight: 700; color: var(--ink); }
        .info-list { list-style: none; margin: 0; padding: 6px 22px 10px; }
        .info-list li {
            display: flex; flex-wrap: wrap; justify-content: space-between; gap: 4px 16px;
            padding: 13px 0; border-bottom: 1px dashed var(--line);
        }
        .info-list li:last-child { border-bottom: 0; }
        .info-list .k { color: var(--muted); font-size: .9rem; font-weight: 500; }
        .info-list .v { font-weight: 600; color: var(--text); text-align: right; word-break: break-word; }

        /* ---------- Buttons ---------- */
        .btn { border-radius: 13px; font-weight: 600; padding: 13px 22px; transition: transform .15s, box-shadow .2s, background .2s; }
        .btn-primary {
            border: 0; color: #fff;
            background: linear-gradient(110deg, var(--tide), var(--tide-2));
            box-shadow: 0 12px 24px -10px rgba(13,122,130,.6);
        }
        .btn-primary:hover, .btn-primary:focus { background: linear-gradient(110deg, #0a6a71, var(--tide)); transform: translateY(-2px); color: #fff; }
        .btn-outline-secondary { border: 1.5px solid var(--line); color: var(--muted); background: #fff; }
        .btn-outline-secondary:hover { background: var(--paper); color: var(--ink); border-color: #b8cad3; }

        @media (max-width: 767px) {
            .hero { padding: 38px 0 108px; }
            .profile { padding: 22px 18px; }
            .info-list .v { text-align: left; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .check path { stroke-dashoffset: 0; }
        }

        /* ---------- Print ---------- */
        @media print {
            body { background: #fff; }
            .hero { background: #fff !important; color: var(--ink); padding: 0 0 10px; }
            .hero::after, .check, .no-print { display: none !important; }
            .hero p { color: var(--muted); }
            .profile { margin-top: 0; box-shadow: none; }
            .info-card { box-shadow: none; break-inside: avoid; }
        }
    </style>
</head>
<body>

<header class="hero text-center">
    <div class="container">
        <div class="check" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M5 12.5l5 5 9-11"/></svg>
        </div>
        <h1>Registration Successful!</h1>
        <p>Student information has been submitted.</p>
    </div>
</header>

<main class="container pb-5">

    <!-- Profile -->
    <section class="profile">
        <div class="row align-items-center g-4">
            <div class="col-md-auto text-center">
                <img src="<?= e($student['image']) ?>" alt="Student Image" class="student-photo">
            </div>
            <div class="col-md text-center text-md-start">
                <h4><?= e($student['name']) ?></h4>
                <div class="sub mb-3"><?= e($student['faculty']) ?> &middot; <?= e($student['department']) ?></div>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <span class="id-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="3"/><circle cx="9" cy="11" r="2"/><path d="M14 10h4M14 14h4"/></svg>
                        Student ID: <?= e($student['student_id']) ?>
                    </span>
                    <span class="chip">Class: <?= e($student['class']) ?></span>
                    <span class="chip">Registered: <?= e($student['registration_date']) ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Details -->
    <div class="row g-4 mt-1">
        <?php foreach ($sections as $sectionTitle => $items): ?>
            <div class="col-12 <?= $sectionTitle === 'Personal Information' ? 'col-lg-7' : ($sectionTitle === 'Parent Information' ? 'col-lg-5' : 'col-lg-12') ?>">
                <div class="info-card">
                    <div class="info-head">
                        <div class="info-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?= $sectionIcons[$sectionTitle] ?></svg>
                        </div>
                        <h5><?= e($sectionTitle) ?></h5>
                    </div>
                    <ul class="info-list">
                        <?php foreach ($items as $key => $label): ?>
                            <li>
                                <span class="k"><?= e($label) ?></span>
                                <span class="v"><?= e($student[$key]) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Actions -->
    <div class="d-flex flex-wrap gap-2 mt-4 no-print">
        <a href="index.php" class="btn btn-primary">Register Another Student</a>
        <button onclick="window.print()" class="btn btn-outline-secondary">Print Information</button>
    </div>

</main>
</body>
</html>