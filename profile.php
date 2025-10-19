<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();
$error = '';
$success = '';

// Get alumni profile information
$alumnusId = $user['alumnus_id'] ?? 0;

// If alumnus_id is 0, try to get it from the database
if ($alumnusId == 0) {
    try {
        $stmt = $pdo->prepare('SELECT alumnus_id FROM users WHERE id = ?');
        $stmt->execute([$user['id']]);
        $userData = $stmt->fetch();
        if ($userData && $userData['alumnus_id']) {
            $alumnusId = (int)$userData['alumnus_id'];
            // Update session
            $_SESSION['user']['alumnus_id'] = $alumnusId;
            $user['alumnus_id'] = $alumnusId;
        }
    } catch (Exception $e) {
        // Ignore error, continue with 0
    }
}

$alumni = null;

if ($alumnusId > 0) {
    try {
        $stmt = $pdo->prepare('SELECT ab.*, c.course FROM alumnus_bio ab LEFT JOIN courses c ON ab.course_id = c.id WHERE ab.id = ?');
        $stmt->execute([$alumnusId]);
        $alumni = $stmt->fetch();
        
        if (!$alumni) {
            $error = "No alumni record found for ID: $alumnusId. Please contact administrator.";
        }
    } catch (Exception $e) {
        $error = "Error loading profile: " . $e->getMessage();
    }
} else {
    $error = "No alumni profile found. Please contact administrator.";
}

// Get all courses for dropdown
$stmt = $pdo->prepare('SELECT * FROM courses ORDER BY course ASC');
$stmt->execute();
$courses = $stmt->fetchAll();

