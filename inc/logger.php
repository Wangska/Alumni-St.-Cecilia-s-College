<?php
/**
 * User Activity Logger
 * Tracks all user actions in the system
 */

class ActivityLogger {
    private static $pdo = null;
    
    private static function getPdo() {
        if (self::$pdo === null) {
            // Get PDO connection
            if (function_exists('get_pdo')) {
                self::$pdo = get_pdo();
            } else {
                // Fallback: create connection directly
                $host = 'localhost';
                $db = 'sccalumni_db';
                $user = 'root';
                $pass = '';
                $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
        }
        return self::$pdo;
    }
    
    /**
     * Log user activity
     */
    public static function log($action, $actionType, $module = null, $description = null) {
        try {
            $pdo = self::getPdo();
            
            // Get user info from session
            $userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
            $username = $_SESSION['username'] ?? $_SESSION['user']['name'] ?? 'Guest';
            
            // Get IP and User Agent
            $ipAddress = self::getIpAddress();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $stmt = $pdo->prepare(
                "INSERT INTO user_logs (user_id, username, action, action_type, module, description, ip_address, user_agent, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
            );
            
            $stmt->execute([
                $userId,
                $username,
                $action,
                $actionType,
                $module,
                $description,
                $ipAddress,
                $userAgent
            ]);
            
            return true;
        } catch (Exception $e) {
            error_log("Activity Logger Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Specific log methods
     */
    public static function logLogin($username) {
        return self::log("User logged in", "login", "Authentication", "User: {$username}");
    }
    
    public static function logLogout($username) {
        return self::log("User logged out", "logout", "Authentication", "User: {$username}");
    }
    
    public static function logCreate($module, $itemName) {
        return self::log("Created new {$module}", "create", $module, "Item: {$itemName}");
    }
    
    public static function logUpdate($module, $itemName) {
        return self::log("Updated {$module}", "update", $module, "Item: {$itemName}");
    }
    
    public static function logDelete($module, $itemName) {
        return self::log("Deleted {$module}", "delete", $module, "Item: {$itemName}");
    }
    
    public static function logView($module, $itemName = null) {
        $desc = $itemName ? "Item: {$itemName}" : "Viewed list";
        return self::log("Viewed {$module}", "view", $module, $desc);
    }
    
    /**
     * Get user's IP address
     */
    private static function getIpAddress() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                   'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
    
    /**
     * Get recent logs
     */
    public static function getRecentLogs($limit = 50, $userId = null) {
        try {
            $pdo = self::getPdo();
            
            $sql = "SELECT * FROM user_logs";
            $params = [];
            
            if ($userId) {
                $sql .= " WHERE user_id = ?";
                $params[] = $userId;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Logs Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get logs by date range
     */
    public static function getLogsByDateRange($startDate, $endDate, $userId = null) {
        try {
            $pdo = self::getPdo();
            
            $sql = "SELECT * FROM user_logs WHERE created_at BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
            
            if ($userId) {
                $sql .= " AND user_id = ?";
                $params[] = $userId;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Logs Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get log statistics
     */
    public static function getLogStats($days = 30) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare(
                "SELECT 
                    action_type,
                    COUNT(*) as count
                FROM user_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY action_type"
            );
            
            $stmt->execute([$days]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Log Stats Error: " . $e->getMessage());
            return [];
        }
    }
}

/**
 * Notification Manager
 */
class NotificationManager {
    private static $pdo = null;
    
    private static function getPdo() {
        if (self::$pdo === null) {
            // Get PDO connection
            if (function_exists('get_pdo')) {
                self::$pdo = get_pdo();
            } else {
                // Fallback: create connection directly
                $host = 'localhost';
                $db = 'sccalumni_db';
                $user = 'root';
                $pass = '';
                $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
        }
        return self::$pdo;
    }
    
    /**
     * Create notification
     */
    public static function create($userId, $title, $message, $type = 'info', $link = null) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare(
                "INSERT INTO notifications (user_id, title, message, type, link, created_at) 
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            
            $stmt->execute([$userId, $title, $message, $type, $link]);
            
            return $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("Create Notification Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get unread notifications count
     */
    public static function getUnreadCount($userId) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0"
            );
            
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['count'];
        } catch (Exception $e) {
            error_log("Get Unread Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get pending user approvals count for admin notifications
     */
    public static function getPendingApprovalsCount() {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as count FROM users u 
                 INNER JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
                 WHERE u.is_verified = 0 AND u.role = 'alumni'"
            );
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['count'];
        } catch (Exception $e) {
            error_log("Get Pending Approvals Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get admin notification count (pending approvals)
     */
    public static function getAdminNotificationCount() {
        return self::getPendingApprovalsCount();
    }
    
    /**
     * Get user notifications
     */
    public static function getUserNotifications($userId, $limit = 10, $unreadOnly = false) {
        try {
            $pdo = self::getPdo();
            
            $sql = "SELECT * FROM notifications WHERE user_id = ?";
            $params = [$userId];
            
            if ($unreadOnly) {
                $sql .= " AND is_read = 0";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Notifications Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            $stmt->execute([$notificationId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Mark As Read Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark all as read
     */
    public static function markAllAsRead($userId) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Mark All As Read Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete notification
     */
    public static function delete($notificationId) {
        try {
            $pdo = self::getPdo();
            
            $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
            $stmt->execute([$notificationId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Delete Notification Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notify admins
     */
    public static function notifyAdmins($title, $message, $type = 'info', $link = null) {
        try {
            $pdo = self::getPdo();
            
            // Get all admin users
            $stmt = $pdo->query("SELECT id FROM users WHERE type = 1");
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($admins as $admin) {
                self::create($admin['id'], $title, $message, $type, $link);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Notify Admins Error: " . $e->getMessage());
            return false;
        }
    }
}

