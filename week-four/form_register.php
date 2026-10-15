
<?php
// Initialize variables
$errors = [];
$success = false;

$fullname = "";
$email = "";
$pob = "";
$gender = "";
$faculty = "";
$languages = [];
$comment = "";

// Escape output for security
function clean($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Allowed options
$allowedFaculties = [
    "computer",
    "medicine",
    "engineering",
    "ecom"
];

$allowedGenders = ["male", "female"];

$allowedLanguages = [
    "Somali",
    "English",
    "Arabic",
    "Chinese"
];

// Process form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pob = trim($_POST["pob"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $faculty = $_POST["faculty"] ?? "";
    $comment = trim($_POST["comment"] ?? "");

    // Fullname validation
    if ($fullname === "") {
        $errors["fullname"] = "Fullname is required.";
    } elseif (strlen($fullname) > 100) {
        $errors["fullname"] = "Fullname must not exceed 100 characters.";
    }

    // Email validation
    if ($email === "") {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Please enter a valid email address.";
    }

    // Place of birth validation
    if ($pob !== "" && strlen($pob) > 100) {
        $errors["pob"] = "Place of birth must not exceed 100 characters.";
    }

    // Gender validation
    if (!in_array($gender, $allowedGenders, true)) {
        $errors["gender"] = "Please select your gender.";
    }

    // Faculty validation
    if (!in_array($faculty, $allowedFaculties, true)) {
        $errors["faculty"] = "Please select a valid faculty.";
    }

    // Languages validation
    $submittedLanguages = $_POST["languages"] ?? [];

    if (!is_array($submittedLanguages)) {
        $submittedLanguages = [];
    }

    $languages = array_values(array_unique(array_filter(
        $submittedLanguages,
        fn($language) => is_string($language)
            && in_array($language, $allowedLanguages, true)
    )));

    if (empty($languages)) {
        $errors["languages"] = "Please select at least one language.";
    }

    // Comment validation
    if (strlen($comment) > 1000) {
        $errors["comment"] = "Comment must not exceed 1000 characters.";
    }

    // Check all validations
    if (empty($errors)) {
        $success = true;
    }
}

