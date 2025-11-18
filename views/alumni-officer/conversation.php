<?php
$currentPage = 'conversation';
?>

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/scratch/alumni-officer.php?page=messages" style="color: #dc2626; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
            <i class="fas fa-arrow-left"></i>
            Back to Messages
        </a>
        <h2 style="color: #111827; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 20px;">
                <?= strtoupper(substr($contact['name'] ?? 'U', 0, 1)) ?>
            </div>
            <?= htmlspecialchars($contact['name']) ?>
        </h2>
        <p style="color: #6b7280; margin: 5px 0 0 62px; font-size: 14px;">
            @<?= htmlspecialchars($contact['username']) ?>
            <?php if ($contact['email']): ?>
                | <?= htmlspecialchars($contact['email']) ?>
            <?php endif; ?>
        </p>
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

<!-- Conversation Container -->
<div class="row">
    <div class="col-lg-12">
        <!-- Unified Chat Box -->
        <div class="stat-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); overflow: hidden;">
            <!-- Messages Thread -->
            <div style="padding: 30px; max-height: 500px; overflow-y: auto; background: #f9fafb;" id="messagesThread">
                <?php if (empty($messages)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-comments" style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;"></i>
                        <p style="color: #6b7280;">No messages in this conversation yet. Start by sending a message below.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php 
                        $isMe = (int)$msg['sender_id'] === (int)$_SESSION['user']['id'];
                        $messageDate = new DateTime($msg['date_created']);
                        ?>
                        
                        <div class="message-bubble <?= $isMe ? 'message-sent' : 'message-received' ?>" style="margin-bottom: 20px; display: flex; <?= $isMe ? 'justify-content: flex-end;' : 'justify-content: flex-start;' ?>">
                            <div style="max-width: 70%; <?= $isMe ? 'text-align: right;' : 'text-align: left;' ?>">
                                <!-- Message Header -->
                                <div style="margin-bottom: 5px; font-size: 12px; color: #9ca3af;">
                                    <strong style="color: #6b7280;"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                                    <span>• <?= $messageDate->format('M j, Y g:i A') ?></span>
                                </div>
                                
                                <!-- Message Content -->
                                <div style="background: <?= $isMe ? 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)' : '#f3f4f6' ?>; color: <?= $isMe ? 'white' : '#111827' ?>; padding: 15px 20px; border-radius: <?= $isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px' ?>; box-shadow: 0 2px 8px rgba(0,0,0,0.1); word-wrap: break-word;">
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
            <form method="POST" action="/scratch/alumni-officer.php?page=messages&action=send" style="background: white; padding: 20px; border-top: 2px solid #e5e7eb;">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="receiver_id" value="<?= $contact['id'] ?>">

                <div style="position: relative; background: white; border: 2px solid #e5e7eb; border-radius: 25px; transition: all 0.3s ease;" class="message-input-box">
                    <textarea class="form-control" name="message" rows="2" placeholder="Type your message here..." required style="border: none; border-radius: 25px; padding: 15px 70px 15px 20px; font-size: 14px; resize: none; background: transparent; box-shadow: none;" onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>
                    <button type="submit" class="btn" style="position: absolute; right: 8px; bottom: 8px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(220, 38, 38, 0.3); transition: all 0.3s ease;" title="Send Message">
                        <i class="fas fa-paper-plane" style="font-size: 16px;"></i>
                    </button>
                </div>
                <small class="text-muted d-block mt-2 text-center" style="font-size: 11px;">
                    <i class="fas fa-info-circle me-1"></i>Press Enter to send • Shift+Enter for new line
                </small>
            </form>
        </div>
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

.message-bubble {
    animation: fadeInUp 0.3s ease;
}

#messagesThread::-webkit-scrollbar {
    width: 8px;
}

#messagesThread::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#messagesThread::-webkit-scrollbar-thumb {
    background: #dc2626;
    border-radius: 10px;
}

#messagesThread::-webkit-scrollbar-thumb:hover {
    background: #b91c1c;
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

/* Scroll to bottom smoothly */
.stat-card {
    scroll-behavior: smooth;
}
</style>

<script>
// Auto-scroll to bottom of messages on page load
document.addEventListener('DOMContentLoaded', function() {
    const messageContainer = document.querySelector('.stat-card:first-of-type');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});
</script>

