<?php

namespace App\Controllers;

use PDO;

class AlumniOfficerController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = get_pdo();
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require_once __DIR__ . '/../../views/alumni-officer/' . $view . '.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/alumni-officer.php';
    }

    public function dashboard(): void
    {
        // Get statistics
        $stats = [
            'pending_alumni' => $this->getPendingAlumniCount(),
            'total_alumni' => $this->getTotalAlumniCount(),
            'upcoming_events' => $this->getUpcomingEventsCount(),
            'forum_posts' => $this->getForumPostsCount(),
            'recent_activities' => $this->getRecentActivities(),
            'monthly_registrations' => $this->getMonthlyRegistrations(),
        ];

        $this->view('dashboard', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
        ]);
    }

    public function verifyAlumni(): void
    {
        try {
            // Get alumni without complete profile setup (pending)
            $stmt = $this->pdo->prepare('
                SELECT u.id, u.name, u.username,
                       a.firstname, a.lastname, a.email, a.batch, a.course_id,
                       c.course
                FROM users u
                LEFT JOIN alumnus_bio a ON u.alumnus_id = a.id
                LEFT JOIN courses c ON a.course_id = c.id
                WHERE u.type = 3 AND (u.alumnus_id IS NULL OR u.alumnus_id = 0)
                ORDER BY u.id DESC
            ');
            $stmt->execute();
            $pendingAlumni = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get alumni with complete profiles (verified)
            $stmt = $this->pdo->prepare('
                SELECT u.id, u.name, u.username,
                       a.firstname, a.lastname, a.email, a.batch, a.course_id,
                       c.course
                FROM users u
                LEFT JOIN alumnus_bio a ON u.alumnus_id = a.id
                LEFT JOIN courses c ON a.course_id = c.id
                WHERE u.type = 3 AND u.alumnus_id IS NOT NULL AND u.alumnus_id > 0
                ORDER BY u.id DESC
                LIMIT 50
            ');
            $stmt->execute();
            $verifiedAlumni = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $pendingAlumni = [];
            $verifiedAlumni = [];
        }

        $this->view('verify-alumni', [
            'pageTitle' => 'Verify Alumni',
            'pendingAlumni' => $pendingAlumni,
            'verifiedAlumni' => $verifiedAlumni,
        ]);
    }

    public function announcements(): void
    {
        // Get all announcements
        try {
            $stmt = $this->pdo->prepare('
                SELECT a.*, "Admin" as author_name
                FROM announcements a
                ORDER BY a.date_created DESC
            ');
            $stmt->execute();
            $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $announcements = [];
        }

        $this->view('announcements', [
            'pageTitle' => 'Announcements',
            'announcements' => $announcements,
        ]);
    }

    public function events(): void
    {
        // Get all events with participant counts
        try {
            $stmt = $this->pdo->prepare('
                SELECT e.*, 
                       COUNT(ec.id) as participant_count,
                       "Admin" as created_by_name
                FROM events e
                LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                GROUP BY e.id
                ORDER BY e.schedule DESC
            ');
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $events = [];
        }

        $this->view('events', [
            'pageTitle' => 'Events & Activities',
            'events' => $events,
        ]);
    }
    
    public function eventParticipants(): void
    {
        $filterEventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
        
        // Get events with participant counts
        try {
            if ($filterEventId > 0) {
                $stmt = $this->pdo->prepare("
                    SELECT e.*, COUNT(ec.id) as participant_count
                    FROM events e
                    LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                    WHERE e.id = ?
                    GROUP BY e.id
                ");
                $stmt->execute([$filterEventId]);
            } else {
                $stmt = $this->pdo->prepare("
                    SELECT e.*, COUNT(ec.id) as participant_count
                    FROM events e
                    LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                    GROUP BY e.id
                    ORDER BY e.schedule DESC
                ");
                $stmt->execute();
            }
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $events = [];
        }
        
        // Get detailed participant information for each event
        $eventParticipants = [];
        foreach ($events as $event) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT ec.*, u.username, ab.firstname, ab.lastname, ab.email
                    FROM event_commits ec
                    LEFT JOIN users u ON ec.user_id = u.id
                    LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                    WHERE ec.event_id = ? AND ec.user_id != 1
                    ORDER BY ec.id DESC
                ");
                $stmt->execute([$event['id']]);
                $eventParticipants[$event['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $eventParticipants[$event['id']] = [];
            }
        }
        
        $this->view('event-participants', [
            'pageTitle' => 'Event Participants',
            'events' => $events,
            'eventParticipants' => $eventParticipants,
        ]);
    }

    public function newsletters(): void
    {
        // Get all galleries (used as newsletters)
        $stmt = $this->pdo->prepare('
            SELECT g.*, u.name as created_by_name
            FROM gallery g
            LEFT JOIN users u ON g.user_id = u.id
            ORDER BY g.created_at DESC
        ');
        $stmt->execute();
        $newsletters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('newsletters', [
            'pageTitle' => 'Newsletters',
            'newsletters' => $newsletters,
        ]);
    }

    public function reports(): void
    {
        // Get comprehensive statistics
        $stats = [
            'total_alumni' => $this->getTotalAlumniCount(),
            'pending_alumni' => $this->getPendingAlumniCount(),
            'total_events' => $this->getTotalEventsCount(),
            'upcoming_events' => $this->getUpcomingEventsCount(),
            'total_posts' => $this->getForumPostsCount(),
            'total_news' => $this->getTotalNewsCount(),
            'monthly_data' => $this->getMonthlyRegistrations(),
            'course_distribution' => $this->getCourseDistribution(),
            'batch_distribution' => $this->getBatchDistribution(),
        ];
        
        // Get announcement statistics
        $announcementStats = $this->getAnnouncementStats();
        
        // Get event statistics
        $eventStats = $this->getEventStats();
        
        // Get forum statistics
        $forumStats = $this->getForumStats();

        $this->view('reports', [
            'pageTitle' => 'Reports & Statistics',
            'stats' => $stats,
            'announcementStats' => $announcementStats,
            'eventStats' => $eventStats,
            'forumStats' => $forumStats,
        ]);
    }
    
    private function getAnnouncementStats(): array
    {
        try {
            // Total announcements
            $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM announcements');
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Recent announcements (last 30 days)
            $stmt = $this->pdo->query('SELECT COUNT(*) as recent FROM announcements WHERE date_created >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
            $recent = $stmt->fetch(PDO::FETCH_ASSOC)['recent'];
            
            // Announcements per month (last 6 months)
            $stmt = $this->pdo->query('
                SELECT DATE_FORMAT(date_created, "%Y-%m") as month, COUNT(*) as count
                FROM announcements
                WHERE date_created >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month ASC
            ');
            $monthly = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent announcements list
            $stmt = $this->pdo->query('
                SELECT id, title, date_created
                FROM announcements
                ORDER BY date_created DESC
                LIMIT 5
            ');
            $recentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'total' => $total,
                'recent' => $recent,
                'monthly' => $monthly,
                'recentList' => $recentList,
            ];
        } catch (Exception $e) {
            return ['total' => 0, 'recent' => 0, 'monthly' => [], 'recentList' => []];
        }
    }
    
    private function getEventStats(): array
    {
        try {
            // Total events
            $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM events');
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Upcoming events
            $stmt = $this->pdo->query('SELECT COUNT(*) as upcoming FROM events WHERE schedule >= NOW()');
            $upcoming = $stmt->fetch(PDO::FETCH_ASSOC)['upcoming'];
            
            // Past events
            $stmt = $this->pdo->query('SELECT COUNT(*) as past FROM events WHERE schedule < NOW()');
            $past = $stmt->fetch(PDO::FETCH_ASSOC)['past'];
            
            // Total participants
            $stmt = $this->pdo->query('SELECT COUNT(DISTINCT user_id) as total FROM event_commits WHERE user_id != 1');
            $totalParticipants = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Events per month (last 6 months)
            $stmt = $this->pdo->query('
                SELECT DATE_FORMAT(schedule, "%Y-%m") as month, COUNT(*) as count
                FROM events
                WHERE schedule >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month ASC
            ');
            $monthly = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Top attended events
            $stmt = $this->pdo->query('
                SELECT e.id, e.title, e.schedule, COUNT(ec.id) as participant_count
                FROM events e
                LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                GROUP BY e.id
                ORDER BY participant_count DESC
                LIMIT 5
            ');
            $topEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'total' => $total,
                'upcoming' => $upcoming,
                'past' => $past,
                'totalParticipants' => $totalParticipants,
                'monthly' => $monthly,
                'topEvents' => $topEvents,
            ];
        } catch (Exception $e) {
            return ['total' => 0, 'upcoming' => 0, 'past' => 0, 'totalParticipants' => 0, 'monthly' => [], 'topEvents' => []];
        }
    }
    
    private function getForumStats(): array
    {
        try {
            // Total topics
            $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM forum_topics');
            $totalTopics = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total comments
            $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM forum_comments');
            $totalComments = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Recent topics (last 30 days)
            $stmt = $this->pdo->query('SELECT COUNT(*) as recent FROM forum_topics WHERE id >= (SELECT MAX(id) - 100 FROM forum_topics)');
            $recentTopics = $stmt->fetch(PDO::FETCH_ASSOC)['recent'];
            
            // Active users (users who posted)
            $stmt = $this->pdo->query('SELECT COUNT(DISTINCT user_id) as active FROM forum_topics WHERE user_id IS NOT NULL');
            $activeUsers = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
            
            // Topics per month (last 6 months - using ID as proxy for date)
            $stmt = $this->pdo->query('
                SELECT COUNT(*) as count
                FROM forum_topics
                LIMIT 6
            ');
            $monthlyTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Most active topics
            $stmt = $this->pdo->query('
                SELECT ft.id, ft.user_id, COUNT(fc.id) as comment_count, u.name as author_name
                FROM forum_topics ft
                LEFT JOIN forum_comments fc ON ft.id = fc.topic_id
                LEFT JOIN users u ON ft.user_id = u.id
                GROUP BY ft.id
                ORDER BY comment_count DESC
                LIMIT 5
            ');
            $activeTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'totalTopics' => $totalTopics,
                'totalComments' => $totalComments,
                'recentTopics' => $recentTopics,
                'activeUsers' => $activeUsers,
                'monthlyTopics' => $monthlyTopics,
                'activeTopics' => $activeTopics,
            ];
        } catch (Exception $e) {
            return ['totalTopics' => 0, 'totalComments' => 0, 'recentTopics' => 0, 'activeUsers' => 0, 'monthlyTopics' => [], 'activeTopics' => []];
        }
    }

    public function concerns(): void
    {
        // Get forum topics as concerns/inquiries
        try {
            $stmt = $this->pdo->prepare('
                SELECT ft.*, u.name as author_name, u.username,
                       COUNT(DISTINCT fc.id) as comment_count
                FROM forum_topics ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN forum_comments fc ON ft.id = fc.topic_id
                GROUP BY ft.id
                ORDER BY ft.id DESC
            ');
            $stmt->execute();
            $concerns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $concerns = [];
        }

        $this->view('concerns', [
            'pageTitle' => 'Alumni Concerns',
            'concerns' => $concerns,
        ]);
    }

    public function moderate(): void
    {
        // Get recent forum posts, comments, and content to moderate
        try {
            $stmt = $this->pdo->prepare('
                SELECT ft.id, ft.title, ft.description, ft.user_id, ft.date_created,
                       u.name as author_name, u.username,
                       COUNT(fc.id) as comment_count
                FROM forum_topics ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN forum_comments fc ON ft.id = fc.topic_id
                GROUP BY ft.id, ft.title, ft.description, ft.user_id, ft.date_created, u.name, u.username
                ORDER BY ft.date_created DESC
                LIMIT 50
            ');
            $stmt->execute();
            $forumTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $forumTopics = [];
        }

        // Get recent comments
        try {
            $stmt = $this->pdo->prepare('
                SELECT fc.id, fc.comment, fc.user_id, fc.topic_id, fc.date_created,
                       u.name as author_name, u.username
                FROM forum_comments fc
                LEFT JOIN users u ON fc.user_id = u.id
                ORDER BY fc.date_created DESC
                LIMIT 50
            ');
            $stmt->execute();
            $recentComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $recentComments = [];
        }

        $this->view('moderate', [
            'pageTitle' => 'Forum Management',
            'forumTopics' => $forumTopics,
            'recentComments' => $recentComments,
        ]);
    }
    
    public function forumView(): void
    {
        $topicId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($topicId <= 0) {
            $_SESSION['error'] = 'Invalid forum topic ID.';
            header('Location: /scratch/alumni-officer.php?page=moderate');
            exit;
        }
        
        // Get topic details
        try {
            $stmt = $this->pdo->prepare('SELECT ft.*, u.name as author_name, u.username FROM forum_topics ft LEFT JOIN users u ON ft.user_id = u.id WHERE ft.id = ?');
            $stmt->execute([$topicId]);
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$topic) {
                $_SESSION['error'] = 'Forum topic not found.';
                header('Location: /scratch/alumni-officer.php?page=moderate');
                exit;
            }
            
            // Get all comments for this topic
            $stmt = $this->pdo->prepare('SELECT fc.*, u.name as author_name, u.username FROM forum_comments fc LEFT JOIN users u ON fc.user_id = u.id WHERE fc.topic_id = ? ORDER BY fc.date_created ASC');
            $stmt->execute([$topicId]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
            header('Location: /scratch/alumni-officer.php?page=moderate');
            exit;
        }
        
        $this->view('forum-view', [
            'topic' => $topic,
            'comments' => $comments,
            'commentCount' => count($comments),
        ]);
    }

    // Helper methods for statistics
    private function getPendingAlumniCount(): int
    {
        try {
            // Count users without alumnus_id (not yet set up)
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM users WHERE type = 3 AND (alumnus_id IS NULL OR alumnus_id = 0)');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalAlumniCount(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM users WHERE type = 3');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getUpcomingEventsCount(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM events WHERE schedule >= CURDATE()');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getForumPostsCount(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM forum_topics');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalEventsCount(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM events');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalNewsCount(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM announcements');
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getRecentActivities(): array
    {
        // Simplified version - get only what exists
        try {
            $activities = [];
            
            // Get recent alumni
            $stmt = $this->pdo->query('SELECT "New Alumni" as activity, name as detail, id as sort_date FROM users WHERE type = 3 ORDER BY id DESC LIMIT 3');
            $activities = array_merge($activities, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Get recent events
            $stmt = $this->pdo->query('SELECT "New Event" as activity, title as detail, schedule as sort_date FROM events ORDER BY schedule DESC LIMIT 3');
            $activities = array_merge($activities, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Get recent announcements
            $stmt = $this->pdo->query('SELECT "New Announcement" as activity, title as detail, date_created as sort_date FROM announcements ORDER BY date_created DESC LIMIT 3');
            $activities = array_merge($activities, $stmt->fetchAll(PDO::FETCH_ASSOC));
            
            // Sort by date and limit
            usort($activities, function($a, $b) {
                return strtotime($b['sort_date']) - strtotime($a['sort_date']);
            });
            
            return array_slice($activities, 0, 10);
        } catch (Exception $e) {
            return [];
        }
    }

    private function getMonthlyRegistrations(): array
    {
        // Since created_at doesn't exist, return sample data for chart
        try {
            $stmt = $this->pdo->prepare('
                SELECT 
                    DATE_FORMAT(NOW() - INTERVAL (id % 12) MONTH, "%Y-%m") as month,
                    COUNT(*) as count
                FROM users
                WHERE type = 3
                GROUP BY month
                ORDER BY month DESC
                LIMIT 12
            ');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If no data, return empty array
            return $data ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    private function getCourseDistribution(): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT c.course, COUNT(*) as count
                FROM alumnus_bio a
                LEFT JOIN courses c ON a.course_id = c.id
                WHERE c.course IS NOT NULL
                GROUP BY c.course
                ORDER BY count DESC
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    private function getBatchDistribution(): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT batch, COUNT(*) as count
                FROM alumnus_bio
                WHERE batch IS NOT NULL AND batch != ""
                GROUP BY batch
                ORDER BY batch DESC
                LIMIT 10
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    // ==================== MESSAGING METHODS ====================
    
    public function messages(): void
    {
        $currentUserId = $_SESSION['user']['id'] ?? 0;
        
        // Get conversations (grouped by unique conversation partners)
        try {
            $stmt = $this->pdo->prepare('
                SELECT 
                    CASE 
                        WHEN m.sender_id = ? THEN m.receiver_id 
                        ELSE m.sender_id 
                    END as contact_id,
                    u.name as contact_name,
                    u.username as contact_username,
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
                GROUP BY contact_id, u.name, u.username
                ORDER BY last_message_date DESC
            ');
            $stmt->execute([$currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId]);
            $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $conversations = [];
        }
        
        // Get total unread count
        try {
            $stmt = $this->pdo->prepare('
                SELECT COUNT(*) as unread FROM messages 
                WHERE receiver_id = ? AND is_read = 0
            ');
            $stmt->execute([$currentUserId]);
            $unreadTotal = $stmt->fetch(PDO::FETCH_ASSOC)['unread'];
        } catch (Exception $e) {
            $unreadTotal = 0;
        }
        
        $this->view('messages', [
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'unreadTotal' => $unreadTotal,
        ]);
    }
    
    public function conversation(): void
    {
        $currentUserId = $_SESSION['user']['id'] ?? 0;
        $contactId = isset($_GET['contact_id']) ? (int)$_GET['contact_id'] : 0;
        
        if ($contactId === 0) {
            header('Location: /scratch/alumni-officer.php?page=messages');
            exit;
        }
        
        // Get contact details
        try {
            $stmt = $this->pdo->prepare('
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
            header('Location: /scratch/alumni-officer.php?page=messages');
            exit;
        }
        
        // Get all messages in this conversation
        try {
            $stmt = $this->pdo->prepare('
                SELECT m.*, 
                       sender.name as sender_name,
                       sender.username as sender_username
                FROM messages m
                LEFT JOIN users sender ON m.sender_id = sender.id
                WHERE (m.sender_id = ? AND m.receiver_id = ?)
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.date_created ASC
            ');
            $stmt->execute([$currentUserId, $contactId, $contactId, $currentUserId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $messages = [];
        }
        
        // Mark messages as read
        try {
            $stmt = $this->pdo->prepare('
                UPDATE messages 
                SET is_read = 1 
                WHERE sender_id = ? AND receiver_id = ? AND is_read = 0
            ');
            $stmt->execute([$contactId, $currentUserId]);
        } catch (Exception $e) {
            // Ignore errors
        }
        
        $this->view('conversation', [
            'pageTitle' => 'Conversation with ' . $contact['name'],
            'contact' => $contact,
            'messages' => $messages,
        ]);
    }
    
    public function composeMessage(): void
    {
        // Get all alumni users for the recipient dropdown
        try {
            $stmt = $this->pdo->prepare('
                SELECT u.id, u.name, u.username, u.type,
                       ab.firstname, ab.lastname, ab.email
                FROM users u
                LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                WHERE u.type IN (2, 3) AND u.id != ?
                ORDER BY u.name ASC
            ');
            $stmt->execute([$_SESSION['user']['id']]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $users = [];
        }
        
        $this->view('compose-message', [
            'pageTitle' => 'Compose Message',
            'users' => $users,
        ]);
    }
    
    public function getUnreadCount(): int
    {
        $currentUserId = $_SESSION['user']['id'] ?? 0;
        
        try {
            $stmt = $this->pdo->prepare('
                SELECT COUNT(*) as unread FROM messages 
                WHERE receiver_id = ? AND is_read = 0
            ');
            $stmt->execute([$currentUserId]);
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['unread'];
        } catch (Exception $e) {
            return 0;
        }
    }
}

