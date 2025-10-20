<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();
$user = current_user();

// Get alumni profile information
$pdo = get_pdo();
$alumnusId = $user['alumnus_id'] ?? 0;

// If alumnus_id is 0, try to get it from the database
if ($alumnusId == 0) {
    try {
        $stmt = $pdo->prepare('SELECT alumnus_id FROM users WHERE id = ?');
        $stmt->execute([$user['id']]);
        $userData = $stmt->fetch();
        if ($userData && $userData['alumnus_id']) {
            $alumnusId = (int)$userData['alumnus_id'];
            // Update session
            $_SESSION['user']['alumnus_id'] = $alumnusId;
            $user['alumnus_id'] = $alumnusId;
        }
    } catch (Exception $e) {
        // Ignore error, continue with 0
    }
}

$alumni = null;

if ($alumnusId > 0) {
    $stmt = $pdo->prepare('SELECT ab.*, c.course FROM alumnus_bio ab LEFT JOIN courses c ON ab.course_id = c.id WHERE ab.id = ?');
    $stmt->execute([$alumnusId]);
    $alumni = $stmt->fetch();
}

// Get recent announcements
$stmt = $pdo->prepare('SELECT * FROM announcements ORDER BY date_created DESC LIMIT 3');
$stmt->execute();
$announcements = $stmt->fetchAll();

// Get upcoming events
$stmt = $pdo->prepare('SELECT * FROM events WHERE schedule >= CURDATE() ORDER BY schedule ASC LIMIT 3');
$stmt->execute();
$events = $stmt->fetchAll();

// Get available jobs
$stmt = $pdo->prepare('SELECT * FROM careers ORDER BY date_created DESC LIMIT 6');
$stmt->execute();
$jobs = $stmt->fetchAll();

