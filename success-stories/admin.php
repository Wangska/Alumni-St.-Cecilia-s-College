<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$user = current_user();

// Check if user is admin
if (!isset($user['type']) || $user['type'] != 1) {
    header('Location: /scratch/dashboard.php');
    exit;
}

$pdo = get_pdo();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $storyId = (int)($_POST['story_id'] ?? 0);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!hash_equals(csrf_token(), $csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } elseif (!$storyId) {
        $error = 'Invalid story ID.';
    } else {
        try {
            if ($action === 'approve') {
                $stmt = $pdo->prepare('UPDATE success_stories SET status = 1 WHERE id = ?');
                $stmt->execute([$storyId]);
                $success = 'Story approved successfully and is now visible on the dashboard.';
            } elseif ($action === 'reject') {
                $stmt = $pdo->prepare('DELETE FROM success_stories WHERE id = ?');
                $stmt->execute([$storyId]);
                $success = 'Story rejected and removed from the system.';
            } elseif ($action === 'unapprove') {
                $stmt = $pdo->prepare('UPDATE success_stories SET status = 0 WHERE id = ?');
                $stmt->execute([$storyId]);
                $success = 'Story unapproved and hidden from the dashboard.';
            }
        } catch (Exception $e) {
            $error = 'Failed to process the request. Please try again.';
        }
    }
}

