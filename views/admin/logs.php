<?php
$currentPage = 'logs';
$pageTitle = 'User Activity Logs';
$title = 'Activity Logs';

require_once __DIR__ . '/../../inc/logger.php';

// Get filter parameters
$filterType = $_GET['type'] ?? 'all';
$filterUser = $_GET['user'] ?? '';
$filterDate = $_GET['date'] ?? '';

// Get logs
$logs = ActivityLogger::getRecentLogs(100);

// Debug: Check if logs are fetched
// error_log("Logs fetched: " . count($logs));

// Get log statistics
$stats = ActivityLogger::getLogStats(30);

ob_start();
?>

<style>
.log-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
    border-left: 4px solid #6c757d;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.log-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    transform: translateX(4px);
}

.log-card.login {
    border-left-color: #28a745;
}

.log-card.logout {
    border-left-color: #6c757d;
}

.log-card.create {
    border-left-color: #007bff;
}

.log-card.update {
    border-left-color: #ffc107;
}

.log-card.delete {
    border-left-color: #dc3545;
}

.log-card.view {
    border-left-color: #17a2b8;
}

.log-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.log-icon.login { background: linear-gradient(135deg, #28a745, #218838); }
.log-icon.logout { background: linear-gradient(135deg, #6c757d, #5a6268); }
.log-icon.create { background: linear-gradient(135deg, #007bff, #0056b3); }
.log-icon.update { background: linear-gradient(135deg, #ffc107, #e0a800); }
.log-icon.delete { background: linear-gradient(135deg, #dc3545, #c82333); }
.log-icon.view { background: linear-gradient(135deg, #17a2b8, #138496); }

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    margin: 10px 0;
}

.stat-label {
    color: #6c757d;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.filter-bar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
</style>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <?php
    $statIcons = [
        'login' => ['icon' => 'fa-sign-in-alt', 'color' => '#28a745', 'label' => 'Logins'],
        'logout' => ['icon' => 'fa-sign-out-alt', 'color' => '#6c757d', 'label' => 'Logouts'],
        'create' => ['icon' => 'fa-plus', 'color' => '#007bff', 'label' => 'Created'],
        'update' => ['icon' => 'fa-edit', 'color' => '#ffc107', 'label' => 'Updated'],
        'delete' => ['icon' => 'fa-trash', 'color' => '#dc3545', 'label' => 'Deleted'],
        'view' => ['icon' => 'fa-eye', 'color' => '#17a2b8', 'label' => 'Views'],
    ];
    
    foreach ($statIcons as $type => $config):
        $count = 0;
        foreach ($stats as $stat) {
            if ($stat['action_type'] === $type) {
                $count = $stat['count'];
                break;
            }
        }
    ?>
    <div class="col-md-2">
        <div class="stat-card">
            <i class="fas <?= $config['icon'] ?>" style="font-size: 28px; color: <?= $config['color'] ?>;"></i>
            <div class="stat-number" style="color: <?= $config['color'] ?>;"><?= $count ?></div>
            <div class="stat-label"><?= $config['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <form method="GET" class="row g-3">
        <input type="hidden" name="page" value="logs">
        <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; color: #2d3142;">Action Type</label>
            <select name="type" class="form-select" style="border-radius: 10px;">
                <option value="all">All Types</option>
                <option value="login" <?= $filterType === 'login' ? 'selected' : '' ?>>Login</option>
                <option value="logout" <?= $filterType === 'logout' ? 'selected' : '' ?>>Logout</option>
                <option value="create" <?= $filterType === 'create' ? 'selected' : '' ?>>Create</option>
                <option value="update" <?= $filterType === 'update' ? 'selected' : '' ?>>Update</option>
                <option value="delete" <?= $filterType === 'delete' ? 'selected' : '' ?>>Delete</option>
                <option value="view" <?= $filterType === 'view' ? 'selected' : '' ?>>View</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; color: #2d3142;">Username</label>
            <input type="text" name="user" class="form-control" placeholder="Filter by username" value="<?= htmlspecialchars($filterUser) ?>" style="border-radius: 10px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-weight: 600; color: #2d3142;">Date</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($filterDate) ?>" style="border-radius: 10px;">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2" style="border-radius: 10px; padding: 10px 24px;">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <a href="/scratch/admin.php?page=logs" class="btn btn-secondary" style="border-radius: 10px; padding: 10px 24px;">
                <i class="fas fa-redo me-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Activity Logs -->
<div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
    <h5 style="color: #2d3142; font-weight: 700; margin-bottom: 20px;">
        <i class="fas fa-history me-2" style="color: #6f42c1;"></i>Recent Activity
        <small class="text-muted" style="font-size: 12px;">(Total logs: <?= count($logs) ?>)</small>
    </h5>
    
    <?php if (empty($logs)): ?>
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <p class="text-muted">No activity logs found.</p>
        </div>
    <?php else: ?>
        <?php 
        $displayedLogs = 0;
        foreach ($logs as $log): 
            // Apply filters
            if ($filterType !== 'all' && $log['action_type'] !== $filterType) continue;
            if ($filterUser && stripos($log['username'], $filterUser) === false) continue;
            if ($filterDate && !str_starts_with($log['created_at'], $filterDate)) continue;
            
            $displayedLogs++;
            
            $iconClass = match($log['action_type']) {
                'login' => 'fa-sign-in-alt',
                'logout' => 'fa-sign-out-alt',
                'create' => 'fa-plus',
                'update' => 'fa-edit',
                'delete' => 'fa-trash',
                'view' => 'fa-eye',
                default => 'fa-info-circle'
            };
        ?>
        <div class="log-card <?= $log['action_type'] ?>">
            <div class="d-flex align-items-start">
                <div class="log-icon <?= $log['action_type'] ?> me-3">
                    <i class="fas <?= $iconClass ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0" style="color: #2d3142; font-weight: 600;">
                            <?= htmlspecialchars($log['action']) ?>
                        </h6>
                        <small class="text-muted">
                            <i class="far fa-clock me-1"></i>
                            <?= date('M d, Y h:i A', strtotime($log['created_at'])) ?>
                        </small>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        <small>
                            <i class="fas fa-user me-1" style="color: #6c757d;"></i>
                            <strong>User:</strong> <?= htmlspecialchars($log['username']) ?>
                        </small>
                        <?php if ($log['module']): ?>
                        <small>
                            <i class="fas fa-layer-group me-1" style="color: #6c757d;"></i>
                            <strong>Module:</strong> <?= htmlspecialchars($log['module']) ?>
                        </small>
                        <?php endif; ?>
                        <small>
                            <i class="fas fa-network-wired me-1" style="color: #6c757d;"></i>
                            <strong>IP:</strong> <?= htmlspecialchars($log['ip_address']) ?>
                        </small>
                    </div>
                    <?php if ($log['description']): ?>
                    <p class="mb-0 mt-2" style="color: #6c757d; font-size: 14px;">
                        <?= htmlspecialchars($log['description']) ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if ($displayedLogs === 0): ?>
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <p class="text-muted">No activity logs match your filters.</p>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

