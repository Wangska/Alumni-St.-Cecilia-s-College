<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$user = current_user();
$userId = $user['id'];
$pdo = get_pdo();

// Get alumni profile information for header
$alumnusId = $user['alumnus_id'] ?? 0;
$alumni = null;

if ($alumnusId > 0) {
    try {
        $stmt = $pdo->prepare('SELECT ab.*, c.course FROM alumnus_bio ab LEFT JOIN courses c ON ab.course_id = c.id WHERE ab.id = ?');
        $stmt->execute([$alumnusId]);
        $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Ignore
    }
}

// Get unread message count for header
$unreadCount = 0;
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) as unread FROM messages WHERE receiver_id = ? AND is_read = 0');
    $stmt->execute([$userId]);
    $unreadCount = (int)$stmt->fetch(PDO::FETCH_ASSOC)['unread'];
} catch (Exception $e) {
    // Ignore
}

// Get page and action
$page = $_GET['page'] ?? 'inbox';
$action = $_GET['action'] ?? '';
$contactId = isset($_GET['contact_id']) ? (int)$_GET['contact_id'] : 0;

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($action)) {
    require_csrf();
    
    try {
        if ($action === 'send') {
            $receiver_id = (int)($_POST['receiver_id'] ?? 0);
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            if ($receiver_id === 0 || empty($message)) {
                throw new Exception('Recipient and message are required.');
            }
            
            $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, subject, message, is_read, date_created) VALUES (?, ?, ?, ?, 0, NOW())');
            $stmt->execute([$userId, $receiver_id, $subject, $message]);
            $_SESSION['success'] = 'Message sent successfully!';
            
            // Redirect to conversation
            header('Location: /scratch/messaging.php?page=conversation&contact_id=' . $receiver_id);
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: /scratch/messaging.php?page=' . $page);
        exit;
    }
}

// Page routing
switch ($page) {
    case 'inbox':
        // Get conversations
        try {
            $stmt = $pdo->prepare('
                SELECT 
                    CASE 
                        WHEN m.sender_id = ? THEN m.receiver_id 
                        ELSE m.sender_id 
                    END as contact_id,
                    u.name as contact_name,
                    u.username as contact_username,
                    u.type as contact_type,
                    MAX(m.date_created) as last_message_date,
                    (SELECT message FROM messages m2 
                     WHERE (m2.sender_id = contact_id AND m2.receiver_id = ?) 
                        OR (m2.sender_id = ? AND m2.receiver_id = contact_id)
                     ORDER BY m2.date_created DESC LIMIT 1) as last_message,
                    COUNT(CASE WHEN m.receiver_id = ? AND m.is_read = 0 THEN 1 END) as unread_count
                FROM messages m
                LEFT JOIN users u ON u.id = CASE 
                    WHEN m.sender_id = ? THEN m.receiver_id 
                    ELSE m.sender_id 
                END
                WHERE m.sender_id = ? OR m.receiver_id = ?
                GROUP BY contact_id, u.name, u.username, u.type
                ORDER BY last_message_date DESC
            ');
            $stmt->execute([$userId, $userId, $userId, $userId, $userId, $userId, $userId]);
            $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $conversations = [];
        }
        
        // Get total unread count (for inbox page stats)
        $unreadTotal = $unreadCount; // Use already fetched count
        
        require __DIR__ . '/views/messaging/inbox.php';
        break;
        
    case 'conversation':
        if ($contactId === 0) {
            header('Location: /scratch/messaging.php');
            exit;
        }
        
        // Get contact details
        try {
            $stmt = $pdo->prepare('
                SELECT u.id, u.name, u.username, u.type,
                       ab.firstname, ab.lastname, ab.email
                FROM users u
                LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                WHERE u.id = ?
            ');
            $stmt->execute([$contactId]);
            $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $contact = null;
        }
        
        if (!$contact) {
            header('Location: /scratch/messaging.php');
            exit;
        }
        
        // Get messages
        try {
            $stmt = $pdo->prepare('
                SELECT m.*, 
                       sender.name as sender_name,
                       sender.username as sender_username
                FROM messages m
                LEFT JOIN users sender ON m.sender_id = sender.id
                WHERE (m.sender_id = ? AND m.receiver_id = ?)
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.date_created ASC
            ');
            $stmt->execute([$userId, $contactId, $contactId, $userId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $messages = [];
        }
        
        // Mark as read
        try {
            $stmt = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0');
            $stmt->execute([$contactId, $userId]);
        } catch (Exception $e) {
            // Ignore
        }
        
        require __DIR__ . '/views/messaging/conversation.php';
        break;
        
    case 'compose':
        // Get all users except current user
        try {
            $stmt = $pdo->prepare('
                SELECT u.id, u.name, u.username, u.type,
                       ab.firstname, ab.lastname, ab.email
                FROM users u
                LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                WHERE u.type IN (2, 3) AND u.id != ?
                ORDER BY u.type ASC, u.name ASC
            ');
            $stmt->execute([$userId]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $users = [];
        }
        
        require __DIR__ . '/views/messaging/compose.php';
        break;
        
    default:
        header('Location: /scratch/messaging.php?page=inbox');
        exit;
}

