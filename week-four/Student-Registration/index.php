<?php
session_start();

$errors = [];
$firstError = '';

$fields = [
    'name' => 'Full Name',
    'pob' => 'Place of Birth',
    'dob' => 'Date of Birth',
    'address' => 'Address',
    'gender' => 'Gender',
    'telephone' => 'Telephone',
    'email' => 'Email',
    'parent_name' => 'Parent Name',
    'parent_telephone' => 'Parent Telephone',
    'relationship' => 'Relationship',
    'student_id' => 'Student ID',
    'faculty' => 'Faculty',
    'department' => 'Department',
    'class' => 'Class',
    'registration_date' => 'Registration Date'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check all required text/select/date fields
    foreach ($fields as $key => $label) {
        if (!isset($_POST[$key]) || trim((string)$_POST[$key]) === '') {
            $errors[$key] = "$label is required!";
        }
    }

    // Name: letters and spaces only
    if (isset($_POST['name']) && trim($_POST['name']) !== '' &&
        !preg_match('/^[\p{L}\s]+$/u', trim($_POST['name']))) {
        $errors['name'] = 'Name must contain letters and spaces only!';
    }

    // Email format
    if (isset($_POST['email']) && trim($_POST['email']) !== '' &&
        !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address!';
    }

    // Allowed gender values
    if (isset($_POST['gender']) && $_POST['gender'] !== '' &&
        !in_array($_POST['gender'], ['Male', 'Female'], true)) {
        $errors['gender'] = 'Please select a valid gender!';
    }

    // Validate date formats
    foreach (['dob', 'registration_date'] as $dateField) {
        if (!empty($_POST[$dateField])) {
            $date = DateTime::createFromFormat('!Y-m-d', $_POST[$dateField]);
            if (!$date || $date->format('Y-m-d') !== $_POST[$dateField]) {
                $errors[$dateField] = 'Please enter a valid date!';
            }
        }
    }

    // Check image upload
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif'
    ];

    if (!isset($_FILES['image']) ||
        $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['image'] = 'Student Image is required!';
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = 'Image upload failed. Please try again!';
    } else {
        $file = $_FILES['image'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $errors['image'] = 'Image must not exceed 2 MB!';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            if (!isset($allowedTypes[$mime]) ||
                @getimagesize($file['tmp_name']) === false) {
                $errors['image'] = 'Only PNG, JPG, JPEG and GIF images are allowed!';
            }
        }
    }

    // Identify first error in the order shown on the form
    $orderedKeys = array_merge(array_keys($fields), ['image']);
    foreach ($orderedKeys as $key) {
        if (isset($errors[$key])) {
            $firstError = $key;
            break;
        }
    }

    // Save image and redirect only if there are no errors
    if (empty($errors)) {
        $uploadDir = __DIR__ . '/uploads/';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            $errors['image'] = 'Unable to create uploads folder!';
            $firstError = 'image';
        } else {
            $extension = $allowedTypes[$mime];
            $imageName = bin2hex(random_bytes(16)) . '.' . $extension;
            $destination = $uploadDir . $imageName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $student = [];

                foreach ($fields as $key => $label) {
                    $student[$key] = trim((string)$_POST[$key]);
                }

                $student['image'] = 'uploads/' . $imageName;
                $_SESSION['student'] = $student;

                header('Location: result.php');
                exit;
            } else {
                $errors['image'] = 'Unable to save the image. Check folder permissions!';
                $firstError = 'image';
            }
        }
    }
}

