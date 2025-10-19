<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Alumni;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Gallery;
use App\Models\Career;
use App\Models\ForumTopic;

class AdminController extends Controller
{
    private Alumni $alumniModel;
    private Event $eventModel;
    private Announcement $announcementModel;
    private Course $courseModel;
    private Gallery $galleryModel;
    private Career $careerModel;
    private ForumTopic $forumTopicModel;

    public function __construct()
    {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user'])) {
            $this->redirect('/scratch/');
            exit;
        }
        
        if (($_SESSION['user']['type'] ?? 0) != 1) {
            $this->redirect('/scratch/dashboard.php');
            exit;
        }
        
        $this->alumniModel = new Alumni();
        $this->eventModel = new Event();
        $this->announcementModel = new Announcement();
        $this->courseModel = new Course();
        $this->galleryModel = new Gallery();
        $this->careerModel = new Career();
        $this->forumTopicModel = new ForumTopic();
    }

    public function dashboard(): void
    {
        // Get all alumni
        $allAlumni = $this->alumniModel->getAll();
        $verifiedAlumni = array_filter($allAlumni, fn($a) => ($a['status'] ?? 0) == 1);
        $pendingAlumni = array_filter($allAlumni, fn($a) => ($a['status'] ?? 0) == 0);
        
        // Get events
        $upcomingEvents = $this->eventModel->getUpcoming();
        $allEvents = $this->eventModel->getAllOrdered();
        
        // Get announcements
        $allAnnouncements = $this->announcementModel->getAll();
        $recentAnnouncements = array_slice($allAnnouncements, 0, 5);
        
        // Get courses
        $allCourses = $this->courseModel->getAll();
        
        // Get recent activity logs
        require_once __DIR__ . '/../../inc/logger.php';
        $recentLogs = \ActivityLogger::getRecentLogs(10);
        
        // Prepare statistics
        $stats = [
            'total_alumni' => count($allAlumni),
            'verified_alumni' => count($verifiedAlumni),
            'pending_alumni' => count($pendingAlumni),
            'upcoming_events' => count($upcomingEvents),
            'total_events' => count($allEvents),
            'active_events' => count($upcomingEvents),
            'total_announcements' => count($allAnnouncements),
            'total_courses' => count($allCourses),
        ];
        
        $this->view('admin.dashboard', [
            'stats' => $stats,
            'announcements' => $recentAnnouncements,
            'events' => array_slice($upcomingEvents, 0, 5),
            'recentLogs' => $recentLogs,
        ]);
    }
    
    public function alumni(): void
    {
        $alumni = $this->alumniModel->getVerifiedWithCourse();
        
        $this->view('admin.alumni', [
            'alumni' => $alumni,
        ]);
    }
    
    public function events(): void
    {
        $events = $this->eventModel->getAllOrdered();
        
        $this->view('admin.events', [
            'events' => $events,
        ]);
    }
    
    public function announcements(): void
    {
        $announcements = $this->announcementModel->getAll();
        
        $this->view('admin.announcements', [
            'announcements' => $announcements,
        ]);
    }
    
    public function courses(): void
    {
        $courses = $this->courseModel->getAllOrdered();
        
        $this->view('admin.courses', [
            'courses' => $courses,
        ]);
    }
    
    public function users(): void
    {
        // Get all alumni
        $allAlumni = $this->alumniModel->getAllWithCourse();
        
        // Separate pending and verified
        $pendingAlumni = array_filter($allAlumni, fn($a) => ($a['status'] ?? 0) == 0);
        $verifiedAlumni = array_filter($allAlumni, fn($a) => ($a['status'] ?? 0) == 1);
        
        $stats = [
            'pending' => count($pendingAlumni),
            'verified' => count($verifiedAlumni),
            'total' => count($allAlumni),
        ];
        
        $this->view('admin.users', [
            'pendingAlumni' => $pendingAlumni,
            'stats' => $stats,
        ]);
    }
    
    public function galleries(): void
    {
        $galleries = $this->galleryModel->getAllOrdered();
        
        $this->view('admin.galleries', [
            'galleries' => $galleries,
        ]);
    }
    
    public function careers(): void
    {
        $careers = $this->careerModel->getAllOrdered();
        
        $this->view('admin.careers', [
            'careers' => $careers,
        ]);
    }
    
    public function settings(): void
    {
        // Get system statistics
        $allAlumni = $this->alumniModel->getAll();
        $verifiedAlumni = array_filter($allAlumni, fn($a) => ($a['status'] ?? 0) == 1);
        $allEvents = $this->eventModel->getAll();
        $allAnnouncements = $this->announcementModel->getAll();
        $allGalleries = $this->galleryModel->getAll();
        $allCourses = $this->courseModel->getAll();
        
        $systemStats = [
            'total_alumni' => count($allAlumni),
            'verified_alumni' => count($verifiedAlumni),
            'total_events' => count($allEvents),
            'total_announcements' => count($allAnnouncements),
            'gallery_images' => count($allGalleries),
            'total_courses' => count($allCourses),
        ];
        
        $this->view('admin.settings', [
            'user' => $_SESSION['user'] ?? [],
            'systemStats' => $systemStats,
        ]);
    }
    
    public function forum(): void
    {
        $forumTopics = $this->forumTopicModel->getAllWithCommentCounts();
        
        $this->view('admin.forum', [
            'forumTopics' => $forumTopics,
        ]);
    }
}