// Fetch all success stories
try {
    $stmt = $pdo->prepare('
        SELECT ss.*, u.username, ab.firstname, ab.lastname 
        FROM success_stories ss 
        LEFT JOIN users u ON ss.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        ORDER BY ss.created DESC
    ');
    $stmt->execute();
    $stories = $stmt->fetchAll();
} catch (Exception $e) {
    $stories = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Success Stories Management - SCC Alumni</title>
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
    
    .main-content {
      padding-top: 2rem;
      padding-bottom: 2rem;
    }
    
    .admin-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .admin-header {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    .admin-body {
      padding: 2rem;
    }
    
    .story-item {
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }
    
    .story-item:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .story-item.pending {
      border-left: 4px solid #f59e0b;
    }
    
    .story-item.approved {
      border-left: 4px solid #10b981;
    }
    
    .status-badge {
      font-size: 0.75rem;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-weight: 600;
    }
    
    .status-pending {
      background: #fef3c7;
      color: #92400e;
    }
    
    .status-approved {
      background: #d1fae5;
      color: #065f46;
    }
    
    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }
    
    .btn-success {
      background: #10b981;
      border-color: #10b981;
    }
    
    .btn-danger {
      background: #ef4444;
      border-color: #ef4444;
    }
    
    /* Side Panel Styling */
    .side-panel .story-item {
      padding: 1rem;
      margin-bottom: 0.75rem;
      border-radius: 8px;
      border-left: 3px solid #f59e0b;
      background: #fefbf3;
    }
    
    .side-panel .story-item:hover {
      background: #fef3c7;
      transform: translateY(-1px);
    }
    
    .side-panel .btn-sm {
      font-size: 0.7rem;
      padding: 0.25rem 0.5rem;
    }
    
    .side-panel .status-badge {
      font-size: 0.7rem;
      padding: 0.2rem 0.5rem;
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
            <a class="nav-link active" href="/scratch/dashboard.php#success-stories">
              <i class="fas fa-star me-2"></i>Success Stories
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/scratch/dashboard.php#testimonials">
              <i class="fas fa-quote-left me-2"></i>Testimonials
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
    <div class="row">
      <!-- Side Panel for Quick Actions -->
      <div class="col-lg-4 mb-4">
        <div class="admin-card side-panel">
          <div class="admin-header">
            <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Pending Stories</h5>
            <p class="mb-0">Quick approval actions</p>
          </div>
          
          <!-- Quick Stats -->
          <div class="admin-body border-bottom">
            <?php 
            $pendingCount = count(array_filter($stories, function($story) { return !$story['status']; }));
            $approvedCount = count(array_filter($stories, function($story) { return $story['status']; }));
            ?>
            <div class="row text-center">
              <div class="col-6">
                <div class="p-2">
                  <div class="fw-bold text-warning" style="font-size: 1.5rem;"><?= $pendingCount ?></div>
                  <small class="text-muted">Pending</small>
                </div>
              </div>
              <div class="col-6">
                <div class="p-2">
                  <div class="fw-bold text-success" style="font-size: 1.5rem;"><?= $approvedCount ?></div>
                  <small class="text-muted">Approved</small>
                </div>
              </div>
            </div>
          </div>
          
          <div class="admin-body">
            <?php 
            $pendingStories = array_filter($stories, function($story) {
                return !$story['status'];
            });
            ?>
            
            <?php if (empty($pendingStories)): ?>
              <div class="text-center py-3">
                <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2 mb-0">No pending stories</p>
              </div>
            <?php else: ?>
              <?php foreach (array_slice($pendingStories, 0, 5) as $story): ?>
                <div class="story-item pending mb-3">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-1" style="font-size: 0.9rem;"><?= htmlspecialchars(substr($story['title'], 0, 30)) ?><?= strlen($story['title']) > 30 ? '...' : '' ?></h6>
                    <span class="status-badge status-pending" style="font-size: 0.7rem;">Pending</span>
                  </div>
                  
                  <p class="text-muted mb-2" style="font-size: 0.8rem;">
                    <i class="fas fa-user me-1"></i>
                    <?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>
                  </p>
                  
                  <p class="mb-2 text-muted" style="font-size: 0.75rem; line-height: 1.3;">
                    <?= htmlspecialchars(substr($story['content'], 0, 80)) ?><?= strlen($story['content']) > 80 ? '...' : '' ?>
                  </p>
                  
                  <div class="d-flex gap-1">
                    <form method="POST" class="d-inline">
                      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                      <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="btn btn-success btn-sm" 
                              onclick="return confirm('Approve this story?')" 
                              style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                        <i class="fas fa-check me-1"></i>Approve
                      </button>
                    </form>
                    
                    <form method="POST" class="d-inline">
                      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                      <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="btn btn-danger btn-sm" 
                              onclick="return confirm('Reject this story?')" 
                              style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                        <i class="fas fa-times me-1"></i>Reject
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              
              <?php if (count($pendingStories) > 5): ?>
                <div class="text-center">
                  <small class="text-muted">And <?= count($pendingStories) - 5 ?> more pending stories...</small>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            
            <!-- Refresh Button -->
            <div class="text-center mt-3">
              <a href="admin.php" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync-alt me-1"></i>Refresh
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Main Content Area -->
      <div class="col-lg-8">
        <div class="admin-card">
          <div class="admin-header">
            <h2 class="mb-3"><i class="fas fa-cogs me-2"></i>Success Stories Management</h2>
            <p class="mb-0">Review and manage alumni success stories</p>
          </div>
          
          <div class="admin-body">
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
            
            <?php if (empty($stories)): ?>
              <div class="text-center py-5">
                <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No Success Stories</h4>
                <p class="text-muted">No success stories have been submitted yet.</p>
              </div>
            <?php else: ?>
              <!-- Debug Info -->
              <div class="alert alert-info mb-4">
                <strong>Debug Info:</strong> Found <?= count($stories) ?> stories total.
                <?php 
                $pendingCount = 0;
                $approvedCount = 0;
                foreach ($stories as $story) {
                    if ($story['status']) {
                        $approvedCount++;
                    } else {
                        $pendingCount++;
                    }
                }
                ?>
                <br>Pending: <?= $pendingCount ?> | Approved: <?= $approvedCount ?>
              </div>
              <?php foreach ($stories as $story): ?>
                <div class="story-item <?= $story['status'] ? 'approved' : 'pending' ?>">
                  <div class="row">
                    <div class="col-md-8">
                      <h5 class="mb-2"><?= htmlspecialchars($story['title']) ?></h5>
                      <p class="text-muted mb-2">
                        <i class="fas fa-user me-2"></i>
                        <?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-calendar me-2"></i>
                        <?= date('M d, Y', strtotime($story['created'])) ?>
                        <?php if (!empty($story['image'])): ?>
                          <span class="mx-2">•</span>
                          <i class="fas fa-image me-1"></i>Has Image
                        <?php endif; ?>
                      </p>
                      <p class="mb-0 text-muted">
                        <?= htmlspecialchars(substr($story['content'], 0, 200)) ?><?= strlen($story['content']) > 200 ? '...' : '' ?>
                      </p>
                    </div>
                    <div class="col-md-4 text-end">
                      <div class="mb-2">
                        <span class="status-badge <?= $story['status'] ? 'status-approved' : 'status-pending' ?>">
                          <?= $story['status'] ? 'Approved' : 'Pending' ?>
                        </span>
                      </div>
                      
                      <?php if (!$story['status']): ?>
                        <form method="POST" class="d-inline">
                          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                          <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                          <input type="hidden" name="action" value="approve">
                          <button type="submit" class="btn btn-success btn-sm me-2" 
                                  onclick="return confirm('Approve this success story? It will be visible on the dashboard.')">
                            <i class="fas fa-check me-1"></i>Approve
                          </button>
                        </form>
                        
                        <form method="POST" class="d-inline">
                          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                          <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                          <input type="hidden" name="action" value="reject">
                          <button type="submit" class="btn btn-danger btn-sm" 
                                  onclick="return confirm('Reject and delete this success story? This action cannot be undone.')">
                            <i class="fas fa-times me-1"></i>Reject
                          </button>
                        </form>
                      <?php else: ?>
                        <form method="POST" class="d-inline">
                          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                          <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                          <input type="hidden" name="action" value="unapprove">
                          <button type="submit" class="btn btn-warning btn-sm me-2" 
                                  onclick="return confirm('Unapprove this success story? It will be hidden from the dashboard.')">
                            <i class="fas fa-eye-slash me-1"></i>Unapprove
                          </button>
                        </form>
                        
                        <form method="POST" class="d-inline">
                          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                          <input type="hidden" name="story_id" value="<?= $story['id'] ?>">
                          <input type="hidden" name="action" value="reject">
                          <button type="submit" class="btn btn-danger btn-sm" 
                                  onclick="return confirm('Delete this success story permanently? This action cannot be undone.')">
                            <i class="fas fa-trash me-1"></i>Delete
                          </button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="mt-4">
              <a href="/scratch/dashboard.php#success-stories" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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
