<?php
// ---------------------------------------------------
// Student Registration System - Upgraded Version
// Features: Photo Upload UI, More Fields, Better Validation
// ---------------------------------------------------
include("registrraion.php");
$errors = [];
$submitted = false;

// Max photo size: 20 MB
define('MAX_PHOTO_BYTES', 20 * 1024 * 1024);

// Store old values so the form can be re-populated after a failed submit
$name    = '';
$email   = '';
$gender  = '';
$course  = '';
$address = '';

$photoChosen  = false;
$photoWebPath = null; // path to the saved photo, used for the success preview

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $gender  = trim($_POST['gender'] ?? '');
    $course  = trim($_POST['course'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // --- Validation ---

    // Name: required, letters/spaces/hyphens/apostrophes only (no numbers)
    if ($name === '') {
        $errors[] = "Full name is required.";
    } elseif (preg_match('/[0-9]/', $name)) {
        $errors[] = "Full name cannot contain numbers.";
    } elseif (!preg_match("/^[a-zA-Z\s\-']+$/", $name)) {
        $errors[] = "Full name can only contain letters, spaces, hyphens, and apostrophes.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Full name must be at least 2 characters long.";
    }

    // Email: required, valid format
    if ($email === '') {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Gender: required
    if ($gender === '') {
        $errors[] = "Please select a gender.";
    } elseif (!in_array($gender, ['Male', 'Female', 'Other'])) {
        $errors[] = "Invalid gender selection.";
    }

    // Course: required
    if ($course === '') {
        $errors[] = "Please select a course.";
    }

    // Address: required, minimum length
    if ($address === '') {
        $errors[] = "Address is required.";
    } elseif (strlen($address) < 10) {
        $errors[] = "Address must be at least 10 characters long.";
    }

    // --- Photo validation (server-side, max 20 MB) ---
    $photoChosen = isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE;

    if ($photoChosen) {
        $photoError = $_FILES['photo']['error'];

        if ($photoError === UPLOAD_ERR_INI_SIZE || $photoError === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = "Photo is too large. Maximum allowed size is 20 MB.";
        } elseif ($photoError !== UPLOAD_ERR_OK) {
            $errors[] = "There was a problem uploading the photo. Please try again.";
        } elseif ($_FILES['photo']['size'] > MAX_PHOTO_BYTES) {
            $errors[] = "Photo is too large. Maximum allowed size is 20 MB.";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $_FILES['photo']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedTypes)) {
                $errors[] = "Photo must be a JPG, PNG, GIF, or WEBP image.";
            } else {
                // Save the uploaded photo
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $filename = 'student_' . uniqid() . '.' . $ext;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                    $photoWebPath = 'uploads/' . $filename;
                } else {
                    $errors[] = "Could not save the uploaded photo. Please try again.";
                }
            }
        }
    }

    if (empty($errors)) {
        $submitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Registration System</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        min-height: 100vh;
        padding: 40px 0;
        font-family: 'Segoe UI', sans-serif;
    }
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }
    .card-header {
        background: #16324f;
        color: #fff;
        border-radius: 16px 16px 0 0 !important;
        padding: 24px;
    }
    .card-header h2 {
        margin: 0;
        font-weight: 600;
    }
    .form-label {
        font-weight: 600;
        color: #16324f;
    }
    .error-box {
        background: #fdecea;
        border: 1px solid #f5c6cb;
        border-left: 5px solid #dc3545;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 24px;
        color: #842029;
    }
    .error-box strong {
        display: block;
        margin-bottom: 8px;
        font-size: 1.05rem;
    }
    .success-box {
        background: #eaf7ec;
        border: 1px solid #c3e6cb;
        border-left: 5px solid #28a745;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 24px;
        color: #1e4620;
    }
    .photo-upload-wrapper {
        border: 2px dashed #16324f33;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: 0.2s;
    }
    .photo-upload-wrapper:hover {
        background: #eef3f7;
        border-color: #16324f;
    }
    #photoPreview {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #16324f;
        display: none;
        margin: 0 auto 12px auto;
    }
    #photoPlaceholder {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #16324f55;
        margin: 0 auto 12px auto;
        opacity: 0.85;
    }
    .photo-hint {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 8px;
    }
    .btn-primary {
        background: #16324f;
        border: none;
        padding: 10px 28px;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-primary:hover {
        background: #0f2436;
    }
    .result-photo {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #28a745;
        margin-bottom: 16px;
    }
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card">
                <div class="card-header text-center">
                    <h2>🎓 Student Registration</h2>
                    <p class="mb-0 mt-1" style="opacity:0.85;">Please fill in your details below</p>
                </div>
                <div class="card-body p-4">

                    <?php if (!empty($errors)): ?>
                        <div class="error-box">
                            <strong>⚠ Please fix the following errors:</strong>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($submitted): ?>
                        <div class="success-box text-center">
                            <?php if ($photoWebPath): ?>
                                <img src="<?= htmlspecialchars($photoWebPath) ?>" class="result-photo" alt="Student photo">
                            <?php else: ?>
                                <img src="assets/default-avatar.png" class="result-photo" alt="Default avatar">
                            <?php endif; ?>
                            <strong>✅ Registration successful!</strong>
                            <p class="mb-0 mt-2">
                                Welcome, <strong><?= htmlspecialchars($name) ?></strong>! You have been
                                registered for <strong><?= htmlspecialchars($course) ?></strong>.
                            </p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" novalidate>
                        <!-- Server-side hint for browsers that respect it -->
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_PHOTO_BYTES ?>">

                        <!-- Photo Upload -->
                        <div class="mb-4">
                            <label class="form-label">Profile Photo <span class="text-muted fw-normal">(optional, max 20 MB)</span></label>
                            <div class="photo-upload-wrapper">
                                <img id="photoPreview" alt="Preview">
                                <img id="photoPlaceholder" src="assets/default-avatar.png" alt="Default avatar">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('photoInput').click()">
                                        📷 Choose Photo
                                    </button>
                                    <button type="button" id="removePhotoBtn" class="btn btn-outline-secondary btn-sm ms-2" style="display:none;" onclick="removePhoto()">
                                        ✕ Remove
                                    </button>
                                </div>
                                <div class="photo-hint">Any JPG, PNG, GIF or WEBP file &middot; up to 20 MB</div>
                                <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none" onchange="previewPhoto(event)">
                            </div>
                            <div id="photoSizeWarning" class="text-danger mt-2" style="display:none;">
                                ⚠ That file is larger than 20 MB. Please choose a smaller image.
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" placeholder="e.g. Jane Doe">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. jane.doe@example.com">
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div>
                                <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="<?= $g ?>" id="gender<?= $g ?>" <?= $gender === $g ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="gender<?= $g ?>"><?= $g ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Course -->
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-select">
                                <option value="">-- Select a course --</option>
                                <?php foreach (['Computer Science', 'Business Administration', 'Electrical Engineering', 'Nursing', 'Psychology', 'Mathematics'] as $c): ?>
                                    <option value="<?= $c ?>" <?= $course === $c ? 'selected' : '' ?>><?= $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Street, city, state, ZIP"><?= htmlspecialchars($address) ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const MAX_PHOTO_BYTES = <?= MAX_PHOTO_BYTES ?>;

    function previewPhoto(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('photoPreview');
        const placeholder = document.getElementById('photoPlaceholder');
        const warning = document.getElementById('photoSizeWarning');
        const removeBtn = document.getElementById('removePhotoBtn');

        if (!file) return;

        if (file.size > MAX_PHOTO_BYTES) {
            warning.style.display = 'block';
            event.target.value = '';
            preview.style.display = 'none';
            placeholder.style.display = 'inline-block';
            removeBtn.style.display = 'none';
            return;
        }

        warning.style.display = 'none';
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'inline-block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }

    function removePhoto() {
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('photoPreview');
        const placeholder = document.getElementById('photoPlaceholder');
        const removeBtn = document.getElementById('removePhotoBtn');
        const warning = document.getElementById('photoSizeWarning');

        input.value = '';
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'inline-block';
        removeBtn.style.display = 'none';
        warning.style.display = 'none';
    }
</script>
</body>
</html>
