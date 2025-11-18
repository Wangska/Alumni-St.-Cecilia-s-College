<?php
$currentPage = 'compose-message';
?>

<!-- Header Section -->
<div class="mb-4">
    <a href="/scratch/alumni-officer.php?page=messages" style="color: #dc2626; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="fas fa-arrow-left"></i>
        Back to Messages
    </a>
    <h2 style="color: #111827; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-pen" style="color: #dc2626;"></i>
        Compose New Message
    </h2>
    <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Send a message to an alumni member</p>
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

<!-- Compose Form -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="stat-card" style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="POST" action="/scratch/alumni-officer.php?page=messages&action=send" id="composeForm">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <!-- Recipient Selection -->
                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                        <i class="fas fa-user me-2" style="color: #dc2626;"></i>
                        To <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="receiver_id" required id="recipientSelect" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 15px; font-size: 14px; transition: all 0.3s ease;">
                        <option value="">-- Select Recipient --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" 
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                    data-email="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                    data-type="<?= (int)$user['type'] === 2 ? 'Alumni Officer' : 'Alumni' ?>">
                                <?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>) 
                                <?php if ($user['email']): ?>
                                    - <?= htmlspecialchars($user['email']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <!-- Recipient Preview Card -->
                    <div id="recipientPreview" class="mt-3" style="display: none; background: #fef2f2; border: 2px solid #fecaca; border-radius: 10px; padding: 15px;">
                        <div class="d-flex align-items-center gap-3">
                            <div id="recipientAvatar" style="width: 45px; height: 45px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 16px;">
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #111827; font-size: 14px;" id="recipientName"></div>
                                <div style="font-size: 12px; color: #6b7280;" id="recipientInfo"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                        <i class="fas fa-comment-dots me-2" style="color: #dc2626;"></i>
                        Your Message <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" name="message" rows="10" placeholder="Type your message here..." required id="messageContent" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 15px; font-size: 14px; resize: vertical; transition: all 0.3s ease;"></textarea>
                    <small class="text-muted" id="charCount">0 characters</small>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: none; padding: 14px 35px; border-radius: 10px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); transition: all 0.3s ease;">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" style="padding: 14px 35px; border-radius: 10px; font-weight: 600; font-size: 15px; border: 2px solid #d1d5db; color: #6b7280; transition: all 0.3s ease;">
                        <i class="fas fa-undo me-2"></i>Reset
                    </button>
                    <a href="/scratch/alumni-officer.php?page=messages" class="btn btn-secondary" style="padding: 14px 35px; border-radius: 10px; font-weight: 600; font-size: 15px;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Tips Card -->
        <div class="stat-card mt-4" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-radius: 12px; padding: 25px; border-left: 4px solid #dc2626;">
            <h6 style="color: #dc2626; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-lightbulb"></i>
                Quick Tips
            </h6>
            <ul style="color: #6b7280; font-size: 13px; margin: 0; padding-left: 20px; line-height: 1.8;">
                <li>Be clear and concise in your message</li>
                <li>Messages are automatically marked as read when viewed</li>
                <li>You can continue conversations by replying from the message thread</li>
                <li>All previous messages with this person will appear in one conversation</li>
            </ul>
        </div>
    </div>
</div>

<style>
.form-control:focus,
.form-select:focus {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
}

.btn-outline-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.btn-secondary:hover {
    background: #6b7280;
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

<script>
// Recipient selection handler
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

// Form reset handler
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    document.getElementById('recipientPreview').style.display = 'none';
    document.getElementById('charCount').textContent = '0 characters';
});

// Form validation
document.getElementById('composeForm').addEventListener('submit', function(e) {
    const recipient = document.getElementById('recipientSelect').value;
    const message = document.getElementById('messageContent').value.trim();
    
    if (!recipient) {
        e.preventDefault();
        alert('Please select a recipient');
        document.getElementById('recipientSelect').focus();
        return false;
    }
    
    if (!message) {
        e.preventDefault();
        alert('Please enter a message');
        document.getElementById('messageContent').focus();
        return false;
    }
});
</script>

