<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../inc/auth.php';

// Require Alumni Officer authentication
require_alumni_officer();

// Database connection helper
if (!function_exists('get_pdo')) {
    function get_pdo(): PDO {
        static $pdo = null;
        if ($pdo instanceof PDO) {
            return $pdo;
        }
        $dsn = 'mysql:host=127.0.0.1;dbname=sccalumni_db;charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, 'root', '', $options);
        return $pdo;
    }
}

$pdo = get_pdo();
$id = (int)($_GET['id'] ?? 0);
$row = $pdo->prepare('SELECT * FROM announcements WHERE id=?');
$row->execute([$id]);
$row = $row->fetch();
if (!$row) { http_response_code(404); exit('Not found'); }

// Check if title column exists
$hasTitle = false;
try {
    $check = $pdo->query("SHOW COLUMNS FROM announcements LIKE 'title'")->fetch();
    $hasTitle = !empty($check);
} catch (Exception $e) {
    $hasTitle = false;
}

// Check if image column exists
$hasImage = false;
try {
    $check = $pdo->query("SHOW COLUMNS FROM announcements LIKE 'image'")->fetch();
    $hasImage = !empty($check);
} catch (Exception $e) {
    $hasImage = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    
    $imagePath = $hasImage ? ($row['image'] ?? null) : null; // Keep existing image
    
    // Handle image upload
    if ($hasImage && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowedExts)) {
            // Delete old image if exists
            if ($imagePath && file_exists(__DIR__ . '/../uploads/' . $imagePath)) {
                @unlink(__DIR__ . '/../uploads/' . $imagePath);
            }
            
            $uniqueName = 'announcement_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $uniqueName;
            
            if (move_uploaded_file($tmpName, $dest)) {
                $imagePath = $uniqueName;
            }
        }
    }
    
    try {
        if ($hasTitle && $hasImage) {
            $announcementTitle = trim($_POST['title'] ?? '');
            $stmt = $pdo->prepare('UPDATE announcements SET title=?, image=?, content=? WHERE id=?');
            $stmt->execute([
                $announcementTitle,
                $imagePath,
                trim($_POST['content'] ?? ''),
                $id
            ]);
        } elseif ($hasTitle) {
            $announcementTitle = trim($_POST['title'] ?? '');
            $stmt = $pdo->prepare('UPDATE announcements SET title=?, content=? WHERE id=?');
            $stmt->execute([
                $announcementTitle,
                trim($_POST['content'] ?? ''),
                $id
            ]);
        } elseif ($hasImage) {
            $stmt = $pdo->prepare('UPDATE announcements SET image=?, content=? WHERE id=?');
            $stmt->execute([
                $imagePath,
                trim($_POST['content'] ?? ''),
                $id
            ]);
        } else {
            $stmt = $pdo->prepare('UPDATE announcements SET content=? WHERE id=?');
            $stmt->execute([
                trim($_POST['content'] ?? ''),
                $id
            ]);
        }
        
        $_SESSION['success'] = 'Announcement updated successfully!';
        header('Location: /scratch/alumni-officer.php?page=announcements');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to update announcement: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement - Alumni Officer</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 40px;
            margin: 0 auto;
            max-width: 900px;
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
        
        .form-control, textarea {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
        }
        
        .form-control:focus, textarea:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.1), 0 4px 12px rgba(220, 38, 38, 0.15);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: none;
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }
        
        .btn-danger:hover {
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
        
        .required::after {
            content: " *";
            color: #dc2626;
        }
        
        .announcement-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(220, 38, 38, 0.05));
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            border: 3px solid rgba(220, 38, 38, 0.2);
        }
        
        textarea {
            min-height: 200px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c00;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h4><i class="fas fa-edit me-2"></i>Edit Announcement</h4>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                
                <!-- Icon Display -->
                <div class="announcement-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                
                <?php if ($hasTitle): ?>
                <!-- Announcement Title -->
                <div class="mb-3">
                    <label class="form-label required">Announcement Title</label>
                    <input type="text" class="form-control" name="title" required value="<?= htmlspecialchars($row['title'] ?? '') ?>">
                </div>
                <?php endif; ?>
                
                <?php if ($hasImage): ?>
                <!-- Current Image Display -->
                <?php if (!empty($row['image'])): ?>
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div class="mb-2">
                        <img src="/scratch/uploads/<?= htmlspecialchars($row['image']) ?>" alt="Current" style="max-width: 100%; max-height: 250px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Announcement Image -->
                <div class="mb-3">
                    <label class="form-label"><?= !empty($row['image']) ? 'Change Image (Optional)' : 'Announcement Image (Optional)' ?></label>
                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this)">
                    <small class="text-muted">Recommended size: 1200x800px. Formats: JPG, PNG, GIF, WEBP</small>
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <img id="preview" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Announcement Content -->
                <div class="mb-4">
                    <label class="form-label required">Announcement Content</label>
                    <textarea class="form-control" name="content" required><?= htmlspecialchars($row['content']) ?></textarea>
                    <small class="text-muted">Update the announcement message.</small>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/scratch/alumni-officer.php?page=announcements" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>Update Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>

