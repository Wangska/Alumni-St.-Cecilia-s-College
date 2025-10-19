<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;

class HomeController extends Controller
{
    public function index(): void
    {
        $courseModel = new Course();
        $courses = $courseModel->getAllOrdered();
        
        $this->view('home', [
            'courses' => $courses,
        ]);
    }
}

