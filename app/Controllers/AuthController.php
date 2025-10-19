<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Alumni;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            
            $username = trim($this->input('username', ''));
            $password = $this->input('password', '');
            
            $user = $this->userModel->findByUsername($username);
            
            if (!$user && $username === 'admin') {
                // Auto-seed admin
                $this->userModel->create([
                    'name' => 'Admin',
                    'username' => 'admin',
                    'password' => md5('admin123'),
                    'type' => 1,
                    'auto_generated_pass' => '',
                    'alumnus_id' => null,
                ]);
                $user = $this->userModel->findByUsername($username);
            }
            
            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => (int)$user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'type' => (int)$user['type'],
                ];
                $this->redirect('/scratch/dashboard.php');
            }
            
            $this->redirect('/scratch/?login=failed');
        }
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            
            $password = $this->input('password', '');
            $passwordConfirm = $this->input('password_confirm', '');
            
            if ($password !== $passwordConfirm) {
                $this->redirect('/scratch/?register=password_mismatch');
                return;
            }
            
            try {
                $avatarFile = null;
                if (!empty($_FILES['avatar']['name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $safeName = 'avatar_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                    $destDir = __DIR__ . '/../../uploads';
                    if (!is_dir($destDir)) {
                        @mkdir($destDir, 0775, true);
                    }
                    $dest = $destDir . '/' . $safeName;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                        $avatarFile = $safeName;
                    }
                }
                
                $alumnusData = [
                    'firstname' => trim($this->input('firstname', '')),
                    'middlename' => trim($this->input('middlename', '')),
                    'lastname' => trim($this->input('lastname', '')),
                    'gender' => trim($this->input('gender', '')),
                    'batch' => (int)$this->input('batch', 0),
                    'course_id' => (int)$this->input('course_id', 0),
                    'email' => trim($this->input('email', '')),
                    'contact' => trim($this->input('contact', '')),
                    'address' => trim($this->input('address', '')),
                    'connected_to' => '',
                    'avatar' => $avatarFile,
                    'status' => 0,
                    'date_created' => date('Y-m-d H:i:s'),
                ];
                
                $fullName = trim(implode(' ', [
                    $alumnusData['firstname'],
                    $alumnusData['middlename'],
                    $alumnusData['lastname'],
                ]));
                
                $userData = [
                    'name' => $fullName,
                    'username' => trim($this->input('username', '')),
                    'password' => $password,
                    'auto_generated_pass' => '',
                ];
                
                $this->userModel->createWithAlumnus($userData, $alumnusData);
                $this->redirect('/scratch/?register=success');
            } catch (\Exception $e) {
                $this->redirect('/scratch/?register=failed');
            }
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/scratch/');
    }
}

