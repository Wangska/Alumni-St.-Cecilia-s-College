<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

// Check if user is logged in
if (!current_user()) {
    header('Location: /scratch/');
    exit;
}

$user = $_SESSION['user'];
$alumni = null;
$pdo = get_pdo();

// Get alumni data if user is alumni
if ($user['type'] == 3 && isset($user['alumnus_id']) && $user['alumnus_id'] > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM alumnus_bio WHERE id = ?");
        $stmt->execute([$user['alumnus_id']]);
        $alumni = $stmt->fetch();
    } catch (Exception $e) {
        // Handle error
    }
}

// Fetch forum topics
$topics = [];
try {
    $stmt = $pdo->prepare("
        SELECT ft.*, u.name as author_name, ab.avatar as author_avatar, ab.firstname, ab.lastname 
        FROM forum_topics ft 
        LEFT JOIN users u ON ft.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        ORDER BY ft.date_created DESC
    ");
    $stmt->execute();
    $topics = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Forum - St. Cecilia's College</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
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

        .profile-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .profile-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .forum-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .forum-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .topic-header {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 1.5rem;
            border-radius: 16px 16px 0 0;
        }

        .topic-meta {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        /* Thread/Twitter-like Styles */
        .thread-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e1e8ed;
            overflow: hidden;
        }
        .thread-header {
            padding: 20px;
            border-bottom: 1px solid #e1e8ed;
        }
        .thread-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .thread-content {
            flex: 1;
        }
        .thread-meta {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .thread-meta strong {
            color: #1a1a1a;
        }
        .thread-text h5 {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .thread-text p {
            color: #333;
            line-height: 1.5;
        }
        .thread-comments {
            background: #f8f9fa;
            border-top: 1px solid #e1e8ed;
        }
        .comment-item {
            padding: 16px 20px;
            border-bottom: 1px solid #e1e8ed;
        }
        .comment-item:last-child {
            border-bottom: none;
        }
        .comment-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6c757d, #495057);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .comment-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .comment-actions .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .comment-actions .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .comment-content {
            flex: 1;
        }
        .comment-meta {
            margin-bottom: 6px;
            font-size: 13px;
        }
        .comment-meta strong {
            color: #1a1a1a;
        }
        .comment-text {
            color: #333;
            line-height: 1.4;
        }
        .comment-form {
            padding: 16px 20px;
            background: white;
        }
        .comment-input-wrapper {
            flex: 1;
        }
        .comment-input {
            border: 1px solid #e1e8ed;
            border-radius: 20px;
            padding: 12px 16px;
            resize: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .comment-input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        .comment-actions {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }
        .comment-actions .btn {
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
        }
        .see-more-comments {
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid #e1e8ed;
        }
        .see-more-btn {
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .see-more-btn:hover {
            color: #dc2626 !important;
            transform: translateY(-1px);
        }
        .see-more-btn i {
            transition: transform 0.3s ease;
        }
        .see-more-btn:hover i {
            transform: translateY(2px);
        }
        .see-more-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .see-more-btn:disabled:hover {
            transform: none;
            color: #6c757d !important;
        }
        .see-more-btn[data-expanded="true"] {
            color: #dc2626 !important;
        }
        .see-more-btn[data-expanded="true"]:hover {
            color: #b91c1c !important;
        }

        .section-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .section-title {
            color: #dc2626;
            font-family: 'Times New Roman', serif;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .section-underline {
            width: 100px;
            height: 3px;
            background: #dc2626;
            margin: 0 auto;
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
          <div style="font-size: 1.1rem; font-weight: 700; line-height: 1.2; color: white;">ALUMNI NEXUS</div>
          <div style="font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px; margin-top: 2px; color: rgba(255,255,255,0.9);">ALUMNI PORTAL</div>
        </div>
      </a>
      
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 0.5rem; transition: all 0.3s ease;">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="/scratch/news/index.php">News</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/jobs/index.php">Jobs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/events/index.php">Events</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/success-stories/index.php">Success Stories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/testimonials/index.php">Testimonials</a>
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

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Section Header -->
        <div class="section-header text-center">
            <h1 class="section-title">ALUMNI FORUM</h1>
            <div class="section-underline"></div>
            <p class="lead text-muted mt-3">Connect with fellow alumni and share your experiences</p>
            <div class="mt-4">
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Topic
                </a>
            </div>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'comment_failed'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to post comment. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Forum Topics -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if (!empty($topics)): ?>
                    <?php foreach ($topics as $topic): ?>
                        <?php
                        // Fetch comments for this topic
                        $topicComments = [];
                        try {
                            $stmt = $pdo->prepare("
                                SELECT fc.*, u.name as author_name, ab.avatar as author_avatar, ab.firstname, ab.lastname 
                                FROM forum_comments fc 
                                LEFT JOIN users u ON fc.user_id = u.id 
                                LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
                                WHERE fc.topic_id = ? 
                                ORDER BY fc.date_created ASC
                            ");
                            $stmt->execute([$topic['id']]);
                            $topicComments = $stmt->fetchAll();
                        } catch (Exception $e) {
                            // Handle error
                        }
                        ?>
                        
                        <!-- Topic Thread -->
                        <div class="thread-card mb-4">
                            <!-- Topic Header -->
                            <div class="thread-header">
                                <div class="d-flex align-items-start">
                                    <div class="thread-avatar">
                                        <?php if (!empty($topic['author_avatar'])): ?>
                                            <img src="/scratch/uploads/<?= htmlspecialchars($topic['author_avatar']) ?>" 
                                                 alt="<?= htmlspecialchars($topic['author_name'] ?? 'User') ?>" 
                                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="thread-content">
                                        <div class="thread-meta">
                                            <strong><?= htmlspecialchars($topic['author_name'] ?? 'Anonymous') ?></strong>
                                            <span class="text-muted">@<?= strtolower(str_replace(' ', '', $topic['author_name'] ?? 'anonymous')) ?></span>
                                            <span class="text-muted">·</span>
                                            <span class="text-muted"><?= date('M d', strtotime($topic['date_created'])) ?></span>
                                        </div>
                                        <div class="thread-text">
                                            <h5 class="mb-2"><?= htmlspecialchars($topic['title']) ?></h5>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($topic['description'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Comments Section -->
                            <div class="thread-comments">
                                <?php if (!empty($topicComments)): ?>
                                    <?php 
                                    $totalComments = count($topicComments);
                                    $displayComments = array_slice($topicComments, 0, 4);
                                    $hasMoreComments = $totalComments > 4;
                                    ?>
                                    <?php foreach ($displayComments as $comment): ?>
                                        <div class="comment-item">
                                            <div class="d-flex align-items-start">
                                                <div class="comment-avatar">
                                                    <?php if (!empty($comment['author_avatar'])): ?>
                                                        <img src="/scratch/uploads/<?= htmlspecialchars($comment['author_avatar']) ?>" 
                                                             alt="<?= htmlspecialchars($comment['author_name'] ?? 'User') ?>" 
                                                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                    <?php else: ?>
                                                        <i class="fas fa-user"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="comment-content">
                                                    <div class="comment-meta">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <strong><?= htmlspecialchars($comment['author_name'] ?? 'Anonymous') ?></strong>
                                                                <span class="text-muted">@<?= strtolower(str_replace(' ', '', $comment['author_name'] ?? 'anonymous')) ?></span>
                                                                <span class="text-muted">·</span>
                                                                <span class="text-muted"><?= date('M d', strtotime($comment['date_created'])) ?></span>
                                                            </div>
                                                            <?php if ($comment['user_id'] == $user['id']): ?>
                                                                <div class="comment-actions">
                                                                    <button class="btn btn-sm btn-outline-primary edit-comment-btn" 
                                                                            data-comment-id="<?= $comment['id'] ?>" 
                                                                            data-comment-text="<?= htmlspecialchars($comment['comment']) ?>"
                                                                            title="Edit comment">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-outline-danger delete-comment-btn" 
                                                                            data-comment-id="<?= $comment['id'] ?>"
                                                                            title="Delete comment">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="comment-text">
                                                        <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php if ($hasMoreComments): ?>
                                        <div class="see-more-comments">
                                            <button class="btn btn-link text-primary see-more-btn" 
                                                    data-topic-id="<?= $topic['id'] ?>" 
                                                    data-offset="4" 
                                                    data-total="<?= $totalComments ?>"
                                                    data-expanded="false">
                                                <i class="fas fa-chevron-down me-1"></i>
                                                View <?= $totalComments - 4 ?> more comments
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <!-- Add Comment Form -->
                                <div class="comment-form">
                                    <form class="comment-form-ajax" data-topic-id="<?= $topic['id'] ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="comment-avatar">
                                                <?php if (!empty($alumni['avatar'])): ?>
                                                    <img src="/scratch/uploads/<?= htmlspecialchars($alumni['avatar']) ?>" 
                                                         alt="<?= htmlspecialchars($user['name']) ?>" 
                                                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                <?php else: ?>
                                                    <i class="fas fa-user"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="comment-input-wrapper">
                                                <textarea class="form-control comment-input" name="comment" placeholder="Add a comment..." rows="2" required></textarea>
                                                <div class="comment-actions">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-paper-plane me-1"></i>Reply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-comments text-muted" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-muted">No forum topics yet</h5>
                        <p class="text-muted">Check back later for discussions</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Comment Modal -->
    <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Delete Comment</h5>
                        <p class="text-muted mb-0">Are you sure you want to delete this comment? This action cannot be undone.</p>
                    </div>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600;">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600;">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle AJAX comment submission
            document.querySelectorAll('.comment-form-ajax').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData();
                    const topicId = this.dataset.topicId;
                    const commentText = this.querySelector('textarea[name="comment"]').value;
                    
                    // Add form data
                    formData.append('comment', commentText);
                    formData.append('topic_id', topicId);
                    
                    if (!commentText.trim()) {
                        return;
                    }
                    
                    // Disable submit button
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Posting...';
                    
                    // Submit comment via AJAX
                    fetch('comment.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear the textarea
                            this.querySelector('textarea').value = '';
                            
                            // Add new comment to the thread with real comment ID
                            addCommentToThread(topicId, commentText, data.comment_id);
                        } else {
                            alert('Error: ' + data.message);
                        }
                        
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to post comment. Please try again.');
                        
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            });
            
            // Handle "View More Comments" / "Show Less Comments" button
            document.querySelectorAll('.see-more-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const topicId = this.dataset.topicId;
                    const offset = parseInt(this.dataset.offset);
                    const isExpanded = this.dataset.expanded === 'true';
                    
                    if (isExpanded) {
                        // Show less comments - collapse back to 4
                        showLessComments(topicId, this);
                    } else {
                        // Show more comments - load additional comments
                        showMoreComments(topicId, offset, this);
                    }
                });
            });
            
            // Edit comment functionality - inline editing
            document.querySelectorAll('.edit-comment-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const commentId = this.dataset.commentId;
                    const commentText = this.dataset.commentText;
                    
                    // Find the comment text element
                    const commentItem = this.closest('.comment-item');
                    const commentTextElement = commentItem.querySelector('.comment-text');
                    const actionsContainer = commentItem.querySelector('.comment-actions');
                    
                    // Store original content
                    const originalText = commentTextElement.innerHTML;
                    
                    // Replace with textarea
                    commentTextElement.innerHTML = `
                        <div class="d-flex flex-column gap-2">
                            <textarea class="form-control" rows="3" style="border-radius: 8px; border: 2px solid #e5e7eb; resize: vertical;">${commentText}</textarea>
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary cancel-edit-btn">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button class="btn btn-sm btn-primary save-edit-btn">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Hide original actions
                    actionsContainer.style.display = 'none';
                    
                    // Add event listeners for save/cancel
                    const saveBtn = commentTextElement.querySelector('.save-edit-btn');
                    const cancelBtn = commentTextElement.querySelector('.cancel-edit-btn');
                    const textarea = commentTextElement.querySelector('textarea');
                    
                    saveBtn.addEventListener('click', function() {
                        const newText = textarea.value.trim();
                        if (newText) {
                            saveCommentEdit(commentId, newText, commentItem, originalText);
                        } else {
                            alert('Comment cannot be empty.');
                        }
                    });
                    
                    cancelBtn.addEventListener('click', function() {
                        commentTextElement.innerHTML = originalText;
                        actionsContainer.style.display = 'flex';
                    });
                    
                    // Focus on textarea
                    textarea.focus();
                });
            });
            
            // Delete comment functionality - modal
            document.querySelectorAll('.delete-comment-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const commentId = this.dataset.commentId;
                    showDeleteModal(commentId);
                });
            });
        });
        
        function addCommentToThread(topicId, commentText, commentId = null) {
            // Find the thread for this topic
            const threadCard = document.querySelector(`[data-topic-id="${topicId}"]`).closest('.thread-card');
            const commentsSection = threadCard.querySelector('.thread-comments');
            
            // Create new comment element
            const commentElement = document.createElement('div');
            commentElement.className = 'comment-item';
            commentElement.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="comment-avatar">
                        <?php if (!empty($alumni['avatar'])): ?>
                            <img src="/scratch/uploads/<?= htmlspecialchars($alumni['avatar']) ?>" 
                                 alt="<?= htmlspecialchars($user['name']) ?>" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="comment-content">
                        <div class="comment-meta">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    <span class="text-muted">@<?= strtolower(str_replace(' ', '', $user['name'])) ?></span>
                                    <span class="text-muted">·</span>
                                    <span class="text-muted">Just now</span>
                                </div>
                                <div class="comment-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-comment-btn" 
                                            data-comment-id="${commentId || 'temp-' + Date.now()}" 
                                            data-comment-text="${commentText.replace(/"/g, '&quot;')}"
                                            title="Edit comment">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-comment-btn" 
                                            data-comment-id="${commentId || 'temp-' + Date.now()}"
                                            title="Delete comment">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="comment-text">
                            ${commentText.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
            `;
            
            // Insert before the comment form
            const commentForm = commentsSection.querySelector('.comment-form');
            commentsSection.insertBefore(commentElement, commentForm);
            
            // Add event listeners to the new comment buttons
            const editBtn = commentElement.querySelector('.edit-comment-btn');
            const deleteBtn = commentElement.querySelector('.delete-comment-btn');
            
            // Update comment IDs if we have a real comment ID
            if (commentId && !commentId.toString().startsWith('temp-')) {
                if (editBtn) editBtn.dataset.commentId = commentId;
                if (deleteBtn) deleteBtn.dataset.commentId = commentId;
            }
            
            if (editBtn) {
                editBtn.addEventListener('click', function() {
                    const commentId = this.dataset.commentId;
                    const commentText = this.dataset.commentText;
                    
                    // Find the comment text element
                    const commentItem = this.closest('.comment-item');
                    const commentTextElement = commentItem.querySelector('.comment-text');
                    const actionsContainer = commentItem.querySelector('.comment-actions');
                    
                    // Store original content
                    const originalText = commentTextElement.innerHTML;
                    
                    // Replace with textarea
                    commentTextElement.innerHTML = `
                        <div class="d-flex flex-column gap-2">
                            <textarea class="form-control" rows="3" style="border-radius: 8px; border: 2px solid #e5e7eb; resize: vertical;">${commentText}</textarea>
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary cancel-edit-btn">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button class="btn btn-sm btn-primary save-edit-btn">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Hide original actions
                    actionsContainer.style.display = 'none';
                    
                    // Add event listeners for save/cancel
                    const saveBtn = commentTextElement.querySelector('.save-edit-btn');
                    const cancelBtn = commentTextElement.querySelector('.cancel-edit-btn');
                    const textarea = commentTextElement.querySelector('textarea');
                    
                    saveBtn.addEventListener('click', function() {
                        const newText = textarea.value.trim();
                        if (newText) {
                            saveCommentEdit(commentId, newText, commentItem, originalText);
                        } else {
                            alert('Comment cannot be empty.');
                        }
                    });
                    
                    cancelBtn.addEventListener('click', function() {
                        commentTextElement.innerHTML = originalText;
                        actionsContainer.style.display = 'flex';
                    });
                    
                    // Focus on textarea
                    textarea.focus();
                });
            }
            
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    const commentId = this.dataset.commentId;
                    showDeleteModal(commentId);
                });
            }
            
            // Scroll to the new comment
            commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        let currentDeleteCommentId = null;
        
        // Confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentDeleteCommentId) {
                deleteComment(currentDeleteCommentId);
            }
        });
        
        function showDeleteModal(commentId) {
            currentDeleteCommentId = commentId;
            const modal = new bootstrap.Modal(document.getElementById('deleteCommentModal'));
            modal.show();
        }
        
        function saveCommentEdit(commentId, newText, commentItem, originalText) {
            console.log('Attempting to edit comment:', commentId, newText);
            
            fetch('edit_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}&comment=${encodeURIComponent(newText)}`
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update the comment text
                    const commentTextElement = commentItem.querySelector('.comment-text');
                    commentTextElement.innerHTML = newText.replace(/\n/g, '<br>');
                    
                    // Show actions again
                    const actionsContainer = commentItem.querySelector('.comment-actions');
                    actionsContainer.style.display = 'flex';
                    
                    // Update the data attribute for future edits
                    const editBtn = commentItem.querySelector('.edit-comment-btn');
                    editBtn.dataset.commentText = newText;
                } else {
                    alert('Error: ' + data.message);
                    // Restore original content
                    commentItem.querySelector('.comment-text').innerHTML = originalText;
                    commentItem.querySelector('.comment-actions').style.display = 'flex';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update comment. Please try again.');
                // Restore original content
                commentItem.querySelector('.comment-text').innerHTML = originalText;
                commentItem.querySelector('.comment-actions').style.display = 'flex';
            });
        }
        
        function deleteComment(commentId) {
            fetch('delete_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal'));
                    modal.hide();
                    
                    // Reload page to show updated comments
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete comment. Please try again.');
            });
        }
        
        function addMoreCommentsToThread(topicId, comments) {
            // Find the thread for this topic
            const threadCard = document.querySelector(`[data-topic-id="${topicId}"]`).closest('.thread-card');
            const commentsSection = threadCard.querySelector('.thread-comments');
            const seeMoreContainer = threadCard.querySelector('.see-more-comments');
            
            // Insert new comments before the "See More" button
            comments.forEach(function(comment) {
                const commentElement = document.createElement('div');
                commentElement.className = 'comment-item';
                commentElement.innerHTML = `
                    <div class="d-flex align-items-start">
                        <div class="comment-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="comment-content">
                            <div class="comment-meta">
                                <strong>${comment.author_name || 'Anonymous'}</strong>
                                <span class="text-muted">@${(comment.author_name || 'anonymous').toLowerCase().replace(/\s+/g, '')}</span>
                                <span class="text-muted">·</span>
                                <span class="text-muted">${formatDate(comment.date_created)}</span>
                            </div>
                            <div class="comment-text">
                                ${comment.comment.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    </div>
                `;
                
                // Insert before the "See More" button
                seeMoreContainer.parentNode.insertBefore(commentElement, seeMoreContainer);
            });
        }
        
        function showMoreComments(topicId, offset, button) {
            // Show loading state
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
            
            // Fetch more comments
            fetch(`get_comments.php?topic_id=${topicId}&offset=${offset}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new comments to the thread
                    addMoreCommentsToThread(topicId, data.comments);
                    
                    // Update button state
                    if (data.hasMore) {
                        button.dataset.offset = offset + 4;
                        button.dataset.expanded = 'true';
                        button.innerHTML = `<i class="fas fa-chevron-up me-1"></i>Show less comments`;
                        button.disabled = false;
                    } else {
                        // All comments loaded - show "Show less" button
                        button.dataset.expanded = 'true';
                        button.innerHTML = `<i class="fas fa-chevron-up me-1"></i>Show less comments`;
                        button.disabled = false;
                    }
                } else {
                    alert('Error: ' + data.message);
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load more comments. Please try again.');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
        
        function showLessComments(topicId, button) {
            // Find the thread for this topic
            const threadCard = document.querySelector(`[data-topic-id="${topicId}"]`).closest('.thread-card');
            const commentsSection = threadCard.querySelector('.thread-comments');
            const commentItems = commentsSection.querySelectorAll('.comment-item');
            
            // Hide all comments except the first 4
            commentItems.forEach((item, index) => {
                if (index >= 4) {
                    item.style.display = 'none';
                }
            });
            
            // Update button state
            const totalComments = parseInt(button.dataset.total);
            const remainingComments = totalComments - 4;
            
            button.dataset.expanded = 'false';
            button.dataset.offset = '4';
            button.innerHTML = `<i class="fas fa-chevron-down me-1"></i>View ${remainingComments} more comments`;
            
            // Scroll to top of comments section
            commentsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 1) {
                return 'Yesterday';
            } else if (diffDays < 7) {
                return `${diffDays} days ago`;
            } else {
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }
        }
    </script>
</body>
</html>
