<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/inc/auth.php';

use App\Controllers\AlumniOfficerController;

// Database connection helper
if (!function_exists('get_pdo')) {
    function get_pdo(): PDO {
        static $pdo = null;
        if ($pdo instanceof PDO) {
            return $pdo;
        }
        $dsn = 'mysql:host=127.0.0.1;dbname=sccalumni_db;charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, 'root', '', $options);
        return $pdo;
    }
}

// CSRF validation helper
if (!function_exists('require_csrf')) {
    function require_csrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sent = $_POST['csrf_token'] ?? '';
            if (!$sent || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
                http_response_code(400);
                exit('Invalid CSRF token');
            }
        }
    }
}

// Require alumni officer authentication
require_alumni_officer();

// Get the page parameter
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

$pdo = get_pdo();

// Handle CRUD operations based on page and action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($action)) {
    require_csrf();
    
    try {
        switch ($page) {
            case 'verify-alumni':
                if ($action === 'approve' && $id) {
                    // For now, approval doesn't do anything since there's no status column
                    // You could add custom logic here if needed
                    $_SESSION['success'] = 'Alumni account noted!';
                } elseif ($action === 'reject' && $id) {
                    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND type = 3');
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Alumni account deleted.';
                }
                header('Location: /scratch/alumni-officer.php?page=verify-alumni');
                exit;
                
            case 'announcements':
                if ($action === 'create') {
                    $title = trim($_POST['title'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    
                    if (empty($title) || empty($content)) {
                        throw new Exception('Title and content are required.');
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO announcements (title, content, date_created) VALUES (?, ?, NOW())');
                    $stmt->execute([$title, $content]);
                    $_SESSION['success'] = 'Announcement posted successfully!';
                    
                } elseif ($action === 'update' && $id) {
                    $title = trim($_POST['title'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    
                    if (empty($title) || empty($content)) {
                        throw new Exception('Title and content are required.');
                    }
                    
                    $stmt = $pdo->prepare('UPDATE announcements SET title = ?, content = ? WHERE id = ?');
                    $stmt->execute([$title, $content, $id]);
                    $_SESSION['success'] = 'Announcement updated successfully!';
                    
                } elseif ($action === 'delete' && $id) {
                    $stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Announcement deleted successfully!';
                }
                header('Location: /scratch/alumni-officer.php?page=announcements');
                exit;
                
            case 'events':
                if ($action === 'create') {
                    $title = trim($_POST['title'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    $schedule = trim($_POST['schedule'] ?? '');
                    
                    if (empty($title) || empty($content) || empty($schedule)) {
                        throw new Exception('All fields are required.');
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO events (title, content, schedule) VALUES (?, ?, ?)');
                    $stmt->execute([$title, $content, $schedule]);
                    $_SESSION['success'] = 'Event published successfully!';
                    
                } elseif ($action === 'update' && $id) {
                    $title = trim($_POST['title'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    $schedule = trim($_POST['schedule'] ?? '');
                    
                    if (empty($title) || empty($content) || empty($schedule)) {
                        throw new Exception('All fields are required.');
                    }
                    
                    $stmt = $pdo->prepare('UPDATE events SET title = ?, content = ?, schedule = ? WHERE id = ?');
                    $stmt->execute([$title, $content, $schedule, $id]);
                    $_SESSION['success'] = 'Event updated successfully!';
                    
                } elseif ($action === 'delete' && $id) {
                    $stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Event deleted successfully!';
                }
                header('Location: /scratch/alumni-officer.php?page=events');
                exit;
                
            case 'newsletters':
                if ($action === 'create') {
                    $about = trim($_POST['about'] ?? '');
                    
                    if (empty($about)) {
                        throw new Exception('Description is required.');
                    }
                    
                    // Handle image upload
                    $image_path = '';
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = __DIR__ . '/uploads/gallery/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $ext;
                        $target = $upload_dir . $filename;
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $image_path = 'uploads/gallery/' . $filename;
                        }
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO gallery (about, image_path, user_id, created_at) VALUES (?, ?, ?, NOW())');
                    $stmt->execute([$about, $image_path, $_SESSION['user_id']]);
                    $_SESSION['success'] = 'Newsletter created successfully!';
                    
                } elseif ($action === 'delete' && $id) {
                    // Get image path before deleting
                    $stmt = $pdo->prepare('SELECT image_path FROM gallery WHERE id = ?');
                    $stmt->execute([$id]);
                    $gallery = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Delete from database
                    $stmt = $pdo->prepare('DELETE FROM gallery WHERE id = ?');
                    $stmt->execute([$id]);
                    
                    // Delete file if exists
                    if ($gallery && !empty($gallery['image_path'])) {
                        $file_path = __DIR__ . '/' . $gallery['image_path'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                    
                    $_SESSION['success'] = 'Newsletter deleted successfully!';
                }
                header('Location: /scratch/alumni-officer.php?page=newsletters');
                exit;
                
            case 'moderate':
                if ($action === 'create-topic') {
                    $title = trim($_POST['title'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    
                    if (empty($title) || empty($description)) {
                        throw new Exception('Title and description are required.');
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO forum_topics (title, description, user_id, date_created) VALUES (?, ?, ?, NOW())');
                    $stmt->execute([$title, $description, $_SESSION['user']['id']]);
                    $_SESSION['success'] = 'Forum topic created successfully!';
                    
                } elseif ($action === 'edit-topic' && $id) {
                    $title = trim($_POST['title'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    
                    if (empty($title) || empty($description)) {
                        throw new Exception('Title and description are required.');
                    }
                    
                    // Verify the topic belongs to the current user
                    $stmt = $pdo->prepare('SELECT user_id FROM forum_topics WHERE id = ?');
                    $stmt->execute([$id]);
                    $topic = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$topic || (int)$topic['user_id'] !== (int)$_SESSION['user']['id']) {
                        throw new Exception('You can only edit your own forum topics.');
                    }
                    
                    $stmt = $pdo->prepare('UPDATE forum_topics SET title = ?, description = ? WHERE id = ?');
                    $stmt->execute([$title, $description, $id]);
                    $_SESSION['success'] = 'Forum topic updated successfully!';
                    
                } elseif ($action === 'delete-topic' && $id) {
                    // Delete topic and its comments
                    $stmt = $pdo->prepare('DELETE FROM forum_comments WHERE topic_id = ?');
                    $stmt->execute([$id]);
                    $stmt = $pdo->prepare('DELETE FROM forum_topics WHERE id = ?');
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Forum topic deleted successfully!';
                    
                } elseif ($action === 'delete-comment' && $id) {
                    $stmt = $pdo->prepare('DELETE FROM forum_comments WHERE id = ?');
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Comment deleted successfully!';
                }
                header('Location: /scratch/alumni-officer.php?page=moderate');
                exit;
                
            case 'messages':
                if ($action === 'send') {
                    $receiver_id = (int)($_POST['receiver_id'] ?? 0);
                    $subject = trim($_POST['subject'] ?? '');
                    $message = trim($_POST['message'] ?? '');
                    $sender_id = $_SESSION['user']['id'];
                    
                    if ($receiver_id === 0 || empty($message)) {
                        throw new Exception('Recipient and message are required.');
                    }
                    
                    $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, subject, message, is_read, date_created) VALUES (?, ?, ?, ?, 0, NOW())');
                    $stmt->execute([$sender_id, $receiver_id, $subject, $message]);
                    $_SESSION['success'] = 'Message sent successfully!';
                    
                    // Redirect to conversation
                    header('Location: /scratch/alumni-officer.php?page=conversation&contact_id=' . $receiver_id);
                    exit;
                }
                header('Location: /scratch/alumni-officer.php?page=messages');
                exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: /scratch/alumni-officer.php?page=' . $page);
        exit;
    }
}

// Route to appropriate controller method
$controller = new \App\Controllers\AlumniOfficerController();
$currentPage = $page;

switch ($page) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'verify-alumni':
        $controller->verifyAlumni();
        break;
    case 'announcements':
        $controller->announcements();
        break;
    case 'events':
        $controller->events();
        break;
    case 'event-participants':
        $controller->eventParticipants();
        break;
    case 'newsletters':
        $controller->newsletters();
        break;
    case 'reports':
        $controller->reports();
        break;
    case 'concerns':
        $controller->concerns();
        break;
    case 'moderate':
        $controller->moderate();
        break;
    case 'forum-view':
        $controller->forumView();
        break;
    case 'messages':
        $controller->messages();
        break;
    case 'conversation':
        $controller->conversation();
        break;
    case 'compose-message':
        $controller->composeMessage();
        break;
    default:
        header('Location: /scratch/alumni-officer.php?page=dashboard');
        exit;
}

