<?php
/**
 * Notifications API
 * Handles notification-related requests for the admin dashboard
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/../inc/logger.php';
require_once __DIR__ . '/../inc/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] != 1) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? null;
    
    if ($method === 'GET') {
        if ($action === 'get_count') {
            // Get unread notification count (both database and system logs)
            $notifications = getNotificationsFromLogs();
            $unreadCount = 0;
            
            foreach ($notifications as $notification) {
                if (!$notification['is_read']) {
                    $unreadCount++;
                }
            }
            
            echo json_encode(['success' => true, 'count' => $unreadCount]);
        } else {
            // Get notifications from system logs
            $notifications = getNotificationsFromLogs();
            echo json_encode(['success' => true, 'notifications' => $notifications]);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? null;
        
        switch ($action) {
            case 'mark_read':
                $notificationId = $input['notification_id'] ?? null;
                if ($notificationId) {
                    // Initialize read notifications session if not exists
                    if (!isset($_SESSION['read_notifications'])) {
                        $_SESSION['read_notifications'] = [];
                    }
                    
                    // Handle different notification types
                    if (strpos($notificationId, 'log_') === 0) {
                        // System log notification - mark as read in session
                        if (!in_array($notificationId, $_SESSION['read_notifications'])) {
                            $_SESSION['read_notifications'][] = $notificationId;
                        }
                        echo json_encode(['success' => true]);
                    } else if (strpos($notificationId, 'notif_') === 0) {
                        // Database notification - mark as read in database
                        $actualId = str_replace('notif_', '', $notificationId);
                        $result = NotificationManager::markAsRead($actualId);
                        echo json_encode(['success' => $result]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Invalid notification ID format']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid notification ID']);
                }
                break;
                
            case 'mark_all_read':
                $userId = $_SESSION['user']['id'];
                
                // Mark all database notifications as read
                $result = NotificationManager::markAllAsRead($userId);
                
                // Mark all system log notifications as read in session
                if (!isset($_SESSION['read_notifications'])) {
                    $_SESSION['read_notifications'] = [];
                }
                
                // Get all current log IDs and mark them as read
                $logs = ActivityLogger::getRecentLogs(50);
                foreach ($logs as $log) {
                    $logId = 'log_' . $log['id'];
                    if (!in_array($logId, $_SESSION['read_notifications'])) {
                        $_SESSION['read_notifications'][] = $logId;
                    }
                }
                
                echo json_encode(['success' => $result]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
    
} catch (Exception $e) {
    error_log("Notifications API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error']);
}

/**
 * Get notifications from system logs
 * Converts recent system logs into notification format
 */
function getNotificationsFromLogs() {
    try {
        // Initialize read notifications session if not exists
        if (!isset($_SESSION['read_notifications'])) {
            $_SESSION['read_notifications'] = [];
        }
        
        // Get recent logs (last 50 entries)
        $logs = ActivityLogger::getRecentLogs(50);
        $notifications = [];
        
        // Convert logs to notifications
        foreach ($logs as $log) {
            $logId = 'log_' . $log['id'];
            $isRead = in_array($logId, $_SESSION['read_notifications']);
            
            $notification = [
                'id' => $logId,
                'title' => getNotificationTitle($log),
                'message' => getNotificationMessage($log),
                'type' => getNotificationType($log),
                'is_read' => $isRead,
                'created_at' => $log['created_at'],
                'link' => getNotificationLink($log)
            ];
            
            $notifications[] = $notification;
        }
        
        // Also get actual notifications from notifications table
        $userId = $_SESSION['user']['id'];
        $actualNotifications = NotificationManager::getUserNotifications($userId, 20);
        
        foreach ($actualNotifications as $notification) {
            $notifId = 'notif_' . $notification['id'];
            $isRead = (bool)$notification['is_read'] || in_array($notifId, $_SESSION['read_notifications']);
            
            $notifications[] = [
                'id' => $notifId,
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => $notification['type'],
                'is_read' => $isRead,
                'created_at' => $notification['created_at'],
                'link' => $notification['link']
            ];
        }
        
        // Sort by creation date (newest first)
        usort($notifications, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Return only the most recent 20 notifications
        return array_slice($notifications, 0, 20);
        
    } catch (Exception $e) {
        error_log("Get Notifications Error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get notification title based on log entry
 */
function getNotificationTitle($log) {
    $action = $log['action'];
    $module = $log['module'] ?? 'System';
    
    switch ($log['action_type']) {
        case 'login':
            return "User Login";
        case 'logout':
            return "User Logout";
        case 'create':
            return "New {$module} Created";
        case 'update':
            return "{$module} Updated";
        case 'delete':
            return "{$module} Deleted";
        case 'view':
            return "{$module} Viewed";
        default:
            return "System Activity";
    }
}

/**
 * Get notification message based on log entry
 */
function getNotificationMessage($log) {
    $username = $log['username'] ?? 'Unknown User';
    $action = $log['action'];
    $description = $log['description'] ?? '';
    $time = date('M j, Y g:i A', strtotime($log['created_at']));
    
    if (!empty($description)) {
        return "{$username} {$action} - {$description} at {$time}";
    } else {
        return "{$username} {$action} at {$time}";
    }
}

/**
 * Get notification type based on log entry
 */
function getNotificationType($log) {
    switch ($log['action_type']) {
        case 'login':
            return 'success';
        case 'logout':
            return 'info';
        case 'create':
            return 'success';
        case 'update':
            return 'info';
        case 'delete':
            return 'danger';
        case 'view':
            return 'info';
        default:
            return 'info';
    }
}

/**
 * Get notification link based on log entry
 */
function getNotificationLink($log) {
    $module = strtolower($log['module'] ?? '');
    
    switch ($module) {
        case 'users':
        case 'user':
            return '/scratch/admin.php?page=users';
        case 'announcements':
        case 'announcement':
            return '/scratch/admin.php?page=announcements';
        case 'events':
        case 'event':
            return '/scratch/admin.php?page=events';
        case 'forum':
            return '/scratch/admin.php?page=forum';
        case 'gallery':
            return '/scratch/admin.php?page=galleries';
        case 'careers':
        case 'career':
            return '/scratch/admin.php?page=careers';
        case 'authentication':
            return '/scratch/admin.php?page=logs';
        default:
            return '/scratch/admin.php?page=logs';
    }
}
?>