// Handle form submission
if ($_POST) {
    require_csrf();
    
    $firstName = trim($_POST['firstname'] ?? '');
    $middleName = trim($_POST['middlename'] ?? '');
    $lastName = trim($_POST['lastname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $batch = trim($_POST['batch'] ?? '');
    $courseId = (int)($_POST['course_id'] ?? 0);
    $bio = trim($_POST['bio'] ?? '');
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = "First name, last name, and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Handle avatar upload
            $avatarFile = $alumni['avatar'] ?? ''; // Keep existing avatar by default
            if (!empty($_FILES['avatar']['name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $safeName = 'avatar_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    // Delete old avatar if exists
                    if ($avatarFile && file_exists($destDir . '/' . $avatarFile)) {
                        @unlink($destDir . '/' . $avatarFile);
                    }
                    $avatarFile = $safeName;
                }
            }
            
            // Update alumni record
            $stmt = $pdo->prepare('UPDATE alumnus_bio SET firstname=?, middlename=?, lastname=?, gender=?, batch=?, course_id=?, email=?, contact=?, address=?, avatar=? WHERE id=?');
            $stmt->execute([
                $firstName,
                $middleName,
                $lastName,
                $gender,
                (int)$batch,
                $courseId,
                $email,
                $phone,
                $address,
                $avatarFile,
                $alumnusId
            ]);
            
            // Update user name in users table
            $fullName = $firstName . ($middleName ? ' ' . $middleName : '') . ' ' . $lastName;
            $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
            $stmt->execute([$fullName, $user['id']]);
            
            $pdo->commit();
            
            // Refresh alumni data
            $stmt = $pdo->prepare('SELECT ab.*, c.course FROM alumnus_bio ab LEFT JOIN courses c ON ab.course_id = c.id WHERE ab.id = ?');
            $stmt->execute([$alumnusId]);
            $alumni = $stmt->fetch();
            
            $success = "Profile updated successfully!";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - St. Cecilia's College Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.1));
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));
        }
        
        .navbar-brand img {
            height: 55px;
            width: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        
        .navbar-brand-text {
            background: linear-gradient(45deg, #ffffff, #f0f9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 600;
            padding: 0.75rem 1.25rem !important;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .nav-link:hover {
            transform: translateY(-3px);
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        
        .profile-btn {
            position: relative;
            overflow: hidden;
        }
        
        .profile-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .profile-btn:hover::before {
            left: 100%;
        }
        
        .profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }
        
        .dropdown-menu {
            animation: slideDown 0.3s ease-out;
        }
        
        .dropdown-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 2px 0;
            padding: 0.6rem 1rem;
            font-weight: 500;
            color: #374151;
        }
        
        .dropdown-item:hover {
            background: #f8fafc;
            color: #1f2937;
        }
        
        .dropdown-item i {
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }
        
        .dropdown-item.text-danger:hover {
            color: #dc2626 !important;
        }
        
        .navbar-toggler {
            border: 2px solid rgba(255,255,255,0.2) !important;
            border-radius: 12px !important;
            padding: 0.5rem !important;
            transition: all 0.3s ease !important;
            background: rgba(255,255,255,0.1) !important;
            backdrop-filter: blur(10px) !important;
        }
        
        .navbar-toggler:hover {
            background: rgba(255,255,255,0.2) !important;
            border-color: rgba(255,255,255,0.4) !important;
            transform: scale(1.05) !important;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25) !important;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 1000px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .form-header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 24px 30px;
            border-radius: 16px;
            margin: -40px -40px 30px -40px;
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }
        
        .form-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 24px;
        }
        
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.1), 0 4px 12px rgba(220, 38, 38, 0.15);
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: none;
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
            background: linear-gradient(135deg, #b91c1c, #991b1b);
        }
        
        .btn-secondary {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 2px solid #6c757d;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .avatar-upload {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            border: 3px dashed #dee2e6;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .avatar-upload:hover {
            border-color: #dc2626;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.15);
        }
        
        .avatar-preview {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.25);
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-preview i {
            font-size: 48px;
            color: #dc2626;
        }
        
        .section-title {
            color: #dc2626;
            font-weight: 700;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f8f9fa;
            font-size: 18px;
        }
        
        .required::after {
            content: " *";
            color: #dc2626;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/scratch/dashboard.php">
                <img src="/scratch/images/scc.png" alt="SCC Logo" class="me-3">
                <div class="navbar-brand-text">
                    <div style="font-size: 1.1rem; font-weight: 700; line-height: 1.2; color: white;">ST. CECILIA'S COLLEGE</div>
                    <div style="font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px; margin-top: 2px; color: rgba(255,255,255,0.9);">ALUMNI PORTAL</div>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 0.5rem; transition: all 0.3s ease;">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/dashboard.php#news">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/dashboard.php#jobs">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/dashboard.php#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/dashboard.php#success-stories">Success Stories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/forum/index.php">Forum</a>
                    </li>
                </ul>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <button class="btn profile-btn" type="button" data-bs-toggle="dropdown" style="background: linear-gradient(135deg, #dc2626, #b91c1c); border: none; border-radius: 25px; padding: 8px 16px; color: white; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); transition: all 0.3s ease;">
                        <?php if (!empty($alumni['avatar'])): ?>
                            <img src="/scratch/uploads/<?= htmlspecialchars($alumni['avatar']) ?>" alt="Profile" class="profile-avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                        <?php else: ?>
                            <div class="profile-avatar bg-white d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-user" style="font-size: 14px;"></i>
                            </div>
                        <?php endif; ?>
                        <span style="font-size: 14px; margin-left: 8px;"><?= htmlspecialchars($user['name']) ?></span>
                        <i class="fas fa-chevron-down ms-2" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); padding: 8px; min-width: 280px; background: white; backdrop-filter: blur(10px);">
                        <li class="px-3 py-3 border-bottom" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 12px; margin-bottom: 8px;">
                            <div class="d-flex align-items-center">
                                <?php if (!empty($alumni['avatar'])): ?>
                                    <img src="/scratch/uploads/<?= htmlspecialchars($alumni['avatar']) ?>" alt="Profile" class="profile-avatar me-3" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 3px solid #dc2626;">
                                <?php else: ?>
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 48px; height: 48px; border: 3px solid #dc2626;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 16px;"><?= htmlspecialchars($user['name']) ?></div>
                                    <small class="text-muted" style="font-size: 13px;"><?= $alumni ? 'Verified Alumni' : 'User Account' ?></small>
                                </div>
                            </div>
                        </li>
                        <li><a class="dropdown-item" href="/scratch/dashboard.php" style="border-radius: 10px; margin: 2px 0; padding: 12px 16px; transition: all 0.3s ease; color: #374151; font-weight: 500;"><i class="fas fa-tachometer-alt me-3" style="color: #3b82f6; width: 20px;"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="/scratch/profile.php" style="border-radius: 10px; margin: 2px 0; padding: 12px 16px; transition: all 0.3s ease; color: #374151; font-weight: 500;"><i class="fas fa-user me-3" style="color: #10b981; width: 20px;"></i>Profile</a></li>
                        <li><hr class="dropdown-divider" style="margin: 8px 0; border-color: #e5e7eb;"></li>
                        <li><a class="dropdown-item text-danger" href="/scratch/logout.php" style="border-radius: 10px; margin: 2px 0; padding: 12px 16px; transition: all 0.3s ease; font-weight: 600;"><i class="fas fa-sign-out-alt me-3" style="color: #dc2626; width: 20px;"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h4><i class="fas fa-user-edit me-2"></i>My Profile</h4>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <!-- Debug Information -->
            <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
                <div class="alert alert-warning" role="alert">
                    <h5><i class="fas fa-bug me-2"></i>Debug Information</h5>
                    <p><strong>User ID:</strong> <?= $user['id'] ?></p>
                    <p><strong>Username:</strong> <?= $user['username'] ?></p>
                    <p><strong>Alumnus ID:</strong> <?= $alumnusId ?></p>
                    <p><strong>Alumni Data:</strong></p>
                    <pre><?= print_r($alumni, true) ?></pre>
                    <p><strong>User Data:</strong></p>
                    <pre><?= print_r($user, true) ?></pre>
                </div>
            <?php endif; ?>

            <?php if ($alumni): ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <!-- Avatar Upload -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="avatar-upload">
                                <div class="avatar-preview" id="avatarPreview">
                                    <?php if (!empty($alumni['avatar'])): ?>
                                        <img src="/scratch/uploads/<?= e($alumni['avatar']) ?>" alt="Avatar">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <label for="avatarInput" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-camera me-2"></i>Change Photo
                                </label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                                <p class="text-muted small mt-2 mb-0">Upload a new profile picture (Optional)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information -->
                    <h5 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label required">First Name</label>
                            <input type="text" class="form-control" name="firstname" value="<?= e($alumni['firstname']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middlename" value="<?= e($alumni['middlename']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Last Name</label>
                            <input type="text" class="form-control" name="lastname" value="<?= e($alumni['lastname']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label required">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?= $alumni['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $alumni['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Batch</label>
                            <select class="form-select" name="batch" required>
                                <option value="">-- Select Batch Year --</option>
                                <?php for ($y=(int)date('Y'); $y>=1980; $y--): ?>
                                    <option value="<?= $y ?>" <?= ((int)$alumni['batch'] === $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Course</label>
                            <select class="form-select" name="course_id" required>
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id'] === (int)$alumni['course_id']) ? 'selected' : '' ?>>
                                        <?= e($c['course']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <h5 class="section-title"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= e($alumni['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact" value="<?= e($alumni['contact']) ?>" placeholder="e.g., +63 912 345 6789">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3" placeholder="Enter complete address"><?= e($alumni['address']) ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="4" placeholder="Tell us about yourself..."><?= e($alumni['bio'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="/scratch/dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Profile Not Found:</strong> Unable to load your alumni profile. Please contact the administrator or try refreshing the page.
                    <br><br>
                    <strong>Debug Info:</strong>
                    <ul>
                        <li>User ID: <?= $user['id'] ?></li>
                        <li>Username: <?= $user['username'] ?></li>
                        <li>Alumnus ID: <?= $alumnusId ?></li>
                        <li>Alumni Data: <?= $alumni ? 'Found' : 'Not Found' ?></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Avatar preview functionality
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>