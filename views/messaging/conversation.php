<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation - Alumni Portal</title>
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
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .message-bubble {
            animation: fadeInUp 0.3s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        
        .message-thread {
            max-height: 600px;
            overflow-y: auto;
            scroll-behavior: smooth;
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
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 20px;">
                    <?= strtoupper(substr($contact['name'] ?? 'U', 0, 1)) ?>
                </div>
                <?= htmlspecialchars($contact['name']) ?>
                <?php if ((int)$contact['type'] === 2): ?>
                    <span class="badge bg-danger" style="font-size: 12px;">Alumni Officer</span>
                <?php endif; ?>
            </h2>
            <p style="color: #6b7280; margin: 5px 0 0 62px; font-size: 14px;">
                @<?= htmlspecialchars($contact['username']) ?>
                <?php if ($contact['email']): ?>
                    | <?= htmlspecialchars($contact['email']) ?>
                <?php endif; ?>
            </p>
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

        <!-- Unified Chat Box -->
        <div class="message-card" style="border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); overflow: hidden;">
            <!-- Messages Thread -->
            <div class="message-thread" style="padding: 30px; max-height: 500px; overflow-y: auto; background: #f9fafb;">
                <?php if (empty($messages)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-comments" style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;"></i>
                        <p style="color: #6b7280;">No messages yet. Start the conversation!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php 
                        $isMe = (int)$msg['sender_id'] === (int)$userId;
                        $messageDate = new DateTime($msg['date_created']);
                        ?>
                        
                        <div class="message-bubble" style="margin-bottom: 20px; display: flex; <?= $isMe ? 'justify-content: flex-end;' : 'justify-content: flex-start;' ?>">
                            <div style="max-width: 70%;">
                                <!-- Sender Info -->
                                <div style="margin-bottom: 5px; font-size: 12px; color: #9ca3af; <?= $isMe ? 'text-align: right;' : 'text-align: left;' ?>">
                                    <strong style="color: #6b7280;"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                                    <span>• <?= $messageDate->format('M j, Y g:i A') ?></span>
                                </div>
                                
                            <!-- Message Bubble -->
                            <div style="background: <?= $isMe ? 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)' : '#f3f4f6' ?>; color: <?= $isMe ? 'white' : '#111827' ?>; padding: 15px 20px; border-radius: <?= $isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px' ?>; box-shadow: 0 2px 8px rgba(0,0,0,0.1); word-wrap: break-word;">
                                    <div style="line-height: 1.5;">
                                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Reply Form - Integrated -->
            <form method="POST" action="/scratch/messaging.php?page=conversation&contact_id=<?= $contact['id'] ?>&action=send" style="background: white; padding: 20px; border-top: 2px solid #e5e7eb;">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="receiver_id" value="<?= $contact['id'] ?>">

                <div style="position: relative; background: white; border: 2px solid #e5e7eb; border-radius: 25px; transition: all 0.3s ease;" class="message-input-box">
                    <textarea class="form-control" name="message" rows="2" placeholder="Type your message here..." required style="border: none; border-radius: 25px; padding: 15px 70px 15px 20px; font-size: 14px; resize: none; background: transparent; box-shadow: none;" onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>
                    <button type="submit" class="btn btn-primary" style="position: absolute; right: 8px; bottom: 8px; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0; box-shadow: 0 2px 10px rgba(220, 38, 38, 0.3); transition: all 0.3s ease;" title="Send Message">
                        <i class="fas fa-paper-plane" style="font-size: 16px;"></i>
                    </button>
                </div>
                <small class="text-muted d-block mt-2 text-center" style="font-size: 11px;">
                    <i class="fas fa-info-circle me-1"></i>Press Enter to send • Shift+Enter for new line
                </small>
            </form>
        </div>
    </div>

    <style>
    .message-input-box:focus-within {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .message-input-box .form-control:focus {
        outline: none;
        box-shadow: none !important;
    }

    .message-input-box button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4) !important;
    }

    .message-thread::-webkit-scrollbar {
        width: 8px;
    }

    .message-thread::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .message-thread::-webkit-scrollbar-thumb {
        background: #dc2626;
        border-radius: 10px;
    }

    .message-thread::-webkit-scrollbar-thumb:hover {
        background: #991b1b;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto-scroll to bottom
    document.addEventListener('DOMContentLoaded', function() {
        const thread = document.querySelector('.message-thread');
        if (thread) {
            thread.scrollTop = thread.scrollHeight;
        }
    });
    </script>
</body>
</html>

