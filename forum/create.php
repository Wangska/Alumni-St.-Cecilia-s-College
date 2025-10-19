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

// Handle form submission
$error = '';
$success = '';

if ($_POST) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($title)) {
        $error = "Please enter a topic title.";
    } elseif (empty($description)) {
        $error = "Please enter a topic description.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_topics (title, description, user_id, date_created) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$title, $description, $user['id']]);
            
            $topicId = $pdo->lastInsertId();
            header('Location: view.php?id=' . $topicId);
            exit;
        } catch (Exception $e) {
            $error = "Failed to create topic. Please try again. Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Topic - Alumni Forum</title>
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

        .create-form {
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

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
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
                        <a class="nav-link" href="/scratch/dashboard.php">Dashboard</a>
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

        <!-- Section Header -->
        <div class="section-header text-center">
            <h1 class="section-title">CREATE NEW TOPIC</h1>
            <div class="section-underline"></div>
            <p class="lead text-muted mt-3">Start a new discussion with your fellow alumni</p>
        </div>

        <!-- Create Form -->
        <div class="create-form">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">
                        <i class="fas fa-heading me-2"></i>Topic Title
                    </label>
                    <input type="text" class="form-control" id="title" name="title" 
                           placeholder="Enter a clear and descriptive title for your topic" 
                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                    <div class="form-text">Choose a title that clearly describes what you want to discuss.</div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">
                        <i class="fas fa-comment me-2"></i>Topic Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="6" 
                              placeholder="Describe your topic in detail. What would you like to discuss with your fellow alumni?" 
                              required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    <div class="form-text">Provide a detailed description to help others understand your topic and join the discussion.</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Create Topic
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
