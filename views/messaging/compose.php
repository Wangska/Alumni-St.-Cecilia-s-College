<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose Message - Alumni Portal</title>
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
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            padding: 14px 35px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        
        #recipientPreview {
            animation: fadeInDown 0.3s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
                            <?php
                            // Get unread message count
                            $unreadMessages = 0;
                            try {
                                $stmt = $pdo->prepare('SELECT COUNT(*) as unread FROM messages WHERE receiver_id = ? AND is_read = 0');
                                $stmt->execute([$user['id']]);
                                $unreadMessages = (int)$stmt->fetch(PDO::FETCH_ASSOC)['unread'];
                            } catch (Exception $e) {
                                // Ignore
                            }
                            if ($unreadMessages > 0): ?>
                                <span class="badge bg-danger ms-2" style="font-size: 10px; padding: 3px 8px; border-radius: 10px;"><?= $unreadMessages ?></span>
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
        <!-- Header -->
        <div class="mb-4">
            <a href="/scratch/messaging.php" style="color: #dc2626; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
                <i class="fas fa-arrow-left"></i>
                Back to Messages
            </a>
            <h2 style="color: #111827; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-pen" style="color: #dc2626;"></i>
                Compose New Message
            </h2>
            <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Send a message to another alumni or the Alumni Officer</p>
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

        <!-- Compose Form -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="message-card">
                    <form method="POST" action="/scratch/messaging.php?page=compose&action=send" id="composeForm">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                        <!-- Recipient -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                                <i class="fas fa-user me-2" style="color: #dc2626;"></i>
                                To <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="receiver_id" required id="recipientSelect" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 15px; font-size: 14px;">
                                <option value="">-- Select Recipient --</option>
                                <optgroup label="Alumni Officers">
                                    <?php foreach ($users as $u): if ((int)$u['type'] === 2): ?>
                                        <option value="<?= $u['id'] ?>" 
                                                data-name="<?= htmlspecialchars($u['name']) ?>"
                                                data-email="<?= htmlspecialchars($u['email'] ?? '') ?>"
                                                data-type="Alumni Officer">
                                            <?= htmlspecialchars($u['name']) ?> (@<?= htmlspecialchars($u['username']) ?>)
                                        </option>
                                    <?php endif; endforeach; ?>
                                </optgroup>
                                <optgroup label="Alumni">
                                    <?php foreach ($users as $u): if ((int)$u['type'] === 3): ?>
                                        <option value="<?= $u['id'] ?>" 
                                                data-name="<?= htmlspecialchars($u['name']) ?>"
                                                data-email="<?= htmlspecialchars($u['email'] ?? '') ?>"
                                                data-type="Alumni">
                                            <?= htmlspecialchars($u['name']) ?> (@<?= htmlspecialchars($u['username']) ?>)
                                        </option>
                                    <?php endif; endforeach; ?>
                                </optgroup>
                            </select>
                            
                            <!-- Recipient Preview -->
                            <div id="recipientPreview" class="mt-3" style="display: none; background: #fef2f2; border: 2px solid #fecaca; border-radius: 10px; padding: 15px;">
                                <div class="d-flex align-items-center gap-3">
                                    <div id="recipientAvatar" style="width: 45px; height: 45px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 16px;"></div>
                                    <div>
                                        <div style="font-weight: 600; color: #111827; font-size: 14px;" id="recipientName"></div>
                                        <div style="font-size: 12px; color: #6b7280;" id="recipientInfo"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                                <i class="fas fa-comment-dots me-2" style="color: #dc2626;"></i>
                                Your Message <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" name="message" rows="10" placeholder="Type your message here..." required id="messageContent" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 15px; font-size: 14px; resize: vertical;"></textarea>
                            <small class="text-muted" id="charCount">0 characters</small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" style="padding: 14px 35px; border-radius: 10px; font-weight: 600; border: 2px solid #d1d5db;">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <a href="/scratch/messaging.php" class="btn btn-secondary" style="padding: 14px 35px; border-radius: 10px; font-weight: 600;">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tips -->
                <div class="message-card" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #dc2626; padding: 25px;">
                    <h6 style="color: #991b1b; font-weight: 700; margin-bottom: 15px;">
                        <i class="fas fa-lightbulb me-2"></i>Quick Tips
                    </h6>
                    <ul style="color: #6b7280; font-size: 13px; margin: 0; padding-left: 20px; line-height: 1.8;">
                        <li>Message the Alumni Officer for account issues or general concerns</li>
                        <li>Connect with fellow alumni to network and share experiences</li>
                        <li>Messages are private and can only be seen by you and the recipient</li>
                        <li>You'll receive notifications when someone replies to your message</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Recipient selection
    document.getElementById('recipientSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const preview = document.getElementById('recipientPreview');
        
        if (option.value) {
            const name = option.dataset.name;
            const email = option.dataset.email;
            const type = option.dataset.type;
            const initial = name.charAt(0).toUpperCase();
            
            document.getElementById('recipientAvatar').textContent = initial;
            document.getElementById('recipientName').textContent = name;
            document.getElementById('recipientInfo').textContent = type + (email ? ' • ' + email : '');
            
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });

    // Character counter
    document.getElementById('messageContent').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count + ' character' + (count !== 1 ? 's' : '');
    });

    // Reset handler
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('recipientPreview').style.display = 'none';
        document.getElementById('charCount').textContent = '0 characters';
    });
    </script>
</body>
</html>

