<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_admin();
$pdo = get_pdo();
$id = (int)($_GET['id'] ?? 0);
$row = $pdo->prepare('SELECT * FROM events WHERE id=?');
$row->execute([$id]);
$row = $row->fetch();
if (!$row) { http_response_code(404); exit('Not found'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    
    // Handle banner upload
    $bannerFile = $row['banner']; // Keep existing banner
    if (!empty($_FILES['banner']['name']) && is_uploaded_file($_FILES['banner']['tmp_name'])) {
        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $safeName = 'banner_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
        $destDir = __DIR__ . '/../uploads';
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }
        $dest = $destDir . '/' . $safeName;
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $dest)) {
            // Delete old banner if exists
            if ($bannerFile && file_exists($destDir . '/' . $bannerFile)) {
                @unlink($destDir . '/' . $bannerFile);
            }
            $bannerFile = $safeName;
        }
    }
    
    $eventTitle = trim($_POST['title'] ?? '');
    $participantLimit = !empty($_POST['participant_limit']) ? (int)$_POST['participant_limit'] : null;
    
    $stmt = $pdo->prepare('UPDATE events SET title=?, content=?, schedule=?, banner=?, participant_limit=? WHERE id=?');
    $stmt->execute([
        $eventTitle,
        trim($_POST['content'] ?? ''),
        trim($_POST['schedule'] ?? ''),
        $bannerFile,
        $participantLimit,
        $id,
    ]);
    
    // Log the update
    ActivityLogger::logUpdate('Event', $eventTitle);
    
    $_SESSION['success'] = 'Event updated successfully!';
    header('Location: /scratch/admin.php?page=events');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - SCC Alumni</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 900px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .form-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 24px 30px;
            border-radius: 16px;
            margin: -40px -40px 30px -40px;
            box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
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
        
        .form-control, .form-select, textarea {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
        }
        
        .form-control:focus, textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1), 0 4px 12px rgba(13, 110, 253, 0.15);
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
            background: linear-gradient(135deg, #0a58ca, #084298);
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
        
        .banner-upload {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            border: 3px dashed #dee2e6;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .banner-upload:hover {
            border-color: #0d6efd;
            background: linear-gradient(135deg, #e7f1ff 0%, #d4e8ff 100%);
            box-shadow: 0 4px 16px rgba(13, 110, 253, 0.15);
        }
        
        .banner-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            margin: 20px auto;
            display: block;
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.25);
        }
        
        .btn-outline-primary {
            border: 2px solid #0d6efd;
            color: #0d6efd;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
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
                <h4><i class="fas fa-calendar-edit me-2"></i>Edit Event</h4>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                
                <!-- Banner Upload -->
                <div class="mb-4">
                    <label class="form-label">Event Banner</label>
                    <div class="banner-upload">
                        <?php if ($row['banner'] && file_exists(__DIR__ . '/../uploads/' . $row['banner'])): ?>
                            <div class="current-banner-section mb-3">
                                <h6 class="text-muted mb-2"><i class="fas fa-image me-2"></i>Current Banner:</h6>
                                <img id="bannerPreview" class="banner-preview" src="/scratch/uploads/<?= e($row['banner']) ?>" alt="Current Banner">
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        File: <?= e($row['banner']) ?>
                                    </small>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="no-banner-section">
                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-3">No banner image uploaded</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="upload-section">
                            <label for="bannerInput" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                <?= ($row['banner'] && file_exists(__DIR__ . '/../uploads/' . $row['banner'])) ? 'Change Banner' : 'Upload Banner' ?>
                            </label>
                            <input type="file" id="bannerInput" name="banner" accept="image/*" style="display: none;">
                            <p class="text-muted small mt-2 mb-0">
                                <?= ($row['banner'] && file_exists(__DIR__ . '/../uploads/' . $row['banner'])) ? 'Upload a new banner to replace the current one' : 'Upload a banner image (Optional)' ?>
                            </p>
                        </div>
                        
                        <img id="newBannerPreview" class="banner-preview" style="display: none;" alt="New Banner Preview">
                    </div>
                </div>
                
                <!-- Event Title -->
                <div class="mb-3">
                    <label class="form-label required">Event Title</label>
                    <input type="text" class="form-control" name="title" required value="<?= e($row['title']) ?>">
                </div>
                
                <!-- Event Schedule -->
                <div class="mb-3">
                    <label class="form-label required">Event Schedule</label>
                    <input type="datetime-local" class="form-control" name="schedule" required 
                           value="<?= date('Y-m-d\TH:i', strtotime($row['schedule'])) ?>">
                </div>
                
                <!-- Event Content -->
                <div class="mb-4">
                    <label class="form-label required">Event Description</label>
                    <textarea class="form-control" name="content" rows="8" required><?= e($row['content']) ?></textarea>
                </div>
                
                <!-- Participant Limit -->
                <div class="mb-4">
                    <label class="form-label">Participant Limit</label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="number" class="form-control" name="participant_limit" min="1" 
                                   value="<?= isset($row['participant_limit']) ? $row['participant_limit'] : '' ?>" 
                                   placeholder="Enter maximum number of participants (optional)">
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave empty for unlimited participants
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/scratch/admin.php?page=events" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Banner preview
        document.getElementById('bannerInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newPreview = document.getElementById('newBannerPreview');
                    newPreview.src = e.target.result;
                    newPreview.style.display = 'block';
                    
                    // Show preview of new image
                    newPreview.style.marginTop = '20px';
                    newPreview.style.border = '2px solid #0d6efd';
                    newPreview.style.borderRadius = '12px';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
