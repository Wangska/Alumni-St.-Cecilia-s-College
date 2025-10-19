<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/scratch/login.php');
            return;
        }
        
        $this->view('dashboard', [
            'user' => $_SESSION['user'],
        ]);
    }
}

