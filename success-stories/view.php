<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$user = current_user();

$pdo = get_pdo();

// Get story ID from URL
$storyId = (int)($_GET['id'] ?? 0);

if (!$storyId) {
    header('Location: /scratch/dashboard.php#success-stories');
    exit;
}

// Fetch the success story
try {
    $stmt = $pdo->prepare('
        SELECT ss.*, u.username, ab.firstname, ab.lastname 
        FROM success_stories ss 
        LEFT JOIN users u ON ss.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        WHERE ss.id = ? AND ss.status = 1
    ');
    $stmt->execute([$storyId]);
    $story = $stmt->fetch();
    
    if (!$story) {
        header('Location: /scratch/dashboard.php#success-stories');
        exit;
    }
} catch (Exception $e) {
    header('Location: /scratch/dashboard.php#success-stories');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($story['title']) ?> - SCC Alumni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }
    
    .navbar {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
      box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
    }
    
    .nav-link {
      font-weight: 500;
      padding: 0.75rem 1.5rem !important;
      border-radius: 25px;
      transition: all 0.3s ease;
      margin: 0 0.25rem;
      letter-spacing: 0.025em;
      font-size: 0.9rem;
      color: rgba(255,255,255,0.9) !important;
    }
    
    .nav-link:hover {
      color: #ffffff !important;
      background: rgba(255,255,255,0.1);
      transform: translateY(-2px);
    }
    
    .nav-link.active {
      background: rgba(255,255,255,0.15);
      color: #ffffff !important;
    }
    
    .profile-btn {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 25px;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    .profile-btn:hover {
      background: rgba(255,255,255,0.2);
      border-color: rgba(255,255,255,0.3);
      color: white;
      transform: translateY(-2px);
    }
    
    .main-content {
      padding-top: 2rem;
      padding-bottom: 2rem;
    }
    
    .story-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .story-header {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    .story-body {
      padding: 2rem;
    }
    
    .story-content {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #374151;
    }
    
    .story-meta {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 1rem;
      margin-top: 2rem;
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
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="/scratch/dashboard.php">
        <i class="fas fa-graduation-cap me-2"></i>SCC Alumni
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="/scratch/dashboard.php#news">
              <i class="fas fa-newspaper me-2"></i>News
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/scratch/dashboard.php#jobs">
              <i class="fas fa-briefcase me-2"></i>Jobs
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/scratch/dashboard.php#testimonials">
              <i class="fas fa-quote-left me-2"></i>Testimonials
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="/scratch/dashboard.php#success-stories">
              <i class="fas fa-star me-2"></i>Success Stories
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/scratch/forum/">
              <i class="fas fa-comments me-2"></i>Forum
            </a>
          </li>
        </ul>
        
        <div class="dropdown">
          <button class="btn profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-user me-2"></i><?= htmlspecialchars($user['username']) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="/scratch/profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
            <li><a class="dropdown-item text-danger" href="/scratch/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container main-content">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="story-card">
          <div class="story-header">
            <h1 class="mb-3"><?= htmlspecialchars($story['title']) ?></h1>
            <div class="d-flex align-items-center justify-content-center gap-4">
              <div>
                <i class="fas fa-user me-2"></i>
                <strong><?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?></strong>
              </div>
              <div>
                <i class="fas fa-calendar me-2"></i>
                <?= date('F d, Y', strtotime($story['created'])) ?>
              </div>
            </div>
          </div>
          
          <div class="story-body">
            <?php if (!empty($story['image'])): ?>
              <div class="text-center mb-4">
                <img src="/scratch/<?= htmlspecialchars($story['image']) ?>" 
                     alt="<?= htmlspecialchars($story['title']) ?>" 
                     class="img-fluid rounded" 
                     style="max-height: 400px; object-fit: cover; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
              </div>
            <?php endif; ?>
            
            <div class="story-content">
              <?= nl2br(htmlspecialchars($story['content'])) ?>
            </div>
            
            <div class="story-meta">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-muted mb-2">
                    <i class="fas fa-user me-2"></i>Author
                  </h6>
                  <p class="mb-0"><?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?></p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted mb-2">
                    <i class="fas fa-calendar me-2"></i>Published
                  </h6>
                  <p class="mb-0"><?= date('F d, Y \a\t g:i A', strtotime($story['created'])) ?></p>
                </div>
              </div>
            </div>
            
            <div class="d-flex gap-3 mt-4">
              <a href="/scratch/dashboard.php#success-stories" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Success Stories
              </a>
              <a href="/scratch/success-stories/create.php" class="btn btn-outline-primary">
                <i class="fas fa-plus me-2"></i>Share Your Story
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
