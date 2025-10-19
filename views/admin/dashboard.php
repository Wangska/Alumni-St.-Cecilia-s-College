<?php
$currentPage = 'dashboard';
$pageTitle = 'Dashboard';
$title = 'Admin Dashboard';

ob_start();
?>

<style>
/* Modern Dashboard Styles */
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

.stat-card-modern {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
}

.stat-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.stat-card-modern.red {
    --card-color-1: #dc3545;
    --card-color-2: #c82333;
}

.stat-card-modern.blue {
    --card-color-1: #0d6efd;
    --card-color-2: #0a58ca;
}

.stat-card-modern.green {
    --card-color-1: #198754;
    --card-color-2: #146c43;
}

.stat-card-modern.purple {
    --card-color-1: #6f42c1;
    --card-color-2: #5a32a3;
}

.stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 16px;
    background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
    color: white;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 4px;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.content-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    margin-bottom: 24px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f3f5;
}

.card-header-modern h5 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
}

.card-header-modern .icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    margin-right: 12px;
}

.btn-modern {
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modern-primary {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
    color: white;
}

.btn-modern-outline {
    background: transparent;
    color: #dc3545;
    border: 2px solid #dc3545;
}

.btn-modern-outline:hover {
    background: #dc3545;
    color: white;
}

.card-content-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.announcement-item {
    padding: 20px;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    border-left: 4px solid #dc3545;
}

.announcement-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.announcement-item:last-child {
    margin-bottom: 0;
}

.event-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.event-item:hover {
    background: #e9ecef;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.event-item:last-child {
    margin-bottom: 0;
}

.event-date-box {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.event-date-box .day {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
}

.event-date-box .month {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
</style>

<!-- Statistics Cards -->
<div class="dashboard-stats">
    <div class="stat-card-modern red">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['total_alumni'] ?? 0) ?></div>
        <div class="stat-label">Total Alumni</div>
    </div>
    
    <div class="stat-card-modern blue">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['upcoming_events'] ?? 0) ?></div>
        <div class="stat-label">Upcoming Events</div>
    </div>
    
    <div class="stat-card-modern green">
        <div class="stat-icon">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['total_announcements'] ?? 0) ?></div>
        <div class="stat-label">Announcements</div>
    </div>
    
    <div class="stat-card-modern purple">
        <div class="stat-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['total_courses'] ?? 0) ?></div>
        <div class="stat-label">Courses</div>
    </div>
</div>

<!-- Recent Announcements & Upcoming Events -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center">
                    <div class="icon-badge">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h5>Recent Announcements</h5>
                </div>
                <a href="/scratch/admin.php?page=announcements" class="btn btn-modern btn-modern-outline btn-sm">
                    View All
                </a>
            </div>
            
            <div class="card-content-wrapper">
                <?php if (!empty($announcements)): ?>
                    <?php foreach (array_slice($announcements, 0, 4) as $announcement): ?>
                        <div class="announcement-item">
                            <div>
                                <h6 class="mb-2 fw-bold text-dark"><?= htmlspecialchars($announcement['title'] ?? '') ?></h6>
                                <p class="mb-2 text-muted small">
                                    <?= htmlspecialchars(substr(strip_tags($announcement['content'] ?? ''), 0, 120)) ?>...
                                </p>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('M d, Y - g:i A', strtotime($announcement['date_created'] ?? 'now')) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No announcements yet.</p>
                        <a href="/scratch/announcements/new.php" class="btn btn-modern btn-modern-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create First Announcement
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center">
                    <div class="icon-badge" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>Upcoming Events</h5>
                </div>
                <a href="/scratch/admin.php?page=events" class="btn btn-modern btn-modern-outline btn-sm">
                    View All
                </a>
            </div>
            
            <div class="card-content-wrapper">
                <?php if (!empty($events)): ?>
                    <?php foreach (array_slice($events, 0, 4) as $event): ?>
                        <?php
                        $eventDate = date_create($event['schedule'] ?? 'now');
                        ?>
                        <div class="event-item">
                            <div class="event-date-box">
                                <div class="day"><?= $eventDate->format('d') ?></div>
                                <div class="month"><?= $eventDate->format('M') ?></div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($event['title'] ?? '') ?></h6>
                                <p class="mb-0 text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= $eventDate->format('g:i A') ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No upcoming events.</p>
                        <a href="/scratch/events/new.php" class="btn btn-modern btn-modern-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="content-card mt-4">
    <div class="card-header-modern">
        <div class="d-flex align-items-center">
            <div class="icon-badge" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                <i class="fas fa-history"></i>
            </div>
            <h5>Recent Activity</h5>
        </div>
        <a href="/scratch/admin.php?page=logs" class="btn btn-modern btn-modern-outline btn-sm">
            View All Logs
        </a>
    </div>
    
    <?php 
    // Debug: Check if recentLogs exists
    $recentLogs = $recentLogs ?? [];
    ?>
    
    <?php if (!empty($recentLogs)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-bottom: 2px solid #dee2e6;">
                    <tr>
                        <th style="padding: 16px; font-weight: 600; color: #2d3142; border: none;">Type</th>
                        <th style="padding: 16px; font-weight: 600; color: #2d3142; border: none;">User</th>
                        <th style="padding: 16px; font-weight: 600; color: #2d3142; border: none;">Action</th>
                        <th style="padding: 16px; font-weight: 600; color: #2d3142; border: none;">Module</th>
                        <th style="padding: 16px; font-weight: 600; color: #2d3142; border: none;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentLogs as $log): 
                        $iconClass = match($log['action_type']) {
                            'login' => 'fa-sign-in-alt',
                            'logout' => 'fa-sign-out-alt',
                            'create' => 'fa-plus',
                            'update' => 'fa-edit',
                            'delete' => 'fa-trash',
                            'view' => 'fa-eye',
                            default => 'fa-info-circle'
                        };
                        
                        $badgeColor = match($log['action_type']) {
                            'login' => 'success',
                            'logout' => 'secondary',
                            'create' => 'primary',
                            'update' => 'warning',
                            'delete' => 'danger',
                            'view' => 'info',
                            default => 'secondary'
                        };
                    ?>
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 16px;">
                            <span class="badge bg-<?= $badgeColor ?>" style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                                <i class="fas <?= $iconClass ?> me-1"></i>
                                <?= ucfirst($log['action_type']) ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <div class="d-flex align-items-center">
                                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #6f42c1, #5a32a3); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                    <i class="fas fa-user" style="color: white; font-size: 14px;"></i>
                                </div>
                                <span style="font-weight: 600; color: #2d3142;"><?= htmlspecialchars($log['username']) ?></span>
                            </div>
                        </td>
                        <td style="padding: 16px; color: #495057; font-size: 14px;">
                            <?= htmlspecialchars($log['action']) ?>
                        </td>
                        <td style="padding: 16px;">
                            <?php if ($log['module']): ?>
                                <span style="background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(111, 66, 193, 0.05)); color: #6f42c1; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    <?= htmlspecialchars($log['module']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px; color: #6c757d; font-size: 13px;">
                            <i class="far fa-clock me-1"></i>
                            <?= date('M d, g:i A', strtotime($log['created_at'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <p class="text-muted">No activity logs yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
