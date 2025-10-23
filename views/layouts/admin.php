<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - SCC Alumni</title>
    
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
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
            background: linear-gradient(180deg, #1a1d29 0%, #2d3142 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-bottom: 3px solid rgba(255,255,255,0.1);
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
            font-size: 13px;
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
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 10px 0;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(220, 53, 69, 0.6);
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 53, 69, 0.8);
        }
        
        .menu-item {
            padding: 14px 25px;
            margin: 4px 12px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            position: relative;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }
        
        .menu-item.active {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.4);
        }
        
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
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
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
            margin: 20px 20px;
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
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 2px solid rgba(220, 53, 69, 0.1);
        }
        
        .top-navbar h4 {
            margin: 0;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
        
        .notification-icon {
            position: relative;
            color: #666;
            font-size: 20px;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
            z-index: 10;
        }
        
        /* Notification Dropdown Styles */
        .notification-container {
            position: relative;
            display: inline-block;
        }
        
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.08);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 10px;
        }
        
        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px 12px 0 0;
        }
        
        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #2d3142;
            font-size: 16px;
        }
        
        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }
        
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }
        
        .notification-item:hover {
            background: rgba(220, 53, 69, 0.05);
        }
        
        .notification-item.unread {
            background: rgba(220, 53, 69, 0.08);
            border-left: 4px solid #dc3545;
        }
        
        .notification-item.unread::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
        }
        
        .notification-title {
            font-weight: 600;
            color: #2d3142;
            font-size: 14px;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        
        .notification-message {
            color: #666;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        
        .notification-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #999;
        }
        
        .notification-type {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .notification-type.info {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .notification-type.success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .notification-type.warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .notification-type.danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .notification-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            text-align: center;
            background: rgba(248, 249, 250, 0.5);
            border-radius: 0 0 12px 12px;
        }
        
        .notification-loading {
            padding: 40px 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #999;
        }
        
        .notification-empty i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 16px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 10px 18px;
            background: rgba(220, 53, 69, 0.05);
            border-radius: 50px;
            transition: all 0.3s ease;
            border: 1px solid rgba(220, 53, 69, 0.1);
        }
        
        .user-profile:hover {
            background: rgba(220, 53, 69, 0.1);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }
        
        .user-profile img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid #dc3545;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }
        
        .user-profile span {
            color: #2d3142;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Content Area */
        .content-area {
            padding: 0;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
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
        
        .stat-card.red .icon { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .stat-card.blue .icon { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
        .stat-card.green .icon { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
        .stat-card.orange .icon { background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%); }
        
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .stat-card p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="/scratch/images/scc.png" alt="SCC Logo">
            <h5><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?></h5>
            <p>Administrator</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="/scratch/admin.php?page=dashboard" class="menu-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="/scratch/admin.php?page=alumni" class="menu-item <?= ($currentPage ?? '') === 'alumni' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Alumni Management</span>
            </a>
            
            <a href="/scratch/admin.php?page=events" class="menu-item <?= ($currentPage ?? '') === 'events' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            
            <a href="/scratch/admin.php?page=announcements" class="menu-item <?= ($currentPage ?? '') === 'announcements' ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            
            <a href="/scratch/admin.php?page=courses" class="menu-item <?= ($currentPage ?? '') === 'courses' ? 'active' : '' ?>">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            
            <a href="/scratch/admin.php?page=careers" class="menu-item <?= ($currentPage ?? '') === 'careers' ? 'active' : '' ?>">
                <i class="fas fa-briefcase"></i>
                <span>Job Postings</span>
            </a>
            
            <a href="/scratch/admin.php?page=galleries" class="menu-item <?= ($currentPage ?? '') === 'galleries' ? 'active' : '' ?>">
                <i class="fas fa-images"></i>
                <span>Gallery</span>
            </a>
            
            <a href="/scratch/admin.php?page=forum" class="menu-item <?= ($currentPage ?? '') === 'forum' ? 'active' : '' ?>">
                <i class="fas fa-comments"></i>
                <span>Forum Topics</span>
            </a>
            
        <a href="/scratch/admin.php?page=success-stories" class="menu-item <?= ($currentPage ?? '') === 'success-stories' ? 'active' : '' ?>">
            <i class="fas fa-star"></i>
            <span>Success Stories</span>
        </a>
        <a href="/scratch/admin.php?page=testimonials" class="menu-item <?= ($currentPage ?? '') === 'testimonials' ? 'active' : '' ?>">
            <i class="fas fa-quote-left"></i>
            <span>Testimonials</span>
        </a>
            
            <div class="menu-divider"></div>
            
            <a href="/scratch/admin.php?page=users" class="menu-item <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i>
                <span>User Accounts</span>
            </a>
            
            <a href="/scratch/admin.php?page=settings" class="menu-item <?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <a href="/scratch/admin.php?page=logs" class="menu-item <?= ($currentPage ?? '') === 'logs' ? 'active' : '' ?>">
                <i class="fas fa-history"></i>
                <span>Activity Logs</span>
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
            <h4><?= $pageTitle ?? 'Dashboard' ?></h4>
            <div class="navbar-right">
                <?php 
                // Get unread notification count from the new notification system
                require_once __DIR__ . '/../../inc/logger.php';
                
                // Get comprehensive notification count
                $notifications = [];
                try {
                    // Get system logs
                    $logs = ActivityLogger::getRecentLogs(50);
                    foreach ($logs as $log) {
                        $logId = 'log_' . $log['id'];
                        $isRead = isset($_SESSION['read_notifications']) && in_array($logId, $_SESSION['read_notifications']);
                        if (!$isRead) {
                            $notifications[] = $logId;
                        }
                    }
                    
                    // Get database notifications
                    $userId = $_SESSION['user']['id'];
                    $dbNotifications = NotificationManager::getUserNotifications($userId, 20);
                    foreach ($dbNotifications as $notification) {
                        if (!$notification['is_read']) {
                            $notifications[] = 'notif_' . $notification['id'];
                        }
                    }
                } catch (Exception $e) {
                    // Fallback to original method
                    $notifications = [];
                }
                
                $unreadCount = count($notifications);
                
                // Get user status (for regular users, admins are always connected)
                $userType = $_SESSION['user']['type'] ?? 0;
                $isAdmin = $userType == 1;
                $status = 'Connected';
                $statusColor = '#28a745';
                $statusIcon = 'fa-check-circle';
                ?>
                
                <!-- Status Indicator -->
                <div style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(40, 167, 69, 0.1); border-radius: 20px; border: 1px solid rgba(40, 167, 69, 0.3);">
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <span style="font-size: 11px; color: #666; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Status</span>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 8px; height: 8px; background: <?= $statusColor ?>; border-radius: 50%; box-shadow: 0 0 6px <?= $statusColor ?>;"></div>
                            <span style="font-size: 13px; color: <?= $statusColor ?>; font-weight: 600;"><?= $status ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Bell with Dropdown -->
                <div class="notification-container">
                    <div class="notification-icon" id="notificationBell" style="text-decoration: none; color: inherit;" title="<?= $unreadCount > 0 ? $unreadCount . ' unread notification' . ($unreadCount > 1 ? 's' : '') : 'No unread notifications' ?>">
                        <i class="fas fa-bell"></i>
                        <?php if ($unreadCount > 0): ?>
                            <span class="notification-badge"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn" style="display: none;">Mark All Read</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-loading">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading notifications...
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="/scratch/admin.php?page=logs" class="btn btn-sm btn-outline-secondary">View All Logs</a>
                        </div>
                    </div>
                </div>
                <div class="user-profile">
                    <img src="/scratch/images/scc.png" alt="User">
                    <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?></span>
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
            <div class="toast align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="successToast" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3); border-left: 4px solid #10b981 !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #065f46; font-size: 14px; margin-bottom: 2px;">Success!</div>
                        <div style="color: #047857; font-size: 13px;"><?= htmlspecialchars($_SESSION['success']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast" aria-label="Close" style="font-size: 12px;"></button>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['deleted'])): ?>
            <div class="toast align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="deletedToast" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(220, 53, 69, 0.3); border-left: 4px solid #dc3545 !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #991b1b; font-size: 14px; margin-bottom: 2px;">Deleted!</div>
                        <div style="color: #b91c1c; font-size: 13px;"><?= htmlspecialchars($_SESSION['deleted']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast" aria-label="Close" style="font-size: 12px;"></button>
                </div>
            </div>
            <?php unset($_SESSION['deleted']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['warning'])): ?>
            <div class="toast align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="warningToast" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3); border-left: 4px solid #f59e0b !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #92400e; font-size: 14px; margin-bottom: 2px;">Warning!</div>
                        <div style="color: #b45309; font-size: 13px;"><?= htmlspecialchars($_SESSION['warning']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast" aria-label="Close" style="font-size: 12px;"></button>
                </div>
            </div>
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="toast align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast" style="min-width: 350px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 8px 32px rgba(220, 53, 69, 0.3); border-left: 4px solid #dc3545 !important; border-radius: 12px;">
                <div class="d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; color: #991b1b; font-size: 14px; margin-bottom: 2px;">Error!</div>
                        <div style="color: #b91c1c; font-size: 13px;"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-dark ms-2" data-bs-dismiss="toast" aria-label="Close" style="font-size: 12px;"></button>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Auto-hide Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notification Dropdown Functionality
            const notificationBell = document.getElementById('notificationBell');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationList = document.getElementById('notificationList');
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            
            let isDropdownOpen = false;
            
            // Toggle dropdown
            notificationBell.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (isDropdownOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
                    closeDropdown();
                }
            });
            
            function openDropdown() {
                notificationDropdown.classList.add('show');
                isDropdownOpen = true;
                loadNotifications();
            }
            
            function closeDropdown() {
                notificationDropdown.classList.remove('show');
                isDropdownOpen = false;
            }
            
            // Load notifications from system logs
            function loadNotifications() {
                notificationList.innerHTML = `
                    <div class="notification-loading">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Loading notifications...
                    </div>
                `;
                
                fetch('/scratch/api/notifications.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayNotifications(data.notifications);
                        } else {
                            notificationList.innerHTML = `
                                <div class="notification-empty">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <div>Error loading notifications</div>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notificationList.innerHTML = `
                            <div class="notification-empty">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>Error loading notifications</div>
                            </div>
                        `;
                    });
            }
            
            function displayNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="notification-empty">
                            <i class="fas fa-bell-slash"></i>
                            <div>No notifications</div>
                        </div>
                    `;
                    markAllReadBtn.style.display = 'none';
                    return;
                }
                
                const unreadCount = notifications.filter(n => !n.is_read).length;
                if (unreadCount > 0) {
                    markAllReadBtn.style.display = 'inline-block';
                } else {
                    markAllReadBtn.style.display = 'none';
                }
                
                notificationList.innerHTML = notifications.map(notification => `
                    <div class="notification-item ${!notification.is_read ? 'unread' : ''}" data-id="${notification.id}">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-meta">
                            <span class="notification-type ${notification.type}">${notification.type}</span>
                            <span class="notification-time">${formatTime(notification.created_at)}</span>
                        </div>
                    </div>
                `).join('');
                
                // Add click handlers to notification items
                notificationList.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.dataset.id;
                        markAsRead(notificationId);
                        this.classList.remove('unread');
                    });
                });
            }
            
            // Mark notification as read
            function markAsRead(notificationId) {
                fetch('/scratch/api/notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'mark_read',
                        notification_id: notificationId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the visual state immediately
                        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.classList.remove('unread');
                        }
                        
                        // Update badge count
                        updateNotificationBadge();
                        
                        // Reload notifications to get updated state
                        if (isDropdownOpen) {
                            loadNotifications();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            // Mark all as read
            markAllReadBtn.addEventListener('click', function() {
                fetch('/scratch/api/notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'mark_all_read'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationList.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('unread');
                        });
                        markAllReadBtn.style.display = 'none';
                        updateNotificationBadge();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
            
            // Update notification badge
            function updateNotificationBadge() {
                fetch('/scratch/api/notifications.php?action=get_count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const badge = document.querySelector('.notification-badge');
                            if (data.count > 0) {
                                if (!badge) {
                                    const newBadge = document.createElement('span');
                                    newBadge.className = 'notification-badge';
                                    notificationBell.appendChild(newBadge);
                                }
                                document.querySelector('.notification-badge').textContent = data.count;
                                // Update tooltip
                                notificationBell.title = data.count + ' unread notification' + (data.count > 1 ? 's' : '');
                            } else {
                                if (badge) {
                                    badge.remove();
                                }
                                // Update tooltip
                                notificationBell.title = 'No unread notifications';
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            // Format time
            function formatTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = now - date;
                
                if (diff < 60000) { // Less than 1 minute
                    return 'Just now';
                } else if (diff < 3600000) { // Less than 1 hour
                    return Math.floor(diff / 60000) + 'm ago';
                } else if (diff < 86400000) { // Less than 1 day
                    return Math.floor(diff / 3600000) + 'h ago';
                } else {
                    return Math.floor(diff / 86400000) + 'd ago';
                }
            }
            
            // Auto-refresh notifications every 10 seconds for better responsiveness
            setInterval(updateNotificationBadge, 10000);
            
            // Also refresh when the page becomes visible (user switches back to tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    updateNotificationBadge();
                }
            });
            // Auto-hide toasts after 5 seconds
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

