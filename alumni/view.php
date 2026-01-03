<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_admin();
$pdo = get_pdo();
$id = (int)($_GET['id'] ?? 0);

// Get alumni details with course name
$stmt = $pdo->prepare('
    SELECT ab.*, c.course 
    FROM alumnus_bio ab
    LEFT JOIN courses c ON c.id = ab.course_id
    WHERE ab.id = ?
');
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) { 
    http_response_code(404); 
    exit('Alumni not found'); 
}

// Get user account details if exists
$userStmt = $pdo->prepare('SELECT * FROM users WHERE alumnus_id = ?');
$userStmt->execute([$id]);
$user = $userStmt->fetch();

// Determine where to go back
$backUrl = '/scratch/admin.php?page=users';
if (isset($_GET['from']) && $_GET['from'] === 'alumni') {
    $backUrl = '/scratch/admin.php?page=alumni';
}

$currentPage = 'users';
$pageTitle = 'View Alumni Details';
$title = 'View Alumni Details';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - SCC Alumni</title>
    
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
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .view-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 40px;
            margin: 0 auto;
            max-width: 1000px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .view-header {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            padding: 24px 30px;
            border-radius: 16px;
            margin: -40px -40px 30px -40px;
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }
        
        .view-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 24px;
        }
        
        .avatar-display {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            border: 3px solid #dee2e6;
            margin-bottom: 30px;
        }
        
        .avatar-image {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.25);
        }
        
        .avatar-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-image i {
            font-size: 64px;
            color: #dee2e6;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3142;
            margin-top: 35px;
            margin-bottom: 20px;
            padding: 12px 20px;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(220, 38, 38, 0.05));
            border-left: 4px solid #dc2626;
            border-radius: 8px;
        }
        
        .section-title i {
            color: #dc2626;
        }
        
        .info-group {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        
        .info-value {
            font-size: 16px;
            color: #2d3142;
            font-weight: 500;
            padding: 12px 18px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }
        
        .info-value.empty {
            color: #adb5bd;
            font-style: italic;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
        }
        
        .status-verified {
            background: linear-gradient(135deg, #198754, #146c43);
            color: white;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border: none;
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
            background: linear-gradient(135deg, #991b1b, #7f1d1d);
            color: white;
        }
        
        .btn-secondary {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 2px solid #6c757d;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="view-card">
            <div class="view-header">
                <h4><i class="fas fa-user me-2"></i>Alumni Information</h4>
            </div>
            
            <!-- Avatar Display -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="avatar-display">
                        <div class="avatar-image">
                            <?php if (!empty($row['avatar'])): ?>
                                <img src="/scratch/uploads/<?= htmlspecialchars($row['avatar']) ?>" alt="Avatar">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <h5 class="mb-0">
                            <?= htmlspecialchars(trim(($row['firstname'] ?? '') . ' ' . ($row['middlename'] ?? '') . ' ' . ($row['lastname'] ?? ''))) ?>
                        </h5>
                    </div>
                </div>
            </div>
            
            <!-- Personal Information -->
            <h5 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h5>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">First Name</div>
                        <div class="info-value"><?= htmlspecialchars($row['firstname'] ?? '') ?: '<span class="empty">Not provided</span>' ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">Middle Name</div>
                        <div class="info-value <?= empty($row['middlename']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($row['middlename'] ?? '') ?: 'Not provided' ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">Last Name</div>
                        <div class="info-value"><?= htmlspecialchars($row['lastname'] ?? '') ?: '<span class="empty">Not provided</span>' ?></div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">Gender</div>
                        <div class="info-value <?= empty($row['gender']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($row['gender'] ?? '') ?: 'Not provided' ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">Batch Year</div>
                        <div class="info-value">
                            <?= htmlspecialchars((string)($row['batch'] ?? '')) ?: '<span class="empty">Not provided</span>' ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <div class="info-label">Course</div>
                        <div class="info-value <?= empty($row['course']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($row['course'] ?? '') ?: 'Not provided' ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <h5 class="section-title"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <?php if (!empty($row['email'])): ?>
                                <i class="fas fa-envelope me-2 text-danger"></i>
                                <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($row['email']) ?>
                                </a>
                            <?php else: ?>
                                <span class="empty">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value <?= empty($row['contact']) ? 'empty' : '' ?>">
                            <?php if (!empty($row['contact'])): ?>
                                <i class="fas fa-phone me-2 text-danger"></i>
                                <?= htmlspecialchars($row['contact']) ?>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <div class="info-group">
                        <div class="info-label">Address</div>
                        <div class="info-value <?= empty($row['address']) ? 'empty' : '' ?>">
                            <?php if (!empty($row['address'])): ?>
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                <?= nl2br(htmlspecialchars($row['address'])) ?>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Connected To</div>
                        <div class="info-value <?= empty($row['connected_to']) ? 'empty' : '' ?>">
                            <?= !empty($row['connected_to']) ? nl2br(htmlspecialchars($row['connected_to'])) : 'Not provided' ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-label">Account Status</div>
                        <div class="info-value">
                            <?php if ((int)($row['status'] ?? 0) === 1): ?>
                                <span class="status-badge status-verified">
                                    <i class="fas fa-check-circle me-1"></i>Verified
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock me-1"></i>Pending Verification
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Account Information (if exists) -->
            <?php if ($user): ?>
                <h5 class="section-title"><i class="fas fa-user-lock me-2"></i>User Account</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Username</div>
                            <div class="info-value">
                                <i class="fas fa-user-circle me-2 text-danger"></i>
                                <?= htmlspecialchars($user['username'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Account Created</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-plus me-2 text-danger"></i>
                                <?= isset($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : 'N/A' ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="<?= $backUrl ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <a href="/scratch/alumni/edit.php?id=<?= $id ?><?= isset($_GET['from']) ? '&from=' . htmlspecialchars($_GET['from']) : '' ?>" 
                   class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Information
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

