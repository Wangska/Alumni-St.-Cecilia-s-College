<?php
// Get user data for navigation
$user = current_user();
$pdo = get_pdo();

// Get alumni profile information for avatar
$alumni = null;
if (isset($user['alumnus_id']) && $user['alumnus_id'] > 0) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM alumnus_bio WHERE id = ?');
        $stmt->execute([$user['alumnus_id']]);
        $alumni = $stmt->fetch();
    } catch (Exception $e) {
        // Ignore error, continue without alumni data
    }
}

// Determine current page for active nav link
$currentPage = basename($_SERVER['PHP_SELF']);
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $pageTitle ?? 'SCC Alumni Portal' ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }
    
    /* Fluid media */
    img, video, canvas, svg {
      max-width: 100%;
      height: auto;
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
    
    .navbar-brand:hover .navbar-brand-text {
      transform: scale(1.02);
      text-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .navbar .nav-link {
      font-weight: 500 !important;
      padding: 0.75rem 1.5rem !important;
      border-radius: 25px !important;
      transition: all 0.3s ease !important;
      margin: 0 0.25rem !important;
      letter-spacing: 0.025em !important;
      font-size: 0.9rem !important;
      color: rgba(255,255,255,0.9) !important;
    }
    
    .navbar .nav-link:hover {
      color: #ffffff !important;
      background: rgba(255,255,255,0.1) !important;
      transform: translateY(-2px) !important;
    }
    
    .navbar .nav-link.active {
      background: rgba(255,255,255,0.15) !important;
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
    }
    
    .navbar-toggler {
      border: 2px solid rgba(255,255,255,0.2) !important;
      border-radius: 12px !important;
      padding: 0.5rem !important;
      transition: all 0.3s ease !important;
    }
    
    .navbar-toggler:hover {
      border-color: rgba(255,255,255,0.4) !important;
      background: rgba(255,255,255,0.1) !important;
    }
    
    .navbar-toggler:focus {
      box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25) !important;
    }
    
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
    }
    
    .main-content {
      padding-top: 2rem;
      padding-bottom: 2rem;
    }
    
    @media (max-width: 992px) {
      .navbar .nav-link {
        padding: 0.5rem 1rem !important;
      }
      .navbar-brand img {
        height: 44px;
      }
      .navbar-brand-text div:first-child {
        font-size: 1rem !important;
      }
      .navbar-brand-text div:last-child {
        font-size: 0.7rem !important;
      }
      .profile-btn span {
        display: none;
      }
      .dropdown-menu {
        max-width: 90vw;
        width: 90vw;
      }
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
          <div style="font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px; margin-top: 2px; color: rgba(255,255,255,0.9);">ST. CECILIA'S COLLEGE</div>
        </div>
      </a>
      
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 0.5rem; transition: all 0.3s ease;">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?= ($currentPath === '/scratch/news/index.php') ? 'active' : '' ?>" href="/scratch/news/index.php">News</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($currentPath === '/scratch/jobs/index.php') ? 'active' : '' ?>" href="/scratch/jobs/index.php">Jobs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($currentPath === '/scratch/events/index.php') ? 'active' : '' ?>" href="/scratch/events/index.php">Events</a>
          </li>
          
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === '/scratch/success-stories/index.php') || ($currentPage === 'create.php' && strpos($_SERVER['REQUEST_URI'], 'success-stories') !== false) ? 'active' : '' ?>" href="/scratch/success-stories/index.php">Success Stories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === '/scratch/testimonials/index.php') || ($currentPage === 'create.php' && strpos($_SERVER['REQUEST_URI'], 'testimonials') !== false) ? 'active' : '' ?>" href="/scratch/testimonials/index.php">Testimonials</a>
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