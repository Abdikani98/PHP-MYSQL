<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #4189DD;
            --blue-deep: #1D5FB0;
            --navy: #0B1F3A;
            --ink: #1B2B44;
            --muted: #6B7A90;
            --line: #D9E4F2;
            --wash: #EEF4FC;
            --paper: #FFFFFF;
            --ok: #178A5C;
            --ok-bg: #E4F6EE;
            --bad: #C23B3B;
            --bad-bg: #FCE9E9;
            --font: "Bricolage Grotesque", "Segoe UI", system-ui, sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; }
        body {
            font-family: var(--font);
            color: var(--ink);
            background:
                radial-gradient(900px 500px at 10% -10%, #BFD8F6 0%, transparent 60%),
                radial-gradient(800px 500px at 100% 110%, #CFE1F8 0%, transparent 55%),
                var(--wash);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        /* One entrance moment: the card rises into place */
        .card {
            width: 100%;
            max-width: 920px;
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            background: var(--paper);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 80px -30px rgba(11, 31, 58, .45);
            animation: rise .8s cubic-bezier(.2, .8, .2, 1) both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(28px) scale(.98); }
            to   { opacity: 1; transform: none; }
        }

        /* Left panel */
        .brand {
            position: relative;
            background: linear-gradient(160deg, var(--blue) 0%, var(--blue-deep) 100%);
            color: #fff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 520px;
            overflow: hidden;
        }
        .brand h1 {
            font-size: clamp(2.2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.02;
            letter-spacing: -.03em;
            position: relative;
            z-index: 2;
        }
        .brand p {
            margin-top: 14px;
            max-width: 28ch;
            line-height: 1.5;
            color: rgba(255, 255, 255, .82);
            position: relative;
            z-index: 2;
        }
        .star {
            position: absolute;
            top: 12%;
            left: 50%;
            width: 230px;
            height: 230px;
            transform: translateX(-50%);
            fill: rgba(255, 255, 255, .95);
            filter: drop-shadow(0 20px 40px rgba(11, 31, 58, .35));
            animation: spin 28s linear infinite;
        }
        @keyframes spin { to { transform: translateX(-50%) rotate(360deg); } }
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, .22);
            left: 50%;
            top: calc(12% + 115px);
            transform: translate(-50%, -50%);
        }
        .ring.r1 { width: 320px; height: 320px; }
        .ring.r2 { width: 440px; height: 440px; }
        .ring.r3 { width: 580px; height: 580px; }

        /* Right panel */
        .panel { padding: 48px 44px; }
        .panel h2 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }
        .panel .sub { color: var(--muted); margin-top: 6px; margin-bottom: 28px; }

        .field { margin-bottom: 22px; }
        .field label {
            display: block;
            font-weight: 500;
            font-size: .95rem;
            margin-bottom: 8px;
        }
        input[type="text"] {
            width: 100%;
            font: inherit;
            color: var(--ink);
            padding: 14px 16px;
            border: 1.5px solid var(--line);
            border-radius: 14px;
            background: var(--wash);
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        input[type="text"]::placeholder { color: #98A6BA; }
        input[type="text"]:focus {
            outline: none;
            background: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(65, 137, 221, .18);
        }

        /* Drop zone */
        .drop {
            position: relative;
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px;
            border: 2px dashed var(--line);
            border-radius: 18px;
            background: var(--wash);
            cursor: pointer;
            transition: border-color .2s, background .2s, transform .2s;
        }
        .drop:hover, .drop.over, .drop:focus-within {
            border-color: var(--blue);
            background: #E6F0FC;
        }
        .drop.over { transform: scale(1.015); }
        .drop input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .thumb {
            flex: none;
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #fff;
            border: 1.5px solid var(--line);
            display: grid;
            place-items: center;
            overflow: hidden;
        }
        .thumb svg { width: 30px; height: 30px; stroke: var(--blue); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .thumb img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .thumb.has img { display: block; animation: pop .35s cubic-bezier(.2, 1.4, .4, 1) both; }
        .thumb.has svg { display: none; }
        @keyframes pop { from { transform: scale(.6); opacity: 0; } to { transform: none; opacity: 1; } }
        .drop strong { display: block; font-weight: 700; }
        .drop span.hint { display: block; margin-top: 3px; color: var(--muted); font-size: .88rem; line-height: 1.4; }
        .drop span.hint.warn { color: var(--bad); }

        .btn {
            width: 100%;
            margin-top: 6px;
            font: inherit;
            font-weight: 700;
            font-size: 1.02rem;
            color: #fff;
            background: var(--navy);
            border: 0;
            padding: 16px 20px;
            border-radius: 14px;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
        }
        .btn:hover { background: var(--blue-deep); box-shadow: 0 12px 24px -12px rgba(29, 95, 176, .8); }
        .btn:active { transform: translateY(1px); }
        .btn:focus-visible, .drop:focus-within { outline: 3px solid rgba(65, 137, 221, .45); outline-offset: 2px; }

        /* Result */
        .result {
            margin-top: 30px;
            padding-top: 26px;
            border-top: 1.5px solid var(--line);
            animation: rise .5s cubic-bezier(.2, .8, .2, 1) both;
        }
        .result h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 14px; }
        .notice {
            padding: 12px 14px;
            border-radius: 12px;
            font-weight: 500;
            font-size: .95rem;
            margin-bottom: 10px;
        }
        .notice.ok { background: var(--ok-bg); color: var(--ok); }
        .notice.bad { background: var(--bad-bg); color: var(--bad); }
        .profile {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 14px 0;
        }
        .profile img {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px var(--blue);
        }
        .profile b { font-size: 1.15rem; font-weight: 700; display: block; }
        .profile small { color: var(--muted); }
        dl.info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px 18px;
            font-size: .9rem;
            margin-top: 14px;
        }
        dl.info dt { color: var(--muted); }
        dl.info dd { word-break: break-all; }

        @media (max-width: 760px) {
            .card { grid-template-columns: 1fr; }
            .brand { min-height: 300px; padding: 32px 28px; }
            .star { width: 150px; height: 150px; top: 8%; }
            .ring { display: none; }
            .panel { padding: 32px 26px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>
    <main class="card">

        <section class="brand">
            <div class="ring r1"></div><div class="ring r2"></div><div class="ring r3"></div>
            <svg class="star" viewBox="0 0 100 100" aria-hidden="true">
                <polygon points="50,4 61.8,36.2 96,36.9 68.8,57.6 78.8,90.4 50,70.8 21.2,90.4 31.2,57.6 4,36.9 38.2,36.2"/>
            </svg>
            <h1>Create your profile</h1>
            <p>Add your name and a photo to finish registering.</p>
        </section>

        <section class="panel">
            <h2>Register</h2>
            <p class="sub">It takes less than a minute.</p>

            <form method="post" enctype="multipart/form-data">
                <div class="field">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your fullname">
                </div>

                <div class="field">
                    <label for="profile">Profile photo</label>
                    <div class="drop" id="drop">
                        <div class="thumb" id="thumb">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m0 0L7 9m5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                            <img id="preview" alt="">
                        </div>
                        <div>
                            <strong id="dropTitle">Drop your photo here or browse</strong>
                            <span class="hint" id="dropHint">PNG, JPG, JPEG or GIF, up to 2 MB</span>
                        </div>
                        <input type="file" id="profile" name="profile" accept=".png,.jpg,.jpeg,.gif">
                    </div>
                </div>

                <input class="btn" type="submit" name="submit" value="Register">
            </form>

            <?php
            if (isset($_POST["submit"])) {
                $name = htmlspecialchars($_POST["name"]);
                $fileName = htmlspecialchars($_FILES["profile"]["name"]);

                echo '<div class="result">';
                echo "<h3>Registration result</h3>";

                $filename = explode (".", $_FILES["profile"]["name"]);
                $extension = end($filename);
                $extensions = array("png", "jpg", "jpeg", "gif");
                $extension = strtolower($extension);

                if ($_FILES["profile"]["size"] <= 2097152) {
                    if (in_array($extension, $extensions)) {
                        if (!file_exists("Images/" . $_POST["name"] . "." .  $extension)) {
                            if (move_uploaded_file($_FILES["profile"]["tmp_name"], "Images/" . $_POST["name"] . "." .  $extension)) {
                                echo '<div class="notice ok">The file has been uploaded successfully</div>';
                                echo '<div class="profile">';
                                echo '<img src="' . "Images/" . $name . "." .  $extension . '" alt="">';
                                echo '<div><b>' . $name . '</b><small>Registered</small></div>';
                                echo '</div>';
                            }
                            else {
                                echo '<div class="notice bad">The file has not been uploaded successfully</div>';
                            }
                        }
                        else {
                            echo '<div class="notice bad">This file Images/' . $name . "." . $extension . ' already exists</div>';
                        }
                    }
                    else {
                        echo '<div class="notice bad">The file extension (' . htmlspecialchars($extension) . ') is not allowed</div>';
                    }
                }
                else {
                    echo '<div class="notice bad">This file size ' . $_FILES["profile"]["size"] . ' is too big</div>';
                }

                echo '<dl class="info">';
                echo "<dt>Name</dt><dd>" . $fileName . "</dd>";
                echo "<dt>Size</dt><dd>" . $_FILES["profile"]["size"] . " bytes</dd>";
                echo "<dt>Temp</dt><dd>" . htmlspecialchars($_FILES["profile"]["tmp_name"]) . "</dd>";
                echo "<dt>Type</dt><dd>" . htmlspecialchars($_FILES["profile"]["type"]) . "</dd>";
                echo '</dl>';
                echo '</div>';
            }
            ?>
        </section>
    </main>

    <script>
        const input = document.getElementById('profile');
        const drop = document.getElementById('drop');
        const thumb = document.getElementById('thumb');
        const preview = document.getElementById('preview');
        const title = document.getElementById('dropTitle');
        const hint = document.getElementById('dropHint');
        const MAX = 2097152;

        function show(file) {
            if (!file) return;
            title.textContent = file.name;
            if (file.size > MAX) {
                hint.textContent = 'This file is over 2 MB. Choose a smaller one.';
                hint.classList.add('warn');
            } else {
                hint.textContent = (file.size / 1024).toFixed(0) + ' KB. Click to change.';
                hint.classList.remove('warn');
            }
            if (file.type.startsWith('image/')) {
                preview.src = URL.createObjectURL(file);
                thumb.classList.add('has');
            }
        }
        input.addEventListener('change', () => show(input.files[0]));
        ['dragenter', 'dragover'].forEach(e => input.addEventListener(e, () => drop.classList.add('over')));
        ['dragleave', 'drop'].forEach(e => input.addEventListener(e, () => drop.classList.remove('over')));
    </script>
</body>
</html>