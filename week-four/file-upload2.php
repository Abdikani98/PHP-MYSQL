<?php
$message = "";
$uploadedImage = "";
$registeredName = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $message = "Please enter your full name.";
    } elseif (
        !isset($_FILES["profile"]) ||
        $_FILES["profile"]["error"] !== UPLOAD_ERR_OK
    ) {
        $message = "Please select an image.";
    } else {
        $file = $_FILES["profile"];
        $extension = strtolower(
            pathinfo($file["name"], PATHINFO_EXTENSION)
        );

        $allowed = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "gif" => "image/gif"
        ];

        $imageInfo = @getimagesize($file["tmp_name"]);

        if ($file["size"] > 2097152) {
            $message = "Image must not exceed 2 MB.";
        } elseif (!isset($allowed[$extension])) {
            $message = "Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif (
            $imageInfo === false ||
            $imageInfo["mime"] !== $allowed[$extension]
        ) {
            $message = "Invalid image file.";
        } else {
            $directory = __DIR__ . "/Images/";

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = bin2hex(random_bytes(16)) . "." . $extension;

            if (move_uploaded_file(
                $file["tmp_name"],
                $directory . $filename
            )) {
                $message = "Registration successful!";
                $registeredName = $name;
                $uploadedImage = "Images/" . $filename;
            } else {
                $message = "Upload failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">

    <style>
        /* Re-theme Bootstrap with the sky blue of the Somali flag */
        :root {
            --bs-primary: #4189DD;
            --bs-primary-rgb: 65, 137, 221;
            --bs-link-color: #1D5FB0;
            --bs-body-font-family: "Bricolage Grotesque", system-ui, "Segoe UI", sans-serif;
            --bs-body-color: #1B2B44;
            --navy: #0B1F3A;
            --blue-deep: #1D5FB0;
            --line: #D9E4F2;
        }
        body {
            min-height: 100vh;
            background:
                radial-gradient(900px 480px at 8% -10%, #BFD8F6 0%, transparent 60%),
                radial-gradient(800px 480px at 100% 110%, #CFE1F8 0%, transparent 55%),
                #EEF4FC;
        }
        .btn-primary {
            --bs-btn-bg: var(--navy);
            --bs-btn-border-color: var(--navy);
            --bs-btn-hover-bg: var(--blue-deep);
            --bs-btn-hover-border-color: var(--blue-deep);
            --bs-btn-active-bg: var(--blue-deep);
            --bs-btn-active-border-color: var(--blue-deep);
            font-weight: 700;
            border-radius: .9rem;
        }
        .form-control {
            border: 1.5px solid var(--line);
            border-radius: .9rem;
            background-color: #F5F9FE;
            padding: .8rem 1rem;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 .25rem rgba(65, 137, 221, .2);
        }

        /* Hero */
        .hero { animation: rise .7s cubic-bezier(.2, .8, .2, 1) both; }
        @keyframes rise {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: none; }
        }
        .hero .mark {
            width: 76px; height: 76px;
            margin: 0 auto 18px;
            border-radius: 24px;
            background: linear-gradient(160deg, #4189DD, #1D5FB0);
            display: grid; place-items: center;
            box-shadow: 0 18px 36px -14px rgba(29, 95, 176, .8);
        }
        .hero .mark svg { width: 40px; height: 40px; fill: #fff; animation: spin 24s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .hero h2 { font-weight: 800; letter-spacing: -.03em; }

        /* Cards */
        .panel-card {
            border: 0;
            border-radius: 1.6rem;
            overflow: hidden;
            box-shadow: 0 30px 70px -34px rgba(11, 31, 58, .5);
        }
        .panel-card .card-header {
            border: 0;
            color: #fff;
            padding: 1.25rem 1.75rem;
        }
        .panel-card .card-header h5 { font-weight: 700; letter-spacing: -.01em; }
        .head-form { background: linear-gradient(160deg, #4189DD, #1D5FB0); }
        .head-data { background: var(--navy); }
        .panel-card .card-body { padding: 1.9rem; }

        /* Photo picker */
        .picker {
            display: flex; align-items: center; gap: 1rem;
            padding: 1rem;
            border: 2px dashed var(--line);
            border-radius: 1.2rem;
            background: #F5F9FE;
            margin-bottom: .75rem;
        }
        .thumb {
            flex: none;
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #fff;
            border: 1.5px solid var(--line);
            display: grid; place-items: center;
            overflow: hidden;
        }
        .thumb svg { width: 28px; height: 28px; stroke: var(--bs-primary); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .thumb img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .thumb.has img { display: block; animation: pop .35s cubic-bezier(.2, 1.4, .4, 1) both; }
        .thumb.has svg { display: none; }
        @keyframes pop { from { transform: scale(.6); opacity: 0; } to { transform: none; opacity: 1; } }

        /* Result */
        .avatar {
            width: 124px; height: 124px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 0 3px var(--bs-primary), 0 18px 30px -14px rgba(11, 31, 58, .5);
            animation: pop .5s cubic-bezier(.2, 1.4, .4, 1) both;
        }
        .data-row {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem;
            padding: .9rem 0;
            border-bottom: 1.5px solid #EEF3FA;
        }
        .data-row:last-child { border-bottom: 0; }
        .data-row span:first-child { color: #6B7A90; }
        .data-row span:last-child { font-weight: 500; text-align: right; word-break: break-all; }
        .empty-state .ring {
            width: 84px; height: 84px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            border: 2px dashed var(--line);
            display: grid; place-items: center;
        }
        .empty-state svg { width: 32px; height: 32px; stroke: #9FB3CE; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        .alert { border: 0; border-radius: 1rem; font-weight: 500; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="text-center mb-5 hero">
        <div class="mark" aria-hidden="true">
            <svg viewBox="0 0 100 100"><polygon points="50,4 61.8,36.2 96,36.9 68.8,57.6 78.8,90.4 50,70.8 21.2,90.4 31.2,57.6 4,36.9 38.2,36.2"/></svg>
        </div>
        <h2>Registration System</h2>
        <p class="text-secondary mb-0">Register and view your information</p>
    </div>

    <?php if ($message !== ""): ?>
        <div class="alert <?= $uploadedImage ? 'alert-success' : 'alert-danger' ?> shadow-sm mx-auto mb-4" style="max-width: 560px;" role="alert">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">

        <!-- LEFT COLUMN: FORM -->
        <div class="col-lg-6">
            <div class="card panel-card h-100">
                <div class="card-header head-form">
                    <h5 class="mb-0">Registration Form</h5>
                </div>

                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                placeholder="Enter your full name"
                                maxlength="100"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="profile" class="form-label fw-medium">
                                Profile Picture
                            </label>

                            <div class="picker">
                                <div class="thumb" id="thumb">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m0 0L7 9m5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                                    <img id="preview" alt="">
                                </div>
                                <div class="small text-secondary" id="pickHint">
                                    Choose a photo and it will appear here.
                                </div>
                            </div>

                            <input
                                type="file"
                                name="profile"
                                id="profile"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.gif"
                                required
                            >

                            <div class="form-text">
                                JPG, PNG, or GIF, maximum 2 MB.
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >
                            Register
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: DATA -->
        <div class="col-lg-6">
            <div class="card panel-card h-100">
                <div class="card-header head-data">
                    <h5 class="mb-0">Registration Data</h5>
                </div>

                <div class="card-body">

                    <?php if ($uploadedImage !== ""): ?>

                        <div class="text-center mb-4">
                            <img
                                src="<?= htmlspecialchars($uploadedImage, ENT_QUOTES, "UTF-8") ?>"
                                alt="Profile picture"
                                class="avatar"
                                width="124"
                                height="124"
                            >
                        </div>

                        <div>
                            <div class="data-row">
                                <span>Full Name</span>
                                <span><?= htmlspecialchars($registeredName, ENT_QUOTES, "UTF-8") ?></span>
                            </div>

                            <div class="data-row">
                                <span>Image Name</span>
                                <span><?= htmlspecialchars(basename($uploadedImage), ENT_QUOTES, "UTF-8") ?></span>
                            </div>

                            <div class="data-row">
                                <span>Status</span>
                                <span class="badge rounded-pill text-bg-success px-3 py-2">Registered</span>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="text-center text-secondary py-5 empty-state">
                            <div class="ring">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                            </div>
                            <h6 class="fw-bold text-body">No registration data yet</h6>
                            <p class="mb-0">
                                Complete the form and click Register.
                                Your data will appear here.
                            </p>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const input = document.getElementById('profile');
    const thumb = document.getElementById('thumb');
    const preview = document.getElementById('preview');
    const hint = document.getElementById('pickHint');
    const MAX = 2097152;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        if (file.size > MAX) {
            hint.textContent = file.name + ' is over 2 MB. Choose a smaller image.';
            hint.className = 'small text-danger';
        } else {
            hint.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            hint.className = 'small text-secondary';
        }
        if (file.type.startsWith('image/')) {
            preview.src = URL.createObjectURL(file);
            thumb.classList.add('has');
        }
    });
</script>

</body>
</html>