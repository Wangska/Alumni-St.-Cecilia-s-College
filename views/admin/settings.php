<?php
$currentPage = 'settings';
$pageTitle = 'Settings';
$title = 'Settings';

ob_start();
?>

<style>
.settings-section {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    width: 100%;
}

.col-lg-6.d-flex {
    min-height: 0;
}

.settings-section .form-modern {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.settings-section .form-modern > button {
    margin-top: auto;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f3f5;
}

.section-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.section-header h5 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
}

.form-modern .form-label {
    font-weight: 600;
    color: #2d3142;
    margin-bottom: 8px;
}

.form-modern .form-control {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-modern .form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
}

.system-stat-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    border-left: 4px solid #dc3545;
}

.system-stat-card h6 {
    color: #6c757d;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.system-stat-card .value {
    font-size: 24px;
    font-weight: 700;
    color: #2d3142;
}
</style>


<div class="row g-4">
    <!-- Account Settings -->
    <div class="col-lg-6 d-flex">
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h5>Account Settings</h5>
            </div>
            
            <form method="POST" action="/scratch/admin.php?page=settings&action=update_account" class="form-modern">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name'] ?? 'Admin') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username'] ?? 'admin') ?>" required>
                    <small class="text-muted">Used for login</small>
                </div>
                
                <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 14px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(220, 53, 69, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.3)';">
                    <i class="fas fa-save me-2"></i>Update Account
                </button>
            </form>
        </div>
    </div>
    
    <!-- Change Password -->
    <div class="col-lg-6 d-flex">
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #ffc107, #ffb300);">
                    <i class="fas fa-key"></i>
                </div>
                <h5>Change Password</h5>
            </div>
            
            <form method="POST" action="/scratch/admin.php?page=settings&action=change_password" class="form-modern">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="new_password" minlength="6" required>
                    <small class="text-muted">Minimum 6 characters</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" minlength="6" required>
                </div>
                
                <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #ffc107, #ffb300); color: white; border: none; border-radius: 12px; padding: 14px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(255, 193, 7, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(255, 193, 7, 0.3)';">
                    <i class="fas fa-key me-2"></i>Change Password
                </button>
            </form>
        </div>
    </div>
    
    <!-- System Statistics -->
    <div class="col-lg-6 d-flex">
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h5>System Statistics</h5>
            </div>
            
            <div class="system-stat-card">
                <h6><i class="fas fa-users me-2"></i>Total Alumni</h6>
                <div class="value"><?= number_format($systemStats['total_alumni'] ?? 0) ?></div>
            </div>
            
            <div class="system-stat-card">
                <h6><i class="fas fa-user-check me-2"></i>Verified Alumni</h6>
                <div class="value"><?= number_format($systemStats['verified_alumni'] ?? 0) ?></div>
            </div>
            
            <div class="system-stat-card">
                <h6><i class="fas fa-calendar-alt me-2"></i>Total Events</h6>
                <div class="value"><?= number_format($systemStats['total_events'] ?? 0) ?></div>
            </div>
            
            <div class="system-stat-card">
                <h6><i class="fas fa-bullhorn me-2"></i>Total Announcements</h6>
                <div class="value"><?= number_format($systemStats['total_announcements'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    
    <!-- Database Management -->
    <div class="col-lg-6 d-flex">
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="fas fa-database"></i>
                </div>
                <h5>Database Management</h5>
            </div>
            
            <div style="flex: 1;">
                <div class="system-stat-card">
                    <h6><i class="fas fa-images me-2"></i>Gallery Images</h6>
                    <div class="value"><?= number_format($systemStats['gallery_images'] ?? 0) ?></div>
                </div>
                
                <div class="system-stat-card">
                    <h6><i class="fas fa-book-open me-2"></i>Total Courses</h6>
                    <div class="value"><?= number_format($systemStats['total_courses'] ?? 0) ?></div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Database Backup</strong><br>
                    <small>Create a backup of your database to prevent data loss.</small>
                </div>
            </div>
            
            <form method="POST" action="/scratch/admin.php?page=settings&action=backup_db" style="margin-top: 20px;">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #198754, #146c43); color: white; border: none; border-radius: 12px; padding: 14px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(25, 135, 84, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(25, 135, 84, 0.3)';">
                    <i class="fas fa-download me-2"></i>Download Database Backup
                </button>
            </form>
        </div>
    </div>
    
    <!-- System Information -->
    <div class="col-lg-12">
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h5>System Information</h5>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="system-stat-card">
                        <h6><i class="fas fa-server me-2"></i>PHP Version</h6>
                        <div class="value" style="font-size: 18px;"><?= phpversion() ?></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="system-stat-card">
                        <h6><i class="fas fa-calendar me-2"></i>Server Time</h6>
                        <div class="value" style="font-size: 18px;"><?= date('g:i A') ?></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="system-stat-card">
                        <h6><i class="fas fa-clock me-2"></i>Server Date</h6>
                        <div class="value" style="font-size: 18px;"><?= date('M d, Y') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
