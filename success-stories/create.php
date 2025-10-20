<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_login();
$user = current_user();

$pdo = get_pdo();
$pageTitle = 'Share Your Success Story - SCC Alumni';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!hash_equals(csrf_token(), $csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } elseif (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($title) > 255) {
        $error = 'Title is too long. Maximum 255 characters allowed.';
    } elseif (strlen($content) < 50) {
        $error = 'Content must be at least 50 characters long.';
    } else {
        $imagePath = null;
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/success-stories/';
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            $fileType = $_FILES['image']['type'];
            $fileSize = $_FILES['image']['size'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $error = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
            } elseif ($fileSize > $maxSize) {
                $error = 'File size too large. Maximum 5MB allowed.';
            } else {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'story_' . time() . '_' . $user['id'] . '.' . $extension;
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $imagePath = 'uploads/success-stories/' . $fileName;
                } else {
                    $error = 'Failed to upload image. Please try again.';
                }
            }
        }
        
        if (!isset($error)) {
            try {
                $stmt = $pdo->prepare('INSERT INTO success_stories (user_id, title, content, image, created, status) VALUES (?, ?, ?, ?, NOW(), 0)');
                $stmt->execute([$user['id'], $title, $content, $imagePath]);
                // Log create
                ActivityLogger::logCreate('Success Story', 'Alumni submitted success story', [
                    'user_id' => (int)$user['id'],
                    'title' => $title
                ]);
                
                // Return JSON response for AJAX
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Your success story has been submitted for review. It will be published once approved by the administrator.'
                ]);
                exit;
            } catch (Exception $e) {
                // Return JSON error response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to submit your story. Please try again.'
                ]);
                exit;
            }
        } else {
            // Return JSON error response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $error
            ]);
            exit;
        }
    }
}
?>
<?php include __DIR__ . '/../inc/header.php'; ?>
  <style>
    .story-form {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .form-header {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    .form-body {
      padding: 2rem;
    }
    
    .form-control, .form-select {
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      padding: 12px 16px;
      transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #dc2626;
      box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
      border: none;
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
    }
    
    .character-count {
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    .alert {
      border-radius: 10px;
      border: none;
    }
  </style>

  <!-- Main Content -->
  <div class="container main-content">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="story-form">
          <div class="form-header">
            <h2 class="mb-3"><i class="fas fa-star me-2"></i>Share Your Success Story</h2>
            <p class="mb-0">Inspire other alumni by sharing your journey and achievements</p>
          </div>
          
          <div class="form-body">
            <?php if (isset($error)): ?>
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
              <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
              </div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
              
              <div class="mb-4">
                <label for="title" class="form-label fw-bold">
                  <i class="fas fa-heading me-2 text-danger"></i>Story Title
                </label>
                <input type="text" class="form-control" id="title" name="title" 
                       placeholder="Enter a compelling title for your success story" 
                       value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                <div class="character-count mt-1">
                  <span id="title-count">0</span>/255 characters
                </div>
              </div>
              
              <div class="mb-4">
                <label for="image" class="form-label fw-bold">
                  <i class="fas fa-image me-2 text-danger"></i>Story Image (Optional)
                </label>
                <input type="file" class="form-control" id="image" name="image" 
                       accept="image/jpeg,image/jpg,image/png,image/gif">
                <div class="form-text">
                  <i class="fas fa-info-circle me-1"></i>
                  Upload a photo related to your success story (JPEG, PNG, or GIF, max 5MB)
                </div>
                <div id="image-preview" class="mt-3" style="display: none;">
                  <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
              </div>
              
              <div class="mb-4">
                <label for="content" class="form-label fw-bold">
                  <i class="fas fa-edit me-2 text-danger"></i>Your Story
                </label>
                <textarea class="form-control" id="content" name="content" rows="8" 
                          placeholder="Share your journey, challenges overcome, achievements, and how SCC helped shape your success. Be detailed and inspiring!" 
                          required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                <div class="character-count mt-1">
                  <span id="content-count">0</span> characters (minimum 50 required)
                </div>
              </div>
              
              <div class="mb-4">
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i>
                  <strong>Note:</strong> Your story will be reviewed by administrators before being published. This helps maintain quality and appropriateness of content.
                </div>
              </div>
              
              <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-paper-plane me-2"></i>Submit Story
                </button>
                <a href="/scratch/dashboard.php#success-stories" class="btn btn-outline-secondary">
                  <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Character count for title
    document.getElementById('title').addEventListener('input', function() {
      const count = this.value.length;
      document.getElementById('title-count').textContent = count;
      
      if (count > 255) {
        this.classList.add('is-invalid');
        document.getElementById('title-count').style.color = '#dc2626';
      } else {
        this.classList.remove('is-invalid');
        document.getElementById('title-count').style.color = '#6b7280';
      }
    });
    
    // Character count for content
    document.getElementById('content').addEventListener('input', function() {
      const count = this.value.length;
      document.getElementById('content-count').textContent = count;
      
      if (count < 50) {
        this.classList.add('is-invalid');
        document.getElementById('content-count').style.color = '#dc2626';
      } else {
        this.classList.remove('is-invalid');
        document.getElementById('content-count').style.color = '#6b7280';
      }
    });
    
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('image-preview');
      const previewImg = document.getElementById('preview-img');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none';
      }
    });
  </script>
<?php include __DIR__ . '/../inc/footer.php'; ?>
