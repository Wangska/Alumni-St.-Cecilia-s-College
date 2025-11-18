<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Alumni Officer Dashboard' ?> - SCC Alumni</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f5f5f5;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: #ffffff;
            box-shadow: 4px 0 15px rgba(0,0,0,0.08);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid #e5e7eb;
        }
        
        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            background: #dc2626;
            border-bottom: 2px solid #b91c1c;
        }
        
        .sidebar-header img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .sidebar-header h5 {
            color: white;
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        
        .sidebar-header p {
            color: rgba(255,255,255,0.95);
            margin: 0;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
            height: calc(100vh - 180px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 10px 0;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #b91c1c;
        }
        
        .menu-item {
            padding: 14px 25px;
            margin: 4px 12px;
            color: #6b7280;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            position: relative;
        }
        
        .menu-item:hover {
            background: #fef2f2;
            color: #dc2626;
            transform: translateX(3px);
        }
        
        .menu-item.active {
            background: #dc2626;
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: #991b1b;
            border-radius: 0 4px 4px 0;
        }
        
        .menu-item i {
            width: 28px;
            margin-right: 14px;
            font-size: 17px;
        }
        
        .menu-item span {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .menu-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 15px 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 30px 40px;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 2px solid #e5e7eb;
            border-radius: 0;
            margin-bottom: 30px;
        }
        
        .sidebar-toggle {
            display: none;
            border: none;
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            border-radius: 10px;
            padding: 8px 10px;
        }
        
        .top-navbar h4 {
            margin: 0;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 24px;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 10px 18px;
            background: rgba(220, 38, 38, 0.05);
            border-radius: 50px;
            transition: all 0.3s ease;
            border: 1px solid rgba(220, 38, 38, 0.1);
        }
        
        .user-profile:hover {
            background: rgba(220, 38, 38, 0.1);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }
        
        .user-profile img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid #dc2626;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }
        
        .user-profile span {
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Content Area */
        .content-area {
            padding: 0;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
            border-color: #dc2626;
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        
        .stat-card p {
            color: #6b7280;
            margin: 0;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Content Hover Effects */
        .btn-action {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary {
            background: #dc2626;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: #b91c1c;
            color: white;
        }
        
        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background: #991b1b;
            color: white;
        }
        
        .btn-success {
            background: #059669;
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            background: #047857;
            color: white;
        }
        
        .btn-add-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-add-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
        }
        
        /* Table Row Hover */
        table tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        table tbody tr:hover {
            background: #fef2f2 !important;
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
        }
        
        /* Card Hover Effects */
        .event-card,
        .announcement-card,
        .activity-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .event-card:hover,
        .announcement-card:hover,
        .activity-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15);
            border-color: #dc2626;
        }
        
        /* List Item Hover */
        .list-group-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .list-group-item:hover {
            background: #fef2f2 !important;
            transform: translateX(3px);
            border-left: 4px solid #dc2626 !important;
        }
        
        /* Clickable Elements */
        .clickable,
        [onclick],
        a:not(.menu-item) {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Stat Card Small Hover */
        .stat-card-small {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
            border-left-width: 6px !important;
        }
        
        /* Badge Hover */
        .badge {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .badge:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="/scratch/images/scc.png" alt="SCC Logo">
            <h5><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Alumni Officer') ?></h5>
            <p>Alumni Officer</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="/scratch/alumni-officer.php?page=dashboard" class="menu-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="/scratch/alumni-officer.php?page=verify-alumni" class="menu-item <?= ($currentPage ?? '') === 'verify-alumni' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i>
                <span>Verify Alumni</span>
            </a>
            
            <a href="/scratch/alumni-officer.php?page=announcements" class="menu-item <?= ($currentPage ?? '') === 'announcements' ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            
            <a href="/scratch/alumni-officer.php?page=events" class="menu-item <?= ($currentPage ?? '') === 'events' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Events & Activities</span>
            </a>
            
            <a href="/scratch/alumni-officer.php?page=messages" class="menu-item <?= ($currentPage ?? '') === 'messages' || ($currentPage ?? '') === 'conversation' || ($currentPage ?? '') === 'compose-message' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
                <?php
                $unreadCount = 0;
                try {
                    $userId = $_SESSION['user']['id'] ?? 0;
                    $pdo = get_pdo();
                    $stmt = $pdo->prepare('SELECT COUNT(*) as unread FROM messages WHERE receiver_id = ? AND is_read = 0');
                    $stmt->execute([$userId]);
                    $unreadCount = (int)$stmt->fetch(PDO::FETCH_ASSOC)['unread'];
                } catch (Exception $e) {
                    // Ignore
                }
                if ($unreadCount > 0): ?>
                    <span class="badge bg-danger ms-auto" style="font-size: 10px; padding: 3px 7px; border-radius: 10px;"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="/scratch/alumni-officer.php?page=reports" class="menu-item <?= ($currentPage ?? '') === 'reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports & Statistics</span>
            </a>
            
            <a href="/scratch/alumni-officer.php?page=moderate" class="menu-item <?= ($currentPage ?? '') === 'moderate' ? 'active' : '' ?>">
                <i class="fas fa-comments"></i>
                <span>Forum</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="/scratch/logout.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div style="display:flex; align-items:center; gap:12px;">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 style="margin:0;"><?= $pageTitle ?? 'Dashboard' ?></h4>
            </div>
            <div class="navbar-right">
                <div class="user-profile">
                    <img src="/scratch/images/scc.png" alt="User">
                    <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Alumni Officer') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <?= $content ?? '' ?>
        </div>
    </div>
    
    <!-- Toast Notification Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="toast align-items-center border-0 show" role="alert" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3); border-left: 4px solid #10b981 !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check" style="color: white; font-size: 18px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #065f46; font-size: 14px; margin-bottom: 2px;">Success!</div>
                        <div style="color: #047857; font-size: 13px;"><?= htmlspecialchars($_SESSION['success']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast"></button>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="toast align-items-center border-0 show" role="alert" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(220, 53, 69, 0.3); border-left: 4px solid #dc3545 !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 18px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #991b1b; font-size: 14px; margin-bottom: 2px;">Error!</div>
                        <div style="color: #b91c1c; font-size: 13px;"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast"></button>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for mobile
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Auto-hide toasts
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000);
            });
        });
    </script>
</body>
</html>

