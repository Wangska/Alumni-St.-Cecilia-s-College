<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Career extends Model
{
    protected string $table = 'careers';

    public function getAllOrdered(): array
    {
        $sql = "SELECT c.*, u.name as posted_by 
                FROM {$this->table} c 
                LEFT JOIN users u ON u.id = c.user_id 
                ORDER BY c.date_created DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getRecent(int $limit = 10): array
    {
        $sql = "SELECT c.*, u.name as posted_by 
                FROM {$this->table} c 
                LEFT JOIN users u ON u.id = c.user_id 
                ORDER BY c.date_created DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}

