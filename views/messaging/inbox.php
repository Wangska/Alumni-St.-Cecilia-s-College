<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Alumni Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #ffffff;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
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
        }
        
        .navbar-brand img {
            height: 55px;
            width: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-brand-text {
            color: white;
            font-weight: 700;
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
        
        .message-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .stat-card-small {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }
        
        .stat-card-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .conversation-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        
        .conversation-item:hover {
            background: #fef2f2;
            border-color: #dc2626;
            transform: translateX(5px);
            color: inherit;
        }
        
        .conversation-item.unread {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
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
                        <a class="nav-link" href="/scratch/news/index.php">News</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="/scratch/messaging.php">
                            Messages
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger ms-2" style="font-size: 10px; padding: 3px 8px; border-radius: 10px;"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
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

    <div class="container">
        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card-small" style="border-left: 4px solid #dc2626;">
                    <div style="width: 50px; height: 50px; background: rgba(220, 38, 38, 0.1); color: #dc2626; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <h4 style="color: #dc2626; font-weight: 700; margin-bottom: 5px;"><?= count($conversations) ?></h4>
                        <p style="color: #6b7280; font-size: 13px; margin: 0;">Conversations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-small" style="border-left: 4px solid #059669;">
                    <div style="width: 50px; height: 50px; background: rgba(5, 150, 105, 0.1); color: #059669; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="color: #059669; font-weight: 700; margin-bottom: 5px;"><?= $unreadTotal ?></h4>
                        <p style="color: #6b7280; font-size: 13px; margin: 0;">Unread Messages</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-small" style="border-left: 4px solid #f59e0b;">
                    <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4 style="color: #f59e0b; font-weight: 700; margin-bottom: 5px;"><?= count($conversations) ?></h4>
                        <p style="color: #6b7280; font-size: 13px; margin: 0;">Contacts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Conversations List -->
        <div class="message-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0" style="color: #111827; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-inbox" style="color: #dc2626;"></i>
                    Inbox
                </h5>
                <a href="/scratch/messaging.php?page=compose" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Message
                </a>
            </div>

            <?php if (empty($conversations)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;"></i>
                    <h5 style="color: #6b7280; font-weight: 600;">No messages yet</h5>
                    <p style="color: #9ca3af; margin-bottom: 20px;">Start a conversation by sending a new message</p>
                    <a href="/scratch/messaging.php?page=compose" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Compose Message
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($conversations as $conv): ?>
                    <a href="/scratch/messaging.php?page=conversation&contact_id=<?= $conv['contact_id'] ?>" 
                       class="conversation-item <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Avatar -->
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 18px; flex-shrink: 0;">
                                <?= strtoupper(substr($conv['contact_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0" style="font-weight: 600; color: #111827; font-size: 15px;">
                                        <?= htmlspecialchars($conv['contact_name']) ?>
                                        <?php if ((int)$conv['contact_type'] === 2): ?>
                                            <span class="badge bg-danger ms-2" style="font-size: 10px;">Alumni Officer</span>
                                        <?php endif; ?>
                                        <?php if ($conv['unread_count'] > 0): ?>
                                            <span class="badge bg-success ms-2" style="font-size: 10px;"><?= $conv['unread_count'] ?> new</span>
                                        <?php endif; ?>
                                    </h6>
                                    <small style="color: #9ca3af; font-size: 12px; white-space: nowrap;">
                                        <?php
                                        $date = new DateTime($conv['last_message_date']);
                                        $now = new DateTime();
                                        $diff = $now->diff($date);
                                        
                                        if ($diff->days == 0) {
                                            echo $date->format('g:i A');
                                        } elseif ($diff->days == 1) {
                                            echo 'Yesterday';
                                        } elseif ($diff->days < 7) {
                                            echo $date->format('l');
                                        } else {
                                            echo $date->format('M j');
                                        }
                                        ?>
                                    </small>
                                </div>
                                <p class="mb-0 text-truncate" style="color: #6b7280; font-size: 13px;">
                                    <?= htmlspecialchars(substr($conv['last_message'] ?? 'No messages', 0, 80)) ?>
                                    <?= strlen($conv['last_message'] ?? '') > 80 ? '...' : '' ?>
                                </p>
                            </div>
                            
                            <i class="fas fa-chevron-right" style="color: #d1d5db; font-size: 14px;"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

