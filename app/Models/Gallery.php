<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    protected string $table = 'gallery';

    public function getAllOrdered(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created DESC";
        return $this->db->fetchAll($sql);
    }
}

