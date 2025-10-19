<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$pdo = get_pdo();
$courses = $pdo->query('SELECT id, course FROM courses ORDER BY course')->fetchAll();
$careers = $pdo->query('SELECT * FROM careers ORDER BY date_created DESC LIMIT 9')->fetchAll();
$announcements = $pdo->query('SELECT * FROM announcements ORDER BY date_created DESC LIMIT 3')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alumni Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <!-- Top bar using Tailwind -->
  <div class="w-full sticky top-0 z-30 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="flex items-center gap-3">
        <img src="/scratch/images/scc.png" alt="SCC Logo" class="h-16 w-16 object-contain" />
        <div class="text-lg font-semibold tracking-wide text-white">St. Cecilia's Alumni</div>
      </div>
      <div class="hidden md:flex items-center gap-6 text-sm font-medium">
        <a href="#about" class="text-white hover:text-gray-200 transition">About Us</a>
        <a href="#news" class="text-white hover:text-gray-200 transition">News</a>
        <a href="#jobs" class="text-white hover:text-gray-200 transition">Jobs</a>
        <a href="#testimonials" class="text-white hover:text-gray-200 transition">Testimonials</a>
        <a href="#success-stories" class="text-white hover:text-gray-200 transition">Success Stories</a>
        <button class="btn d-flex align-items-center gap-2 px-3 py-2 text-white fw-semibold" style="border-radius: 50px; border: 2px solid white; background: transparent;" data-bs-toggle="modal" data-bs-target="#loginModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
            <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
          </svg>
          Login
        </button>
        <button class="btn d-flex align-items-center gap-2 px-3 py-2 fw-semibold" style="border-radius: 50px; background: white; color: #dc2626; border: none;" data-bs-toggle="modal" data-bs-target="#registerModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc2626" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
          </svg>
          Register
        </button>
      </div>
    </div>
  </div>

  <!-- Hero section with carousel -->
  <section class="relative h-[85vh] md:h-[90vh]">
    <div id="heroCarousel" class="carousel slide carousel-fade h-full" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner h-full">
        <div class="carousel-item active h-full">
          <img src="/scratch/images/scc1.png" alt="Campus 1" class="d-block w-100 h-100 object-fit-cover" style="filter: blur(1.5px) brightness(0.7);"/>
        </div>
        <div class="carousel-item h-full">
          <img src="/scratch/images/scc2.png" alt="Campus 2" class="d-block w-100 h-100 object-fit-cover" style="filter: blur(1.5px) brightness(0.7);"/>
        </div>
        <div class="carousel-item h-full">
          <img src="/scratch/images/scc3.png" alt="Campus 3" class="d-block w-100 h-100 object-fit-cover" style="filter: blur(1.5px) brightness(0.7);"/>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/40" style="backdrop-filter: blur(1px); -webkit-backdrop-filter: blur(1px);"></div>
    <div class="absolute inset-0 z-10 max-w-7xl mx-auto h-full flex items-center px-4">
      <div class="text-white max-w-4xl">
          <p class="leading-tight text-shadow" style="font-family: 'Brush Script MT', cursive; color:rgb(255, 255, 255); font-weight: 600; font-size: 7rem;">"Welcome Home,<br/>St. Cecilia's Alumni!"</p>
         <p class="mt-4 text-white text-shadow" style="font-size: 1.5rem; font-family: 'Poppins', sans-serif;">Reconnect, Remember, and Relive Your College Moments.</p>
        <div class="mt-6 flex gap-3">
          <button class="btn btn-danger d-flex align-items-center gap-2 px-4 py-3 fw-semibold" style="border-radius: 50px; background: #dc2626; border: none;" data-bs-toggle="modal" data-bs-target="#registerModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            </svg>
            Join Now
          </button>
          <button class="btn d-flex align-items-center gap-2 px-4 py-3 fw-semibold text-white" style="border-radius: 50px; background: rgba(220, 38, 38, 0.8); border: none;" data-bs-toggle="modal" data-bs-target="#loginModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
              <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
            </svg>
            Login
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us Section -->
  <section id="about" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-3">About Us</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Learn more about St. Cecilia's College Alumni Association</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="h-full p-6 bg-white rounded-3 shadow-sm">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
            <p class="text-gray-600 mb-4">
              <!-- Add your mission statement here -->
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <p class="text-gray-600">
              <!-- Add more mission details here -->
              Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="h-full p-6 bg-white rounded-3 shadow-sm">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
            <p class="text-gray-600 mb-4">
              <!-- Add your vision statement here -->
              Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </p>
            <p class="text-gray-600">
              <!-- Add more vision details here -->
              Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
          </div>
        </div>
      </div>

      <div class="mt-8 p-6 bg-white rounded-3 shadow-sm">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our History</h3>
        <p class="text-gray-600 mb-4">
          <!-- Add your history here -->
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>
        <p class="text-gray-600">
          <!-- Add more history details here -->
          Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-3">What Our Alumni Say</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Hear from our successful alumni about their experiences and memories</p>
      </div>

      <div class="row g-4">
        <!-- Testimonial Card 1 -->
        <div class="col-md-4">
          <div class="h-full p-6 bg-gray-50 rounded-3 shadow-sm">
            <div class="mb-4">
              <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
              </svg>
            </div>
            <p class="text-gray-600 mb-4">
              <!-- Add testimonial text here -->
              "Lorem ipsum dolor sit amet, consectetur adipiscing elit. The education and memories I gained here shaped my career and life."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                JD
              </div>
              <div>
                <p class="font-bold text-gray-900">John Doe</p>
                <p class="text-sm text-gray-500">Class of 2020</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial Card 2 -->
        <div class="col-md-4">
          <div class="h-full p-6 bg-gray-50 rounded-3 shadow-sm">
            <div class="mb-4">
              <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
              </svg>
            </div>
            <p class="text-gray-600 mb-4">
              <!-- Add testimonial text here -->
              "Lorem ipsum dolor sit amet, consectetur adipiscing elit. The friendships and connections I made here last a lifetime."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                JS
              </div>
              <div>
                <p class="font-bold text-gray-900">Jane Smith</p>
                <p class="text-sm text-gray-500">Class of 2019</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial Card 3 -->
        <div class="col-md-4">
          <div class="h-full p-6 bg-gray-50 rounded-3 shadow-sm">
            <div class="mb-4">
              <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
              </svg>
            </div>
            <p class="text-gray-600 mb-4">
              <!-- Add testimonial text here -->
              "Lorem ipsum dolor sit amet, consectetur adipiscing elit. This institution prepared me for the challenges ahead."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                MB
              </div>
              <div>
                <p class="font-bold text-gray-900">Mike Brown</p>
                <p class="text-sm text-gray-500">Class of 2021</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Stories Section -->
  <section id="success-stories" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-3">Success Stories</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Celebrating the achievements of our outstanding alumni</p>
      </div>

      <div class="row g-4">
        <!-- Success Story 1 -->
        <div class="col-md-6">
          <div class="h-full bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="h-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center p-4">
                  <div class="text-center text-white">
                    <div class="w-24 h-24 rounded-full bg-white text-red-600 flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                      AS
                    </div>
                    <p class="text-sm font-semibold">Class of 2018</p>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">Alex Santos</h3>
                  <p class="text-red-600 font-semibold mb-3">CEO, Tech Startup</p>
                  <p class="text-gray-600 mb-3">
                    <!-- Add success story here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Started from humble beginnings to building a successful tech company.
                  </p>
                  <p class="text-gray-600 text-sm">
                    <!-- Add more details here -->
                    "The values and education I received here gave me the foundation to pursue my entrepreneurial dreams."
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Story 2 -->
        <div class="col-md-6">
          <div class="h-full bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="h-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center p-4">
                  <div class="text-center text-white">
                    <div class="w-24 h-24 rounded-full bg-white text-red-600 flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                      MR
                    </div>
                    <p class="text-sm font-semibold">Class of 2017</p>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">Maria Rodriguez</h3>
                  <p class="text-red-600 font-semibold mb-3">Award-Winning Engineer</p>
                  <p class="text-gray-600 mb-3">
                    <!-- Add success story here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pioneering work in renewable energy and sustainable development.
                  </p>
                  <p class="text-gray-600 text-sm">
                    <!-- Add more details here -->
                    "The mentorship and support from faculty members here inspired me to make a difference in the world."
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Story 3 -->
        <div class="col-md-6">
          <div class="h-full bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="h-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center p-4">
                  <div class="text-center text-white">
                    <div class="w-24 h-24 rounded-full bg-white text-red-600 flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                      DL
                    </div>
                    <p class="text-sm font-semibold">Class of 2016</p>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">David Lee</h3>
                  <p class="text-red-600 font-semibold mb-3">International Consultant</p>
                  <p class="text-gray-600 mb-3">
                    <!-- Add success story here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Working with Fortune 500 companies across multiple continents.
                  </p>
                  <p class="text-gray-600 text-sm">
                    <!-- Add more details here -->
                    "The global perspective and critical thinking skills I developed here opened doors worldwide."
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Story 4 -->
        <div class="col-md-6">
          <div class="h-full bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="h-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center p-4">
                  <div class="text-center text-white">
                    <div class="w-24 h-24 rounded-full bg-white text-red-600 flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                      SP
                    </div>
                    <p class="text-sm font-semibold">Class of 2015</p>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">Sarah Peterson</h3>
                  <p class="text-red-600 font-semibold mb-3">Published Author & Educator</p>
                  <p class="text-gray-600 mb-3">
                    <!-- Add success story here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Multiple bestselling books and inspiring thousands of students.
                  </p>
                  <p class="text-gray-600 text-sm">
                    <!-- Add more details here -->
                    "The passion for learning I discovered here continues to drive my work in education today."
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- News & Announcements Section -->
  <section id="news" class="py-16" style="background: #f9fafb;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="mb-3" style="font-size: 2.5rem; font-weight: 700; color: #7f1d1d; font-family: 'Georgia', serif;">NEWS & ANNOUNCEMENTS</h2>
        <div style="width: 80px; height: 3px; background: linear-gradient(90deg, #dc2626, #991b1b); margin: 0 auto 16px;"></div>
      </div>

      <?php if (!empty($announcements)): ?>
        <div class="row g-4">
          <?php foreach ($announcements as $announcement): ?>
            <div class="col-md-4">
              <div class="card h-100 border-0 shadow-sm" style="border-radius: 0; overflow: hidden; transition: all 0.3s ease;">
                <!-- Image -->
                <div style="height: 280px; background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); position: relative; overflow: hidden;">
                  <?php if (!empty($announcement['image'])): ?>
                    <img src="/scratch/uploads/<?= htmlspecialchars($announcement['image']) ?>" alt="<?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                      <i class="fas fa-bullhorn" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="card-body d-flex flex-column" style="padding: 32px;">
                  <h5 class="card-title mb-3" style="font-size: 1.25rem; font-weight: 700; color: #1f2937; line-height: 1.4;">
                    <?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?>
                  </h5>
                  <p class="card-text text-muted mb-4 flex-grow-1" style="font-size: 0.95rem; line-height: 1.6;">
                    <?= htmlspecialchars(substr(strip_tags($announcement['content'] ?? ''), 0, 150)) ?>...
                  </p>
                  <button class="btn w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); border: none; padding: 12px; transition: all 0.3s ease;" data-bs-toggle="modal" data-bs-target="#announcementModal<?= $announcement['id'] ?>" onmouseover="this.style.background='linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%)'">
                    Read More
                  </button>
                </div>
              </div>
            </div>

            <!-- Announcement Detail Modal -->
            <div class="modal fade" id="announcementModal<?= $announcement['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
                  <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); color: white; padding: 24px 30px; border: none;">
                    <div class="d-flex align-items-center">
                      <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-bullhorn" style="font-size: 24px;"></i>
                      </div>
                      <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;"><?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?></h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body" style="padding: 30px;">
                    <?php if (!empty($announcement['image'])): ?>
                      <img src="/scratch/uploads/<?= htmlspecialchars($announcement['image']) ?>" alt="<?= htmlspecialchars($announcement['title'] ?? 'Announcement') ?>" class="img-fluid mb-4" style="border-radius: 12px; max-height: 400px; width: 100%; object-fit: cover;">
                    <?php endif; ?>
                    <div class="mb-3">
                      <small class="text-muted">
                        <i class="far fa-calendar me-2"></i>
                        <?= date('F d, Y - g:i A', strtotime($announcement['date_created'] ?? 'now')) ?>
                      </small>
                    </div>
                    <div style="line-height: 1.8; color: #374151;">
                      <?= nl2br(htmlspecialchars($announcement['content'] ?? '')) ?>
                    </div>
                  </div>
                  <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
          <p class="text-muted">No announcements available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Available Jobs Section -->
  <section id="jobs" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-3">Available Jobs</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Explore career opportunities from our alumni network and partner companies</p>
      </div>

      <?php if (!empty($careers)): ?>
        <!-- Jobs Carousel -->
        <div id="jobsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
          <div class="carousel-indicators">
            <?php 
            $totalSlides = ceil(count($careers) / 3);
            for ($i = 0; $i < $totalSlides; $i++): 
            ?>
              <button type="button" data-bs-target="#jobsCarousel" data-bs-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $i + 1 ?>"></button>
            <?php endfor; ?>
          </div>
          
          <div class="carousel-inner pb-5">
            <?php 
            $chunks = array_chunk($careers, 3);
            foreach ($chunks as $index => $chunk): 
            ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="row g-4 px-md-5">
                  <?php foreach ($chunk as $job): ?>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm hover:shadow-lg transition-shadow" style="border-left: 4px solid #dc2626;">
                        <div class="card-body p-4">
                          <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 bg-danger bg-opacity-10 p-3 rounded">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc2626" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                              </svg>
                            </div>
                            <div class="ms-3 flex-grow-1">
                              <h5 class="card-title mb-1 fw-bold text-danger"><?= htmlspecialchars($job['job_title']) ?></h5>
                              <h6 class="text-muted mb-0" style="font-size: 0.9rem;"><?= htmlspecialchars($job['company']) ?></h6>
                            </div>
                          </div>
                          
                          <div class="mb-3">
                            <span class="badge bg-light text-dark border mb-2">
                              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-geo-alt-fill me-1" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                              </svg>
                              <?= htmlspecialchars($job['location']) ?>
                            </span>
                          </div>
                          
                          <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars(strip_tags($job['description'])) ?>
                          </p>
                          
                          <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-clock me-1" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                              </svg>
                              <?= date('M d, Y', strtotime($job['date_created'])) ?>
                            </small>
                            <button class="btn btn-sm btn-danger" onclick="alert('Please login to view full job details and apply.')">
                              View Details
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          
          <?php if ($totalSlides > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#jobsCarousel" data-bs-slide="prev" style="width: 5%;">
              <span class="carousel-control-prev-icon bg-danger rounded-circle p-3" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#jobsCarousel" data-bs-slide="next" style="width: 5%;">
              <span class="carousel-control-next-icon bg-danger rounded-circle p-3" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc2626" class="mb-3" viewBox="0 0 16 16">
            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
          </svg>
          <p class="text-muted">No job listings available at the moment. Check back soon!</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-lg border-0">
        <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <form method="post" action="/scratch/auth_login.php" class="p-4">
          <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
          <div class="text-center mb-4">
            <div class="mx-auto mb-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;background:linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
              </svg>
            </div>
            <h4 class="fw-bold text-danger mb-1">Alumni Network</h4>
            <p class="text-muted small mb-0">Sign in to your account</p>
          </div>
          <div class="mb-3">
            <label class="form-label text-danger fw-medium small">Username</label>
            <input class="form-control rounded-3 py-2" name="username" placeholder="Enter your username" required style="background:#fef2f2;border-color:#fecaca;">
          </div>
          <div class="mb-4">
            <label class="form-label text-danger fw-medium small">Password</label>
            <input type="password" class="form-control rounded-3 py-2" name="password" placeholder="Enter your password" required style="background:#fef2f2;border-color:#fecaca;">
          </div>
          <button class="btn btn-danger w-100 py-2 fw-medium rounded-3 d-flex align-items-center justify-content-center gap-2" style="background:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
              <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
            </svg>
            Login
          </button>
          <div class="text-center mt-4">
            <span class="text-muted small">Don't have an account?</span>
            <a href="#" class="text-danger fw-medium text-decoration-underline small" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Create Account</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-lg border-0">
        <button type="button" class="btn-close position-absolute end-0 top-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <form method="post" action="/scratch/auth_register.php" enctype="multipart/form-data">
          <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
          <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
          <div class="text-center mb-4">
            <div class="mx-auto mb-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;background:linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
              </svg>
            </div>
            <h4 class="fw-bold text-danger mb-1">Create New Account</h4>
            <p class="text-muted small mb-0">Join the alumni network</p>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">First Name <span>*</span></label>
              <input class="form-control rounded-3" name="firstname" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Middle Name</label>
              <input class="form-control rounded-3" name="middlename" style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Last Name <span>*</span></label>
              <input class="form-control rounded-3" name="lastname" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Gender <span>*</span></label>
              <select class="form-select rounded-3" name="gender" required style="background:#fef2f2;border-color:#fecaca;">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Batch / School Year <span>*</span></label>
              <select class="form-select rounded-3" name="batch" required style="background:#fef2f2;border-color:#fecaca;">
                <option value="">-- Select Batch Year --</option>
                <?php for ($y=(int)date('Y'); $y>=1980; $y--): ?>
                  <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Course <span>*</span></label>
              <select class="form-select rounded-3" name="course_id" required style="background:#fef2f2;border-color:#fecaca;">
                <option value="">-- Choose Course --</option>
                <?php foreach ($courses as $c): ?>
                  <option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['course']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Email <span>*</span></label>
              <input type="email" class="form-control rounded-3" name="email" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Mobile <span>*</span></label>
              <input class="form-control rounded-3" name="contact" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-12">
              <label class="form-label text-danger fw-medium small">Address <span>*</span></label>
              <textarea class="form-control rounded-3" name="address" rows="2" required style="background:#fef2f2;border-color:#fecaca;"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Username <span>*</span></label>
              <input class="form-control rounded-3" name="username" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Password <span>*</span></label>
              <input type="password" class="form-control rounded-3" name="password" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Confirm Password <span>*</span></label>
              <input type="password" class="form-control rounded-3" name="password_confirm" required style="background:#fef2f2;border-color:#fecaca;">
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-medium small">Avatar (Optional)</label>
              <input type="file" class="form-control rounded-3" name="avatar" style="background:#fef2f2;border-color:#fecaca;">
            </div>
          </div>
          
          <!-- Document Upload Section -->
          <div class="mt-4 p-3" style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc2626;">
            <h6 class="text-danger fw-bold mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                <path d="M8.5 1.5A1.5 1.5 0 0 0 7 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2h-5a1.5 1.5 0 0 0-1.5 1.5z"/>
              </svg>
              Verification Documents
            </h6>
            <p class="text-muted small mb-3">Upload required documents to verify your alumni status</p>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label text-danger fw-medium small">Transcript of Records (TOR) <span>*</span></label>
                <input type="file" name="documents[tor]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                <small class="text-muted">PDF, JPG, PNG (Max 5MB)</small>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label text-danger fw-medium small">Diploma/Certificate <span>*</span></label>
                <input type="file" name="documents[diploma]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                <small class="text-muted">PDF, JPG, PNG (Max 5MB)</small>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium small">ID Card (Optional)</label>
                <input type="file" name="documents[id_card]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Student ID or Alumni ID</small>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-medium small">Other Documents (Optional)</label>
                <input type="file" name="documents[other]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Any additional verification documents</small>
              </div>
            </div>
            
            <div class="alert alert-info small mb-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
              <strong>Note:</strong> Your account will be reviewed by administrators. You'll be notified once approved.
            </div>
          </div>
          
          <button type="submit" class="btn btn-danger w-100 py-2 fw-medium rounded-3 mt-4 d-flex align-items-center justify-content-center gap-2" style="background:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            </svg>
            Create Account
          </button>
          <div class="text-center mt-3 mb-2">
            <span class="text-muted small">Already have an account?</span>
            <a href="#" class="text-danger fw-medium text-decoration-underline small" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In</a>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer id="contact" class="py-5" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
    <div class="max-w-7xl mx-auto px-4">
      <div class="row g-4 text-white">
        <div class="col-md-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="/scratch/images/scc.png" alt="SCC Logo" class="h-16 w-16 object-contain bg-white rounded-circle p-2" style="height: 4rem; width: 4rem;">
            <h5 class="mb-0 fw-bold">St. Cecilia's College</h5>
          </div>
          <p class="small mb-0">Cebu South National Highway, Ward II, Minglanilla, Cebu</p>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#about" class="text-white text-decoration-none hover:text-gray-200">About Us</a></li>
            <li class="mb-2"><a href="#testimonials" class="text-white text-decoration-none hover:text-gray-200">Testimonials</a></li>
            <li class="mb-2"><a href="#success-stories" class="text-white text-decoration-none hover:text-gray-200">Success Stories</a></li>
            <li class="mb-2"><a href="/scratch/dashboard.php" class="text-white text-decoration-none hover:text-gray-200">Dashboard</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Contact Us</h5>
          <ul class="list-unstyled small">
            <li class="mb-2 d-flex align-items-start gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
              </svg>
              Cebu South National Highway, Ward II, Minglanilla, Cebu
            </li>
            <li class="mb-2 d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
              </svg>
              (032) 123-4567
            </li>
            <li class="mb-2 d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
              </svg>
              alumni@stcecilia.edu.ph
            </li>
          </ul>
        </div>
      </div>
      <hr class="my-4 border-white opacity-25">
      <div class="text-center small">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> St. Cecilia's College - Cebu, Inc. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Registration Success Modal -->
  <div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; max-width: 400px;">
        <!-- Header -->
        <div class="text-center p-4" style="background: linear-gradient(135deg, #10b981, #059669);">
          <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
              </svg>
            </div>
          </div>
          <h4 class="text-white mb-1" style="font-weight: 700; font-size: 20px;">Registration Successful!</h4>
          <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Your account has been created</p>
        </div>

        <!-- Body -->
        <div class="p-4 text-center">
          <div class="mb-3">
            <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#10b981" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg>
            </div>
            <h5 class="mb-2" style="color: #065f46; font-weight: 700; font-size: 16px;">Account Under Review</h5>
            <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.5;">
              Your account is being reviewed by administrators. You'll be notified once approved.
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 pt-0">
          <button type="button" class="btn w-100 py-2 fw-semibold text-white" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 8px; border: none; font-size: 14px;" data-bs-dismiss="modal">
            Got It, Thanks!
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Registration Error Modal -->
  <div class="modal fade" id="registrationErrorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; max-width: 400px;">
        <!-- Header -->
        <div class="text-center p-4" style="background: linear-gradient(135deg, #dc3545, #c82333);">
          <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
          </div>
          <h4 class="text-white mb-1" style="font-weight: 700; font-size: 20px;">Registration Failed</h4>
          <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Please check the errors below</p>
        </div>

        <!-- Body -->
        <div class="p-4 text-center">
          <div class="mb-3">
            <div style="width: 40px; height: 40px; background: #fef2f2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
            <h5 class="mb-2" style="color: #dc3545; font-weight: 700; font-size: 16px;">Registration Error</h5>
            <div id="errorMessage" class="text-muted mb-0" style="font-size: 14px; line-height: 1.5;">
              <!-- Error message will be inserted here -->
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 pt-0">
          <button type="button" class="btn w-100 py-2 fw-semibold text-white" style="background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 8px; border: none; font-size: 14px;" data-bs-dismiss="modal">
            Try Again
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Login Error Modal -->
  <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; max-width: 400px;">
        <!-- Header -->
        <div class="text-center p-4" style="background: linear-gradient(135deg, #dc3545, #c82333);">
          <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
          </div>
          <h4 class="text-white mb-1" style="font-weight: 700; font-size: 20px;">Login Failed</h4>
          <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Please check your credentials</p>
        </div>

        <!-- Body -->
        <div class="p-4 text-center">
          <div class="mb-3">
            <div style="width: 40px; height: 40px; background: #fef2f2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
            <h5 class="mb-2" style="color: #dc3545; font-weight: 700; font-size: 16px;">Invalid Credentials</h5>
            <div id="loginErrorMessage" class="text-muted mb-0" style="font-size: 14px; line-height: 1.5;">
              <!-- Error message will be inserted here -->
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 pt-0">
          <button type="button" class="btn w-100 py-2 fw-semibold text-white" style="background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 8px; border: none; font-size: 14px;" data-bs-dismiss="modal">
            Try Again
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Login Unverified Modal -->
  <div class="modal fade" id="loginUnverifiedModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; max-width: 400px;">
        <!-- Header -->
        <div class="text-center p-4" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
          <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
          </div>
          <h4 class="text-white mb-1" style="font-weight: 700; font-size: 20px;">Account Pending</h4>
          <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Your account is under review</p>
        </div>

        <!-- Body -->
        <div class="p-4 text-center">
          <div class="mb-3">
            <div style="width: 40px; height: 40px; background: #fff3cd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffc107" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
            </div>
            <h5 class="mb-2" style="color: #856404; font-weight: 700; font-size: 16px;">Account Not Verified</h5>
            <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.5;">
              Your account is currently under review by administrators. You'll be notified once your account has been verified and activated.
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 pt-0">
          <button type="button" class="btn w-100 py-2 fw-semibold text-white" style="background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: 8px; border: none; font-size: 14px;" data-bs-dismiss="modal">
            Understood
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show registration success modal if redirected with success parameter
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      
      if (urlParams.get('register') === 'success') {
        const successModal = new bootstrap.Modal(document.getElementById('registrationSuccessModal'));
        successModal.show();
        
        // Clean up URL after showing modal
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      } else if (urlParams.get('register') === 'error') {
        const errorMessage = urlParams.get('message') || 'An unknown error occurred during registration.';
        document.getElementById('errorMessage').textContent = errorMessage;
        
        const errorModal = new bootstrap.Modal(document.getElementById('registrationErrorModal'));
        errorModal.show();
        
        // Clean up URL after showing modal
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      } else if (urlParams.get('login') === 'incorrect') {
        document.getElementById('loginErrorMessage').textContent = 'Invalid username or password. Please check your credentials and try again.';
        
        const loginErrorModal = new bootstrap.Modal(document.getElementById('loginErrorModal'));
        loginErrorModal.show();
        
        // Clean up URL after showing modal
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      } else if (urlParams.get('login') === 'unverified') {
        const unverifiedModal = new bootstrap.Modal(document.getElementById('loginUnverifiedModal'));
        unverifiedModal.show();
        
        // Clean up URL after showing modal
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      }
    });
  </script>
  <style>
    .modal-backdrop.show {
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      background-color: rgba(0, 0, 0, 0.5);
    }

    /* News Card Hover Effect */
    #news .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }

    /* Success Modal Animations */
    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes checkmark {
      0% {
        transform: scale(0) rotate(-45deg);
        opacity: 0;
      }
      50% {
        transform: scale(1.2) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
      }
    }
  </style>
</body>
</html>


