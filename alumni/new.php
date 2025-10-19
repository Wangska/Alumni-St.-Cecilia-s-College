<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_admin();
$pdo = get_pdo();
$courses = $pdo->query('SELECT id, course FROM courses ORDER BY course')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    
    // Handle avatar upload
    $avatarFile = null;
    if (!empty($_FILES['avatar']['name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $safeName = 'avatar_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
        $destDir = __DIR__ . '/../uploads';
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }
        $dest = $destDir . '/' . $safeName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
            $avatarFile = $safeName;
        }
    }
    
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $alumniName = $firstname . ' ' . $lastname;
    
    $stmt = $pdo->prepare('INSERT INTO alumnus_bio (firstname, middlename, lastname, gender, batch, course_id, email, contact, address, connected_to, avatar, status, date_created) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())');
    $stmt->execute([
        $firstname,
        trim($_POST['middlename'] ?? ''),
        $lastname,
        trim($_POST['gender'] ?? ''),
        (int)($_POST['batch'] ?? 2000),
        (int)($_POST['course_id'] ?? 0),
        trim($_POST['email'] ?? ''),
        trim($_POST['contact'] ?? ''),
        trim($_POST['address'] ?? ''),
        trim($_POST['connected_to'] ?? ''),
        $avatarFile,
        (int)($_POST['status'] ?? 1)
    ]);
    
    // Log the creation
    ActivityLogger::logCreate('Alumni', $alumniName);
    
    $_SESSION['success'] = 'Alumni added successfully!';
    header('Location: /scratch/admin.php?page=alumni');
    exit;
}

$currentPage = 'alumni';
$pageTitle = 'Add New Alumni';
$title = 'Add Alumni';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - SCC Alumni</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 40px;
            margin: 0 auto;
            max-width: 1000px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .form-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 24px 30px;
            border-radius: 16px;
            margin: -40px -40px 30px -40px;
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);
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
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1), 0 4px 12px rgba(220, 53, 69, 0.15);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);
        }
        
        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
            background: linear-gradient(135deg, #c82333, #991b1b);
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
            border-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.15);
        }
        
        .avatar-preview {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(220, 53, 69, 0.25);
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-preview i {
            font-size: 64px;
            color: #dee2e6;
        }
        
        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3142;
            margin-top: 35px;
            margin-bottom: 20px;
            padding: 12px 20px;
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
            border-left: 4px solid #dc3545;
            border-radius: 8px;
        }
        
        .section-title i {
            color: #dc3545;
        }
        
        .required::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h4><i class="fas fa-user-plus me-2"></i>Add New Alumni</h4>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                
                <!-- Avatar Upload -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="avatar-upload">
                            <div class="avatar-preview" id="avatarPreview">
                                <i class="fas fa-user"></i>
                            </div>
                            <label for="avatarInput" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-camera me-2"></i>Choose Avatar
                            </label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                            <p class="text-muted small mt-2 mb-0">Upload a profile picture (Optional)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <h5 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">First Name</label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middlename">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Last Name</label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">Gender</label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Batch</label>
                        <select class="form-select" name="batch" required>
                            <option value="">-- Select Batch Year --</option>
                            <?php for ($y=(int)date('Y'); $y>=1980; $y--): ?>
                                <option value="<?= $y ?>" <?= ($y === (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Course</label>
                        <select class="form-select" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= (int)$c['id'] ?>"><?= e($c['course']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <h5 class="section-title"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="contact" placeholder="e.g., +63 912 345 6789">
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Enter complete address"></textarea>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Connected To</label>
                        <textarea class="form-control" name="connected_to" rows="2" placeholder="e.g., Alumni associations, organizations"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="0">Pending Verification</option>
                            <option value="1" selected>Verified</option>
                        </select>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/scratch/admin.php?page=alumni" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>Save Alumni
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Avatar preview
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').innerHTML = 
                        '<img src="' + e.target.result + '" alt="Avatar Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
