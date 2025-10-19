<?php

namespace App\Models;

use App\Core\Model;

class ForumTopic extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'forum_topics';
    }
    
    /**
     * Get all forum topics with user information
     */
    public function getAllWithUser()
    {
        $sql = "SELECT ft.*, u.name as author_name, u.username 
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                ORDER BY ft.date_created DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get forum topic with comment count
     */
    public function getWithCommentCount($id)
    {
        $sql = "SELECT ft.*, u.name as author_name, u.username,
                (SELECT COUNT(*) FROM forum_comments WHERE topic_id = ft.id) as comment_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                WHERE ft.id = ?";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all topics with comment counts
     */
    public function getAllWithCommentCounts()
    {
        $sql = "SELECT ft.*, u.name as author_name, u.username,
                (SELECT COUNT(*) FROM forum_comments WHERE topic_id = ft.id) as comment_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                ORDER BY ft.date_created DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

