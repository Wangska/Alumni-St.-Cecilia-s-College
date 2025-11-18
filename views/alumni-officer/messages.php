<?php
$currentPage = 'messages';
?>

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="color: #111827; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-envelope" style="color: #dc2626;"></i>
            Messages
        </h2>
        <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Communicate with alumni members</p>
    </div>
    <a href="/scratch/alumni-officer.php?page=compose-message" class="btn-add-modern" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 12px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); border: none; transition: all 0.3s ease;">
        <i class="fas fa-plus"></i>
        New Message
    </a>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card-small" style="background: white; border-radius: 12px; padding: 20px; border-left: 4px solid #dc2626; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 15px;">
            <div class="stat-icon-small" style="background: rgba(220, 38, 38, 0.1); color: #dc2626; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h4 style="color: #dc2626; font-weight: 700; margin-bottom: 5px;"><?= count($conversations) ?></h4>
                <p style="color: #6b7280; font-size: 13px; margin: 0;">Total Conversations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-small" style="background: white; border-radius: 12px; padding: 20px; border-left: 4px solid #059669; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 15px;">
            <div class="stat-icon-small" style="background: rgba(5, 150, 105, 0.1); color: #059669; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div>
                <h4 style="color: #059669; font-weight: 700; margin-bottom: 5px;"><?= $unreadTotal ?></h4>
                <p style="color: #6b7280; font-size: 13px; margin: 0;">Unread Messages</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-small" style="background: white; border-radius: 12px; padding: 20px; border-left: 4px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 15px;">
            <div class="stat-icon-small" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h4 style="color: #3b82f6; font-weight: 700; margin-bottom: 5px;"><?= count($conversations) ?></h4>
                <p style="color: #6b7280; font-size: 13px; margin: 0;">Active Contacts</p>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
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
<div class="stat-card" style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h5 class="mb-4" style="color: #111827; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-comments" style="color: #dc2626;"></i>
        Conversations
    </h5>

    <?php if (empty($conversations)): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;"></i>
            <h5 style="color: #6b7280; font-weight: 600;">No messages yet</h5>
            <p style="color: #9ca3af; margin-bottom: 20px;">Start a conversation by sending a new message</p>
            <a href="/scratch/alumni-officer.php?page=compose-message" class="btn btn-primary" style="background: #dc2626; border: none; padding: 10px 25px; border-radius: 10px;">
                <i class="fas fa-plus me-2"></i>Compose Message
            </a>
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($conversations as $conv): ?>
                <a href="/scratch/alumni-officer.php?page=conversation&contact_id=<?= $conv['contact_id'] ?>" 
                   class="list-group-item list-group-item-action" 
                   style="border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 10px; padding: 20px; transition: all 0.3s ease; text-decoration: none; <?= $conv['unread_count'] > 0 ? 'background: #fef2f2;' : '' ?>">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Avatar -->
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 18px; flex-shrink: 0;">
                            <?= strtoupper(substr($conv['contact_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        
                        <!-- Conversation Details -->
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0" style="font-weight: 600; color: #111827; font-size: 15px;">
                                    <?= htmlspecialchars($conv['contact_name']) ?>
                                    <?php if ($conv['unread_count'] > 0): ?>
                                        <span class="badge bg-danger ms-2" style="font-size: 10px; padding: 3px 8px;"><?= $conv['unread_count'] ?> new</span>
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
                        
                        <!-- Arrow Icon -->
                        <i class="fas fa-chevron-right" style="color: #d1d5db; font-size: 14px;"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.list-group-item:hover {
    background: #fef2f2 !important;
    border-color: #dc2626 !important;
    transform: translateX(5px);
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
}
</style>

