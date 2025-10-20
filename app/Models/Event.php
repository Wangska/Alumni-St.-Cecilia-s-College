<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Event extends Model
{
    protected string $table = 'events';

    public function getUpcoming(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE schedule >= NOW() ORDER BY schedule ASC";
        return $this->db->fetchAll($sql);
    }

    public function getPast(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE schedule < NOW() ORDER BY schedule DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllOrdered(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY schedule DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllWithParticipantCounts(): array
    {
        $sql = "
            SELECT e.*, 
                   COUNT(ec.id) as participant_count
            FROM {$this->table} e 
            LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
            GROUP BY e.id 
            ORDER BY e.schedule DESC
        ";
        return $this->db->fetchAll($sql);
    }
}