function old($key) {
    return htmlspecialchars($_POST[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

function fieldError($key, $errors) {
    if (isset($errors[$key])) {
        return '<div class="invalid-feedback">'
            . htmlspecialchars($errors[$key], ENT_QUOTES, 'UTF-8')
            . '</div>';
    }
    return '';
}

/* ---------- Design helpers (presentation only) ---------- */
function icon($name) {
    $paths = [
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
        'pin'      => '<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/>',
        'home'     => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>',
        'phone'    => '<path d="M5 4h4l2 5-2.5 1.500a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3 7l9 6 9-6"/>',
        'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.7 3-6 7-6s7 2.3 7 6"/><path d="M16 4.500a3.5 3.5 0 0 1 0 7M18 14.500c2.2.600 4 2.3 4 5.5"/>',
        'heart'    => '<path d="M12 20s-8-4.8-8-10.500A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 8 2.500C20 15.2 12 20 12 20z"/>',
        'id'       => '<rect x="3" y="5" width="18" height="14" rx="3"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.500-1.5 1.7-2 3-2s2.5.500 3 2M14 10h4M14 14h4"/>',
        'building' => '<path d="M4 21V5l8-3 8 3v16"/><path d="M9 9h2M13 9h2M9 13h2M13 13h2M10 21v-4h4v4"/>',
        'book'     => '<path d="M4 4h7a3 3 0 0 1 3 3v14a2 2 0 0 0-2-2H4z"/><path d="M20 4h-4a3 3 0 0 0-2 .800"/><path d="M20 4v15h-6"/>',
        'cap'      => '<path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11.500V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"/>',
        'gender'   => '<circle cx="12" cy="9" r="5"/><path d="M12 14v7M9 18h6"/>',
    ];
    return '<svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . ($paths[$name] ?? '') . '</svg>';
}

function inputField($key, $label, $type = 'text', $ico = 'user') {
    global $errors;

    $invalid = isset($errors[$key]) ? ' is-invalid' : '';
    $value = old($key);

    return '<div class="col-md-6 mb-3">
        <label for="' . $key . '" class="form-label">'
        . $label . ' <span class="req">*</span></label>
        <div class="input-wrap">' . icon($ico) . '
        <input type="' . $type . '" id="' . $key . '" name="' . $key
        . '" class="form-control' . $invalid . '" value="' . $value
        . '" required>' . fieldError($key, $errors) . '</div></div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration Form</title>
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
            --danger: #d3344a;
            --ok: #1a9a6a;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--paper);
            color: var(--text);
            min-height: 100vh;
        }
        h1, h2, h3, h5, .display { font-family: 'Bricolage Grotesque', 'DM Sans', sans-serif; }

        /* ---------- Layout ---------- */
        .shell {
            display: grid;
            grid-template-columns: 380px 1fr;
            min-height: 100vh;
        }

        /* ---------- Side panel ---------- */
        .side {
            position: sticky; top: 0;
            height: 100vh;
            background:
                radial-gradient(900px 500px at -10% -10%, rgba(18,161,166,.35), transparent 60%),
                radial-gradient(600px 400px at 110% 110%, rgba(245,179,53,.16), transparent 60%),
                linear-gradient(170deg, var(--ink) 0%, var(--ink-2) 100%);
            color: #fff;
            padding: 38px 34px;
            display: flex; flex-direction: column; gap: 26px;
            overflow: hidden;
        }
        .side::after {            /* quiet wave texture */
            content: ""; position: absolute; left: 0; right: 0; bottom: -2px; height: 140px;
            background:
              url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 140' preserveAspectRatio='none'%3E%3Cpath d='M0 70 C100 20 200 120 300 70 S500 20 600 70 S750 110 800 70 V140 H0Z' fill='%23ffffff' fill-opacity='.05'/%3E%3Cpath d='M0 95 C120 50 220 140 340 95 S560 50 680 95 S780 120 800 100 V140 H0Z' fill='%23ffffff' fill-opacity='.05'/%3E%3C/svg%3E") center/cover no-repeat;
            pointer-events: none;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-mark {
            width: 42px; height: 42px; border-radius: 13px;
            background: linear-gradient(135deg, var(--tide-2), var(--tide));
            display: grid; place-items: center;
            box-shadow: 0 8px 20px rgba(18,161,166,.35);
        }
        .brand-mark svg { width: 24px; height: 24px; }
        .brand b { font-family: 'Bricolage Grotesque'; font-size: 1.05rem; letter-spacing: .2px; }
        .brand small { display: block; color: #9db8c8; font-size: .78rem; margin-top: -2px; }

        /* Live ID card – the memorable element */
        .idcard {
            position: relative;
            background: linear-gradient(160deg, rgba(255,255,255,.14), rgba(255,255,255,.05));
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 22px;
            padding: 26px 22px 22px;
            backdrop-filter: blur(6px);
            text-align: center;
            animation: cardIn .9s cubic-bezier(.2,.8,.2,1) both;
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(18px) rotate(-1.5deg); } to { opacity: 1; transform: none; } }
        .ring-wrap { position: relative; width: 132px; height: 132px; margin: 0 auto 16px; }
        .ring-wrap svg.ring { position: absolute; inset: 0; transform: rotate(-90deg); }
        .ring-bg { stroke: rgba(255,255,255,.14); }
        .ring-fg { stroke: var(--sun); stroke-linecap: round; transition: stroke-dashoffset .6s cubic-bezier(.3,.8,.3,1); }
        .avatar {
            position: absolute; inset: 11px; border-radius: 50%;
            background: rgba(255,255,255,.1) center/cover no-repeat;
            display: grid; place-items: center; color: #9db8c8; overflow: hidden;
        }
        .avatar svg { width: 46px; height: 46px; }
        .pct {
            position: absolute; right: -4px; bottom: 2px;
            background: var(--sun); color: var(--ink);
            font-weight: 700; font-size: .78rem;
            padding: 3px 9px; border-radius: 99px;
            box-shadow: 0 4px 12px rgba(0,0,0,.25);
        }
        .idc-name { font-family: 'Bricolage Grotesque'; font-size: 1.3rem; font-weight: 700; line-height: 1.2; min-height: 1.6rem; word-break: break-word; }
        .idc-name.ph, .idc-line.ph { color: #86a3b6; font-weight: 500; }
        .idc-line { color: #cfe0ea; font-size: .92rem; margin-top: 4px; min-height: 1.3rem; }
        .idc-meta {
            display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
            margin-top: 18px; padding-top: 16px;
            border-top: 1px dashed rgba(255,255,255,.22);
            text-align: left;
        }
        .idc-meta span { display: block; font-size: .72rem; color: #9db8c8; }
        .idc-meta b { font-size: .92rem; font-weight: 600; word-break: break-word; }

        .steps { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px; position: relative; z-index: 1; }
        .steps button {
            width: 100%; display: flex; align-items: center; gap: 12px;
            background: transparent; border: 0; color: #cfe0ea;
            padding: 11px 12px; border-radius: 12px; text-align: left;
            font-weight: 500; transition: background .2s, color .2s;
        }
        .steps button:hover, .steps button.active { background: rgba(255,255,255,.08); color: #fff; }
        .steps button:focus-visible { outline: 2px solid var(--sun); outline-offset: 2px; }
        .dot {
            width: 26px; height: 26px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,.3);
            display: grid; place-items: center; flex: none;
            transition: all .3s;
        }
        .dot svg { width: 14px; height: 14px; opacity: 0; transition: opacity .2s; }
        .steps button.done .dot { background: var(--sun); border-color: var(--sun); color: var(--ink); }
        .steps button.done .dot svg { opacity: 1; }
        .steps small { display: block; color: #8fabbc; font-weight: 400; }

        /* ---------- Main ---------- */
        .main { padding: 46px clamp(20px, 5vw, 64px) 70px; }
        .main-inner { max-width: 820px; margin: 0 auto; }
        .page-head h1 { font-size: clamp(1.9rem, 3.4vw, 2.6rem); font-weight: 800; letter-spacing: -.5px; color: var(--ink); margin: 0 0 8px; }
        .page-head p { color: var(--muted); margin: 0 0 28px; font-size: 1.02rem; }
        .req { color: var(--danger); }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 28px 28px 14px;
            margin-bottom: 22px;
            scroll-margin-top: 20px;
            box-shadow: 0 1px 0 rgba(11,37,56,.03), 0 14px 34px -18px rgba(11,37,56,.18);
        }
        .panel-head { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
        .panel-ico {
            width: 46px; height: 46px; border-radius: 14px; flex: none;
            background: var(--foam); color: var(--tide);
            display: grid; place-items: center;
        }
        .panel-ico svg { width: 24px; height: 24px; }
        .panel-head h5 { margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--ink); }
        .panel-head small { color: var(--muted); }

        .form-label { font-weight: 600; font-size: .9rem; color: #34495a; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-wrap .fi {
            position: absolute; left: 14px; top: 13px; width: 20px; height: 20px;
            color: #8aa1b0; pointer-events: none; transition: color .2s;
        }
        .input-wrap:focus-within .fi { color: var(--tide); }
        .form-control, .form-select {
            min-height: 48px;
            padding: 10px 14px 10px 44px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            background-color: #fbfdfe;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .form-select { background-position: right 14px center; }
        .form-control:hover, .form-select:hover { border-color: #b8cad3; }
        .form-control:focus, .form-select:focus {
            background: #fff; border-color: var(--tide);
            box-shadow: 0 0 0 4px rgba(13,122,130,.14);
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger); background-image: none; background-color: #fff8f9;
        }
        .form-select.is-invalid { background-image: var(--bs-form-select-bg-img); }
        .form-control.is-invalid:focus, .form-select.is-invalid:focus { box-shadow: 0 0 0 4px rgba(211,52,74,.14); }
        .invalid-feedback { display: block; font-size: .84rem; font-weight: 500; color: var(--danger); margin-top: 6px; }

        /* Dropzone */
        .dz-wrap { display: flex; flex-direction: column; }
        .dz-wrap input[type=file] { position: absolute; width: 1px; height: 1px; opacity: 0; order: 0; }
        .dropzone {
            order: 1; margin: 0;
            display: flex; align-items: center; gap: 14px;
            min-height: 48px; padding: 12px 14px;
            border: 1.5px dashed #a9c0cb; border-radius: 12px;
            background: #fbfdfe; cursor: pointer;
            transition: all .2s;
        }
        .dropzone:hover, .dropzone.drag { border-color: var(--tide); background: var(--foam); }
        .dz-wrap input:focus-visible + .dropzone { box-shadow: 0 0 0 4px rgba(13,122,130,.2); border-color: var(--tide); }
        .dz-wrap input.is-invalid + .dropzone { border-color: var(--danger); background: #fff8f9; }
        .dz-thumb {
            width: 46px; height: 46px; border-radius: 12px; flex: none;
            background: var(--foam) center/cover no-repeat; color: var(--tide);
            display: grid; place-items: center;
        }
        .dz-thumb svg { width: 22px; height: 22px; }
        .dz-text b { display: block; font-size: .92rem; font-weight: 600; word-break: break-all; }
        .dz-text span { font-size: .8rem; color: var(--muted); }
        .dz-wrap .invalid-feedback { order: 2; }

        /* Alerts */
        .alert-danger {
            border: 1px solid #f6c5cc; border-left: 5px solid var(--danger);
            border-radius: 14px; background: #fff3f5; color: #8d1f31;
        }
        .alert-danger .alert-heading { font-family: 'Bricolage Grotesque'; font-weight: 700; font-size: 1.05rem; }

        /* Buttons */
        .actions { display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap; }
        .btn { border-radius: 13px; font-weight: 600; padding: 14px 22px; transition: transform .15s, box-shadow .2s, background .2s; }
        .btn-primary {
            flex: 1 1 260px; border: 0; color: #fff;
            background: linear-gradient(110deg, var(--tide), var(--tide-2));
            box-shadow: 0 12px 24px -10px rgba(13,122,130,.6);
        }
        .btn-primary:hover, .btn-primary:focus { background: linear-gradient(110deg, #0a6a71, var(--tide)); transform: translateY(-2px); color: #fff; }
        .btn-primary:active { transform: translateY(0); }
        .btn-outline-secondary { border: 1.5px solid var(--line); color: var(--muted); background: #fff; }
        .btn-outline-secondary:hover { background: var(--paper); color: var(--ink); border-color: #b8cad3; }

        /* Mobile progress bar */
        .m-bar { display: none; }

        @media (max-width: 991px) {
            .shell { grid-template-columns: 1fr; }
            .side { position: relative; height: auto; padding: 22px 20px 26px; gap: 18px; }
            .steps { display: none; }
            .idcard { display: flex; align-items: center; gap: 16px; text-align: left; padding: 16px; }
            .ring-wrap { width: 86px; height: 86px; margin: 0; flex: none; }
            .avatar { inset: 8px; } .avatar svg { width: 30px; height: 30px; }
            .pct { font-size: .68rem; padding: 2px 7px; }
            .idc-meta { display: none; }
            .idc-name { font-size: 1.1rem; }
            .m-bar { display: block; position: sticky; top: 0; z-index: 20; height: 4px; background: rgba(13,122,130,.15); }
            .m-bar i { display: block; height: 100%; width: 0; background: linear-gradient(90deg, var(--tide), var(--sun)); transition: width .5s; }
            .main { padding-top: 28px; }
            .panel { padding: 22px 18px 10px; border-radius: 16px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>
<div class="m-bar" aria-hidden="true"><i id="mBar"></i></div>

<div class="shell">

    <!-- ========== Side panel: live ID card ========== -->
    <aside class="side">
        <div class="brand">
            <div class="brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11.500V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"/></svg>
            </div>
            <div><b>Student Registration</b><small>New student record</small></div>
        </div>

        <div class="idcard" aria-live="polite">
            <div class="ring-wrap">
                <svg class="ring" viewBox="0 0 132 132" width="132" height="132">
                    <circle class="ring-bg" cx="66" cy="66" r="60" fill="none" stroke-width="6"/>
                    <circle class="ring-fg" id="ringFg" cx="66" cy="66" r="60" fill="none" stroke-width="6"
                            stroke-dasharray="376.99" stroke-dashoffset="376.99"/>
                </svg>
                <div class="avatar" id="avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                </div>
                <span class="pct" id="pct">0%</span>
            </div>
            <div class="idc-body">
                <div class="idc-name ph" id="cardName">Your full name</div>
                <div class="idc-line ph" id="cardLine">Faculty · Department</div>
                <div class="idc-meta">
                    <div><span>Student ID</span><b id="cardId">—</b></div>
                    <div><span>Class</span><b id="cardClass">—</b></div>
                </div>
            </div>
        </div>

        <ul class="steps" id="steps">
            <li><button type="button" data-target="sec1">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                <span>Personal information<small id="c1">0 of 8 done</small></span></button></li>
            <li><button type="button" data-target="sec2">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                <span>Parent information<small id="c2">0 of 3 done</small></span></button></li>
            <li><button type="button" data-target="sec3">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                <span>Academic information<small id="c3">0 of 5 done</small></span></button></li>
        </ul>
    </aside>

    <!-- ========== Form ========== -->
    <main class="main">
        <div class="main-inner">

            <div class="page-head">
                <h1>Register a new student</h1>
                <p>Fill in the details below. Fields marked <span class="req">*</span> are required.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading">⚠ Please complete or correct these fields:</h5>
                    <ul class="mb-0">
                        <?php foreach ($errors as $message): ?>
                            <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" id="studentForm" novalidate>

                <section class="panel" id="sec1" data-section>
                    <div class="panel-head">
                        <div class="panel-ico"><?= icon('user') ?></div>
                        <div><h5>Personal information</h5><small>Who the student is and how to reach them</small></div>
                    </div>
                    <div class="row">
                        <?= inputField('name', 'Full Name', 'text', 'user') ?>
                        <?= inputField('pob', 'Place of Birth', 'text', 'pin') ?>
                        <?= inputField('dob', 'Date of Birth', 'date', 'calendar') ?>
                        <?= inputField('address', 'Address', 'text', 'home') ?>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender <span class="req">*</span></label>
                            <div class="input-wrap">
                                <?= icon('gender') ?>
                                <select name="gender" id="gender"
                                        class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>"
                                        required>
                                    <option value="">Choose Gender</option>
                                    <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                                </select>
                                <?= fieldError('gender', $errors) ?>
                            </div>
                        </div>

                        <?= inputField('telephone', 'Telephone', 'tel', 'phone') ?>
                        <?= inputField('email', 'Email', 'email', 'mail') ?>

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Student Image <span class="req">*</span></label>
                            <div class="dz-wrap" id="dzWrap">
                                <input type="file" name="image" id="image"
                                       class="<?= isset($errors['image']) ? 'is-invalid' : '' ?>"
                                       accept=".png,.jpg,.jpeg,.gif" required>
                                <label for="image" class="dropzone" id="dropzone">
                                    <span class="dz-thumb" id="dzThumb">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                                    </span>
                                    <span class="dz-text">
                                        <b id="dzName">Choose or drop a photo</b>
                                        <span id="dzHint">PNG, JPG, JPEG or GIF · max 2 MB</span>
                                    </span>
                                </label>
                                <?= fieldError('image', $errors) ?>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="panel" id="sec2" data-section>
                    <div class="panel-head">
                        <div class="panel-ico"><?= icon('users') ?></div>
                        <div><h5>Parent information</h5><small>Who we contact for the student</small></div>
                    </div>
                    <div class="row">
                        <?= inputField('parent_name', 'Parent Name', 'text', 'user') ?>
                        <?= inputField('parent_telephone', 'Parent Telephone', 'tel', 'phone') ?>
                        <?= inputField('relationship', 'Relationship', 'text', 'heart') ?>
                    </div>
                </section>

                <section class="panel" id="sec3" data-section>
                    <div class="panel-head">
                        <div class="panel-ico"><?= icon('cap') ?></div>
                        <div><h5>Academic information</h5><small>Where the student studies</small></div>
                    </div>
                    <div class="row">
                        <?= inputField('student_id', 'Student ID', 'text', 'id') ?>
                        <?= inputField('faculty', 'Faculty', 'text', 'building') ?>
                        <?= inputField('department', 'Department', 'text', 'book') ?>
                        <?= inputField('class', 'Class', 'text', 'users') ?>
                        <?= inputField('registration_date', 'Registration Date', 'date', 'calendar') ?>
                    </div>
                </section>

                <div class="actions">
                    <button type="submit" class="btn btn-primary btn-lg">Register Student</button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg">Clear Form</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
const form = document.getElementById('studentForm');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');

nameInput.addEventListener('input', function () {
    this.setCustomValidity(
        /^[\p{L}\s]+$/u.test(this.value.trim()) || this.value.trim() === ''
            ? ''
            : 'Name must contain letters and spaces only!'
    );
});

emailInput.addEventListener('input', function () {
    this.setCustomValidity(
        this.value === '' || this.validity.typeMismatch
            ? (this.value === '' ? '' : 'Please enter a valid email address!')
            : ''
    );
});

form.addEventListener('submit', function (event) {
    let firstInvalid = null;

    // Check required fields and show red borders/messages
    form.querySelectorAll('[required]').forEach(function (field) {
        const emptyFile = field.type === 'file' && field.files.length === 0;
        const emptyValue = field.type !== 'file' && field.value.trim() === '';
        const invalid = emptyFile || emptyValue || !field.checkValidity();

        field.classList.toggle('is-invalid', invalid);

        let feedback = field.parentElement.querySelector('.client-error');
        if (invalid) {
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback client-error';
                field.insertAdjacentElement('afterend', feedback);
            }
            feedback.textContent = emptyFile || emptyValue
                ? 'This field is required!'
                : (field.validationMessage || 'Please enter a valid value.');

            if (!firstInvalid) firstInvalid = field;
        } else if (feedback) {
            feedback.remove();
        }
    });

    if (firstInvalid) {
        event.preventDefault();
        firstInvalid.focus();
        firstInvalid.reportValidity?.();
    }
});

/* ---------- Design behaviour: live card, progress, image preview ---------- */
(function () {
    const $ = (id) => document.getElementById(id);
    const required = Array.from(form.querySelectorAll('[required]'));
    const CIRC = 376.99;
    const imageInput = $('image');

    function isFilled(f) {
        return f.type === 'file' ? f.files.length > 0 : f.value.trim() !== '';
    }

    function setCard(el, text, placeholder) {
        el.textContent = text || placeholder;
        el.classList.toggle('ph', !text);
    }

    function update() {
        // overall progress
        const done = required.filter(isFilled).length;
        const pct = Math.round((done / required.length) * 100);
        $('pct').textContent = pct + '%';
        $('ringFg').style.strokeDashoffset = CIRC * (1 - pct / 100);
        $('mBar').style.width = pct + '%';

        // per-section progress
        document.querySelectorAll('[data-section]').forEach(function (sec, i) {
            const fields = Array.from(sec.querySelectorAll('[required]'));
            const d = fields.filter(isFilled).length;
            $('c' + (i + 1)).textContent = d + ' of ' + fields.length + ' done';
            document.querySelector('.steps button[data-target="' + sec.id + '"]')
                .classList.toggle('done', d === fields.length);
        });

        // live ID card
        setCard($('cardName'), nameInput.value.trim(), 'Your full name');
        const fac = $('faculty').value.trim(), dep = $('department').value.trim();
        setCard($('cardLine'), [fac, dep].filter(Boolean).join(' · '), 'Faculty · Department');
        $('cardId').textContent = $('student_id').value.trim() || '—';
        $('cardClass').textContent = $('class').value.trim() || '—';
    }

    function showImage() {
        const f = imageInput.files[0];
        const avatar = $('avatar'), thumb = $('dzThumb');
        if (!f) {
            avatar.style.backgroundImage = ''; avatar.firstElementChild.style.display = '';
            thumb.style.backgroundImage = ''; thumb.firstElementChild.style.display = '';
            $('dzName').textContent = 'Choose or drop a photo';
            $('dzHint').textContent = 'PNG, JPG, JPEG or GIF · max 2 MB';
            return;
        }
        const url = URL.createObjectURL(f);
        avatar.style.backgroundImage = 'url(' + url + ')';
        avatar.firstElementChild.style.display = 'none';
        thumb.style.backgroundImage = 'url(' + url + ')';
        thumb.firstElementChild.style.display = 'none';
        $('dzName').textContent = f.name;
        $('dzHint').textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB · click to change';
    }

    form.addEventListener('input', update);
    form.addEventListener('change', update);
    form.addEventListener('reset', function () { setTimeout(function () { showImage(); update(); }, 0); });
    imageInput.addEventListener('change', showImage);

    // drag & drop
    const dz = $('dropzone');
    ['dragenter', 'dragover'].forEach(function (ev) {
        dz.addEventListener(ev, function (e) { e.preventDefault(); dz.classList.add('drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        dz.addEventListener(ev, function (e) { e.preventDefault(); dz.classList.remove('drag'); });
    });
    dz.addEventListener('drop', function (e) {
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            imageInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

    // side navigation
    document.querySelectorAll('.steps button').forEach(function (b) {
        b.addEventListener('click', function () {
            document.getElementById(b.dataset.target).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    update();
})();

<?php if ($firstError !== ''): ?>
const firstErrorField = document.getElementById(<?= json_encode($firstError) ?>);
if (firstErrorField) {
    firstErrorField.focus();
}
<?php endif; ?>
</script>
</body>
</html>