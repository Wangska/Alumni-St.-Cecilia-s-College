<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Announcement extends Model
{
    protected string $table = 'announcements';

    public function getAllOrdered(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY date_posted DESC";
        return $this->db->fetchAll($sql);
    }

    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY date_posted DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}

