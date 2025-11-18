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

// Check if banner column exists
$hasBanner = false;
try {
    $check = $pdo->query("SHOW COLUMNS FROM events LIKE 'banner'")->fetch();
    $hasBanner = !empty($check);
} catch (Exception $e) {
    $hasBanner = false;
}

// Check if participant_limit column exists
$hasParticipantLimit = false;
try {
    $check = $pdo->query("SHOW COLUMNS FROM events LIKE 'participant_limit'")->fetch();
    $hasParticipantLimit = !empty($check);
} catch (Exception $e) {
    $hasParticipantLimit = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    
    // Handle banner upload
    $bannerFile = '';
    if ($hasBanner && !empty($_FILES['banner']['name']) && is_uploaded_file($_FILES['banner']['tmp_name'])) {
        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array(strtolower($ext), $allowedExts)) {
            $safeName = 'event_banner_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
            $destDir = __DIR__ . '/../uploads';
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0775, true);
            }
            $dest = $destDir . '/' . $safeName;
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $dest)) {
                $bannerFile = $safeName;
            }
        }
    }
    
    try {
        $eventTitle = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $schedule = trim($_POST['schedule'] ?? '');
        
        if (empty($eventTitle) || empty($content) || empty($schedule)) {
            throw new Exception('All required fields must be filled.');
        }
        
        $participantLimit = $hasParticipantLimit && !empty($_POST['participant_limit']) ? (int)$_POST['participant_limit'] : null;
        
        if ($hasBanner && $hasParticipantLimit) {
            $stmt = $pdo->prepare('INSERT INTO events (title, content, schedule, banner, participant_limit) VALUES (?,?,?,?,?)');
            $stmt->execute([$eventTitle, $content, $schedule, $bannerFile, $participantLimit]);
        } elseif ($hasBanner) {
            $stmt = $pdo->prepare('INSERT INTO events (title, content, schedule, banner) VALUES (?,?,?,?)');
            $stmt->execute([$eventTitle, $content, $schedule, $bannerFile]);
        } elseif ($hasParticipantLimit) {
            $stmt = $pdo->prepare('INSERT INTO events (title, content, schedule, participant_limit) VALUES (?,?,?,?)');
            $stmt->execute([$eventTitle, $content, $schedule, $participantLimit]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO events (title, content, schedule) VALUES (?,?,?)');
            $stmt->execute([$eventTitle, $content, $schedule]);
        }
        
        $_SESSION['success'] = 'Event created successfully!';
        header('Location: /scratch/alumni-officer.php?page=events');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to create event: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Alumni Officer</title>
    
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
        
        .form-control, .form-select, textarea {
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
        
        .banner-upload {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 16px;
            border: 3px dashed #fca5a5;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .banner-upload:hover {
            border-color: #dc2626;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.15);
        }
        
        .banner-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            margin: 20px auto;
            display: none;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.25);
        }
        
        .btn-outline-danger {
            border: 2px solid #dc2626;
            color: #dc2626;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .required::after {
            content: " *";
            color: #dc2626;
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
                <h4><i class="fas fa-calendar-plus me-2"></i>Create New Event</h4>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                
                <?php if ($hasBanner): ?>
                <!-- Banner Upload -->
                <div class="mb-4">
                    <label class="form-label">Event Banner</label>
                    <div class="banner-upload">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">Upload an event banner image</p>
                        <label for="bannerInput" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Choose Banner
                        </label>
                        <input type="file" id="bannerInput" name="banner" accept="image/*" style="display: none;">
                        <img id="bannerPreview" class="banner-preview" alt="Banner Preview">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Event Title -->
                <div class="mb-3">
                    <label class="form-label required">Event Title</label>
                    <input type="text" class="form-control" name="title" required placeholder="Enter event title">
                </div>
                
                <!-- Event Schedule -->
                <div class="mb-3">
                    <label class="form-label required">Event Schedule</label>
                    <input type="datetime-local" class="form-control" name="schedule" required>
                </div>
                
                <!-- Event Content -->
                <div class="mb-4">
                    <label class="form-label required">Event Description</label>
                    <textarea class="form-control" name="content" rows="8" required placeholder="Enter detailed event description..."></textarea>
                </div>
                
                <?php if ($hasParticipantLimit): ?>
                <!-- Participant Limit -->
                <div class="mb-4">
                    <label class="form-label">Participant Limit</label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="number" class="form-control" name="participant_limit" min="1" placeholder="Enter maximum number of participants (optional)">
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave empty for unlimited participants
                            </small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/scratch/alumni-officer.php?page=events" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Banner preview
        const bannerInput = document.getElementById('bannerInput');
        if (bannerInput) {
            bannerInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('bannerPreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>

