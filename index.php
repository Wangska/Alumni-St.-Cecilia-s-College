<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$pdo = get_pdo();
$courses = $pdo->query('SELECT id, course FROM courses ORDER BY course')->fetchAll();
$careers = $pdo->query('SELECT * FROM careers ORDER BY date_created DESC LIMIT 9')->fetchAll();
$announcements = $pdo->query('SELECT * FROM announcements ORDER BY date_created DESC LIMIT 3')->fetchAll();

// Landing page events (public view)
try {
    $stmt = $pdo->prepare('
        SELECT e.*, COUNT(ec.id) as participant_count
        FROM events e 
        LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
        GROUP BY e.id 
        ORDER BY e.schedule ASC 
        LIMIT 3
    ');
    $stmt->execute();
    $events = $stmt->fetchAll();
} catch (Exception $e) {
    $events = [];
}

// Public success stories (approved only)
try {
    $stmt = $pdo->prepare('
        SELECT ss.*, ab.firstname, ab.lastname 
        FROM success_stories ss 
        LEFT JOIN users u ON ss.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        WHERE ss.status = 1 
        ORDER BY ss.created DESC 
        LIMIT 6
    ');
    $stmt->execute();
    $successStories = $stmt->fetchAll();
} catch (Exception $e) {
    $successStories = [];
}

// Public testimonials (approved only)
try {
    $stmt = $pdo->prepare('
        SELECT t.*, ab.firstname, ab.lastname 
        FROM testimonials t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
        WHERE t.status = 1
        ORDER BY t.created DESC
        LIMIT 6
    ');
    $stmt->execute();
    $testimonials = $stmt->fetchAll();
} catch (Exception $e) {
    $testimonials = [];
}

// Public gallery images
try {
    $stmt = $pdo->prepare('
        SELECT id, image_path, about, created
        FROM gallery
        ORDER BY created DESC
        LIMIT 20
    ');
    $stmt->execute();
    $galleryImages = $stmt->fetchAll();
} catch (Exception $e) {
    $galleryImages = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alumni Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Smooth scroll for in-page navigation */
    html { scroll-behavior: smooth; }
    /* Prevent anchor targets from hiding under sticky header */
    section { scroll-margin-top: 88px; }
    /* About section theming - SCC red with soft gradient shapes */
    .about-section { position: relative; background: linear-gradient(180deg, #fff5f5 0%, #ffffff 100%); }
    .about-bg-shape { position: absolute; inset: 0; background:
      radial-gradient(1200px 600px at -10% -10%, rgba(220,38,38,0.12), transparent 60%),
      radial-gradient(900px 480px at 110% 0%, rgba(220,38,38,0.08), transparent 55%),
      radial-gradient(800px 520px at 50% 120%, rgba(220,38,38,0.06), transparent 60%);
      pointer-events: none; }
    #about .card,
    #about .h-full { border: 1px solid #f1f5f9; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    #about .card:hover,
    #about .h-full:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(220,38,38,0.12); border-color: #fecaca; }
    #about .icon-circle { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #7f1d1d, #dc2626); color:#fff; box-shadow: 0 8px 24px rgba(220,38,38,.25); }
    /* About section title and headings in SCC red and serif font */
    #about .section-title { color:#dc2626 !important; font-family: 'Times New Roman', serif; font-weight:700; letter-spacing:.5px; }
    #about h3 { color:#dc2626 !important; font-family: 'Times New Roman', serif; font-weight:700; }
  </style>
</head>
<body>
  <div id="top"></div>
  <!-- Top bar using Tailwind -->
  <div class="w-full sticky top-0 z-30 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <a href="#top" class="flex items-center gap-3 text-decoration-none" style="color:#ffffff;">
        <img src="/scratch/images/scc.png" alt="SCC Logo" class="h-16 w-16 object-contain" />
        <div class="text-lg font-semibold tracking-wide text-white">Alumni Nexus</div>
      </a>
      <div class="hidden md:flex items-center gap-6 text-sm font-medium">
        <a href="#about" class="text-white hover:text-gray-200 transition">About Us</a>
        <a href="#news" class="text-white hover:text-gray-200 transition">News</a>
        <a href="#events" class="text-white hover:text-gray-200 transition">Events</a>
        <a href="#jobs" class="text-white hover:text-gray-200 transition">Jobs</a>
        <a href="#success-stories" class="text-white hover:text-gray-200 transition">Success Stories</a>
        <a href="#testimonials" class="text-white hover:text-gray-200 transition">Testimonials</a>
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

  <!-- Gallery (Glassmorphism, compact, 1-by-1 looping carousel) -->
  <section id="gallery" class="py-12" style="background:#f9fafb;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-4">
        <h2 class="text-3xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">GALLERY</h2>
        <div class="mx-auto" style="width: 80px; height: 3px; background: #dc2626;"></div>
      </div>
      <div class="mx-auto" style="max-width: 960px;">
        <div class="p-3 rounded-4 shadow-sm position-relative" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.35);">
          <?php if (!empty($galleryImages)): ?>
          <style>
            /* Subtle glass edge and hover lift */
            #gallery .glass-wrap:hover { box-shadow: 0 16px 40px rgba(220,38,38,0.10); }
            #gallery .carousel-control-prev-icon, #gallery .carousel-control-next-icon { display:none; }
            #gallery .control-btn {
              width: 44px; height: 44px; border-radius: 12px;
              background: rgba(15, 23, 42, 0.25);
              backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
              color: #ffffff; display: inline-flex; align-items: center; justify-content: center;
              border: 1px solid rgba(255,255,255,0.35);
              box-shadow: 0 8px 20px rgba(0,0,0,0.20);
              transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
            }
            #gallery .control-btn:hover { transform: translateY(-2px); background: rgba(220,38,38,0.22); box-shadow: 0 12px 28px rgba(220,38,38,0.35); }
          </style>
          <div id="landingGalleryCarousel" class="carousel slide glass-wrap" data-bs-ride="carousel" data-bs-interval="2500" data-bs-wrap="true">
            <div class="carousel-inner" style="height: 420px; border-radius: 14px; overflow:hidden;">
              <?php foreach ($galleryImages as $index => $g): $file = (string)($g['image_path'] ?? ''); $hasFile = $file !== '' && file_exists(__DIR__ . '/uploads/' . $file); ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> h-100">
                <?php if ($hasFile): ?>
                  <img src="/scratch/uploads/<?= htmlspecialchars($file) ?>" alt="<?= htmlspecialchars($g['about'] ?? 'Gallery image') ?>" class="d-block w-100 h-100" style="object-fit:cover;" />
                <?php else: ?>
                  <div class="d-flex align-items-center justify-content-center h-100 w-100" style="background:linear-gradient(135deg,#f3f4f6,#e5e7eb);">
                    <div class="text-center">
                      <i class="fas fa-image text-muted" style="font-size:3rem;"></i>
                      <div class="text-muted mt-2 small">No image available</div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#landingGalleryCarousel" data-bs-slide="prev">
              <span class="control-btn" aria-hidden="true">
                <img src="/scratch/images/icons8-arrow-96.png" alt="Prev" style="width:18px;height:18px;object-fit:contain;transform: rotate(0deg);" />
              </span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#landingGalleryCarousel" data-bs-slide="next">
              <span class="control-btn" aria-hidden="true">
                <img src="/scratch/images/icons8-arrow-96.png" alt="Next" style="width:18px;height:18px;object-fit:contain;transform: rotate(180deg);" />
              </span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
          <?php else: ?>
            <div class="text-center py-5" style="background:rgba(255,255,255,0.35); border-radius:14px;">
              <i class="fas fa-images text-muted" style="font-size:2rem;"></i>
              <div class="text-muted mt-2">No gallery images yet</div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us Section -->
  <section id="about" class="about-section py-16">
    <div class="about-bg-shape"></div>
    <div class="max-w-7xl mx-auto px-4 position-relative" style="z-index: 1;">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold section-title mb-3">About Us</h2>
        <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
        <p class="text-gray-600 max-w-2xl mx-auto">Learn more about St. Cecilia's College Alumni Association</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="h-full p-6 bg-white rounded-3 shadow-sm">
            <h3 class="text-2xl font-bold mb-4 d-flex align-items-center gap-2"><i class="fas fa-bullseye text-danger"></i>Our Mission</h3>
            <p class="text-gray-600 mb-3">In pursuing the mission, St. Cecilia's College commits itself to:</p>
            <ol class="text-gray-700 ps-3" style="line-height: 1.8;">
              <li class="mb-2">Cultivate and inculcate Christian values in pupils/students to become men and women of faith and integrity;</li>
              <li class="mb-2">Provide students with knowledge and skills in academics, technology, and the arts through modern teaching methods and techniques;</li>
              <li class="mb-2">Foster the development of love for country and service to fellowmen;</li>
              <li class="mb-2">Upgrade teachers' skills and competencies in instruction and management through a Faculty Development Program;</li>
              <li class="mb-2">Develop the critical thinking skills of students;</li>
              <li class="mb-2">Provide opportunities for students to grow and lead;</li>
              <li>Inculcate in the students the love, care, and preservation of Mother Nature.</li>
            </ol>
          </div>
        </div>
        <div class="col-md-6">
          <div class="h-full p-6 bg-white rounded-3 shadow-sm">
            <h3 class="text-2xl font-bold mb-4 d-flex align-items-center gap-2"><i class="fas fa-lightbulb text-warning"></i>Our Vision</h3>
            <p class="text-gray-700" style="line-height: 1.8;">SCC is a non-stock, non-profit educational institution that envisions itself to be a Center of Excellence in Academics, Technology, and the Arts. It aspires to produce professionals and leaders who are globally competitive, imbued with Christian values, integrity, patriotism, and stewardship, through quality human education.</p>
          </div>
        </div>
      </div>

      <!-- Core Values -->
      <div class="mt-8 p-6 bg-white rounded-3 shadow-sm">
         <h3 class="text-2xl font-bold mb-4 d-flex align-items-center gap-2"><i class="fas fa-heart text-danger"></i>Core Values</h3>
        <div class="row g-4">
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-cross text-danger"></i>Christ-centeredness</h5><p class="mb-0 text-gray-700">Cecilians put Christ at the center of thoughts and actions for the common good, showing sympathy and empathy for others.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-award text-warning"></i>Excellence</h5><p class="mb-0 text-gray-700">Cecilians strive to excel in imparting knowledge and skills with enthusiasm and goodwill.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-handshake text-success"></i>Commitment</h5><p class="mb-0 text-gray-700">Cecilians give their best and go beyond expectations; they are self‑motivated and holistic in growth.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-shield-alt text-primary"></i>Integrity</h5><p class="mb-0 text-gray-700">Cecilians act honestly and responsibly, guided by moral conviction to do what is right.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-flag text-info"></i>Love of Country</h5><p class="mb-0 text-gray-700">Cecilians prioritize national interest, respect Filipino culture and tradition, support local products, and serve the community.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-lightbulb text-warning"></i>Innovativeness</h5><p class="mb-0 text-gray-700">Cecilians are open‑minded, creative, functional, and resourceful—logical and artistic in bringing ideas to life.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-palette" style="color:#8b5cf6"></i>Arts Lover</h5><p class="mb-0 text-gray-700">Cecilians have a heart for the arts—creating, appreciating, and understanding God’s creation and human works to inspire others.</p></div></div>
           <div class="col-md-6 col-lg-3"><div class="h-100 p-4 bg-gray-50 rounded-3 border"><h5 class="fw-bold mb-2 d-flex align-items-center gap-2"><i class="fas fa-seedling text-success"></i>Nurturance</h5><p class="mb-0 text-gray-700">Cecilians care for God’s creation, value knowledge and skills, and nurture peaceful, harmonious relationships.</p></div></div>
        </div>
      </div>

      <!-- History -->
      <div class="mt-4 p-6 bg-white rounded-3 shadow-sm">
         <h3 class="text-2xl font-bold mb-3 d-flex align-items-center gap-2"><i class="fas fa-history text-secondary"></i>Our History</h3>
        <h5 class="fw-bold text-gray-800 mb-2">Formative Years (1999–2009)</h5>
        <p class="text-gray-700 mb-3" style="line-height: 1.8;">The formative years saw the development of the first ten pupils and the construction of essential facilities. Enrollment grew steadily, and within five years SCC was considered the “Performing Arts Center of the South” for its Summer Workshops in Music, Painting, Speech, and more. After the fifth year, the founder sold the school to the current owner, Mrs. Rosalina N. Go.</p>
        <p class="text-gray-700 mb-3" style="line-height: 1.8;">The Preschool expanded to Primary and Elementary; a complete High School followed. In June 2005, SCC was registered with the SEC as a non‑stock, non‑profit corporation under the name St. Cecilia’s College – Cebu, Inc., with Mrs. Maria de la Rosa as Principal. As the population grew, SCC rented nearby space for Elementary and High School while constructing the five‑story St. La Salle Building at the original Preschool location.</p>
        <p class="text-gray-700 mb-3" style="line-height: 1.8;">The Elementary Department held its first graduation in March 2006, followed by the High School in March 2007 with seven graduates. The College Department opened in June 2007 offering BEED, BSED, BSIT, BSHM, and BSBA under College Dean Mr. Alfredo Moreno, Jr. The sixth story of the building was completed in December 2007; by June 2008, Grades 4–6, High School, and College occupied the new facility.</p>
        <p class="text-gray-700 mb-0" style="line-height: 1.8;">In July 2008, the Preschool, Elementary, SPED, and Secondary curricula received Government Recognition from DepEd. In November 2008, Pensionne St. Cecilia—now the Basic Home Economics building—was acquired. To further enhance quality, SCC sought LASSO assistance in February 2009 and was granted Consultancy status, with services led by Br. Ophelia S. Fugoso AFSC, Ms. Isabel Macrina V. Encabo, and Mr. Mark Joel N. Go.</p>
      </div>
    </div>
  </div>
  </section>

  <!-- News and Announcements (Dashboard style) -->
  <section id="news" class="py-16" style="background: #f9fafb;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">NEWS & ANNOUNCEMENTS</h2>
        <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
      </div>
      <?php if (!empty($announcements)): ?>
      <div class="row g-4">
        <?php foreach ($announcements as $a): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius:12px; overflow:hidden;">
            <div style="height:200px; overflow:hidden; background:#f3f4f6;">
              <?php if (!empty($a['image'])): ?>
                <img src="/scratch/uploads/<?= htmlspecialchars($a['image']) ?>" alt="Announcement" class="w-100 h-100" style="object-fit:cover;">
              <?php else: ?>
                <div class="d-flex align-items-center justify-content-center h-100"><i class="fas fa-bullhorn text-muted" style="font-size:3rem;"></i></div>
              <?php endif; ?>
            </div>
            <div class="p-4 d-flex flex-column flex-grow-1">
              <h5 class="fw-bold mb-2" style="color:#1f2937; font-size:1.15rem;"><?= htmlspecialchars($a['title'] ?? 'Announcement') ?></h5>
              <div class="text-muted small mb-2"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($a['date_created'])) ?></div>
              <p class="text-muted mb-4" style="line-height:1.6;"><?= htmlspecialchars(substr($a['content'] ?? '', 0, 140)) ?>...</p>
              <button class="btn mt-auto" style="background:#dc2626; color:#fff; border:none; padding:12px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#landingAnnouncement<?= (int)$a['id'] ?>">Read More</button>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="landingAnnouncement<?= (int)$a['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.3);">
              <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); color:#fff; border:none; border-radius:16px 16px 0 0;">
                <h5 class="modal-title mb-0" style="font-weight:700; font-size:20px;"><?= htmlspecialchars($a['title'] ?? 'Announcement') ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" style="padding:24px;">
                <?php if (!empty($a['image'])): ?>
                  <img src="/scratch/uploads/<?= htmlspecialchars($a['image']) ?>" alt="<?= htmlspecialchars($a['title'] ?? 'Announcement') ?>" class="img-fluid mb-3" style="border-radius:12px; width:100%; max-height:420px; object-fit:cover;">
                <?php endif; ?>
                <div class="text-muted mb-3"><i class="fas fa-calendar me-1"></i><?= date('F d, Y - g:i A', strtotime($a['date_created'] ?? 'now')) ?></div>
                <div style="color:#374151; line-height:1.7; white-space:pre-wrap;"><?= nl2br(htmlspecialchars($a['content'] ?? '')) ?></div>
              </div>
              <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e5e7eb; border-radius:0 0 16px 16px;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Close</button>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="text-center mt-4"><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:12px 24px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">View All News</button></div>
      <?php else: ?>
        <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;"><i class="fas fa-bullhorn text-muted" style="font-size:2rem;"></i></div>
          <h4 class="text-muted">No announcements yet</h4>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Upcoming Events (Dashboard style, requires login to join) -->
  <section id="events" class="py-16" style="background: #f8f9fa;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">UPCOMING EVENTS</h2>
        <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
      </div>
      <div class="row g-4">
        <?php if (!empty($events)): ?>
          <?php foreach ($events as $event): $limit = $event['participant_limit'] ?? null; $count = (int)($event['participant_count'] ?? 0); ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius:12px; overflow:hidden;">
              <div style="height:200px; overflow:hidden; background:linear-gradient(135deg,#7f1d1d,#991b1b);">
                <?php if (!empty($event['banner']) && file_exists(__DIR__ . '/uploads/' . $event['banner'])): ?>
                  <img src="/scratch/uploads/<?= htmlspecialchars($event['banner']) ?>" class="w-100 h-100" style="object-fit:cover;" alt="<?= htmlspecialchars($event['title']) ?>">
                <?php else: ?>
                  <div class="d-flex align-items-center justify-content-center h-100 text-white"><div class="text-center"><i class="fas fa-calendar-alt" style="font-size:3rem; margin-bottom:.5rem;"></i><h6 class="mb-0"><?= htmlspecialchars($event['title']) ?></h6></div></div>
                <?php endif; ?>
              </div>
              <div class="p-4 d-flex flex-column flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="fw-bold mb-0" style="color:#1f2937; font-size:1.25rem;"><?= htmlspecialchars($event['title']) ?></h5>
                  <span class="badge bg-success text-white">Registration Open</span>
                </div>
                <p class="text-muted small mb-2"><i class="fas fa-calendar me-1"></i><?= date('M d, Y \a\t g:i A', strtotime($event['schedule'])) ?></p>
                <p class="text-muted mb-3" style="line-height:1.6; min-height:64px;"><?= htmlspecialchars(substr((string)($event['content'] ?? ''), 0, 120)) ?><?= strlen((string)($event['content'] ?? ''))>120?'...':'' ?></p>
                <div class="mb-3" style="min-height:72px;">
                  <small class="text-muted"><i class="fas fa-users me-1"></i><strong><?= $count ?></strong><?php if ($limit): ?>/ <strong><?= (int)$limit ?></strong> participants <span class="badge bg-info ms-1" style="font-size:.75rem;"><?= max((int)$limit-$count,0) ?> left</span><?php else: ?> participants<?php endif; ?></small>
                  <?php if ($limit): $pct = $count>0?($count/(int)$limit)*100:0; $bar = $pct>=100?'#7f1d1d':($pct>=80?'#b45309':'#dc2626'); ?>
                  <div class="d-flex align-items-center" style="gap:8px; margin-top:6px;">
                    <div style="background:#f0f0f0; height:6px; border-radius:3px; overflow:hidden; flex:1;"><div style="background:<?= $bar ?>; height:100%; width:<?= min($pct,100) ?>%;"></div></div>
                    <small class="text-muted" style="width:40px; text-align:right; flex-shrink:0;"><?= round($pct,1) ?>%</small>
                  </div>
                  <?php endif; ?>
                </div>
                <button class="btn mt-auto w-100" style="background:#dc2626; color:#fff; border:none; padding:12px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">Login to Join</button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);"><div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;"><i class="fas fa-calendar-alt text-muted" style="font-size:2rem;"></i></div><h4 class="text-muted">No upcoming events</h4></div>
        <?php endif; ?>
      </div>
      <div class="text-center mt-4"><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:12px 24px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">View All Events</button></div>
    </div>
  </section>

  <!-- Available Jobs (Dashboard style, requires login to apply) -->
  <section id="jobs" class="py-16" style="background:#f8f9fa;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">AVAILABLE JOBS</h2>
        <div class="mx-auto" style="width: 100px; height: 3px; background: #dc2626;"></div>
      </div>
      <div class="row g-4">
        <?php if (!empty($careers)): ?>
          <?php foreach ($careers as $job): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius:12px; overflow:hidden;">
              <div style="height:200px; overflow:hidden; background:linear-gradient(135deg,#7f1d1d,#991b1b);">
                <?php if (!empty($job['company_logo']) && file_exists(__DIR__ . '/uploads/' . $job['company_logo'])): ?>
                  <img src="/scratch/uploads/<?= htmlspecialchars($job['company_logo']) ?>" class="w-100 h-100" style="object-fit:contain; background:#fff;" alt="<?= htmlspecialchars($job['company'] ?? 'Company') ?> Logo">
                <?php else: ?>
                  <div class="d-flex align-items-center justify-content-center h-100"><i class="fas fa-briefcase text-white" style="font-size:4rem;"></i></div>
                <?php endif; ?>
              </div>
              <div class="p-4 d-flex flex-column flex-grow-1">
                <h5 class="fw-bold mb-3" style="color:#1f2937; font-size:1.25rem;"><?= htmlspecialchars($job['job_title'] ?? ($job['title'] ?? '')) ?></h5>
                <p class="text-muted mb-4" style="line-height:1.6; flex-grow:1;"><?= htmlspecialchars(substr((string)($job['description'] ?? ''), 0, 120)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center mb-3"><small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars((string)($job['location'] ?? 'Location not specified')) ?></small><small class="text-muted"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($job['date_created'])) ?></small></div>
                <div class="d-flex gap-2 mt-auto"><button class="btn flex-fill" style="background:#6b7280; color:#fff; border:none; padding:12px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#landingJob<?= (int)$job['id'] ?>">Read More</button><button class="btn flex-fill" style="background:#dc2626; color:#fff; border:none; padding:12px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">Login to Apply</button></div>
              </div>
            </div>
          </div>
          <!-- Job Modal -->
          <div class="modal fade" id="landingJob<?= (int)$job['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.3);">
                <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); color:#fff; border:none; border-radius:16px 16px 0 0;">
                  <h5 class="modal-title mb-0" style="font-weight:700; font-size:20px;"><?= htmlspecialchars($job['job_title'] ?? ($job['title'] ?? 'Job')) ?> at <?= htmlspecialchars($job['company'] ?? 'Company') ?></h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                  <?php if (!empty($job['company_logo']) && file_exists(__DIR__ . '/uploads/' . $job['company_logo'])): ?>
                    <div class="text-center mb-3"><img src="/scratch/uploads/<?= htmlspecialchars($job['company_logo']) ?>" alt="<?= htmlspecialchars($job['company'] ?? 'Company') ?> Logo" style="max-height:120px; object-fit:contain; background:#fff; border-radius:12px; padding:8px;"></div>
                  <?php endif; ?>
                  <div class="text-muted mb-3"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars((string)($job['location'] ?? 'Location not specified')) ?><span class="mx-2">•</span><i class="fas fa-calendar me-1"></i><?= date('F d, Y - g:i A', strtotime($job['date_created'] ?? 'now')) ?></div>
                  <div style="color:#374151; line-height:1.7; white-space:pre-wrap;"><?= nl2br(htmlspecialchars((string)($job['description'] ?? ''))) ?></div>
                </div>
                <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e5e7eb; border-radius:0 0 16px 16px;">
                  <button class="btn" style="background:#dc2626; color:#fff; border:none; padding:10px 20px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login to Apply</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Close</button>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);"><div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;"><i class="fas fa-briefcase text-muted" style="font-size:2rem;"></i></div><h4 class="text-muted">No job openings available</h4></div>
        <?php endif; ?>
      </div>
      <div class="text-center mt-4"><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:12px 24px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">View All Jobs</button></div>
    </div>
  </section>

  <!-- Success Stories (Dashboard style) -->
  <section id="success-stories" class="py-16" style="background:#f8f9fa;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12"><h2 class="text-4xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">SUCCESS STORIES</h2><div class="mx-auto" style="width:100px; height:3px; background:#dc2626;"></div></div>
      <?php if (!empty($successStories)): ?>
      <?php 
        $storiesItems = array_slice($successStories, 0, 6);
        $storySlides = array_chunk($storiesItems, 3);
      ?>
      <style>
        /* Modern glass buttons with subtle red glow */
        .landing-stories .control-btn {
          width: 52px; height: 52px; border-radius: 14px;
          background: rgba(15, 23, 42, 0.25);
          backdrop-filter: blur(8px);
          -webkit-backdrop-filter: blur(8px);
          color: #ffffff;
          display: inline-flex; align-items: center; justify-content: center;
          border: 1px solid rgba(255,255,255,0.35);
          box-shadow: 0 8px 24px rgba(220, 38, 38, 0.35), inset 0 0 0 1px rgba(255,255,255,0.08);
          transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
          font-size: 22px; font-weight: 800; line-height: 1;
        }
        .landing-stories .control-btn:hover {
          transform: translateY(-2px);
          background: rgba(220, 38, 38, 0.22);
          border-color: rgba(255,255,255,0.55);
          box-shadow: 0 14px 30px rgba(220, 38, 38, 0.45);
        }
        .landing-stories .control-btn:focus { outline: none; box-shadow: 0 0 0 4px rgba(220,38,38,0.25), 0 10px 24px rgba(220,38,38,0.35); }
        .landing-stories .carousel-control-prev-icon, .landing-stories .carousel-control-next-icon { display: none; }
        .landing-stories .carousel-control-prev, .landing-stories .carousel-control-next { width: auto; opacity: 1; top: 50%; transform: translateY(-50%); }
        .landing-stories .carousel-control-prev { left: -12px; }
        .landing-stories .carousel-control-next { right: -12px; }
        @media (max-width: 576px) {
          .landing-stories .control-btn { width: 44px; height: 44px; border-radius: 12px; font-size: 20px; }
          .landing-stories .carousel-control-prev { left: -8px; }
          .landing-stories .carousel-control-next { right: -8px; }
        }
      </style>
      <div id="landingStoriesCarousel" class="carousel slide landing-stories" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($storySlides as $slideIndex => $slideStories): ?>
          <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
            <div class="row g-4">
              <?php foreach ($slideStories as $index => $story): ?>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow d-flex flex-column" style="border-radius:12px; overflow:hidden;">
                  <div style="height:480px; overflow:hidden; background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <?php if (!empty($story['image'])): ?>
                      <img src="/scratch/<?= htmlspecialchars($story['image']) ?>" alt="<?= htmlspecialchars($story['title'] ?? 'Story') ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center;">
                    <?php else: ?>
                      <div class="d-flex align-items-center justify-content-center h-100"><i class="fas fa-star text-white" style="font-size:4rem;"></i></div>
                    <?php endif; ?>
                  </div>
                  <div class="p-4 d-flex flex-column flex-grow-1">
                    <h5 class="fw-bold mb-3" style="color:#1f2937; font-size:1.25rem;"><?= htmlspecialchars(($story['firstname'] ?? '').' '.($story['lastname'] ?? '')) ?></h5>
                    <p class="text-muted mb-4" style="line-height:1.6; flex-grow:1;"><?= htmlspecialchars(substr((string)($story['content'] ?? ''),0,120)) ?><?= strlen((string)($story['content'] ?? ''))>120?'...':'' ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-auto"><small class="text-muted"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($story['created'])) ?></small><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:8px 16px; font-weight:600; border-radius:8px; font-size:.9rem;" data-bs-toggle="modal" data-bs-target="#loginModal">Read More</button></div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#landingStoriesCarousel" data-bs-slide="prev">
          <span class="control-btn" aria-hidden="true">
            <img src="/scratch/images/icons8-arrow-96.png" alt="Prev" style="width:22px;height:22px;object-fit:contain;transform: rotate(0deg);" />
          </span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#landingStoriesCarousel" data-bs-slide="next">
          <span class="control-btn" aria-hidden="true">
            <img src="/scratch/images/icons8-arrow-96.png" alt="Next" style="width:22px;height:22px;object-fit:contain;transform: rotate(180deg);" />
          </span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <?php else: ?>
        <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);"><i class="fas fa-star text-muted" style="font-size:4rem;"></i><h4 class="text-muted mt-3">No Success Stories Yet</h4></div>
      <?php endif; ?>
      <div class="text-center mt-4"><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:12px 24px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">View All Stories</button></div>
    </div>
  </section>

  <!-- Testimonials (Dashboard style) -->
  <section id="testimonials" class="py-16" style="background:#f8f9fa;">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12"><h2 class="text-4xl font-bold mb-2" style="color:#dc2626; font-family: 'Times New Roman', serif;">TESTIMONIALS</h2><div class="mx-auto" style="width:100px; height:3px; background:#dc2626;"></div></div>
      <?php if (!empty($testimonials)): ?>
      <?php 
        $items = array_slice($testimonials, 0, 6);
        $slides = array_chunk($items, 3);
      ?>
      <style>
        /* Same glass button treatment for testimonials */
        .landing-testimonials .control-btn {
          width: 52px; height: 52px; border-radius: 14px;
          background: rgba(15, 23, 42, 0.25);
          backdrop-filter: blur(8px);
          -webkit-backdrop-filter: blur(8px);
          color: #ffffff;
          display: inline-flex; align-items: center; justify-content: center;
          border: 1px solid rgba(255,255,255,0.35);
          box-shadow: 0 8px 24px rgba(220, 38, 38, 0.35), inset 0 0 0 1px rgba(255,255,255,0.08);
          transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
          font-size: 22px; font-weight: 800; line-height: 1;
        }
        .landing-testimonials .control-btn:hover {
          transform: translateY(-2px);
          background: rgba(220, 38, 38, 0.22);
          border-color: rgba(255,255,255,0.55);
          box-shadow: 0 14px 30px rgba(220, 38, 38, 0.45);
        }
        .landing-testimonials .control-btn:focus { outline: none; box-shadow: 0 0 0 4px rgba(220,38,38,0.25), 0 10px 24px rgba(220,38,38,0.35); }
        .landing-testimonials .carousel-control-prev-icon, .landing-testimonials .carousel-control-next-icon { display: none; }
        .landing-testimonials .carousel-control-prev, .landing-testimonials .carousel-control-next { width: auto; opacity: 1; top: 50%; transform: translateY(-50%); }
        .landing-testimonials .carousel-control-prev { left: -12px; }
        .landing-testimonials .carousel-control-next { right: -12px; }
        @media (max-width: 576px) {
          .landing-testimonials .control-btn { width: 44px; height: 44px; border-radius: 12px; font-size: 20px; }
          .landing-testimonials .carousel-control-prev { left: -8px; }
          .landing-testimonials .carousel-control-next { right: -8px; }
        }
      </style>
      <div id="landingTestimonialsCarousel" class="carousel slide landing-testimonials" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($slides as $slideIndex => $slideItems): ?>
          <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
            <div class="row g-4">
              <?php foreach ($slideItems as $t): ?>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100" style="border-radius:12px; overflow:hidden;">
                  <div style="height:480px; overflow:hidden; background:linear-gradient(135deg,#3b82f6,#1d4ed8);">
                    <?php if (!empty($t['graduation_photo'])): ?>
                      <img src="/scratch/<?= htmlspecialchars($t['graduation_photo']) ?>" alt="<?= htmlspecialchars($t['author_name'] ?? (($t['firstname'] ?? '').' '.($t['lastname'] ?? ''))) ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center;">
                    <?php else: ?>
                      <div class="d-flex align-items-center justify-content-center h-100"><i class="fas fa-user-graduate text-white" style="font-size:4rem;"></i></div>
                    <?php endif; ?>
                  </div>
                  <div class="p-4 d-flex flex-column">
                    <h5 class="fw-bold mb-2" style="color:#1f2937;"><?= htmlspecialchars($t['author_name'] ?? (($t['firstname'] ?? '').' '.($t['lastname'] ?? ''))) ?></h5>
                    <p class="text-muted small mb-2"><?= htmlspecialchars($t['course'] ?? '') ?><?= !empty($t['graduation_year']) ? ', Class of '.(int)$t['graduation_year'] : '' ?></p>
                    <p class="mb-0" style="line-height:1.7; font-style:italic; color:#4b5563;">"<?= htmlspecialchars($t['quote'] ?? '') ?>"</p>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#landingTestimonialsCarousel" data-bs-slide="prev">
          <span class="control-btn" aria-hidden="true">
            <img src="/scratch/images/icons8-arrow-96.png" alt="Prev" style="width:22px;height:22px;object-fit:contain;transform: rotate(0deg);" />
          </span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#landingTestimonialsCarousel" data-bs-slide="next">
          <span class="control-btn" aria-hidden="true">
            <img src="/scratch/images/icons8-arrow-96.png" alt="Next" style="width:22px;height:22px;object-fit:contain;transform: rotate(180deg);" />
          </span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <?php else: ?>
        <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);"><i class="fas fa-quote-left text-muted" style="font-size:4rem;"></i><h4 class="text-muted mt-3">No Testimonials Yet</h4></div>
      <?php endif; ?>
      <div class="text-center mt-4"><button type="button" class="btn" style="background:#dc2626; color:#fff; border:none; padding:12px 24px; font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#loginModal">View All Testimonials</button></div>
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
            <li class="mb-2"><a href="#success-stories" class="text-white text-decoration-none hover:text-gray-200">Success Stories</a></li>
            <li class="mb-2"><a href="#testimonials" class="text-white text-decoration-none hover:text-gray-200">Testimonials</a></li>
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
      <div class="text-center small text-white">
        <p class="mb-0" style="color:#ffffff;">
          &copy; <?php echo date('Y'); ?> St. Cecilia's College - Cebu, Inc. All rights reserved.
        </p>
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
      // Smooth scroll for all in-page anchors with fallback JS (for browsers ignoring CSS smooth)
      document.querySelectorAll('a[href^="#"]').forEach(function(anchor){
        anchor.addEventListener('click', function(e){
          const targetId = this.getAttribute('href');
          if (targetId.length > 1) {
            const el = document.querySelector(targetId);
            if (el) {
              e.preventDefault();
              const y = el.getBoundingClientRect().top + window.pageYOffset - 88; // account for sticky header
              window.scrollTo({ top: y, behavior: 'smooth' });
            }
          }
        });
      });

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