// Get success stories
$stmt = $pdo->prepare('
    SELECT ss.*, u.username, ab.firstname, ab.lastname 
    FROM success_stories ss 
    LEFT JOIN users u ON ss.user_id = u.id 
    LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
    WHERE ss.status = 1 
    ORDER BY ss.created DESC 
    LIMIT 6
');
$stmt->execute();
$successStories = $stmt->fetchAll();

// Get testimonials
$stmt = $pdo->prepare('
    SELECT t.*, u.username, ab.firstname, ab.lastname 
    FROM testimonials t 
    LEFT JOIN users u ON t.user_id = u.id 
    LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
    WHERE t.status = 1 
    ORDER BY t.created DESC 
    LIMIT 6
');
$stmt->execute();
$testimonials = $stmt->fetchAll();

        // Get events using your existing table structure
        try {
            $stmt = $pdo->prepare('
                SELECT e.*, 
                       COUNT(ec.id) as participant_count,
                       CASE WHEN EXISTS(
                           SELECT 1 FROM event_commits ec2 
                           WHERE ec2.event_id = e.id AND ec2.user_id = ?
                       ) THEN 1 ELSE 0 END as is_registered
                FROM events e 
                LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                GROUP BY e.id 
                ORDER BY e.schedule ASC 
                LIMIT 3
            ');
            $stmt->execute([$user['id']]);
            $events = $stmt->fetchAll();
        } catch (Exception $e) {
            $events = [];
        }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alumni Dashboard - St. Cecilia's College</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
    
    .navbar-brand:hover .navbar-brand-text {
      transform: scale(1.02);
      text-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
    
    .profile-dropdown {
      position: relative;
    }
    
    .profile-btn {
      background: rgba(255,255,255,0.1);
      border: 2px solid rgba(255,255,255,0.2);
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 25px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 500;
    }
    
    .profile-btn:hover {
      background: rgba(255,255,255,0.2);
      border-color: rgba(255,255,255,0.3);
      transform: translateY(-2px);
    }
    
    .profile-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255,255,255,0.3);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: all 0.3s ease;
    }
    
    .profile-btn:hover .profile-avatar {
      border-color: rgba(255,255,255,0.5);
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
      transform: scale(1.05);
    }

    /* Enhanced Dropdown Styling */
    .dropdown-menu {
      animation: slideDown 0.3s ease-out;
    }

    .dropdown-item:hover {
      background: linear-gradient(135deg, #f3f4f6, #e5e7eb) !important;
      transform: translateX(4px);
      color: #1f2937 !important;
    }

    .dropdown-item i {
      transition: all 0.3s ease;
    }

    .dropdown-item:hover i {
      transform: scale(1.1);
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .dropdown-menu {
      background: white;
      border: 1px solid #e5e7eb;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 0.5rem;
      margin-top: 0.5rem;
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
    
    /* Navbar Toggler Enhancement */
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
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .nav-link {
        padding: 0.75rem 1.25rem !important;
        font-size: 0.8rem;
        margin: 0.25rem 0;
        text-align: center;
      }
      
      .profile-btn {
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        margin: 0.5rem auto;
        display: flex;
        justify-content: center;
      }
      
      .navbar-brand img {
        height: 45px;
      }
      
      .navbar-brand-text {
        font-size: 0.8rem;
      }
      
      .navbar-brand-text div:first-child {
        font-size: 0.85rem;
      }
      
      .navbar-brand-text div:last-child {
        font-size: 0.65rem;
      }
    }
    
    @media (max-width: 576px) {
      .navbar-brand {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .navbar-brand img {
        height: 40px;
        margin-bottom: 0.25rem;
      }
      
      .navbar-brand-text {
        text-align: left;
      }
    }
    
    .hero-section {
      background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('/scratch/images/scc1.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 70vh;
      display: flex;
      align-items: center;
      position: relative;
    }
    
    .hero-content {
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      border: 1px solid rgba(255,255,255,0.2);
    }
    
    .welcome-text {
      font-family: 'Brush Script MT', cursive;
      font-size: 4rem;
      color: #dc2626;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    
    .subtitle {
      font-size: 1.5rem;
      color: #374151;
      font-weight: 500;
      margin-bottom: 2rem;
    }
    
    .stats-card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      border: 1px solid #e5e7eb;
      transition: all 0.3s ease;
      height: 100%;
    }
    
    .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: white;
      margin-bottom: 1rem;
    }
    
    .stats-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }
    
    .stats-label {
      color: #6b7280;
      font-weight: 500;
      font-size: 1rem;
    }
    
    .content-card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      border: 1px solid #e5e7eb;
      height: 100%;
    }
    
    .content-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #f3f4f6;
    }
    
    .content-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1f2937;
      margin: 0;
    }
    
    .content-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: white;
    }
    
    .announcement-item, .event-item {
      padding: 1rem;
      border-radius: 12px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }
    
    .announcement-item:hover, .event-item:hover {
      background: #f1f5f9;
      transform: translateX(5px);
    }
    
    .announcement-title, .event-title {
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }
    
    .announcement-date, .event-date {
      color: #6b7280;
      font-size: 0.875rem;
    }
    
    .btn-modern {
      background: linear-gradient(135deg, #dc2626, #991b1b);
      border: none;
      border-radius: 25px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      color: white;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
      color: white;
    }
    
    .profile-info {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid #cbd5e1;
    }
    
    .profile-avatar-large {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid white;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      margin: 0 auto 1.5rem;
      display: block;
    }
    
    .profile-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1f2937;
      text-align: center;
      margin-bottom: 0.5rem;
    }
    
    .profile-details {
      color: #6b7280;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    
    .profile-badge {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-block;
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
          <div style="font-size: 1.1rem; font-weight: 700; line-height: 1.2; color: white;">ST. CECILIA'S COLLEGE</div>
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
              <a class="nav-link" href="/scratch/testimonials/index.php">Testimonials</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/success-stories/index.php">Success Stories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/forum/index.php">Forum</a>
            </li>
            <?php if (isset($user['type']) && $user['type'] == 1): ?>
            <li class="nav-item">
              <a class="nav-link" href="/scratch/success-stories/admin.php">
                <i class="fas fa-cogs me-2"></i>Admin
              </a>
            </li>
            <?php endif; ?>
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

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="hero-content text-center">
            <h1 class="welcome-text">Welcome Back, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>!</h1>
            <p class="subtitle">Stay connected with your St. Cecilia's College community</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- News and Announcements Section -->
  <section id="news" class="py-5" style="background: #f8f9fa;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="text-center mb-5">
            <h2 class="display-4 fw-bold" style="color: #dc2626; font-family: 'Times New Roman', serif;">NEWS & ANNOUNCEMENTS</h2>
            <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
          </div>
        </div>
      </div>
      
      <div class="row g-4">
        <?php if (!empty($announcements)): ?>
          <?php foreach ($announcements as $announcement): ?>
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius: 12px; overflow: hidden;">
                <!-- Image Section -->
                <div style="height: 200px; overflow: hidden; background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                  <?php if (!empty($announcement['image'])): ?>
                    <img src="/scratch/uploads/<?= htmlspecialchars($announcement['image']) ?>" alt="Announcement" class="w-100 h-100" style="object-fit: cover;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                      <i class="fas fa-bullhorn text-muted" style="font-size: 3rem;"></i>
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Content Section -->
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                  <h5 class="card-title fw-bold mb-3" style="color: #1f2937; font-size: 1.25rem;">
                    <?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?>
                  </h5>
                  <p class="card-text text-muted mb-4 flex-grow-1" style="line-height: 1.6;">
                    <?= htmlspecialchars(substr($announcement['content'] ?? '', 0, 120)) ?>...
                  </p>
                  <button class="btn w-100 mt-auto" style="background: #dc2626; color: white; border: none; padding: 12px; font-weight: 600; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#dashAnnouncementModal<?= $announcement['id'] ?>">
                    Read More
                  </button>
                </div>
              </div>
            </div>
            <!-- Dashboard Announcement Modal -->
            <div class="modal fade" id="dashAnnouncementModal<?= $announcement['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.3);">
                  <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); color:#fff; border:none; border-radius:16px 16px 0 0;">
                    <h5 class="modal-title mb-0" style="font-weight:700; font-size:20px;"><?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="padding:24px;">
                    <?php if (!empty($announcement['image'])): ?>
                      <img src="/scratch/uploads/<?= htmlspecialchars($announcement['image']) ?>" alt="<?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?>" class="img-fluid mb-3" style="border-radius:12px; width:100%; max-height:420px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="text-muted mb-3"><i class="fas fa-calendar me-1"></i><?= date('F d, Y - g:i A', strtotime($announcement['date_created'] ?? 'now')) ?></div>
                    <div style="color:#374151; line-height:1.7; white-space:pre-wrap;">
                      <?= nl2br(htmlspecialchars($announcement['content'] ?? '')) ?>
                    </div>
                  </div>
                  <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e5e7eb; border-radius:0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="text-center py-5">
              <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-bullhorn text-muted" style="font-size: 2rem;"></i>
              </div>
              <h5 class="text-muted">No announcements yet</h5>
              <p class="text-muted">Check back later for updates</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="text-center mt-4">
        <a href="/scratch/news/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
          <i class="fas fa-arrow-right me-2"></i>View All News
        </a>
      </div>
    </div>
  </section>

  <!-- Upcoming Events Section -->
  <section id="events" class="py-5" style="background: #f8f9fa;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="text-center mb-5">
            <h2 class="display-4 fw-bold" style="color: #dc2626; font-family: 'Times New Roman', serif;">UPCOMING EVENTS</h2>
            <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <?php if (empty($events)): ?>
          <div class="col-12">
            <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
              <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                <i class="fas fa-calendar-alt" style="font-size:2rem; color:#dc2626;"></i>
              </div>
              <h4 class="text-muted">No upcoming events</h4>
              <p class="text-muted">Check back later for new alumni events</p>
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($events as $event): ?>
            <?php
              $eventDate = new DateTime($event['schedule']);
              $now = new DateTime();
              $isUpcoming = $eventDate > $now;
              $limit = $event['participant_limit'] ?? ($event['max_participants'] ?? null);
              $participantCount = (int)($event['participant_count'] ?? 0);
              $isFull = $limit && $participantCount >= (int)$limit;
              $statusText = !$isUpcoming ? 'Past Event' : ($isFull ? 'Event Full' : 'Registration Open');
              $statusClass = !$isUpcoming ? 'bg-secondary' : ($isFull ? 'bg-danger' : 'bg-success');
            ?>
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius: 12px; overflow: hidden;">
                <!-- Image Section -->
                <div style="height: 200px; overflow: hidden; background: linear-gradient(135deg, #7f1d1d, #991b1b);">
                  <?php if (!empty($event['banner']) && file_exists(__DIR__ . '/uploads/' . $event['banner'])): ?>
                    <img src="/scratch/uploads/<?= htmlspecialchars($event['banner']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-100 h-100" style="object-fit: cover;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                      <div class="text-center">
                        <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: .5rem;"></i>
                        <h6 class="mb-0"><?= htmlspecialchars($event['title']) ?></h6>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Content Section -->
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold mb-0" style="color: #1f2937; font-size: 1.25rem;">
                      <?= htmlspecialchars($event['title']) ?>
                    </h5>
                    <span class="badge <?= $statusClass ?> text-white"><?= $statusText ?></span>
                  </div>
                  <p class="text-muted small mb-2">
                    <i class="fas fa-calendar me-1"></i><?= $eventDate->format('M d, Y \a\t g:i A') ?>
                  </p>
                  <p class="card-text text-muted mb-3" style="line-height: 1.6; min-height: 64px;">
                    <?= htmlspecialchars(substr((string)($event['content'] ?? ''), 0, 120)) ?><?= strlen((string)($event['content'] ?? '')) > 120 ? '...' : '' ?>
                  </p>

                  <div class="mb-3" style="min-height: 72px;">
                    <small class="text-muted">
                      <i class="fas fa-users me-1"></i>
                      <strong><?= $participantCount ?></strong>
                      <?php if ($limit): ?>
                        / <strong><?= (int)$limit ?></strong> participants
                        <span class="badge bg-info ms-1" style="font-size: .75rem;"><?= max((int)$limit - $participantCount, 0) ?> left</span>
                      <?php else: ?>
                        participants
                      <?php endif; ?>
                    </small>

                    <?php if ($limit): ?>
                      <?php
                        $percentage = $participantCount > 0 ? ($participantCount / (int)$limit) * 100 : 0;
                        $barColor = $percentage >= 100 ? '#7f1d1d' : ($percentage >= 80 ? '#b45309' : '#dc2626');
                      ?>
                      <div class="mt-1 d-flex align-items-center" style="gap: 8px; margin-top: 6px;">
                        <div style="background:#f0f0f0; height: 6px; border-radius: 3px; overflow:hidden; flex: 1;">
                          <div style="background: <?= $barColor ?>; height: 100%; width: <?= min($percentage, 100) ?>%; transition: width .3s;"></div>
                        </div>
                        <small class="text-muted" style="width: 40px; text-align: right; flex-shrink: 0;"><?= round($percentage, 1) ?>%</small>
                      </div>
                    <?php else: ?>
                      <div style="height: 14px;"></div>
                    <?php endif; ?>
                  </div>

                  <!-- Participants and capacity sit directly above the button to align with other sections -->
                  <div class="mt-auto">
                    <?php if ($isUpcoming && !$isFull): ?>
                      <form method="POST" action="/scratch/events/index.php" class="w-100">
                        <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
                        <?php if (!empty($event['is_registered'])): ?>
                          <button type="submit" name="action" value="leave" class="btn btn-outline-danger w-100" style="border-radius: 8px;">
                            <i class="fas fa-user-minus me-1"></i>Leave Event
                          </button>
                        <?php else: ?>
                          <button type="submit" name="action" value="join" class="btn w-100" style="border-radius: 8px; background:#dc2626; color:#fff; border:none;">
                            <i class="fas fa-user-plus me-1"></i>Join Event
                          </button>
                        <?php endif; ?>
                      </form>
                    <?php elseif ($isFull): ?>
                      <button class="btn btn-secondary w-100" style="border-radius: 8px;" disabled>
                        <i class="fas fa-users me-1"></i>Event Full
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="text-center mt-4">
        <a href="/scratch/events/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
          <i class="fas fa-arrow-right me-2"></i>View All Events
        </a>
      </div>
    </div>
  </section>

  <!-- Available Jobs Section -->
  <section id="jobs" class="py-5" style="background: #f8f9fa;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="text-center mb-5">
            <h2 class="display-4 fw-bold" style="color: #dc2626; font-family: 'Times New Roman', serif;">AVAILABLE JOBS</h2>
            <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
          </div>
        </div>
      </div>
      
      <div class="row g-4">
        <?php if (!empty($jobs)): ?>
          <?php foreach ($jobs as $job): ?>
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius: 12px; overflow: hidden;">
                <!-- Image Section -->
                <div style="height: 200px; overflow: hidden; background: linear-gradient(135deg, #7f1d1d, #991b1b);">
                  <?php if (!empty($job['company_logo']) && file_exists(__DIR__ . '/uploads/' . $job['company_logo'])): ?>
                    <img src="/scratch/uploads/<?= htmlspecialchars($job['company_logo']) ?>" alt="<?= htmlspecialchars($job['company'] ?? 'Company') ?> Logo" class="w-100 h-100" style="object-fit: contain; background: #fff;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                      <i class="fas fa-briefcase text-white" style="font-size: 4rem;"></i>
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Content Section -->
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                  <h5 class="card-title fw-bold mb-3" style="color: #1f2937; font-size: 1.25rem;">
                    <?= htmlspecialchars($job['job_title'] ?? ($job['title'] ?? '')) ?>
                  </h5>
                  <p class="card-text text-muted mb-4 flex-grow-1" style="line-height: 1.6;">
                    <?= htmlspecialchars(substr((string)($job['description'] ?? ''), 0, 120)) ?>...
                  </p>
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                      <i class="fas fa-map-marker-alt me-1"></i>
                      <?= htmlspecialchars((string)($job['location'] ?? 'Location not specified')) ?>
                    </small>
                    <small class="text-muted">
                      <i class="fas fa-calendar me-1"></i>
                      <?= date('M d, Y', strtotime($job['date_created'])) ?>
                    </small>
                  </div>
                  <div class="d-flex gap-2 mt-auto">
                    <button class="btn flex-fill" style="background: #6b7280; color: white; border: none; padding: 12px; font-weight: 600; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#jobModal<?= (int)$job['id'] ?>">Read More</button>
                    <a href="/scratch/jobs/index.php" class="btn flex-fill" style="background: #dc2626; color: white; border: none; padding: 12px; font-weight: 600; border-radius: 8px;">Apply Now</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Job Details Modal -->
            <div class="modal fade" id="jobModal<?= (int)$job['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.3);">
                  <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); color:#fff; border:none; border-radius:16px 16px 0 0;">
                    <h5 class="modal-title mb-0" style="font-weight:700; font-size:20px;">
                      <?= htmlspecialchars($job['job_title'] ?? ($job['title'] ?? 'Job')) ?> at <?= htmlspecialchars($job['company'] ?? 'Company') ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="padding:24px;">
                    <?php if (!empty($job['company_logo']) && file_exists(__DIR__ . '/uploads/' . $job['company_logo'])): ?>
                      <div class="text-center mb-3">
                        <img src="/scratch/uploads/<?= htmlspecialchars($job['company_logo']) ?>" alt="<?= htmlspecialchars($job['company'] ?? 'Company') ?> Logo" style="max-height: 120px; object-fit: contain; background:#fff; border-radius:12px; padding:8px;">
                      </div>
                    <?php endif; ?>
                    <div class="text-muted mb-3">
                      <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars((string)($job['location'] ?? 'Location not specified')) ?>
                      <span class="mx-2">•</span>
                      <i class="fas fa-calendar me-1"></i><?= date('F d, Y - g:i A', strtotime($job['date_created'] ?? 'now')) ?>
                    </div>
                    <div style="color:#374151; line-height:1.7; white-space:pre-wrap;">
                      <?= nl2br(htmlspecialchars((string)($job['description'] ?? ''))) ?>
                    </div>
                  </div>
                  <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e5e7eb; border-radius:0 0 16px 16px;">
                    <a href="/scratch/jobs/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 10px 20px; font-weight: 600; border-radius: 8px;">Apply Now</a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="text-center py-5">
              <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-briefcase text-muted" style="font-size: 2rem;"></i>
              </div>
              <h5 class="text-muted">No job openings available</h5>
              <p class="text-muted">Check back later for new opportunities</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="text-center mt-4">
        <a href="/scratch/jobs/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
          <i class="fas fa-arrow-right me-2"></i>View All Jobs
        </a>
      </div>
    </div>
  </section>

  <!-- Success Stories Section -->
  <section id="success-stories" class="py-5" style="background: #f8f9fa;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="text-center mb-5">
            <h2 class="display-4 fw-bold" style="color: #dc2626; font-family: 'Times New Roman', serif;">SUCCESS STORIES</h2>
            <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
        <?php if (!empty($successStories)): ?>
        <!-- Moved CTA to bottom of section for consistent layout -->
        <?php endif; ?>
          </div>
        </div>
      </div>
      
      <div class="row g-4">
        <?php if (empty($successStories)): ?>
          <div class="col-12">
            <div class="text-center py-5">
              <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
              <h4 class="text-muted mt-3">No Success Stories Yet</h4>
              <p class="text-muted">Be the first to share your success story!</p>
              <a href="success-stories/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
                <i class="fas fa-arrow-right me-2"></i>View All Stories
              </a>
            </div>
          </div>
        <?php else: ?>
          <?php 
            // Show up to 6 stories, 3 per slide
            $storiesItems = array_slice($successStories, 0, 6);
            $storySlides = array_chunk($storiesItems, 3);
            $gradients = [
              'linear-gradient(135deg, #f59e0b, #d97706)',
              'linear-gradient(135deg, #10b981, #059669)',
              'linear-gradient(135deg, #3b82f6, #1d4ed8)',
              'linear-gradient(135deg, #8b5cf6, #7c3aed)',
              'linear-gradient(135deg, #ef4444, #dc2626)',
              'linear-gradient(135deg, #06b6d4, #0891b2)'
            ];
            $icons = ['fa-star', 'fa-trophy', 'fa-medal', 'fa-crown', 'fa-gem', 'fa-rocket'];
          ?>
          <style>
            .stories-carousel .control-btn { width: 44px; height: 44px; border-radius: 50%; background: #dc2626; color: #fff; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(220,38,38,.35); border: 2px solid rgba(255,255,255,.75); }
            .stories-carousel .carousel-control-prev-icon, .stories-carousel .carousel-control-next-icon { display: none; }
          </style>
          <div id="storiesCarousel" class="carousel slide stories-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php foreach ($storySlides as $slideIndex => $slideStories): ?>
              <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
                <div class="row g-4">
                  <?php foreach ($slideStories as $index => $story): ?>
                  <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius: 12px; overflow: hidden;">
                      <div style="height: 480px; overflow: hidden; background: <?= $gradients[$index % count($gradients)] ?>;">
                        <?php if (!empty($story['image'])): ?>
                          <img src="/scratch/<?= htmlspecialchars($story['image']) ?>" alt="<?= htmlspecialchars($story['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        <?php else: ?>
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas <?= $icons[$index % count($icons)] ?> text-white" style="font-size: 4rem;"></i>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <h5 class="card-title fw-bold mb-3" style="color: #1f2937; font-size: 1.25rem;">
                          <?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>
                        </h5>
                        <p class="card-text text-muted mb-4 flex-grow-1" style="line-height: 1.6;">
                          <?= htmlspecialchars(substr($story['content'], 0, 120)) ?><?= strlen($story['content']) > 120 ? '...' : '' ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                          <small class="text-muted"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($story['created'])) ?></small>
                          <a href="success-stories/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 8px 16px; font-weight: 600; border-radius: 8px; font-size: 0.9rem;">Read More</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#storiesCarousel" data-bs-slide="prev"><span class="control-btn" aria-hidden="true"><i class="fas fa-chevron-left"></i></span><span class="visually-hidden">Previous</span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#storiesCarousel" data-bs-slide="next"><span class="control-btn" aria-hidden="true"><i class="fas fa-chevron-right"></i></span><span class="visually-hidden">Next</span></button>
          </div>
        <?php endif; ?>
        <?php if (!empty($successStories)): ?>
        <div class="text-center mt-4">
          <a href="success-stories/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
            <i class="fas fa-arrow-right me-2"></i>View All Stories
          </a>
          <?php if (isset($user['type']) && $user['type'] == 1): ?>
            <a href="success-stories/admin.php" class="btn ms-3" style="background: #6b7280; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
              <i class="fas fa-cogs me-2"></i>Manage Stories
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-5" style="background: #f8f9fa;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="text-center mb-5">
            <h2 class="display-4 fw-bold" style="color: #dc2626; font-family: 'Times New Roman', serif;">TESTIMONIALS</h2>
            <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
            <?php if (!empty($testimonials)): ?>
            <!-- CTA moved to bottom of section -->
            <?php endif; ?>
          </div>
        </div>
      </div>
      
      <?php if (empty($testimonials)): ?>
        <div class="text-center py-5">
          <i class="fas fa-quote-left text-muted" style="font-size: 4rem;"></i>
          <h4 class="text-muted mt-3">No Testimonials Yet</h4>
          <p class="text-muted">Be the first to share your inspiring testimonial!</p>
          <a href="testimonials/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
            <i class="fas fa-arrow-right me-2"></i>View All Testimonials
          </a>
        </div>
      <?php else: ?>
        <?php 
          // Show up to 6 testimonials, 3 per slide
          $items = array_slice($testimonials, 0, 6);
          $slides = array_chunk($items, 3);
          $gradients = [
            'linear-gradient(135deg, #3b82f6, #1d4ed8)',
            'linear-gradient(135deg, #10b981, #059669)',
            'linear-gradient(135deg, #8b5cf6, #7c3aed)'
          ];
          if (isset($_GET['debug'])) {
            echo "<!-- Debug: Total testimonials: " . count($testimonials) . ", Items sliced: " . count($items) . ", Slides: " . count($slides) . " -->";
          }
        ?>
        <style>
          /* Testimonials carousel controls */
          .testimonial-carousel .carousel-control-prev,
          .testimonial-carousel .carousel-control-next {
            width: auto;
            opacity: 1;
            transition: transform 0.2s ease, opacity 0.2s ease;
          }
          .testimonial-carousel .carousel-control-prev:hover,
          .testimonial-carousel .carousel-control-next:hover {
            transform: scale(1.05);
          }
          .testimonial-carousel .control-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #dc2626; /* SCC red */
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.35);
            border: 2px solid rgba(255,255,255,0.75);
          }
          .testimonial-carousel .control-btn i { font-size: 18px; }
          .testimonial-carousel .carousel-control-prev-icon,
          .testimonial-carousel .carousel-control-next-icon { display: none; }
        </style>
        <div id="testimonialsCarousel" class="carousel slide testimonial-carousel" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($slides as $slideIndex => $slideItems): ?>
            <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
              <div class="row g-4">
                <?php foreach ($slideItems as $index => $testimonial): $gradient = $gradients[$index % count($gradients)]; ?>
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="card border-0 shadow h-100" style="border-radius: 12px; overflow: hidden;">
                    <div style="height: 480px; overflow: hidden; background: <?= $gradient ?>;">
                      <?php if (!empty($testimonial['graduation_photo'])): ?>
                        <img src="/scratch/<?= htmlspecialchars($testimonial['graduation_photo']) ?>" alt="<?= htmlspecialchars($testimonial['author_name']) ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                      <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100"><i class="fas fa-user-graduate text-white" style="font-size: 4rem;"></i></div>
                      <?php endif; ?>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                      <h5 class="fw-bold mb-2" style="color:#1f2937;"><?= htmlspecialchars($testimonial['author_name']) ?></h5>
                      <p class="text-muted small mb-2"><?= htmlspecialchars($testimonial['course']) ?>, Class of <?= (int)($testimonial['graduation_year'] ?? 0) ?></p>
                      <p class="mb-0" style="line-height:1.7; font-style: italic; color:#4b5563;">"<?= htmlspecialchars($testimonial['quote']) ?>"</p>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
            <span class="control-btn" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
            <span class="control-btn" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
        <div class="text-center mt-4">
          <a href="testimonials/index.php" class="btn" style="background: #dc2626; color: white; border: none; padding: 12px 24px; font-weight: 600; border-radius: 8px;">
            <i class="fas fa-arrow-right me-2"></i>View All Testimonials
          </a>
        </div>
      <?php endif; ?>
    </div>
  </section>


  <!-- Footer -->
  <footer id="contact" class="py-5" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
    <div class="container">
      <div class="row g-4 text-white">
        <div class="col-md-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="/scratch/images/scc.png" alt="SCC Logo" class="h-16 w-16 object-contain bg-white rounded-circle p-2" style="height: 4rem; width: 4rem;">
            <h5 class="mb-0 fw-bold">St. Cecilia's College</h5>
          </div>
          <p class="small mb-0">Cebu South National Highway, Ward II, Minglanilla, Cebu</p>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#about" class="text-white text-decoration-none hover:text-gray-200">About Us</a></li>
            <li class="mb-2"><a href="#testimonials" class="text-white text-decoration-none hover:text-gray-200">Testimonials</a></li>
            <li class="mb-2"><a href="#success-stories" class="text-white text-decoration-none hover:text-gray-200">Success Stories</a></li>
            <li class="mb-2"><a href="/scratch/dashboard.php" class="text-white text-decoration-none hover:text-gray-200">Dashboard</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Contact Us</h5>
          <ul class="list-unstyled small">
            <li class="mb-2 d-flex align-items-start gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
              </svg>
              Cebu South National Highway, Ward II, Minglanilla, Cebu
            </li>
            <li class="mb-2 d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
              </svg>
              (032) 123-4567
            </li>
            <li class="mb-2 d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
              </svg>
              alumni@stcecilia.edu.ph
            </li>
          </ul>
        </div>
      </div>
      <hr class="my-4 border-white opacity-25">
      <div class="text-center small text-white">
        <p class="mb-0" style="color:#ffffff;">&copy; <?php echo date('Y'); ?> St. Cecilia's College - Cebu, Inc. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <style>
    @media (min-width: 1200px) {
      .display-4 {
        font-size: 2.25rem;
      }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