// Faculty display names
$facultyNames = [
    "computer" => "Computer and IT",
    "medicine" => "Medicine and Health Science",
    "engineering" => "Engineering",
    "ecom" => "Business"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: linear-gradient(135deg, #eaf1ff, #f8f9fa);
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .registration-card {
            max-width: 750px;
            margin: 50px auto;
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.10);
        }

        .card-header-custom {
            background: #0d6efd;
            color: white;
            padding: 28px;
            text-align: center;
        }

        .card-header-custom h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-header-custom p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        .form-label {
            font-weight: 600;
            color: #343a40;
        }

        .form-control,
        .form-select {
            padding: 11px 13px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 22px;
        }

        .btn {
            padding: 11px 24px;
            border-radius: 8px;
            font-weight: 600;
        }

        .result-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 18px;
            overflow-wrap: anywhere;
        }

        .result-item {
            margin-bottom: 12px;
        }

        .result-item strong {
            color: #343a40;
        }

        @media (max-width: 576px) {
            .registration-card {
                margin: 20px 12px;
            }

            .card-header-custom h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card registration-card">

        <div class="card-header-custom">
            <h1>Student Registration</h1>
            <p>Fill in your information to register</p>
        </div>

        <div class="card-body p-4 p-md-5">

            <?php if ($success): ?>

                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Registration Successful!</h4>
                    <p class="mb-0">
                        Your information has passed validation.
                    </p>
                </div>

                <h4 class="form-section-title">Student Information</h4>

                <div class="result-box">

                    <div class="result-item">
                        <strong>Fullname:</strong>
                        <?= clean($fullname) ?>
                    </div>

                    <div class="result-item">
                        <strong>Email:</strong>
                        <?= clean($email) ?>
                    </div>

                    <div class="result-item">
                        <strong>Place of Birth:</strong>
                        <?= $pob !== "" ? clean($pob) : "Not provided" ?>
                    </div>

                    <div class="result-item">
                        <strong>Gender:</strong>
                        <?= clean(ucfirst($gender)) ?>
                    </div>

                    <div class="result-item">
                        <strong>Faculty:</strong>
                        <?= clean($facultyNames[$faculty]) ?>
                    </div>

                    <div class="result-item">
                        <strong>Languages:</strong>
                        <?= clean(implode(", ", $languages)) ?>
                    </div>

                    <div class="result-item mb-0">
                        <strong>Comment:</strong>
                        <p class="mb-0 mt-1">
                            <?= $comment !== "" ? nl2br(clean($comment)) : "No comment" ?>
                        </p>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <a href="<?= clean($_SERVER["PHP_SELF"]) ?>"
                       class="btn btn-primary">
                        Register Another Student
                    </a>
                </div>

            <?php else: ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <h5 class="alert-heading">Please fix these errors:</h5>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= clean($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>

                    <h4 class="form-section-title">
                        Personal Information
                    </h4>

                    <!-- Fullname -->
                    <div class="mb-3">
                        <label for="fullname" class="form-label">
                            Fullname <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>"
                            id="fullname"
                            name="fullname"
                            placeholder="Enter your full name"
                            value="<?= clean($fullname) ?>"
                            maxlength="100"
                            required
                        >

                        <?php if (isset($errors["fullname"])): ?>
                            <div class="invalid-feedback">
                                <?= clean($errors["fullname"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>

                        <input
                            type="email"
                            class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                            id="email"
                            name="email"
                            placeholder="example@email.com"
                            value="<?= clean($email) ?>"
                            required
                        >

                        <?php if (isset($errors["email"])): ?>
                            <div class="invalid-feedback">
                                <?= clean($errors["email"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Place of Birth -->
                    <div class="mb-3">
                        <label for="pob" class="form-label">
                            Place of Birth
                        </label>

                        <input
                            type="text"
                            class="form-control <?= isset($errors['pob']) ? 'is-invalid' : '' ?>"
                            id="pob"
                            name="pob"
                            placeholder="Enter your place of birth"
                            value="<?= clean($pob) ?>"
                            maxlength="100"
                        >

                        <?php if (isset($errors["pob"])): ?>
                            <div class="invalid-feedback">
                                <?= clean($errors["pob"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label d-block">
                            Gender <span class="text-danger">*</span>
                        </label>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="gender"
                                id="male"
                                value="male"
                                <?= $gender === "male" ? "checked" : "" ?>
                            >

                            <label class="form-check-label" for="male">
                                Male
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="gender"
                                id="female"
                                value="female"
                                <?= $gender === "female" ? "checked" : "" ?>
                            >

                            <label class="form-check-label" for="female">
                                Female
                            </label>
                        </div>

                        <?php if (isset($errors["gender"])): ?>
                            <div class="text-danger small mt-1">
                                <?= clean($errors["gender"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Faculty -->
                    <div class="mb-3">
                        <label for="faculty" class="form-label">
                            Faculty <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select <?= isset($errors['faculty']) ? 'is-invalid' : '' ?>"
                            id="faculty"
                            name="faculty"
                            required
                        >
                            <option value="">Select your faculty</option>

                            <option value="computer"
                                <?= $faculty === "computer" ? "selected" : "" ?>>
                                Computer and IT
                            </option>

                            <option value="medicine"
                                <?= $faculty === "medicine" ? "selected" : "" ?>>
                                Medicine and Health Science
                            </option>

                            <option value="engineering"
                                <?= $faculty === "engineering" ? "selected" : "" ?>>
                                Engineering
                            </option>

                            <option value="ecom"
                                <?= $faculty === "ecom" ? "selected" : "" ?>>
                                Business
                            </option>
                        </select>

                        <?php if (isset($errors["faculty"])): ?>
                            <div class="invalid-feedback">
                                <?= clean($errors["faculty"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Languages -->
                    <div class="mb-4">
                        <label class="form-label d-block">
                            Languages <span class="text-danger">*</span>
                        </label>

                        <?php foreach ($allowedLanguages as $language): ?>
                            <div class="form-check form-check-inline mb-2">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="languages[]"
                                    id="lang_<?= strtolower($language) ?>"
                                    value="<?= clean($language) ?>"
                                    <?= in_array($language, $languages, true) ? "checked" : "" ?>
                                >

                                <label
                                    class="form-check-label"
                                    for="lang_<?= strtolower($language) ?>"
                                >
                                    <?= clean($language) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>

                        <?php if (isset($errors["languages"])): ?>
                            <div class="text-danger small mt-1">
                                <?= clean($errors["languages"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Comment -->
                    <div class="mb-4">
                        <label for="comment" class="form-label">
                            Comment
                        </label>

                        <textarea
                            class="form-control <?= isset($errors['comment']) ? 'is-invalid' : '' ?>"
                            id="comment"
                            name="comment"
                            rows="4"
                            maxlength="1000"
                            placeholder="Write your comment here..."
                        ><?= clean($comment) ?></textarea>

                        <?php if (isset($errors["comment"])): ?>
                            <div class="invalid-feedback">
                                <?= clean($errors["comment"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            Register Student
                        </button>

                        <button type="reset" class="btn btn-outline-secondary">
                            Clear Form
                        </button>
                    </div>

                    <p class="text-muted small mt-3 mb-0">
                        Fields marked with * are required.
                    </p>

                </form>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
