<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Alumni extends Model
{
    protected string $table = 'alumnus_bio';

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }

    public function getAllWithCourses(): array
    {
        $sql = "SELECT a.*, c.course 
                FROM {$this->table} a 
                LEFT JOIN courses c ON c.id = a.course_id 
                ORDER BY a.id DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllWithCourse(): array
    {
        return $this->getAllWithCourses();
    }

    public function getVerifiedWithCourse(): array
    {
        $sql = "SELECT a.*, c.course 
                FROM {$this->table} a 
                LEFT JOIN courses c ON c.id = a.course_id 
                WHERE a.status = 1
                ORDER BY a.id DESC";
        return $this->db->fetchAll($sql);
    }

    public function findWithCourse(int $id): ?array
    {
        $sql = "SELECT a.*, c.course 
                FROM {$this->table} a 
                LEFT JOIN courses c ON c.id = a.course_id 
                WHERE a.id = ? 
                LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    public function searchByBatch(string $batch): array
    {
        return $this->where('batch', $batch);
    }

    public function searchByCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId);
    }
}

