<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        return $this->db->fetch($sql, [$username]);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }

    public function createWithAlumnus(array $userData, array $alumnusData): int
    {
        $this->db->beginTransaction();
        
        try {
            // Create alumnus record
            $alumnus = new Alumni();
            $alumnusId = $alumnus->create($alumnusData);
            
            // Create user record
            $userData['alumnus_id'] = $alumnusId;
            $userData['type'] = 3; // Alumnus type
            $userData['password'] = md5($userData['password']); // Legacy MD5
            
            $userId = $this->create($userData);
            
            $this->db->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        // Support legacy MD5
        return hash_equals($hashedPassword, md5($password)) || 
               hash_equals($hashedPassword, $password);
    }

    public function isAdmin(array $user): bool
    {
        return (int)($user['type'] ?? 3) === 1;
    }

    public function isOfficer(array $user): bool
    {
        return (int)($user['type'] ?? 3) === 2;
    }
}

