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

// Get topic ID
$topicId = $_GET['id'] ?? 0;

// Fetch topic details
$topic = null;
try {
    $stmt = $pdo->prepare("SELECT ft.*, u.name as author_name FROM forum_topics ft LEFT JOIN users u ON ft.user_id = u.id WHERE ft.id = ?");
    $stmt->execute([$topicId]);
    $topic = $stmt->fetch();
} catch (Exception $e) {
    header('Location: index.php');
    exit;
}

if (!$topic) {
    header('Location: index.php');
    exit;
}

// Handle comment submission
if ($_POST && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    if ($comment) {
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_comments (topic_id, user_id, comment, date_created) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$topicId, $user['id'], $comment]);
            header('Location: view.php?id=' . $topicId);
            exit;
        } catch (Exception $e) {
            $error = "Failed to post comment. Please try again. Error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a comment.";
    }
}

// Fetch comments
$comments = [];
try {
    $stmt = $pdo->prepare("SELECT fc.*, u.name as author_name FROM forum_comments fc LEFT JOIN users u ON fc.user_id = u.id WHERE fc.topic_id = ? ORDER BY fc.date_created ASC");
    $stmt->execute([$topicId]);
    $comments = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($topic['title']) ?> - Alumni Forum</title>
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
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            margin-right: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .nav-link {
            color: white !important;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
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

        .topic-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .topic-header {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 2rem;
        }

        .comment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            padding: 1.5rem;
            border-left: 4px solid #8b5cf6;
        }

        .comment-form {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
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

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/scratch/dashboard.php">
                <img src="/scratch/images/scc.png" alt="SCC Logo">
                <div>
                    <div style="font-size: 1.2rem;">ST. CECILIA'S COLLEGE</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">ALUMNI PORTAL</div>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                        <a class="nav-link" href="/scratch/testimonials/index.php">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/success-stories/index.php">Success Stories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/forum/index.php">Forum</a>
                    </li>
                </ul>
                
                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?php if (!empty($alumni['avatar'])): ?>
                            <img src="/scratch/uploads/<?= htmlspecialchars($alumni['avatar']) ?>" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                        <?php else: ?>
                            <i class="fas fa-user me-2"></i>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($user['name']) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/scratch/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="/scratch/profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/scratch/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Forum
            </a>
        </div>

        <!-- Topic Card -->
        <div class="topic-card">
            <div class="topic-header">
                <h1 class="mb-3"><?= htmlspecialchars($topic['title']) ?></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">
                            <i class="fas fa-user me-1"></i>
                            Posted by <?= htmlspecialchars($topic['author_name'] ?? 'Anonymous') ?>
                        </small>
                    </div>
                    <div>
                        <small class="opacity-75">
                            <i class="fas fa-clock me-1"></i>
                            <?= date('M d, Y \a\t g:i A', strtotime($topic['date_created'])) ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <p class="mb-0" style="line-height: 1.8; font-size: 1.1rem;">
                    <?= nl2br(htmlspecialchars($topic['description'])) ?>
                </p>
            </div>
        </div>

        <!-- Comment Form -->
        <div class="comment-form">
            <h4 class="mb-3">
                <i class="fas fa-comment me-2"></i>Add Your Comment
            </h4>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Share your thoughts on this topic..." required style="border-radius: 8px; border: 2px solid #e5e7eb; resize: vertical;"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Post Comment
                    </button>
                </div>
            </form>
        </div>

        <!-- Comments Section -->
        <div class="mb-4">
            <h4 class="mb-3">
                <i class="fas fa-comments me-2"></i>Comments (<?= count($comments) ?>)
            </h4>
            
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="text-dark"><?= htmlspecialchars($comment['author_name'] ?? 'Anonymous') ?></strong>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?= date('M d, Y \a\t g:i A', strtotime($comment['date_created'])) ?>
                            </small>
                        </div>
                        <p class="mb-0" style="line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-comment text-muted" style="font-size: 1.5rem;"></i>
                    </div>
                    <p class="text-muted">No comments yet. Be the first to share your thoughts!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
