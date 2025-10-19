<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Course extends Model
{
    protected string $table = 'courses';

    public function getAllOrdered(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY course ASC";
        return $this->db->fetchAll($sql);
    }
}

